<template>
  <v-snackbar v-model="showSnackbar" :timeout="2000" :color="type" bottom right class="mb-3">
    {{ message }}
    <template v-slot:actions>
      <v-btn
        color="blak"
        variant="text"
        @click="closeSnackbar"
        icon="mdi-close"
      ></v-btn>
    </template>
  </v-snackbar>
</template>

<script setup>
import { useNotificacionStore } from "~/store/notificacion";

const notificationStore = useNotificacionStore();
const { showSnackbar, message, type } = storeToRefs(useNotificacionStore());
const closeSnackbar = () => {
  notificationStore.hideSnackbar();
};
</script>
