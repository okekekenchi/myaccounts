import Vue from 'vue'
import Vuex from 'vuex'

import Main from './modules/main'
import User from './modules/user'
import DynamicModule from './modules/dynamic_module'

Vue.use(Vuex)

export default new Vuex.Store({

    modules:
    {
        Main,
        User,
        Role: new DynamicModule('roles'),
    }
})
