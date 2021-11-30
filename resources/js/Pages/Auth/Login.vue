<template> 
<form class="mt-8 space-y-6 lg:px-10 md:px-5 pb-5" @submit.prevent="login">
  <error v-if="errorStatus"></error>
  <div>
    <label for="email" class="block text-gray-700 text-base font-bold mb-2">Email address:</label>
    <input id="email" name="email" type="email" autocomplete="email" v-model="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address"
    pattern="^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$">
      <p v-if="inputErrors['email'].length > 0 && this.email.length > 0" class="text-red-500 text-xs italic">{{inputErrors['email']}}</p>
  </div>
  <div>
    <label for="password" class="block text-gray-700 text-base font-bold mb-2">Password:</label>
    <input id="password" name="password" type="password" autocomplete="current-password" v-model="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
      <p v-if="inputErrors['password'].length > 0 && this.password.length > 0" class="text-red-500 text-xs italic">{{inputErrors['password']}}</p>
  </div>

  <div class="mb-10">
    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-base text-bold font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
      <span class="absolute left-0 inset-y-0 flex items-center pl-3">
        <!-- Heroicon name: solid/lock-closed -->
        <svg v-if="!isEnable" class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
        </svg>
        <svg v-if="isEnable" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z" />
        </svg>
      </span>
      Sign in
    </button>
  </div>
  <div class="flex justify-center">
    <div class="text-base font-bold">
     <router-link
        :to="{ name : 'reset-password'}"
        class="font-medium text-indigo-600 hover:text-indigo-500"
      >
        Forgot your password?
      </router-link>
    </div>
  </div>
</form>
   

</template>

<script>
import { mapState, mapGetters } from "vuex";
import { AUTH_LOGIN } from "../../store/actions/auth";
//import { useReCaptcha } from 'vue-recaptcha-v3';
import Error from "../../Components/Errors/Error.vue";

export default ({
  props:{

  },
  components:{
    Error,
  },
  mounted(){
      console.log("Login component mounted");
  },
  data(){
    return {
        email : "",
        password : "",
        inputErrors:{
          email:"",
          password:"",
        },
        notSubmitted: true,
        selectedSign: true,
    }
  },
   
  computed: {
    isEnable() {
      return this.validateEmail() && this.validatePassword() && this.notSubmitted;
    },
    ...mapState({
      
    }),
    ...mapGetters(["errorStatus"]),
  },
  methods : {
    validatePassword(){
      if(this.password.length < 8){
        this.inputErrors.password = "Password field are required > 8 chars."
        return false;
      }
      else{
        this.inputErrors.password = ""
        return true;
      }
    },
    validateEmail() {
      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)){
        this.inputErrors.email = ""
        return true;
      } 
      else{
        this.inputErrors.email = "Please insert a valid email address."
        return false;
      } 
    }, 
    startSubmit(){
      this.notSubmitted = false;
    },
    stopSubmit(){
       this.notSubmitted = true;
    },
    checkForm(){
      return this.isEnable;
    },
    login: function() {
      const result = this.checkForm();
      console.log("result: "+result)
      if(!result)return;
      this.startSubmit();
      const data = {
          url: "/api/login",
          params: { "email" : this.email, "password" : this.password , "recaptcha": token }
      }
      this.$store.dispatch(AUTH_LOGIN, data).then(() => {
        this.$router.push({ name: 'welcomeUser' });
      })
      .catch(err =>{
        this.stopSubmit();
      })
      /*this.recaptcha().then(token =>{
        const data = {
          url: "/api/login",
          //params: { "email" : this.email, "password" : this.password , "recaptcha": token }
        }
        this.$store.dispatch(AUTH_LOGIN data).then(() => {
          this.$router.push({ name: 'welcomeUser' });
        })
        .catch(err =>{
          this.stopSubmit();
        })
      }).catch( err => {
        console.log(err);
      })*/
     
    }
  }, 
  setup() {
      /*
    const { executeRecaptcha, recaptchaLoaded } = useReCaptcha()
    const recaptcha = async () => {
      await recaptchaLoaded()
      const token = await executeRecaptcha("login")
      return token;
    }
    return { recaptcha }*/
  },
});
</script>