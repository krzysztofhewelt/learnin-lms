import { createStore } from 'vuex'
import login from "./modules/login"
import course from "./modules/course"
import task from "./modules/task"
import mark from "./modules/mark"
import user from "./modules/user"
import admin from "./modules/admin"
import locale from "./modules/locale"

const store = createStore({
   modules: {
       login,
       course,
       task,
       mark,
       user,
       admin,
       locale
   }
  }
)

export default store
