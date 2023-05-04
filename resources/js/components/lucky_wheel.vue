<template lang="">
    <div>
        <h2>{{ header }} ( <img src="/img/coin.png" class="chakra-coin">  {{ total_mycoin }} )</h2>

        <div class="ps-checkout ps-section--shopping" style="background-color: #fff; padding: 30px 0px 200px 0px">
            <div class="container">
                <div class="ps-section__content ">
                    <div class="row justify-content-md-center">
                        <div class="col-md-12" style="padding-right: 30px; padding-left: 30px;">
                            <div class="text-center">
                                <h2 class="free-spin-day" >ใช้ {{ coint_turn_around }} coin เพื่อหมุนกงล้อ</h2><br>
                                <div v-if="free_wheel == 0">
                                    <h2 class="free-spin-day" id="free-spin-dayx">คุณได้สิทธิ์หมุนฟรี 1 ครั้ง/วัน </h2><br>
                                </div>
                                <div  v-if="free_wheel > 0 && check_wheel_turn > 0">

                                    <div v-if="coin_recive == 0">
                                        <h2 class="free-spin-day" id="free-spin-dayx">
                                        <img src="/img/coin.png" class="chakra-coin"> 
                                        ได้รับ {{ coin_recive }} coin </h2><br>
                                    </div>
                                    <div v-else>
                                        <h2 class="free-spin-day" id="free-spin-dayx">
                                        <img src="/img/coin.png" class="chakra-coin"> 
                                        หมุนกงล้อมหาสนุกได้ {{ coin_recive }} point</h2><br>
                                    </div>
                                    
                                </div>
                            </div>
                            <div style="max-width:300px; margin: 0 auto; position: relative;" class="justify-content-md-center">
                                <img id="xxx" src="/img/2159559-22222.png" alt="spinner-bg" /> 
                                    <div style="position: relative; z-index: 2;">

                                        <Pie :data="chartData" :options="chartOption" />

                                        <button v-on:click="onClickRotate()"  id="spin-btn">Spin</button>
                                        <img id="spinner-arrow" src="/img/spinner-arrow-.svg" alt="spinner-arrow" />
                                    </div>
                                    <div id="final-value">
                                        <div style="position: relative; z-index: 2; ">
                                            <img id="label_wheel" src="/img/label_wheel.png" alt="label_wheel" /> 
                                            <p id="final-text" class="text-white text-label-bot">คลิกที่ปุ่ม Spin เพื่อเริ่มเกมส์</p>
                                           
                                        </div>
                                    </div>
                                <br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</template>
<script >
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Pie } from 'vue-chartjs'
import ChartDataLabels from "chartjs-plugin-datalabels";

ChartJS.register(ArcElement, Tooltip, Legend, ChartDataLabels)

import { mapActions } from 'vuex'

