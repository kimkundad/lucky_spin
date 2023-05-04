<template lang="">
    <div>
        <h2>{{ header }}</h2>
            <div class="ps-section__content">
                <div class="card-yello text-center">
                    <h4 class="text-white">Cavia168 Point Rewards</h4>
                    <div class="d-flex justify-content-center">
                        <img class="h-30" src="/img/coin.png" >
                        <h2 class="text-white" id="my_point_old">{{ user.point }}</h2>
                    </div>

                    <div class="card-wh">
                        <div class="d-flex justify-content-center">

                            <div class="pcmall" v-for="i in 7" :key="i">
                                <div v-if="i <= check_point_day" :id="'sp_' + i" class="pcmall-dailycheckin active">

                                    <div v-if="i === 1" class="pcmall-point ">
                                        <img src="/img/coin.png" class="chakra-coin mt-10" />
                                        <div>+ {{first_day}}</div>
                                    </div>
                                    <div v-else-if="i > 1 && i < 7" class="pcmall-point">
                                        <img src="/img/coin.png" class="chakra-coin mt-10" />
                                        <div>+ {{mid_day}}</div>
                                    </div>
                                    <div v-else class="pcmall-point">
                                        <img src="/img/coin.png" class="chakra-coin mt-10" />
                                        <div>+ {{last_day}}</div>
                                    </div>
                                        <span>วันที่ {{ i }}</span>
                                </div>

                                <div v-else :id="'sp_' + i" class="pcmall-dailycheckin ">
                                    <div v-if="i === 1" class="pcmall-point">
                                        <img src="/img/coin.png" class="chakra-coin mt-10" />
                                        <div>+ {{first_day}}</div>
                                    </div>
                                    <div v-else-if="i > 1 && i < 7" class="pcmall-point">
                                        <img src="/img/coin.png" class="chakra-coin mt-10" />
                                        <div> + {{mid_day}}</div>
                                    </div>
                                    <div v-else class="pcmall-point">
                                        <img src="/img/coin.png" class="chakra-coin mt-10" />
                                        <div> + {{last_day}}</div>
                                    </div>
                                        <span>วันที่ {{ i }}</span>
                                </div>

                            </div>
                            
                        </div>
                        <button class="pcmall-dailycheckin_btn" id="dailycheckin_btn" data-inactive="true" :disabled="isButtonDisabled"  v-on:click="getPoint()" > เช็คอินทุกวันเพื่อรับ Point </button>
                    </div>

                </div>
            </div>
    </div>
</template>
<script>

import { mapActions } from 'vuex'
export default {
    name:"dashboard",
    data () {
        const header = "เช็คอินทุกวันเพื่อรับ Points";
            return { 
                header, 
                user:this.$store.state.auth.user,
                check_point_day: 0,
                apiData: null,
                isButtonDisabled: false,
                check_day_point_recive: false,
                point_return: 0,
                first_day:0,
                mid_day:0,
                last_day:0
            }
        },
        methods: {
            ...mapActions({
                updateProfile:'auth/updateprofile'
            }),
            getUser(){
                axios.get('/get_point_reward')
                     .then((response)=>{
                       let spinBtn = document.getElementById("dailycheckin_btn");
                       this.check_point_day = response.data.check_point_day
                       this.check_day_point_recive = response.data.check_day_point_recive
                       let d = document.getElementById("sp_"+parseInt(response.data.check_point_day+1));
                       console.log('00', "sp_"+parseInt(response.data.check_point_day+1))
                       this.first_day = response.data.first_day
                       this.mid_day = response.data.mid_day
                       this.last_day = response.data.last_day

                       console.log('------',this.check_point_day)
                       if(this.check_day_point_recive == false){
                        this.isButtonDisabled = true;
                        spinBtn.innerText = "แวะเข้ามาอีกพรุ่งนี้ รับเลย "+ response.data.point_return +" coins";
                       }else{
                        this.isButtonDisabled = false;
                        d.className += " active";
                       }
                       document.getElementById('dailycheckin_btn').dataset.inactive = this.isButtonDisabled
                       console.log('------',this.check_day_point_recive)
                     })
            },
            getPoint : function(event) {
                axios.get('/getPoint').then((response) => {
                this.apiData = response.data
                this.isButtonDisabled = true;
                if(response.data.success == true){
                    this.updateProfile()
                    let spinBtn = document.getElementById("dailycheckin_btn");
                    spinBtn.innerText = "แวะเข้ามาอีกพรุ่งนี้ รับเลย "+response.data.next_point+" coins";
                    document.getElementById("my_point_old").innerHTML = '';
                    document.getElementById("my_point_old").innerHTML = response.data.last_point;
                    document.getElementById('dailycheckin_btn').dataset.inactive = this.isButtonDisabled
                }else{
                    alert(response.data.msg)
                }
                
            })
            }
        },
        created() {
            this.getUser();

        }
}

</script>
<style scoped>

</style>