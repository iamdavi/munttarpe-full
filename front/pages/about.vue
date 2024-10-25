<template>
  <div>About Page</div>
</template>

<script lang="ts" setup>
import { useAuthStore } from "~/store/auth"; // import the auth store we just created
import { onMounted } from "vue";

const authStore = useAuthStore(); // use authenticateUser action from  auth store

onMounted(async () => {
  if (!authStore.user) {
    await authStore.getUserData(); // Llama a la acción en el store de Pinia
  }
});
</script>
