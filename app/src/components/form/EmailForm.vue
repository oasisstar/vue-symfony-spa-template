<template>
  <symfony-form method="POST" action="/users/email" @onSuccess="onEmailSuccess" error-class="form-field-error">
    <b-form-group label="Email address:" label-for="email">
      <b-form-input id="email" type="email" name="tempEmail" placeholder="Enter your email" v-model="email" />
    </b-form-group>
    <b-button type="submit" variant="primary">Submit</b-button>
  </symfony-form>
</template>

<script>
export default {
  name: "EmailForm",
  props: {
    email: { type: String, required: true }
  },
  methods: {
    onEmailSuccess: function() {
      this.$toasted.show("Email has been updated.");
    }
  }
};
</script>

<style scoped>
</style>
