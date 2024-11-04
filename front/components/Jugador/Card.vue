<template>
  <v-card
    class="player-card mx-auto h-100 d-flex flex-column"
    :color="jugador?.equipo?.color"
    width="300"
    :variant="jugador.rol == 'entrenador' ? 'outlined' : 'tonal'"
  >
    <v-chip
      v-if="jugador.posicion"
      class="payer-logo-position"
      label
      size="small"
      variant="outlined"
    >
      {{ jugador.posicion }}
    </v-chip>
    <div class="px-8 pt-8 player-logo-container">
      <HomeIcon :color="jugador.equipo?.color" iconType="player" />
      <span class="player-logo-name">{{ jugador.mote }}</span>
      <span class="player-logo-number">{{ jugador.dorsal }}</span>
      <!--
      <span class="player-logo-image">
        <v-img :src="jugador.imageUrl" width="131" height="auto"></v-img>
      </span>
        -->
    </div>
    <v-card-item>
      <div>
        <div class="text-button mb-1 text-center">
          {{ jugador.nombre }} {{ jugador.apellidos }}
        </div>
      </div>
    </v-card-item>
    <v-spacer></v-spacer>
    <v-card-actions v-if="!isPreview">
      <v-btn
        variant="tonal"
        prepend-icon="mdi-pencil-outline"
        block
        @click="editJugador"
      >
        Editar
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script setup lang="ts">
import type { Jugador } from "~/interfaces/jugadorInterfaces";

interface Props {
  jugador: Jugador;
  isPreview: boolean;
}
const props = defineProps<Props>();
const emit = defineEmits(["editJugadorData"]);
const jugador = ref(props.jugador);

watch(
  () => props.jugador,
  (newVal) => {
    console.log("Nuevo valor :", newVal);
  }
);

const editJugador = () => {
  emit("editJugadorData");
};
</script>
