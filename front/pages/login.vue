<template>
  <v-row class="h-100" align="center" justify="center">
    <v-col lg="4" md="6" sm="12">
      <v-card variant="tonal">
        <v-img
          class="align-end text-white"
          position="top"
          height="200"
          src="/img/login.jpg"
          gradient="to right top, rgb(9,73,4), rgba(9,73,4,50%)"
          cover
        >
          <v-card-title class="text-h5 pb-0"> Inicia sesión </v-card-title>
          <v-card-subtitle class="white-space-unset mb-2">
            Accede al panel desde el que podrás administrar el contenido de la
            página
          </v-card-subtitle>
        </v-img>
        <v-form class="h-100 d-flex flex-column" @submit.prevent="login">
          <v-card-text class="pb-1">
            <v-text-field
              type="email"
              label="Email"
              placeholder="Ingrese email"
              variant="underlined"
              v-model.trim="user.email"
            ></v-text-field>
            <v-text-field
              type="password"
              label="Contraseña"
              placeholder="********"
              variant="underlined"
              v-model.trim="user.password"
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn type="submit" variant="tonal" block :loading="loading">
              Acceder
            </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-col>
  </v-row>
</template>

<script lang="ts" setup>
import { storeToRefs } from "pinia"; // import storeToRefs helper hook from pinia
import { useAuthStore } from "~/store/auth"; // import the auth store we just created
import { ref } from "vue";

const { authenticateUser } = useAuthStore(); // use authenticateUser action from  auth store
const { authenticated } = storeToRefs(useAuthStore()); // make authenticated state reactive with storeToRefs
const router = useRouter();
const loading = ref(false);

const user = ref({
  email: "itsdavid.otero@gmail.com",
  password: "prueba",
});

const login = async () => {
  loading.value = true;
  await authenticateUser(user.value); // call authenticateUser and pass the user object
  loading.value = false;
  // redirect to homepage if user is authenticated
  if (authenticated) {
    router.push("/");
  }
};
</script>
