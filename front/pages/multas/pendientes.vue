<template>
  <v-row class="mt-container">
    <v-col>
      <div class="d-flex justify-space-between">
        <div class="d-flex flex-column">
          <div class="d-flex align-center ga-2">
            <v-icon icon="mdi-invoice-clock-outline" size="large"></v-icon>
            <h1>Multas pendientes</h1>
          </div>
        </div>
        <v-btn
          v-if="concepto.equipo"
          class="float-right"
          color="green-darken-1"
          prepend-icon="mdi-plus"
          variant="outlined"
          @click="crearEvent"
        >
          Crear
        </v-btn>
      </div>
    </v-col>
  </v-row>
  <v-row>
    <v-col cols="12" md="4">
      <v-row>
        <v-col cols="12">
          <MultaEquipoSelect />
        </v-col>
        <v-col cols="12" v-if="concepto.equipo">
          <MultaConceptosList />
        </v-col>
      </v-row>
    </v-col>
    <!--
      <MultaListPagadasPendientes v-if="multaStore.multaEquipo" />
-->

    <v-col cols="12" md="8">
      <div class="text-center" v-if="!concepto.equipo || !multas.length">
        <v-img
          class="ma-auto"
          aspect-ratio="16/9"
          width="300"
          position="top"
          src="/img/empty_state/multas_equipo.svg"
          cover
        />
        <div v-if="!concepto.equipo">
          <p class="text-subtitle-2 font-italic mt-3">
            <i> ~ Selecciona un equipo para cargar los datos ~ </i>
          </p>
        </div>
        <div v-if="concepto.equipo && !multas.length">
          <p class="text-subtitle-2 font-italic mt-3">
            <i> ~ Prueba a crear tu primera multa ~ </i>
          </p>
          <p class="d-flex justify-center mt-3">
            <v-btn
              class="float-right"
              color="green-darken-1"
              prepend-icon="mdi-plus"
              variant="outlined"
              @click="crearEvent"
            >
              Crear primera multa
            </v-btn>
          </p>
        </div>
      </div>
    </v-col>
  </v-row>
</template>

<script setup lang="ts">
import { useEquipoStore } from "~/store/equipo";
import { useMultaStore } from "~/store/multa";
import { useConceptoStore } from "~/store/concepto";

const { getEquipos } = useEquipoStore();
const { concepto } = storeToRefs(useConceptoStore());
const { multas } = storeToRefs(useMultaStore());

const crearEvent = () => {};

onMounted(() => {
  getEquipos();
});
</script>
