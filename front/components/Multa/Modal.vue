<template>
  <v-dialog v-model="props.isOpen" persistent max-width="500">
    <v-form @submit.prevent="handleModalForm">
      <v-card
        :prepend-icon="
          props.action == 'create'
            ? 'mdi-invoice-plus-outline'
            : 'mdi-pencil-outline'
        "
        title="Añadir multa a jugador"
      >
        <template v-slot:append>
          <v-btn
            icon="mdi-close"
            variant="text"
            @click="$emit('closeDialog')"
          ></v-btn>
        </template>
        <v-card-text class="pb-1">
          <v-select
            v-model="multa.jugador"
            label="Jugador *"
            variant="underlined"
            :items="jugadores"
            color="blue-grey-lighten-2"
            item-text="nombre"
          >
            <template v-slot:selection="{ item }">
              {{ item.raw.mote }}
              ({{ item.raw.dorsal ? item.raw.dorsal : "--" }})
            </template>
            <template v-slot:item="{ props, item }">
              <v-list-item
                v-bind="props"
                :title="
                  item.raw.dorsal
                    ? item.raw.mote + ' (' + item.raw.dorsal + ')'
                    : item.raw.mote + '(--)'
                "
              >
              </v-list-item>
            </template>
          </v-select>
          <v-date-input
            v-model="multa.fecha"
            clearable
            label="Fecha de la multa"
            variant="underlined"
            ok-text="Establecer"
            cancel-text="Cancelar"
          ></v-date-input>
          <v-select
            v-model="multa.concepto"
            label="Concepto *"
            variant="underlined"
            :items="conceptos"
            color="blue-grey-lighten-2"
            hide-details
            item-texto="texto"
            @update:modelValue="conceptoChangeEvent"
          >
            <template v-slot:selection="{ item }">
              {{ item.raw.texto }} ({{ item.raw.valor }} &euro;)
            </template>
            <template v-slot:item="{ props, item }">
              <v-list-item
                v-bind="props"
                :title="`${item.raw.texto} (${item.raw.valor} &euro;)`"
              >
              </v-list-item>
            </template>
          </v-select>
          <v-text-field
            label="Cantidad a pagar *"
            class="mb-3 mt-2"
            v-model="multa.precio"
            variant="underlined"
            persistent-hint
          >
            <template v-slot:append-inner>
              <v-icon icon="mdi-currency-eur" size="x-small"></v-icon>
            </template>
          </v-text-field>
          <v-textarea
            v-model="multa.descripcion"
            label="Descripción de la multa"
            variant="outlined"
          ></v-textarea>
          <v-checkbox
            label="Multa pagada"
            v-model="multa.pagada"
            color="green-darken-4"
            hide-details
          ></v-checkbox>
          <v-expand-transition>
            <v-text-field
              v-if="multa.pagada"
              label="Cantidad pagada *"
              class="mb-3 mt-2"
              v-model="multa.precioPagado"
              variant="underlined"
              hint="En caso de decimales, usar coma, p.e.: 1,50"
              persistent-hint
            >
              <template v-slot:append-inner>
                <v-icon icon="mdi-currency-eur" size="x-small"></v-icon>
              </template>
            </v-text-field>
          </v-expand-transition>
        </v-card-text>
        <v-card-actions>
          <v-btn @click="$emit('closeDialog')"> Cancelar </v-btn>
          <v-spacer></v-spacer>
          <v-btn type="submit" variant="outlined" color="green-darken-1">
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from "vue";
import { useMultaStore } from "~/store/multa";
import { useJugadorStore } from "~/store/jugador";
import { useConceptoStore } from "~/store/concepto";
import type { Concepto } from "~/interfaces/conceptoInterfaces";

const props = defineProps<{
  isOpen: boolean;
  action: "create" | "modify";
}>();

const { multa } = storeToRefs(useMultaStore());
const { jugadores } = storeToRefs(useJugadorStore());
const { conceptos } = storeToRefs(useConceptoStore());

const { createMulta } = useMultaStore();

const handleModalForm = () => {
  createMulta();
};

const conceptoChangeEvent = (e: Concepto) => {
  const conceptoData = conceptos.value.find((m) => m.id == e.id);
  multa.value.precio = conceptoData?.valor ? conceptoData.valor : null;
  multa.value.precioPagado = conceptoData?.valor ? conceptoData.valor : null;
  multa.value.descripcion = conceptoData?.valor ? conceptoData.texto : "";
};

watch(
  () => multa.value.fecha,
  (newValue: any) => {
    if (newValue && !(newValue instanceof Date)) {
      multa.value.fecha = new Date(newValue);
    }
  }
);

onMounted(() => {
  //   multaStore.getMultas();
  //   formatDate();
});
</script>
