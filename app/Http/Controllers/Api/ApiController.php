<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\point_reward;
use App\Models\point;
use App\Models\User;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class ApiController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_user()
    {
        return response([
            'user' => auth()->user()
        ], Response::HTTP_OK);
    }

    public function get_point_reward(){

        $set = DB::table('settings')
          ->where('id', 1)
          ->first();
      
        $check_point_day = DB::table('point_rewards')
        ->where('user_id', auth()->user()->id)
        ->count();

        $check_day_point = DB::table('point_rewards')
        ->where('point_date', date("Y-m-d"))
        ->where('user_id', auth()->user()->id)
        ->count();

        $check_day_point_recive = true;

        if($check_day_point > 0){
            $check_day_point_recive = false;
        }

        $point_return = 0;

        if($check_point_day > 0 && $check_point_day < 6){
            $point_return = $set->mid_day;
        }else{
            $point_return = $set->last_day;
        }

        return response([
            'check_point_day' => $check_point_day,
            'check_day_point_recive' => $check_day_point_recive,
            'point_return' => $point_return,
            'first_day' => $set->first_day,
            'mid_day' => $set->mid_day,
            'last_day' => $set->last_day,

        ], Response::HTTP_OK);

    }

    

    public function data_labels(){

        $wheelsetting = \DB::table('wheelsetting')->select('text')->wherein('id', [1,2,3,4,5,6])->get();
        $data = array();

        if(isset($wheelsetting)){
            foreach($wheelsetting as $u){
                $data[] = $u->text;
            }
        }

        return response()->json($data);
    }


    public function data_wheel(){

        $wheelsetting = \DB::table('wheelsetting')->where('maxDegree', '>', 0)->get();

		$fruits = array();

		foreach ($wheelsetting as $key => $value) {
			$fruits[$value->value] = $value->percent;
		}

        return response()->json($wheelsetting);

    }

    public function addwheelresult(){

        $wheelsetting = \DB::table('wheelsetting')->get();

        //check free day wheel

        $free_wheel = \DB::table('wheel_logs')
            ->where('user_id', auth()->user()->id)
            ->whereDate('date_time', date("Y-m-d"))
            ->count();

        //end check free day wheel

        $cat = DB::table('settings')
          ->where('id', 1)
          ->first();

        $coins_wheel_turn = $cat->coint_turn;

        $get_free_coin = 0;

        if(auth()->user()->point < $coins_wheel_turn){

            return response()->json(
                [
                    'msg' => 'coin ของคุณไม่เพียงพอ',
                    'data' => null,
                    'status' => 202
                ]
            );

        }

		$fruits = array();

		foreach ($wheelsetting as $key => $value) {
			$fruits[$value->value] = $value->percent;
		}

        $newFruits = array();
		foreach ($fruits as $fruit => $value) {
			$newFruits = array_merge($newFruits, array_fill(0, $value, $fruit));
		}

		$coin = $newFruits[array_rand($newFruits)];

        

            \DB::table('wheel_logs')->insert(
				array(
					'user_id' => auth()->user()->id,
					'coins' =>  $coin,
                    'date_time' => date('Y-m-d H:i:s')
				)
			);

        if($free_wheel > 0){

            $get_free_coin = $coins_wheel_turn;

            $delobj = new point();
            $delobj->user_key = auth()->user()->phone;
            $delobj->date = date("Y-m-d");
            $delobj->total_valid_bet_amount = $coins_wheel_turn;
            $delobj->point = $coins_wheel_turn;
            $delobj->detail = 'ใช้ Point '.$coins_wheel_turn.' เพื่อหมุนกงล้อ';
            $delobj->type = 1;
            $delobj->status = 5;
            $delobj->save();

        }

        
     
        $package = new point();
        $package->user_key = auth()->user()->phone;
        $package->date = date("Y-m-d");
        $package->total_valid_bet_amount = $coin;
        $package->point = $coin;
        $package->type = 0;
        $package->detail = 'ได้ Point '.$coin.' จากการหมุนกงล้อ';
        $package->status = 5;
        $package->save();

 
        $user = User::where('phone', auth()->user()->phone)->first();
 
        //$total_point = $user->point;
        $total_point = 0;
 
        $objs = point::where('user_key', auth()->user()->phone)->get();
        
        foreach($objs as $u){
             if($u->type == 0){
                 $total_point += $u->point;
             }elseif($u->type == 2){
                 $total_point += $u->point;
             }else{
                 $total_point -= $u->point;
             }
        }
 
        $ob = point::find($package->id);
        $ob->last_point = $total_point;
        $ob->save();
 
        $package = User::find($user->id);
        $package->point = $total_point;
        $package->save();

    

    $wheel_final = \DB::table('wheelsetting')->where('value', $coin)->first();

    
        return response()->json(
            [
                'data' => $wheel_final,
                'status' => 201,
                'coin_return' => $coin-$get_free_coin
            ]
        );

    }

    public function spin_wheel(){

        $cat = DB::table('settings')
          ->where('id', 1)
          ->first();

        $coins_wheel_turn = $cat->coint_turn;

        $free_wheel = \DB::table('wheel_logs')
            ->where('user_id', auth()->user()->id)
            ->whereDate('date_time', date("Y-m-d"))
            ->count();

        $objs = DB::table('wheel_logs')->select(
            'wheel_logs.*',
            'wheel_logs.id as id_q',
            'users.*'
            )
            ->leftjoin('users', 'users.id',  'wheel_logs.user_id')
            ->where('wheel_logs.coins', '>',0)
            ->orderby('wheel_logs.id', 'desc')
            ->limit(12)
            ->get();

        $wheelsetting = \DB::table('wheelsetting')->select('text')->wherein('id', [1,2,3,4,5,6])->get();

        return response([
            'wheel_logs' => $objs,
            'obj' => $wheelsetting,
            'free_wheel' => $free_wheel,
            'coins_wheel_turn' => $coins_wheel_turn,
            'data_user_point' => auth()->user()->point

        ], Response::HTTP_OK);
    }

    public function getPoint(){

        $set = DB::table('settings')
          ->where('id', 1)
          ->first();

        $check_point = DB::table('point_rewards')
        ->where('user_id', auth()->user()->id)
        ->count();

        $point = 0;
        $point_next = 0;

        $check_day_point = DB::table('point_rewards')
        ->where('point_date', date("Y-m-d"))
        ->where('user_id', auth()->user()->id)
        ->count();


        if($check_day_point == 0){

        

        if($check_point == 0){

            $point = $set->first_day;
            $point_next = $set->mid_day;

            $delobj = new point_reward();
            $delobj->user_id = auth()->user()->id;
            $delobj->coins = $set->first_day;
            $delobj->day = 1;
            $delobj->point_date = date("Y-m-d");
            $delobj->status = 1;
            $delobj->save();

        }

        if($check_point > 0 && $check_point < 6){

            $point = $set->mid_day;
            $point_next = $set->mid_day;

            $delobj = new point_reward();
            $delobj->user_id = auth()->user()->id;
            $delobj->coins = $set->mid_day;
            $delobj->day = 1;
            $delobj->point_date = date("Y-m-d");
            $delobj->status = 1;
            $delobj->save();

        }

        if($check_point == 6){

            $point = $set->last_day;
            $point_next = $set->last_day;

            $delobj = new point_reward();
            $delobj->user_id = auth()->user()->id;
            $delobj->coins = $set->last_day;
            $delobj->day = 1;
            $delobj->point_date = date("Y-m-d");
            $delobj->status = 1;
            $delobj->save();

        }

        $package = new point();
        $package->user_key = auth()->user()->phone;
        $package->date = date("Y-m-d");
        $package->total_valid_bet_amount = $point;
        $package->point = $point;
        $package->type = 0;
        $package->detail = 'ได้ Point '.$point.' จากการเช็คอินรายวัน';
        $package->status = 5;
        $package->save();

        
 
        $user = User::where('phone', auth()->user()->phone)->first();
 
        //$total_point = $user->point;
        $total_point = 0;
 
        $objs = point::where('user_key', auth()->user()->phone)->get();
        
        foreach($objs as $u){
             if($u->type == 0){
                 $total_point += $u->point;
             }elseif($u->type == 2){
                 $total_point += $u->point;
             }else{
                 $total_point -= $u->point;
             }
        }
 
        $ob = point::find($package->id);
        $ob->last_point = $total_point;
        $ob->save();
 
        $package = User::find($user->id);
        $package->point = $total_point;
        $package->save();

        return response([
            'next_point' => $point_next,
            'point_return' => $point,
            'last_point' => $package->point,
            'success' => true
        ], Response::HTTP_OK);

    }else{

        return response([
            'msg' => 'วันนี้คุณกดรับไปแล้ว',
            'success' => false
        ], 202);

    }

    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
        
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     $input = $request->all();
   
    //     $validator = Validator::make($input, [
    //         'name' => 'required',
    //         'detail' => 'required'
    //     ]);
   
    //     if($validator->fails()){
    //         return $this->sendError('Validation Error.', $validator->errors());       
    //     }
   
    //     $product = Product::create($input);
   
    //     return $this->sendResponse(new ProductResource($product), 'Product Created Successfully.');
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     $product = Product::find($id);
  
    //     if (is_null($product)) {
    //         return $this->sendError('Product not found.');
    //     }
   
    //     return $this->sendResponse(new ProductResource($product), 'Product Retrieved Successfully.');
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     $input = $request->all();
   
    //     $validator = Validator::make($input, [
    //         'name' => 'required',
    //         'detail' => 'required'
    //     ]);
   
    //     if($validator->fails()){
    //         return $this->sendError('Validation Error.', $validator->errors());       
    //     }
    //     $product = Product::find($id);   
    //     $product->name = $input['name'];
    //     $product->detail = $input['detail'];
    //     $product->save();
   
    //     return $this->sendResponse(new ProductResource($product), 'Product Updated Successfully.');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     $product = Product::find($id);
    //     $product->delete();
   
    //     return $this->sendResponse([], 'Product Deleted Successfully.');
    // }
}
