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
    <v-col cols="12" md="8" v-if="equipoSelected && jugadoresMultas.length">
      <v-row>
        <v-col cols="12" md="4">
          <v-card title="Total de multas">
            <v-card-subtitle class="show-all-text"
              >Gráfico donde se ve la cantidad porcentual que ha pagado cada
              jugador</v-card-subtitle
            >
            <v-card-text>
              <MultaChartDoughnut type="porcentajeJugadores" />
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script setup lang="ts">
import { useEquipoStore } from "~/store/equipo";
import { useMultaStore } from "~/store/multa";

const { getEquipos } = useEquipoStore();
const { equipoSelected } = storeToRefs(useEquipoStore());
const { jugadoresMultas } = storeToRefs(useMultaStore());

onMounted(() => {
  getEquipos();
});
</script>
