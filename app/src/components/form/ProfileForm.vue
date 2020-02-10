<template>
  <symfony-form method="PATCH" action="/profiles" @onSuccess="onProfileSuccess" error-class="form-field-error">
    <b-form-group label="First name:" label-for="fname">
      <b-form-input name="firstName" type="text" id="fname" v-model="profile.firstName" />
    </b-form-group>
    <b-form-group label="Last name:" label-for="lname">
      <b-form-input name="lastName" type="text" id="lname" v-model="profile.lastName" />
    </b-form-group>
    <b-form-group label="Facebook URL:" label-for="profile-fburl">
      <b-form-input name="facebookUrl" type="text" id="profile-fburl" v-model="profile.facebookUrl" />
    </b-form-group>
    <b-form-group label="Description:" label-for="profile-description">
      <b-form-textarea name="description" id="profile-description" v-model="profile.description" placeholder="Enter something" />
    </b-form-group>
    <b-button type="submit" variant="primary">Submit</b-button>
  </symfony-form>
</template>

<script>
export default {
  name: "ProfileForm",
  props: {
    profile: { type: Object, required: true }
  },
  methods: {
    onProfileSuccess: function() {
      this.$toasted.show("Profile has been updated.");
    }
  }
};
</script>

<style scoped>
</style>
