<template>
  <v-card>
    <v-card class="mx-auto" :disabled="loading" :loading="loading">
      <template v-slot:title>
        <span class="font-weight-black">{{ equipo.nombre }}</span>
      </template>

      <template v-slot:prepend>
        <v-avatar color="transparent border-0 me-3" rounded="0" size="x-small">
          <HomeIconDynamic :color="equipo.color" />
        </v-avatar>
      </template>

      <v-card-actions>
        <v-btn
          text="Eliminar"
          @click="handleDeleteEvent"
          prepend-icon="mdi-trash-can-outline"
          color="red"
          variant="outlined"
        >
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          text="Editar"
          prepend-icon="mdi-pencil-outline"
          @click="handleEditButton"
          color="primary"
          variant="outlined"
        >
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-card>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useEquipoStore } from "~/store/equipo";
import type { Equipo } from "~/interfaces/equipoInterfaces";

interface Props {
  equipo: Equipo;
}
const emits = defineEmits(["editEquipoEvent"]);
const props = defineProps<Props>();
const { deleteEquipo, setEquipo } = useEquipoStore();

const equipo = ref(props.equipo);
const loading = ref(false);

const handleDeleteEvent = async () => {
  loading.value = true;
  await deleteEquipo(equipo.value.id);
  loading.value = false;
};

const handleEditButton = () => {
  setEquipo(equipo.value);
  emits("editEquipoEvent");
};
</script>
