require('./bootstrap');

import { createApp } from "vue";
import router from "./router";
import store from "./store";
import App from "./Layout/App.vue";
//import { VueReCaptcha } from 'vue-recaptcha-v3';


const app = createApp({ 
    components: {
        App
    }
});

app.use(router).use(store).mount("#app");