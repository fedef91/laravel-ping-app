<template>
    <main class="min-h-screen pt-16 bg-gray-100">
        <div class="bg-blue-500 sm:bg-pink-500 md:bg-red-500 lg:bg-green-500 xl:bg-yellow-500">
            blue: xs, pink: small, red: md, green: lg, yellow: xl
        </div>
       <router-view></router-view>
    </main>
  
</template>

<script>
    export default {
        name: "app",
        components:{
            //navigationComponent,
            //FooterComponent
        },
        data(){
            return {loading : false};
        },
        created: function() {
           
        },
    }
</script>
