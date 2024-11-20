<template>
  <v-row class="mt-container">
    <v-col>
      <div class="d-flex justify-space-between align-center">
        <div class="d-flex align-center ga-2">
          <v-icon icon="mdi-trophy-outline" size="large"></v-icon>
          <h1>Partidos</h1>
        </div>
      </div>
    </v-col>
  </v-row>
  <v-autocomplete
    v-model="equipoSelected"
    :items="allEquipos"
    hint="Selecciona un equipo para ver las multas y los pagos"
    filterable
    persistent-hint
    color="blue-grey-lighten-2"
    item-text="nombre"
    label="Equipo *"
    :rules="[(v) => !!v || 'Debes seleccionar al menos 1 equipo']"
    variant="underlined"
  >
    <template v-slot:selection="{ item }">
      <div class="me-3 icon-dinamic-wrapper">
        <v-img :src="item.raw.logo"></v-img>
      </div>
      {{ item.raw.nombre }}
    </template>
    <template v-slot:item="{ props, item }">
      <v-list-item v-bind="props" :title="item.raw.nombre">
        <template v-slot:prepend>
          <div class="me-3 icon-dinamic-wrapper">
            <v-img :src="item.raw.logo"></v-img>
          </div>
        </template>
      </v-list-item>
    </template>
  </v-autocomplete>
</template>

<script setup lang="ts">
import { ref } from "vue";
import allEquipos from "~/data/allEquipos.json";

const equipoSelected = ref(null);

onMounted(() => {
  console.log(allEquipos);
});
</script>
