
import {  createRouter, createWebHistory } from "vue-router";

import Welcome from "../Pages/Welcome.vue"

import Auth from "../Pages/Auth/Auth.vue";
import Login from "../Pages/Auth/Login.vue";
import Register from "../Pages/Auth/Register.vue";
import ResetPassword from "../Pages/Auth/ResetPassword.vue";

import NotFound from "../Components/NotFound.vue";

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
    /* Welcome route */
    {
      path: '/',
      name: 'welcome',
      component: Welcome,
    },  
    /* Login and Register routes */
    {
      path: "/auth",
      name: 'auth',
      redirect: '/auth/login',
      component: Auth,
      children:
      [
        {
          path: 'login',
          name: 'login',
          component: Login,
          beforeEnter: ifNotAuthenticated
        },
        {
          path: 'register',
          name: 'register',
          component: Register,
          beforeEnter: ifNotAuthenticated
        }, 
        {
          path: 'reset-password',
          name: 'reset-password',
          component: ResetPassword,
          beforeEnter: ifNotAuthenticated
        }, 
      ]
    },  
    /* Route not exist */
    {
      path: '/:pathMatch(.*)*',
      component:NotFound
  }
  ]
});