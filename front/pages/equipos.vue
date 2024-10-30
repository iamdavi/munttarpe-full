<template>
  <v-row class="mt-container">
    <v-col>
      <div class="d-flex justify-space-between align-center">
        <div class="d-flex align-center ga-2">
          <v-icon icon="mdi-account-group" size="large"></v-icon>
          <h1>Equipos</h1>
        </div>
        <v-btn
          class="float-right"
          color="green-darken-1"
          prepend-icon="mdi-plus"
          variant="outlined"
          @click="dialog = true"
        >
          Crear
        </v-btn>
      </div>
    </v-col>
  </v-row>
  <EquipoModal
    :isOpen="dialog"
    actionType="crear"
    @closeDialog="dialog = false"
  />
</template>

<script setup>
import { storeToRefs } from "pinia";
import { useEquipoStore } from "~/store/equipo";
import { ref, onMounted } from "vue";

const { getEquipos } = useEquipoStore();
const { equipos } = storeToRefs(useEquipoStore());
const dialog = ref(false);

onMounted(() => {
  getEquipos();
});
</script>
