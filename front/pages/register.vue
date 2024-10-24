<template>
  <div>
    <div class="title">
      <h2>Register</h2>
    </div>
    <div class="container form">
      <label for="uname"><b>Email</b></label>
      <input
        v-model="user.email"
        type="text"
        class="input"
        placeholder="Enter Email"
        name="uname"
        required
      />

      <label for="psw"><b>Password</b></label>
      <input
        v-model="user.password"
        type="password"
        class="input"
        placeholder="Enter Password"
        name="psw"
        required
      />

      <button @click.prevent="register" class="button">Register</button>
    </div>
  </div>
</template>
<script lang="ts" setup>
import { storeToRefs } from "pinia"; // import storeToRefs helper hook from pinia
import { useAuthStore } from "~/store/auth"; // import the auth store we just created

const { registerUser } = useAuthStore(); // use authenticateUser action from  auth store
const { authenticated } = storeToRefs(useAuthStore()); // make authenticated state reactive with storeToRefs

const user = ref({
  email: "itsdavid.otero@gmail.com",
  password: "prueba",
});
const router = useRouter();

const register = async () => {
  await registerUser(user.value); // call authenticateUser and pass the user object
  // redirect to homepage if user is authenticated
  if (authenticated) {
    router.push("/");
  }
};
</script>
