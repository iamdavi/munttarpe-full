<template>
  <v-row class="mt-container">
    <v-col>
      <div class="d-flex justify-space-between align-center">
        <div class="d-flex align-center ga-2">
          <v-icon icon="mdi-handball" size="large"></v-icon>
          <h1>Jugadores</h1>
        </div>
        <v-btn
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
  <v-row class="d-flex justify-center ga-8 pb-6">
    <div v-for="jugador in jugadores" :key="jugador.id">
      <JugadorCard
        :jugador="jugador"
        :is-preview="false"
        @editJugadorData="editJugadorEvent(jugador)"
      />
    </div>
  </v-row>
  <JugadorModal
    :isOpen="dialog"
    :actionType="actionType"
    @closeDialog="closeHandler"
  />
</template>

<script setup lang="ts">
import { storeToRefs } from "pinia";
import { useJugadorStore } from "~/store/jugador";
import { useEquipoStore } from "~/store/equipo";
import { ref, onMounted } from "vue";
import type { Jugador } from "~/interfaces/jugadorInterfaces";

const { getJugadores, clearJugador, setJugador } = useJugadorStore();
const { jugadores } = storeToRefs(useJugadorStore());
const { getEquipos } = useEquipoStore();

const dialog = ref(false);
const actionType = ref("crear");

const editJugadorEvent = (jugador: Jugador) => {
  setJugador(jugador);
  actionType.value = "edit";
  dialog.value = true;
};

const closeHandler = () => {
  dialog.value = false;
  clearJugador();
};

const crearEvent = () => {
  clearJugador();
  actionType.value = "crear";
  dialog.value = true;
};

onMounted(() => {
  getJugadores();
  getEquipos();
});
</script>
