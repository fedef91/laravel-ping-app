import { createStore } from 'vuex'

import user from "./modules/user";
import auth from "./modules/auth";
import errors from "./modules/errors";
import csrf from "./modules/csrf";

const debug = process.env.NODE_ENV !== "production";

export default createStore({
  modules: {
    user,
    auth,
    errors,
    csrf
  },
  strict: debug
});
