<template>
  <div class="container">
    <b-card no-body>
      <b-tabs card lazy>
        <b-tab title="Personal data" id="profile" active>
          <profile-form :profile="profile" />
        </b-tab>
        <b-tab id="account" title="Account settings">
          <b-card title="Change email" class="text-center">
            <email-form :email="email" />
          </b-card>
          <b-card title="Change password" class="text-center mt-2">
            <password-form />
          </b-card>
        </b-tab>
      </b-tabs>
    </b-card>
  </div>
</template>

<script>
import EmailForm from "@/components/form/EmailForm";
import PasswordForm from "@/components/form/PasswordForm";
import ProfileForm from "@/components/form/ProfileForm";

export default {
  name: "Settings",
  components: {
    EmailForm,
    PasswordForm,
    ProfileForm
  },
  data() {
    return {
      user: this.$auth.user()
    };
  },
  computed: {
    email: function() {
      return this.user.email;
    },
    profile: function() {
      return this.user.profile;
    }
  }
};
</script>

<style scoped>
</style>
