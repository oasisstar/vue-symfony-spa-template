import Vue from "vue";
import Router from "vue-router";
import Home from "./components/views/Home.vue";
import About from "./components/views/About.vue";
import Login from "./components/views/auth/Login";
import page404 from "./components/views/Page404";
import SignUp from "./components/views/auth/SignUp";
import Resetting from "./components/views/auth/Resetting";
import ResettingConfirmed from "./components/views/auth/ResettingConfirmed";
import Settings from "./components/views/profile/Settings";
import EmailConfirmed from "./components/views/auth/EmailConfirmed";

Vue.use(Router);

let router = new Router({
  routes: [
    {
      path: "/",
      name: "home",
      component: Home
    },
    {
      path: "/about",
      name: "about",
      component: About,
      meta: { auth: true }
    },
    {
      path: "/settings",
      name: "settings",
      component: Settings,
      meta: { auth: true, title: "Settings" }
    },
    {
      path: "/resetting",
      name: "resetting",
      component: Resetting,
      meta: { auth: false }
    },
    {
      path: "/resetting/confirm/:confirmationToken",
      name: "resseting_confirm",
      component: ResettingConfirmed,
      meta: { auth: false }
    },
    {
      path: "/register",
      name: "register",
      component: SignUp,
      meta: { auth: false }
    },
    {
      path: "*",
      name: "404",
      component: page404
    },
    {
      path: "/login",
      name: "login",
      component: Login,
      meta: {
        title: "Login",
        auth: false
      }
    },
    {
      path: "/login/confirm/:confirmationToken",
      name: "confirm_email",
      component: EmailConfirmed,
      meta: { title: "Confirmation" }
    }
  ],
  mode: "history"
});

router.beforeEach((to, from, next) => {
  if (undefined !== to.meta.title) {
    document.title = to.meta.title;
  } else {
    document.title = "Example";
  }
  next();
});

export default router;