export default {
    name:"lucky_wheel",
    components: { Pie },
    data () {
        const header = "หมุนวงล้อนำโชค";
            return { 
                header, 
                user:this.$store.state.auth.user,
                free_wheel: 0,
                check_wheel_turn: 0,
                prizeNumber: 8,
                freeze: false,
                rolling: false,
                wheelDeg: 0,
                total_mycoin: 0,
                datalabels: null,
                coin_recive: 0,
                value_rotation : 0,
                coint_turn_around:0,
                rotationValues: []
            }
        },
        computed: {
            chartData() { return {
                labels: this.datalabels,
                    datasets: [
                    {
                        backgroundColor: [
                        "#8b35bc",
                        "#b163da",
                        "#8b35bc",
                        "#b163da",
                        "#8b35bc",
                        "#b163da"
                        ],
                        data: [16, 16, 16, 16, 16, 16]
                    }
                    ],
            } },
            chartOption() { return {
                responsive: true,
                rotation: this.value_rotation,
                    animation: { duration: 0 },
                    plugins: {
                        //hide tooltip and legend
                        tooltip: false,
                        legend: {
                        display: false,
                        },
                        //display labels inside pie chart
                        datalabels: {
                        rotation: (context) =>
                        context.dataIndex * (360 / context.chart.data.labels.length) +
                        360 / context.chart.data.labels.length / 2 +
                        270 +
                        context.chart.options.rotation,
                        color: "#ffffff",
                        formatter: (_, context) => context.chart.data.labels[context.dataIndex],
                        font: { 
                            size: 16,
                            family: 'Kanit',
                        },
                        align: 'start',
                        anchor: 'end',
                        },
                    },
            } 
        },
        },
        methods: {
            ...mapActions({
                updateProfile:'auth/updateprofile'
            }),
            getSpin_wheel_data(){
                axios.get('/spin_wheel')
                     .then((response)=>{
                        this.free_wheel = response.data.free_wheel
                        this.total_mycoin = response.data.data_user_point
                        this.coint_turn_around = response.data.coins_wheel_turn
                    })
            },
            data_labels(){
                // ดึงข้อมูลมาแสดงที่ Labels ของ chart
                axios.get('/data_labels')
                     .then((response)=>{
                        this.datalabels = response.data
                    })
            },
            getWheel(){
                // ดึงข้อมูลรางวัลทั้งหมดออกมา DB wheelsetting 
                 axios.get('/data_wheel')
                     .then((response)=>{
                        this.rotationValues = response.data;
                     })
            },
            clearInterval(){

            },
            async onClickRotate () {
                // กดปุ่ม Spin เพื่อเริ่ม
                var my_coints;
                var my_cointsxx;
                let randomDegree;
                const wheel = document.getElementById("wheel");
                const spinBtn = document.getElementById("spin-btn");
                const finalValue = document.getElementById("final-text");
                

                //Spinner count
                let count = 0;
                //100 rotations for animation and last rotation for result
                let resultValue = 101;
                
                // ดึงข้อมูลว่าเราหมุนได้รางวัลอะไรออกมา
                await axios.post('/addwheelresult')
                     .then((response)=>{

                        //disabled ปุ่ม ไม่ให้กดซ้ำ
                        spinBtn.disabled = true;
                        let randomDegree;

                        if(response.status == 200){
                            console.log('value----', response.data.data.value)
                            
                            finalValue.innerHTML = `<p>Good Luck!</p>`;
                            randomDegree = Math.floor(Math.random() * (response.data.data.maxDegree - response.data.data.minDegree) + response.data.data.minDegree);
                            console.log('ran', randomDegree)

                            let rotationInterval = window.setInterval(() => {
                                this.value_rotation = this.value_rotation + resultValue

                                if (this.value_rotation >= 360) {
                                    count += 1;
                                    resultValue -= 5;
                                    this.value_rotation = 0;
                                }else if (count > 15 && this.value_rotation == randomDegree) {

                                    for (let i of this.rotationValues) {

                                        console.log('for----', i.minDegree)

                                        // if the angleValue is between min and max then display it
                                        if (randomDegree >= i.minDegree && randomDegree <= i.maxDegree) {
                                        //แสดงข้อความรางวัลที่ได้รับ
                                        finalValue.innerHTML = `<p> ${i.message}</p>`;

                                        //ปลดล็อคปุ่ม
                                        spinBtn.disabled = false;
                                        break;
                                        }
                                    }
                                    clearInterval(rotationInterval);
                                    count = 0;
                                    resultValue = 101;

                                    this.coin_recive = response.data.data.value
                                    this.free_wheel = 1
                                    this.check_wheel_turn = 1
                                    // หักลบกับส่วนที่ใช้ coin ในการหมุนอัพเดท coin อีกรอบ
                                    // รอบที่สะสมไม่ได้มาจาก users->point แต่เกิดจาการ sum ใน DB points ที่ type = 0 
                                    this.total_mycoin = this.total_mycoin + response.data.coin_return

                                }

                            }, 10);
                        }
                })

                }
        },
        created() {
            this.getSpin_wheel_data();
            this.data_labels()
            this.getWheel()
        }
}

</script>
<style scoped>

</style>