<template lang="">
    <div>
        <h2>{{ header }}</h2>

        <div class="ps-checkout ps-section--shopping" style="background-color: #fff; padding: 30px 0px 200px 0px">
            <div class="container">
                <div class="ps-section__content ">
                    <div class="row justify-content-md-center">
                        <div class="col-md-12" style="padding-right: 30px; padding-left: 30px;">
                            <div class="text-center">
                                <div v-if="free_wheel == 0">
                                    <h2 class="free-spin-day" id="free-spin-dayx">คุณได้สิทธิ์หมุนฟรี 1 ครั้ง/วัน</h2><br>
                                </div>
                                <div v-else>
                                    <h2 class="free-spin-day" id="free-spin-dayx">
                                    <img src="{{ url('/img/coin.png') }}" class="chakra-coin"> 
                                    หมุนกงล้อมหาสนุก {{ number_format($coins_wheel_turn, 0) }} point</h2>
                                </div>
                            </div>
                            <div style="max-width:300px; margin: 0 auto; position: relative;" class="justify-content-md-center">
                                <div>
                                    <!-- type: image -->
                                    <FortuneWheel
                                    style="width: 500px"
                                    type="image"
                                    :useWeight="true"
                                    :prizes="prizes"
                                    :angleBase="-10"
                                    @rotateStart="onImageRotateStart"
                                    @rotateEnd="onRotateEnd"
                                    >
                                    <img slot="wheel" src="@/assets/wheel.png" />
                                    <img slot="button" src="@/assets/button.png" />
                                    </FortuneWheel>

                                    <!-- type: canvas -->
                                    <FortuneWheel
                                    style="width: 500px"
                                    :canvas="canvasOptions"
                                    :prizes="prizes"
                                    :verify="cavansVerify"
                                    @rotateStart="onCanvasRotateStart"
                                    @rotateEnd="onRotateEnd"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</template>
<script >
import FortuneWheel from 'vue-fortune-wheel'
import 'vue-fortune-wheel/lib/vue-fortune-wheel.css'

import { mapActions } from 'vuex'

export default {
    name:"lucky_wheel",
    components: { FortuneWheel },
    data () {
        const header = "หมุนวงล้อนำโชค";
            return { 
                header, 
                user:this.$store.state.auth.user,
                free_wheel:0,
                prizeNumber: 8,
                freeze: false,
                rolling: false,
                wheelDeg: 0,
                getWheel: null,
                PostWheel: null,
                cavansVerify: true, // Whether the turntable in canvas mode is enabled for verification
      canvasOptions: {
        borderWidth: 6,
        borderColor: '#584b43'
      },
      prizes: [
        {
          id: 1, //* The unique id of each prize, an integer greater than 0
          name: 'Blue', // Prize name, display value when type is canvas (this parameter is not needed when type is image)
          value: 'Blue\'s value', //* Prize value, return value after spinning
          bgColor: '#45ace9', // Background color (no need for this parameter when type is image)
          color: '#ffffff', // Font color (this parameter is not required when type is image)
          probability: 30, //* Probability, up to 4 decimal places (the sum of the probabilities of all prizes
          weight: 1 // Weight, if useWeight is true, the probability is calculated by weight (weight must be an integer), so probability is invalid
        },
        {
          id: 2,
          name: 'Red',
          value: 'Red\'s value',
          bgColor: '#dd3832',
          color: '#ffffff',
          probability: 40,
          weight: 1
        },
        {
          id: 3,
          name: 'Yellow',
          value: 'Yellow\'s value',
          bgColor: '#fef151',
          color: '#ffffff',
          probability: 30,
          weight: 1
        }
      ]
            }
        },
        methods: {
    onImageRotateStart() {
      console.log('onRotateStart')
    },
    onCanvasRotateStart(rotate) {
      if (this.canvasVerify) {
        const verified = true // true: the test passed the verification, false: the test failed the verification
        this.DoServiceVerify(verified, 2000).then((verifiedRes) => {
          if (verifiedRes) {
            console.log('Verification passed, start to rotate')
            rotate() // Call the callback, start spinning
            this.canvasVerify = false // Turn off verification mode
          } else {
            alert('Failed verification')
          }
        })
        return
      }
      console.log('onCanvasRotateStart')
    },
    onRotateEnd(prize) {
      alert(prize.value)
    },
    // Simulate the request back-end interface, verified: whether to pass the verification, duration: delay time
    DoServiceVerify(verified, duration) {
      return new Promise((resolve) => {
        setTimeout(() => {
          resolve(verified)
        }, duration)
      })
    }
  },
        created() {
            this.getSpin_wheel_data();
        }
}

</script>
<style scoped>

</style>