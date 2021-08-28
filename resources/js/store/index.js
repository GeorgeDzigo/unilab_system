import Vuex from 'vuex'
import Vue from 'vue'

// Modules
import main from './modules/main'

Vue.use(Vuex)

window.store = new Vuex.Store({
    modules: {
        main
    },
    strict: true

});

//
// export default new Vuex.Store({
//     state: window.App.initialState || {},
//     modules: {
//         main
//     },
//     strict: true
// })
