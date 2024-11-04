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
          @click="createEquipoEvent"
        >
          Crear
        </v-btn>
      </div>
    </v-col>
  </v-row>
  <v-row>
    <v-col cols="12" md="4" lg="3" v-for="equipo in equipos" :key="equipo.id">
      <EquipoList :equipo="equipo" @editEquipoEvent="editEquipoEvent" />
    </v-col>
  </v-row>
  <EquipoModal
    :isOpen="dialog"
    :actionType="actionType"
    @closeDialog="dialog = false"
  />
</template>

<script setup>
import { storeToRefs } from "pinia";
import { useEquipoStore } from "~/store/equipo";
import { ref, onMounted } from "vue";

const { getEquipos, clearEquipo } = useEquipoStore();
const { equipos } = storeToRefs(useEquipoStore());

const dialog = ref(false);
const actionType = ref("crear");

const createEquipoEvent = () => {
  clearEquipo();
  actionType.value = "crear";
  dialog.value = true;
};

const editEquipoEvent = () => {
  actionType.value = "edit";
  dialog.value = true;
};

onMounted(() => {
  getEquipos();
});
</script>
