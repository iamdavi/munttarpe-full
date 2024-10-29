<template>
  <v-row justify="center" align="center" class="h-screen align-content-center">
    <v-col class="text-center text-md-end" md="6" sm="12" cols="12">
      <HomeIcon iconType="full" />
    </v-col>
    <v-col md="6" sm="12" cols="12">
      <h3 class="text-center text-md-start text-h3 font-weight-black">
        {{ $t("hello") }}
        <br />
        <span class="text-green-munttarpe changingtext"></span>
      </h3>
    </v-col>
  </v-row>
</template>
