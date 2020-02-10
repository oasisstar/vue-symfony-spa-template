import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import BootstrapVue from "bootstrap-vue";
import "./registerServiceWorker";
import "bootstrap-vue/dist/bootstrap-vue.css";
import "./assets/sass/common.sass";
import axios from "axios";
import VueAxios from "vue-axios";
import VueAuth from "@websanova/vue-auth";
import VueAuthCore from "./security/core";
import VueToastr from "vue-toasted";
import symfonyForm from "vue-symfony-form";
import connector from "./security/formConnector";

axios.defaults.withCredentials = true;
axios.defaults.crossDomain = true;

Vue.router = router;
Vue.http = axios;

Vue.component("vue-toastr", VueToastr);
Vue.use(symfonyForm, {
  connector: connector
});
Vue.use(VueToastr, {
  duration: 800
});
Vue.use(VueAxios, axios);
Vue.use(VueAuth, VueAuthCore);
Vue.use(BootstrapVue);

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
