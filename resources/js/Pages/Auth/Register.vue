<template>
<form class="mt-8 space-y-6 lg:px-10 md:px-5 pb-5" @submit.prevent="register">
    <input type="hidden" name="remember" value="true">
 
    <div >
      <label for="name" class="block text-gray-700 text-base font-bold mb-2">Name:</label>
      <input id="name" name="name" type="text" autocomplete="name" v-model="name" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Name">
    </div>
    <div >
      <label for="email-address" class="block text-gray-700 text-base font-bold mb-2">Email address:</label>
      <input id="email-address" name="email" type="email" autocomplete="email"  v-model="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900  focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address">
    </div>
    <div>
      <label for="password" class="block text-gray-700 text-base font-bold mb-2">Password:</label>
      <input id="password" name="password" type="password" autocomplete="current-password"  v-model="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
    </div>
    <div >
      <label for="password_confirmation" class="block text-gray-700 text-base font-bold mb-2">Password confirm:</label>
      <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="password_confirmation"  v-model="password_confirmation" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
    </div>
  
  <div>
    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      <span class="absolute left-0 inset-y-0 flex items-center pl-3">
        <!-- Heroicon name: solid/lock-closed -->
        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
        </svg>
      </span>
      Join us
    </button>
  </div>
</form>   
</template>

<script>
import { mapState } from "vuex";
import { AUTH_REGISTER } from "../../store/actions/auth";

export default ({
    setup() {
        
    },
    mounted(){
        console.log("Register component mounted");
    },
    data(){
      return {
        name : "",
        email : "",
        password : "",
        password_confirmation : "",
      }
    },
    computed: {
      ...mapState({
        //token: state => `${state.recaptcha.token}`,
        //status: state => `${state.recaptcha.status}`,
      })
    },
    methods : {
      register: function() {
        const data = {
          url: "/api/register",
          params: {
            name : this.name,
            email : this.email,
            password : this.password,
            password_confirmation : this.password_confirmation,
            crash_validation : "crash validation"
            //recaptcha : this.token
          }
        }
        this.$store.dispatch( AUTH_REGISTER, data).then(() => {
          console.log("pushboard")
          this.$router.push("board");
        });
      }
    },
})
</script>