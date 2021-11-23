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
    
    [USER_REQUEST]: ({ commit, dispatch }, data) => {
      return new Promise((resolve, reject) => {
        commit(USER_REQUEST); 
        axios.get("api/user/info")
        .then(resp => { 
          commit(USER_SUCCESS, resp) 
          resolve(resp);
        })
        .catch( err => { 
          commit(USER_ERROR) 
          dispatch(AUTH_LOGOUT);
        }) 
      });  
    },
    [LOGOUT_REQUEST]: ({ commit, dispatch }) => {
      return new Promise((resolve, reject) => {
        commit(USER_REQUEST);
        axios.post("api/logout")
        .then(resp => { 
          commit(LOGOUT_SUCCESS);
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
    [USER_SUCCESS]: (state, resp) => {
      state.status = "success";
      state.profile = resp.data.success.info;
    },
    [USER_ERROR]: state => {
      state.status = "error";
    },
    [AUTH_LOGOUT]: state => {
      state.profile = {};
    }
  };
  
  export default {
    state,
    getters,
    actions,
    mutations
  };