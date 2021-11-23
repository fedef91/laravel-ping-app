
import {  createRouter, createWebHistory } from "vue-router";

import Welcome from "../Pages/Welcome.vue"

import store from "../store";

/* check if an user is not auth */
const ifNotAuthenticated = (to, from, next) => {
  if (!store.getters.isAuthenticated) {
    next();
    return;
  }
  else next({ name: 'welcome' });
};

/* check if an user is not auth */
const ifAuthenticated = (to, from, next) => {
  if (store.getters.isAuthenticated) {
    next();
    return;
  }
  else next({ name: 'login' });
};

export default createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'welcome',
      component: Welcome,
    },  
  ]
});