<template>
  <b-container>
    <b-alert :show="confirmStatus === true" variant="success">
      Email was confirmed. You can <router-link to="/login">login</router-link> now.
    </b-alert>
    <b-alert :show="confirmStatus === false" variant="danger">
      There are no users attached to this account. Please try <router-link to="/register">Sign Up</router-link> again.
    </b-alert>
  </b-container>
</template>

<script>
import { apiJoin } from "../../../utils/path";

export default {
  name: "EmailConfirmed",
  data() {
    return {
      confirmStatus: null
    };
  },
  mounted: function() {
    let token = this.$route.params.confirmationToken;

    this.axios
      .post(apiJoin("/auth/register/confirm"), {
        token: token
      })
      .then(() => {
        this.confirmStatus = true;
        if (this.$auth.check()) {
          this.$auth.logout({
            redirect: "/login"
          });
        }
      })
      .catch(() => (this.confirmStatus = false));
  }
};
</script>

<style scoped>
</style>
