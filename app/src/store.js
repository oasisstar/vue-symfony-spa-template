import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    currentJWT: ""
  },
  getters: {
    jwt: state => state.currentJWT,
    jwtData: (state, getters) =>
      state.currentJWT ? JSON.parse(atob(getters.jwt.split(".")[1])) : null,
    jwtUsername: (state, getters) =>
      getters.jwtData ? getters.jwtData.username : null,
    jwtRoles: (state, getters) =>
      getters.jwtData ? getters.jwtData.roles : null
  },
  mutations: {
    setJWT(state, token) {
      state.currentJWT = token;
    }
  },
  actions: {
    init({ commit }, { token }) {
      commit("setJWT", token);
    }
  }
});
