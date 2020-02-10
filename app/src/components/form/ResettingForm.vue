<template>
  <div>
    <symfony-form v-if="!isAlertShown" method="POST" action="/auth/resetting" @onSuccess="showSuccessAlert">
      <b-form-group label="Email address:" label-for="email">
        <b-form-input id="email" type="email" name="email" placeholder="Enter your email" />
      </b-form-group>
      <b-button variant="primary" type="submit">Submit</b-button>
    </symfony-form>
    <div v-if="isAlertShown">
      <p>If the email you typed matches yours, you will receive a message which contains a link you must click to reset your password.</p>
      <p>Note: You can only request a new password once within 2 hours.</p>
      <p>If you don't get an email check your spam folder or try again.</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "ResettingForm",
  data() {
    return {
      email: null,
      isAlertShown: false
    };
  },
  methods: {
    showSuccessAlert: function() {
      this.isAlertShown = true;
    }
  }
};
</script>

<style scoped>
</style>
