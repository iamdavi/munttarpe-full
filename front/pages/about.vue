<template>
  <div>About Page</div>
</template>

<script lang="ts" setup>
import { useAuthStore } from "~/store/auth";
import { onMounted } from "vue";

const authStore = useAuthStore();

onMounted(async () => {
  if (!authStore.user) {
    await authStore.getUserData();
  }
});
</script>
