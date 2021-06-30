import Vue from 'vue';
import VueX from 'vuex';
import createPersistedState  from 'vuex-persistedstate'

import {modules} from './modules'

Vue.use(VueX);

export default new VueX.Store({
  
  state: {
    number: 1,
  },

  getters: {
    
  },

  mutations: {
    
  },

  actions: {
    
  },

  modules: modules,
})