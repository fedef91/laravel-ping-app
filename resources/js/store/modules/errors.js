import {
    SET_ERROR,
    SET_ERROR_CLIENT,
    CLEAR_ERROR
  } from "../actions/errors";
  
  const state = {
    status: false,
    errorMessage: "",
    errors: {},
  };
  
  const getters = {
    getErrors: state => state.errors,
    getErrorMessage: state => state.errorMessage, 
    errorStatus: state => state.status
  };
  const actions = {
    [SET_ERROR]: ({ commit,dispatch } , error) => {
        commit(SET_ERROR, error)
    },
    [SET_ERROR_CLIENT]: ({ commit } , error) => {
      commit(SET_ERROR_CLIENT, error)
    },
    [CLEAR_ERROR]: ({ commit }) => {
        commit(CLEAR_ERROR)
    },
  };
   
  const mutations = {
    [SET_ERROR]: (state, error) => {
      state.status = true;
      state.errorMessage = error;
      state.errors = error.response.data;
    },
    [SET_ERROR_CLIENT]: (state, error) => {
      state.status = true;
      state.errorMessage = error;
      state.errors = error;
    },
    [CLEAR_ERROR]: state => {
      state.status = false;
      state.errorMessage = "";
      state.errors = {};
    },
  };
  
  export default {
    state,
    getters,
    actions,
    mutations
  };