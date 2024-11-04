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
      <v-btn
        color="green-darken-1"
        prepend-icon="mdi-calendar-refresh"
        class="mt-3"
        variant="outlined"
        block
        @click="
          eventType = 'period';
          actionType = 'crear';
          isDialogOpen = true;
        "
      >
        Crear evento recurrente
      </v-btn>
      <p class="text-caption text-grey">
        P.e.: entrenamientos, eventos semanales, recordatorios...
      </p>
    </v-col>
    <v-col cols="12" md="7" xl="8">
      <v-row>
        <v-col
          cols="12"
          class="d-flex flex-column flex-md-row justify-space-between align-center ga-3"
        >
          <h3>Todos los eventos</h3>
          <v-btn
            variant="outlined"
            prepend-icon="mdi-plus"
            color="green-darken-1"
            @click="
              eventType = 'day';
              actionType = 'crear';
              isDialogOpen = true;
            "
          >
            Evento para {{ formatedDate }}
          </v-btn>
        </v-col>
        <v-col
          xl="4"
          md="6"
          sm="12"
          cols="12"
          class="position-relative"
          v-for="event in databaseStore.eventos"
          :key="event.id"
        >
          <EventoCard
            :evento="event"
            :equipos="getEquipoDataById(event.equipos)"
            @deleteEvent="showDeleteDialog(event)"
            @editEvent="editEventHandler(event)"
          />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
  <v-dialog v-model="deleteEventDialog" max-width="500">
    <v-card title="Eliminar evento?" prepend-icon="mdi-calendar-remove-outline">
      <v-divider></v-divider>
      <div class="position-relative card-delete-preview">
        <EventoCard
          :evento="eventToDelete"
          :equipos="getEquipoDataById(eventToDelete?.equipos)"
          :isPreview="true"
        />
      </div>
      <v-divider></v-divider>
      <v-card-actions class="d-flex justify-space-between">
        <v-btn
          @click="deleteEventDialog = false"
          prepend-icon="mdi-close"
          variant="outlined"
        >
          Cancelar
        </v-btn>
        <v-btn
          prepend-icon="mdi-trash-can-outline"
          color="red"
          variant="outlined"
          @click="deleteEventAction()"
          :loading="databaseStore.loadingDeleteDoc"
        >
          Eliminar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <EventoFormModal
    :isOpen="isDialogOpen"
    :actionType="actionType"
    :eventType="eventType"
    :eventDay="formatedDate"
    :evento="evento"
    @closeDialog="
      isDialogOpen = false;
      updateEventMarkers();
    "
  />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useDate } from "vuetify";

const date = useDate();
const selectedDate = ref(new Date());
const formatedDate = ref(date.format(selectedDate, "keyboardDate"));
const datePicker = ref();

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

      if (!date) return;

      const eventsToHandle = databaseStore.eventos.filter((event) => {
        if (event.eventType == "period") {
          return event.weeksDay.includes(obtenerNumeroDia(date));
        } else {
          return event.day === date;
        }
      });

      if (!eventsToHandle.length) return;

      const eventDots = cell.querySelector(".event-dots")
        ? cell.querySelector(".event-dots")
        : document.createElement("div");
      eventDots.classList.add("event-dots");
      eventDots.innerHTML = "";

      eventsToHandle.forEach((event) => {
        event.equipos.forEach((equipo) => {
          // En caso de que ya haya un punto para el equipo, no colocamos más
          if (eventDots.querySelector(`[data-equipo="${equipo}"]`)) return;

          const equipoData = databaseStore.equipos.find((e) => e.id == equipo);
          const dot = document.createElement("div");
          dot.dataset.equipo = equipo;
          dot.classList.add("event-dot");
          dot.style.backgroundColor = equipoData.color;
          eventDots.appendChild(dot);
        });
      });
      cell.appendChild(eventDots);
    });
  });
};

onMounted(async () => {
  await databaseStore.getEquipos();
  await databaseStore.getEvents();
  updateEventMarkers();
});

watch(selectedDate, (newVal) => {
  formatedDate.value = date.format(newVal, "keyboardDate");
});
</script>
