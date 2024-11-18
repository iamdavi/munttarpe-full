<template>
  <v-row class="mt-container">
    <v-col>
      <div class="d-flex justify-space-between align-center">
        <div class="d-flex align-center ga-2">
          <v-icon icon="mdi-chart-line" size="large"></v-icon>
          <h1>Estadísticas de multas</h1>
        </div>
      </div>
    </v-col>
  </v-row>
  <v-row>
    <v-col cols="12" md="4">
      <v-row>
        <v-col cols="12">
          <MultaEquipoSelect />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" md="8">
      <MultaChartDoughnut />
    </v-col>
  </v-row>
</template>

<script setup lang="ts">
import { useEquipoStore } from "~/store/equipo";

const { getEquipos } = useEquipoStore();

onMounted(() => {
  getEquipos();
});
</script>
