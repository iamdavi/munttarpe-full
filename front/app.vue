<template>
  <v-app>
    <AppNavbar />
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
    <Snackbar />
  </v-app>
</template>

<script setup lang="ts">
import "~/assets/css/style.css";
</script>
