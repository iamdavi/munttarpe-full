<template>
  <div>Hello Home Page</div>
</template>
<script lang="ts" setup>
definePageMeta({
  middleware: "auth",
});
</script>
