<template>
  <b-row class="justify-content-center">
    <symfony-form v-if="!isAlertShown" action="/auth/resetting/confirm" method="PATCH" data-prefix="data" @onSuccess="showSuccessAlert" error-class="form-field-error">
      <b-form-group label="Password:" label-for="pwd">
        <b-form-input id="pwd" type="password" name="data[plainPassword][first]" placeholder="*********" />
      </b-form-group>
      <b-form-group label="Repeat Password:" label-for="rpwd">
        <b-form-input id="rpwd" type="password" name="data[plainPassword][second]" placeholder="*********" />
      </b-form-group>
      <input type="hidden" name="token" :value="confirmToken" />
      <b-button variant="primary" type="submit">Submit</b-button>
    </symfony-form>
    <b-alert variant="success" :show="isAlertShown">
      <p>Your password was successfully changed.</p>
    </b-alert>
  </b-row>
</template>

<script>
export default {
  name: "ResettingConfirmed",
  data() {
    return {
      isAlertShown: false,
      confirmToken: this.$route.params.confirmationToken
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
