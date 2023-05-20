<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <router-link :to="{name:'lucky_wheel'}">
                    <img :src="'/img/xx1.jpg'" class="img-fluid"/> 
                    <p class="text-white">แบนเนอร์ ที่ลิงค์ไปยัง หมุนวงล้อนำโชค</p>
                </router-link>
                <router-link :to="{name:'pointRewards'}">
                    <img :src="'/img/PR2023-Summoner-War-HNY-event-2.jpg'" class="img-fluid"> 
                    <p class="text-white">แบนเนอร์ ที่ลิงค์ไปยัง กิจกรรมเช็คอินทุกวันเพื่อรับ Points</p>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
console.log("Welcome", localStorage.getItem('token'))
export default {
    name:"dashboard",
    data(){
        return {
            user:this.$store.state.auth.user
        }
    }
}
</script>