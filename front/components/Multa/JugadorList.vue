<template>
  <v-card
    :title="`${props.jugador.mote} (${props.jugador.dorsal})`"
    :subtitle="`${props.jugador.nombre} ${props.jugador.apellidos}`"
    variant="outlined"
  >
    <template v-slot:append>
      <div class="d-flex flex-column align-center pe-n2">
        <span> Total </span>
        <v-sheet rounded class="px-2 py-1">
          <span class="d-flex align-center">
            {{ totalPagoJugador(props.jugador.multas) }}
            <v-icon icon="mdi-currency-eur" size="x-small"></v-icon>
          </span>
        </v-sheet>
      </div>
    </template>
    <v-card-text class="pb-0">
      <v-list lines="one" bg-color="transparent" nav class="pa-0">
        <div v-for="multa in props.jugador.multas" :key="multa.id">
          <v-list-item>
            <template v-slot:prepend="{ isActive }">
              <v-list-item-action start>
                <v-checkbox
                  v-model="selectedMultas"
                  :multiple="true"
                  :value="multa.id"
                  density="compact"
                  :hide-details="true"
                ></v-checkbox>
              </v-list-item-action>
            </template>
            <v-list-item-title
              class="text-body-1 d-flex justify-space-between align-center pt-2 pb-1"
            >
              <div class="d-flex flex-column">
                <span> {{ multa.concepto?.texto }} </span>
                <span class="text-caption text-grey">
                  {{ $formatDate(multa.fecha) }}
                </span>
              </div>
              <span class="d-flex">
                {{ multa.precio }}
                <v-icon icon="mdi-currency-eur" size="x-small"></v-icon>
              </span>
            </v-list-item-title>
          </v-list-item>
        </div>
      </v-list>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
import type { Jugador } from "~/interfaces/jugadorInterfaces";
import type { Multa } from "~/interfaces/multaInterfaces";
import { useMultaStore } from "~/store/multa";

const props = defineProps<{ jugador: Jugador }>();
const { selectedMultas, onlyPendientes } = storeToRefs(useMultaStore());

const totalPagoJugador = (multas: Multa[]) => {
  let result = 0;
  multas.map((m: Multa) => {
    if (m.pagada) return;
    result = m.precio ? result + m.precio : result;
  });
  return result.toFixed(2);
};
</script>
