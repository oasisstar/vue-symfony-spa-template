<template>
    <b-container>
        <b-alert :show="showError" variant="danger">
            Invalid credentials
        </b-alert>
        <b-row class="justify-content-center">
            <b-form @submit.prevent="submit">
                <b-form-group label="Email address:" label-for="email">
                    <b-form-input id="email" v-model="email" type="email" name="username" placeholder="Enter your email" />
                </b-form-group>
                <b-form-group label="Password:" label-for="pwd">
                    <b-form-input id="pwd" v-model="password" type="password" name="password" placeholder="***********" />
                </b-form-group>
                <b-form-checkbox v-model="rememberMe">
                    Remember me
                </b-form-checkbox>
                <div class="w-100">
                    <router-link to="/resetting">Forgot password?</router-link>
                    <b-button variant="primary" type="submit" class="ml-2">Submit</b-button>
                </div>
            </b-form>
        </b-row>
    </b-container>
</template>

<script>
export default {
  name: "Login",
  data() {
    return {
      email: null,
      password: null,
      rememberMe: false,
      showError: false
    };
  },
  methods: {
    submit: function() {
      this.$auth
        .login({
          data: { username: this.email, password: this.password },
          rememberMe: this.rememberMe
        })
        .catch(() => {
          this.showError = true;
        });
    }
  }
};
</script>

<style scoped>
</style>
