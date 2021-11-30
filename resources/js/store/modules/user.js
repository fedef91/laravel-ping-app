import { 
    USER_REQUEST,
    LOGOUT_REQUEST, 
    USER_ERROR, 
    USER_SUCCESS,
    LOGOUT_SUCCESS 
  } from "../actions/user";
  
  import { AUTH_LOGOUT, AUTH_REQUEST } from "../actions/auth";
  
  const state = { 
    status: "", 
    profile: { }, 
  };
  
  const getters = {
    getProfile: state => state.profile,
    isProfileLoaded: state => !!state.profile.name
  };
  
  const actions = {
    
    [USER_REQUEST]: ({ commit }, resp) => {
      
    },

    [LOGOUT_REQUEST]: ({ commit, dispatch }) => {
      return new Promise((resolve, reject) => {
        commit(USER_REQUEST);
        axios.post("api/logout")
        .then(resp => { 
          commit(USER_SUCCESS);
          dispatch(AUTH_LOGOUT);
          resolve();
        })
        .catch( err => { 
          commit(USER_ERROR) 
          dispatch(AUTH_LOGOUT);
        }) 
      });  
    }
  };
  
  const mutations = {
    [USER_REQUEST]: state => {
      state.status = "loading";
    },
    [LOGOUT_SUCCESS]: state => {
      state.status = "success";
      state.profile = {};
    },
    [USER_SUCCESS]: (state, data) => {
      state.status = "success";
      state.profile = data.user_info;
    },
    [USER_ERROR]: state => {
      state.status = "error";
      state.profile = {};
    },

  };
  
  export default {
    state,
    getters,
    actions,
    mutations
  };