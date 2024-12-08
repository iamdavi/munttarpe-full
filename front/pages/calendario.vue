<template>
  <v-row class="mt-container">
    <v-col>
      <div class="d-flex justify-space-between align-center">
        <div class="d-flex align-center ga-2">
          <v-icon icon="mdi-calendar-multiselect" size="large"></v-icon>
          <h1>Eventos</h1>
        </div>
      </div>
    </v-col>
  </v-row>
  <v-row>
    <v-col cols="12" md="5" xl="4">
      <v-btn
        class="mb-3"
        block
        variant="outlined"
        prepend-icon="mdi-plus"
        color="green-darken-1"
        @click="openEventoModal(false)"
      >
        Evento para {{ formatedDate }}
      </v-btn>
      <v-date-picker
        :weekdays="[1, 2, 3, 4, 5, 6, 0]"
        first-day-of-week="1"
        width="auto"
        v-model="selectedDate"
        show-adjacent-months
        ref="datePicker"
        elevation="4"
        @update:month="updateEventMarkers"
      >
      </v-date-picker>
    </v-col>
    <v-col cols="12" md="7" xl="8">
      <v-row>
        <v-col cols="12" class="text-end">
          <v-btn
            color="green-darken-1"
            prepend-icon="mdi-calendar-refresh"
            variant="outlined"
            @click="openEventoModal(true)"
          >
            Crear evento recurrente
          </v-btn>
          <p class="text-caption text-grey">
            P.e.: entrenamientos, eventos semanales, recordatorios...
          </p>
        </v-col>
        <v-col
          xl="4"
          md="6"
          sm="12"
          cols="12"
          class="position-relative"
          v-for="event in eventos"
          :key="event.id"
        >
          <EventoCard
            :isPreview="false"
            :evento="event"
            @deleteEvent="showDeleteDialog(event)"
            @editEvent="editEventHandler(event)"
          />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
  <v-dialog v-model="deleteDialog" max-width="500">
    <v-card title="Eliminar evento?" prepend-icon="mdi-calendar-remove-outline">
      <v-divider></v-divider>
      <div class="position-relative card-delete-preview">
        <EventoCard :evento="evento" :isPreview="true" />
      </div>
      <v-divider></v-divider>
      <v-card-actions class="d-flex justify-space-between">
        <v-btn
          @click="deleteDialog = false"
          prepend-icon="mdi-close"
          variant="outlined"
        >
          Cancelar
        </v-btn>
        <v-btn
          prepend-icon="mdi-trash-can-outline"
          color="red"
          variant="outlined"
          @click="deleteEventAction(evento.id)"
        >
          Eliminar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <EventoModal
    :isOpen="dialog"
    @closeDialog="
      dialog = false
      // updateEventMarkers();
    "
  />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useDate } from "vuetify";
import { useEventoStore } from "~/store/evento";
import { useEquipoStore } from "~/store/equipo";
import type { Evento } from "~/interfaces/eventoInterfaces";

const date = useDate();
const selectedDate = ref(new Date());
const formatedDate = ref(date.format(selectedDate, "keyboardDate"));
const datePicker = ref();
const dialog = ref(false);
const deleteDialog = ref(false);

const { getEquipos } = useEquipoStore();
const { getEventos, setEvento, deleteEvento } = useEventoStore();
const { eventos, evento } = storeToRefs(useEventoStore());

const openEventoModal = (isRecurrente: boolean) => {
  evento.value.recurrente = isRecurrente;
  dialog.value = true;
};

const deleteEventAction = (id: number) => {
  deleteEvento(id);
  deleteDialog.value = false;
};

const showDeleteDialog = (event: Evento) => {
  setEvento(event);
  deleteDialog.value = true;
};

const editEventHandler = (event: Evento) => {};

const updateEventMarkers = () => {
  // Usa un timeout para asegurar que el DOM esté renderizado
  nextTick(() => {
    const cells = datePicker?.value?.$el?.querySelectorAll(
      ".v-date-picker-month__day"
    );

    const obtenerNumeroDia = (fecha: string) => {
      const [dia, mes, año] = fecha.split("/").map(Number);
      const fechaObj = new Date(año, mes - 1, dia); // mes - 1 porque los meses en JavaScript son de 0 a 11
      return fechaObj.getDay();
    };

    cells.forEach((cell: any) => {
      const date = cell
        .getAttribute("data-v-date")
        ?.replaceAll("-", "/")
        .split("/")
        .reverse()
        .join("/");

      console.log(date);

      if (!date) return;

      const eventsToHandle = eventos.value.filter((event) => {
        if (event.recurrente) {
          return event.dias.includes(obtenerNumeroDia(date));
        } else {
          return event.dias === date;
        }
      });

      if (!eventsToHandle.length) return;

      const eventDots = cell.querySelector(".event-dots")
        ? cell.querySelector(".event-dots")
        : document.createElement("div");
      eventDots.classList.add("event-dots");
      eventDots.innerHTML = "";

      eventsToHandle.forEach((event) => {
        event.equipos?.forEach((equipo) => {
          // En caso de que ya haya un punto para el equipo, no colocamos más
          if (eventDots.querySelector(`[data-equipo="${equipo}"]`)) return;

          // const equipoData = equipos.find((e) => e.id == equipo);
          // const dot = document.createElement("div");
          // dot.dataset.equipo = equipo.id;
          // dot.classList.add("event-dot");
          // dot.style.backgroundColor = equipoData.color;
          // eventDots.appendChild(dot);
        });
      });
      cell.appendChild(eventDots);
    });
  });
};

onMounted(() => {
  getEquipos();
  getEventos();
  evento.value.fecha = formatedDate.value;
  updateEventMarkers();
});

watch(selectedDate, (newVal) => {
  formatedDate.value = date.format(newVal, "keyboardDate");
  evento.value.fecha = formatedDate.value;
});
</script>

<style scoped>
.card-delete-preview {
  scale: 0.8;
}
</style>
