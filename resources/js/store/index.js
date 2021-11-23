import { createStore } from 'vuex'

import user from "./modules/user";
import auth from "./modules/auth";
import errors from "./modules/errors";


const debug = process.env.NODE_ENV !== "production";

export default createStore({
  modules: {
    user,
    auth,
    errors,
  },
  strict: debug
});
