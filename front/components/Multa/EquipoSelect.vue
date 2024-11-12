<template>
  <v-select
    v-model="equipoSelected"
    @update:modelValue="equipoChangeEvent()"
    :items="equipos"
    hint="Selecciona un equipo para ver las multas y los pagos"
    persistent-hint
    color="blue-grey-lighten-2"
    item-text="nombre"
    label="Equipo *"
    :rules="[(v) => !!v || 'Debes seleccionar al menos 1 equipo']"
    variant="underlined"
  >
    <template v-slot:selection="{ item }">
      <div class="me-3 icon-dinamic-wrapper">
        <HomeIconDynamic :color="item.raw.color" />
      </div>
      {{ item.raw.nombre }}
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
  </v-select>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useEquipoStore } from "~/store/equipo";
import { useConceptoStore } from "~/store/concepto";
import { type Equipo } from "~/interfaces/equipoInterfaces";

const { equipos } = storeToRefs(useEquipoStore());
const { concepto } = storeToRefs(useConceptoStore());
const { getConceptos } = useConceptoStore();

const equipoSelected = ref<Equipo | null>(null);

const equipoChangeEvent = () => {
  concepto.value.texto = "";
  concepto.value.valor = null;
  concepto.value.equipo = equipoSelected.value;
  getConceptos();
};
</script>
