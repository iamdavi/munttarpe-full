<template>
  <v-dialog v-model="props.isOpen" max-width="500" persistent>
    <v-form @submit.prevent="handleModalForm">
      <v-card
        :prepend-icon="
          evento.recurrente ? 'mdi-calendar-refresh' : 'mdi-calendar-plus'
        "
        title="Crear evento"
        :subtitle="dialogSubtitle"
      >
        <template v-slot:append>
          <v-btn icon="mdi-close" variant="text" @click="closeDialog"></v-btn>
        </template>
        <v-card-text class="pb-0">
          <v-select
            v-if="evento.recurrente"
            label="Días de la semana *"
            :items="daysOfWeek"
            multiple
            v-model="evento.dias"
            :rules="[(v) => !!v || 'Debes seleccionar un día']"
            variant="underlined"
          ></v-select>
          <v-select
            label="Tipo de evento *"
            :items="eventoTypes"
            v-model="evento.tipo"
            :rules="[(v) => !!v || 'Debes seleccionar el tipo de evento']"
            variant="underlined"
          ></v-select>
          <v-autocomplete
            max-width="500px"
            v-model="evento.equipos"
            :items="equipos"
            color="blue-grey-lighten-2"
            item-text="nombre"
            label="Equipo *"
            chips
            closable-chips
            multiple
            :rules="[(v) => !!v || 'Debes seleccionar al menos 1 equipo']"
            variant="underlined"
          >
            <template v-slot:chip="{ props, item }">
              <v-chip v-bind="props" :text="item.raw.nombre">
                <template v-slot:prepend>
                  <div class="me-3 icon-dinamic-wrapper">
                    <HomeIconDynamic :color="item.raw.color" />
                  </div>
                </template>
              </v-chip>
            </template>

            <template v-slot:item="{ props, item }">
              <v-list-item v-bind="props" :title="item.raw.nombre">
                <template v-slot:prepend>
                  <div class="me-3 icon-dinamic-wrapper">
                    <HomeIconDynamic :color="item.raw.color" />
                  </div>
                </template>
              </v-list-item>
            </template>
          </v-autocomplete>
          <v-text-field
            v-model="evento.hora"
            :active="timeModal"
            :focused="timeModal"
            variant="underlined"
            label="Hora del evento"
            prepend-icon="mdi-clock-time-four-outline"
            readonly
          >
            <v-dialog v-model="timeModal" activator="parent" width="auto">
              <v-card>
                <v-time-picker
                  class="px-1 py-3 pa-sm-4"
                  v-if="timeModal"
                  v-model="evento.hora"
                  width="auto"
                ></v-time-picker>
                <v-card-actions>
                  <v-btn block variant="outlined" @click="timeModal = false">
                    Establecer hora
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-text-field>
          <v-textarea
            label="Descripción del evento"
            variant="outlined"
            v-model="evento.descripcion"
          ></v-textarea>
        </v-card-text>
        <template v-slot:actions>
          <v-btn @click="closeDialog"> Cancelar </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            type="submit"
            variant="outlined"
            color="green-darken-1"
            :loading="loading"
          >
            Guardar
          </v-btn>
        </template>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { eventoTypes, daysOfWeek } from "~/data/eventoData";
import { useEventoStore } from "~/store/evento";
import { useEquipoStore } from "~/store/equipo";

const { createEvento, clearEvento } = useEventoStore();
const { evento } = storeToRefs(useEventoStore());
const { equipos } = storeToRefs(useEquipoStore());

const props = defineProps({
  isOpen: Boolean,
});

const emit = defineEmits(["closeDialog"]);

// Estado local para el diálogo y formulario
const loading = ref(false);
const timeModal = ref(false);
const rules = {
  required: (value: any) => !!value || "Este campo es obligatorio",
};

// Función para cerrar el diálogo
const closeDialog = () => {
  clearEvento();
  emit("closeDialog");
};

// Función para guardar los datos
const handleModalForm = async (event: any) => {
  loading.value = true;
  const results = await event;
  try {
    if (!results.valid) throw new Error("Formulario inválido");
    await createEvento();
  } catch (error) {
    console.log(error);
  } finally {
    loading.value = false;
    emit("closeDialog");
  }
};

const dialogSubtitle = computed(() => {
  return evento.value.recurrente
    ? "Periodico"
    : `Para el día ${evento.value.fecha}`;
});
</script>
