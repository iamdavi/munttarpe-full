<template>
  <v-card
    height="100%"
    elevation="3"
    :prepend-icon="getPrependIcon()"
    :variant="getCardVariant()"
    :title="$t(props.evento.tipo)"
    :subtitle="getSubtitle()"
    class="d-flex flex-column justify-space-between"
  >
    <template v-slot:append v-if="!props.isPreview">
      <v-btn
        density="compact"
        variant="text"
        icon="mdi-close"
        @click="$emit('deleteEvent', props.evento)"
      ></v-btn>
    </template>
    <v-card-text class="pb-0 mt-n2 px-1">
      <v-divider></v-divider>
      <v-chip
        v-for="equipo in props.evento.equipos"
        :key="equipo.id"
        :variant="getCardVariant()"
        class="ma-1"
      >
        <div class="me-3 icon-dinamic-wrapper">
          <HomeIconDynamic :color="equipo.color" />
        </div>
        {{ equipo.nombre }}
      </v-chip>
    </v-card-text>
    <div>
      <div v-if="!!props.evento.descripcion">
        <p class="px-4 py-2">{{ props.evento.descripcion }}</p>
      </div>
      <v-divider></v-divider>
      <v-card-actions v-if="!props.isPreview">
        <v-btn
          block
          variant="outlined"
          prepend-icon="mdi-pencil-outline"
          @click="$emit('editEvent')"
        >
          Editar
        </v-btn>
      </v-card-actions>
    </div>
  </v-card>
  <v-tooltip
    text="Evento recurrente"
    location="top end"
    v-if="props.evento?.recurrente"
  >
    <template v-slot:activator="{ props }">
      <div v-bind="props" class="custom-badge-recursive-card elevation-5">
        <v-icon icon="mdi-calendar-refresh-outline" size="x-small"></v-icon>
      </div>
    </template>
  </v-tooltip>
</template>

<script setup lang="ts">
import { daysOfWeek } from "~/data/eventoData";
import type { Evento } from "~/interfaces/eventoInterfaces";

const props = defineProps<{
  evento: Evento;
  isPreview: boolean;
}>();
const emits = defineEmits(["deleteEvent", "editEvent"]);

const getSubtitle = () => {
  let result = props.evento.hora ? props.evento.hora : "??:??";
  result += " - ";
  if (props.evento.recurrente) {
    const daysNames = getNameOfDaysByArray(props.evento.dias);
    result += daysNames.map((e) => e.slice(0, 2)).join(", ");
  } else {
    result += props.evento.dias;
  }
  return result;
};

const getNameOfDaysByArray = (days: any) => {
  const names: string[] = [];
  const daysSelectedValues = Object.values(days);
  daysOfWeek.forEach((d) => {
    if (daysSelectedValues.includes(d.value)) names.push(d.title);
  });
  return names;
};

const getPrependIcon = () => {
  if (props.evento.tipo == "partido") return "mdi-tournament";
  if (props.evento.tipo == "entrenamiento") return "mdi-strategy";
  if (props.evento.tipo == "evento") return "mdi-account-group-outline";
  return "";
};

const getCardVariant = (): "tonal" | "outlined" | "flat" | undefined => {
  if (props.evento.tipo == "partido") return "tonal";
  if (props.evento.tipo == "entrenamiento") return "outlined";
  if (props.evento.tipo == "evento") return "flat";
  return undefined;
};
</script>

<style scoped>
.v-overlay__content {
  display: flex;
  height: 100%;
  width: 100%;
  flex-direction: column;
  padding: 12px !important;
}

.custom-badge-recursive-card {
  background-color: #43a047;
  position: absolute;
  right: 0px;
  top: 0px;
  border-radius: 50%;
  padding: 3px 7px;
}
</style>
