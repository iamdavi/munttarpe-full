<template>
  <v-row class="mt-container">
    <v-col>
      <div class="d-flex justify-space-between align-center">
        <div class="d-flex align-center ga-2">
          <v-icon icon="mdi-invoice-check-outline" size="large"></v-icon>
          <h1>Multas pagadas</h1>
        </div>
        <div>
          <MultaEquipoSelect />
        </div>
      </div>
    </v-col>
  </v-row>
  <v-row>
    <v-col cols="12" md="8">
      <v-row>
        <v-col
          cols="12"
          md="6"
          lg="4"
          v-for="jugadorMultas in jugadoresMultas"
          :key="jugadorMultas.id"
        >
          <MultaJugadorList
            v-if="jugadorMultas.multas.some((m) => m.pagada)"
            :jugador="jugadorMultas"
            :showPagadas="true"
          />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12">
      <v-expand-transition>
        <v-row v-if="selectedMultas.length != 0">
          <v-col>
            <v-btn
              @click="deleteMultas()"
              variant="outlined"
              color="red-darken-4"
              prepend-icon="mdi-trash-can-outline"
              block
            >
              Eliminar
            </v-btn>
          </v-col>
        </v-row>
      </v-expand-transition>
    </v-col>
  </v-row>
</template>

<script setup lang="ts">
import { useEquipoStore } from "~/store/equipo";
import { useMultaStore } from "~/store/multa";

const { getEquipos } = useEquipoStore();
const { deleteMultas } = useMultaStore();
const { jugadoresMultas, selectedMultas } = storeToRefs(useMultaStore());

onMounted(() => {
  getEquipos();
});

onBeforeUnmount(() => {
  selectedMultas.value = [];
});
</script>
