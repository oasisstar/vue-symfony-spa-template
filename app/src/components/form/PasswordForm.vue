<template>
  <symfony-form method="PATCH" action="/users/password" @onSuccess="onPasswordSuccess" error-class="form-field-error">
    <b-form-group label="Password:" label-for="pwd">
      <b-form-input id="pwd" type="password" name="plainPassword[first]" placeholder="*********" />
    </b-form-group>
    <b-form-group label="Repeat Password:" label-for="rpwd">
      <b-form-input id="rpwd" type="password" name="plainPassword[second]" placeholder="*********" />
    </b-form-group>
    <b-button type="submit" variant="primary">Submit</b-button>
  </symfony-form>
</template>

<script>
export default {
  name: "PasswordForm",
  methods: {
    onPasswordSuccess: function() {
      this.$toasted.show("Password has been updated.");
    }
  }
};
</script>

<style scoped>
</style>
