/* eslint-disable promise/param-names */
import axios from "axios";

import {
    AUTH_ERROR,
    AUTH_SUCCESS,
    AUTH_LOGOUT,
    AUTH_REQUEST,
    AUTH_REGISTER,
    AUTH_LOGIN,
  } from "../actions/auth";
  
  import { USER_REQUEST } from "../actions/user";
  import { SET_ERROR, CLEAR_ERROR } from "../actions/errors";
  
  const state = {
    token: "",
    status: "",
    hasLoadedOnce: false
  };
  
  const getters = {
    isAuthenticated: state => !!state.token,
    authStatus: state => state.status
  };
  
  const actions = {
    [AUTH_REGISTER]: ({ commit, dispatch } , data) => {
      return new Promise((resolve, reject) => {
        commit(AUTH_REQUEST);
        axios.post("/api/register", data.params)
        .then(resp => {
          localStorage.setItem("user-token",resp.data.success.token)
          axios.defaults.headers.common['Authorization'] = `Bearer ${resp.data.success.token}`;
          dispatch(USER_REQUEST);
          commit(AUTH_SUCCESS, resp.data.success.token);
          resolve(resp);
        })
        .catch(err => {
          commit(AUTH_ERROR);
          dispatch(SET_ERROR, err);
          reject(err)
        })  
      });
    },
    [AUTH_LOGIN]: ({ commit, dispatch } , data) => {
        return new Promise((resolve, reject) => {
          commit(AUTH_REQUEST);
          axios.post("/api/login", data.params)
          .then(resp => {
            localStorage.setItem("user-token",resp.data.success.token)
            axios.defaults.headers.common['Authorization'] = `Bearer ${resp.data.success.token}`;
            dispatch(USER_REQUEST);
            commit(AUTH_SUCCESS, resp.data.success.token);
            resolve(resp);
          })
          .catch(err => {
            commit(AUTH_ERROR);
            dispatch(SET_ERROR, err);
            reject(err)
          })  
        });
      },
    [AUTH_LOGOUT]: ({ commit ,  dispatch}) => {
      return new Promise((resolve, reject) => {
        delete axios.defaults.headers.common['Authorization'];
        localStorage.removeItem("user-token")
        commit(AUTH_SUCCESS, "")
        console.log("Auth logout coming");
        resolve();
      });
    }
  };
  
  const mutations = {
    [AUTH_REQUEST ]: state => {
      state.status = "loading";
    },
    [AUTH_SUCCESS]: (state, token) => {
      state.status = "success";
      state.token = token;
      state.hasLoadedOnce = true;
    },
    [AUTH_ERROR]: state => {
      state.status = "error";
      state.hasLoadedOnce = true;
    },
    [AUTH_LOGOUT]: state => {
      state.token = "";
    }
  };
  
  export default {
    state,
    getters,
    actions,
    mutations
  };
