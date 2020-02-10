<template>
    <b-container>
        <b-row class="justify-content-center">
            <register-form></register-form>
        </b-row>
    </b-container>
</template>

<script>
import RegisterForm from "@/components/form/RegisterForm";

export default {
  name: "SignUp",
  components: { RegisterForm }
};
</script>

<style scoped>
</style>
