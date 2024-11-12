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
            v-model="form.jugador"
            label="Jugador *"
            variant="underlined"
            :items="getJugadoresOfTeam()"
            color="blue-grey-lighten-2"
            item-title="nombre"
            item-value="id"
          >
            <template v-slot:selection="{ props, item }">
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
          <v-text-field
            v-model="formattedDate"
            @click="timeModal = true"
            variant="underlined"
            label="Día de la multa *"
            prepend-icon="mdi-calendar-month-outline"
            readonly
          >
            <v-dialog v-model="timeModal" activator="parent" width="auto">
              <v-card>
                <v-date-picker
                  v-model="form.fecha"
                  @update:modelValue="formatDate"
                ></v-date-picker>
                <v-card-actions>
                  <v-btn block variant="outlined" @click="timeModal = false">
                    Establecer fecha
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-text-field>
          <v-select
            v-model="form.concepto"
            label="Concepto *"
            variant="underlined"
            :items="getConceptosMultasByTeam()"
            color="blue-grey-lighten-2"
            hide-details
            item-title="concepto"
            item-value="id"
            @update:modelValue="conceptoChangeEvent"
          >
            <template v-slot:selection="{ props, item }">
              {{ item.raw.concepto }} ({{ item.raw.valor }} &euro;)
            </template>
            <template v-slot:item="{ props, item }">
              <v-list-item
                v-bind="props"
                :title="`${item.raw.concepto} (${item.raw.valor} &euro;)`"
              >
              </v-list-item>
            </template>
          </v-select>
          <v-text-field
            label="Cantidad a pagar *"
            class="mb-3 mt-2"
            v-model="form.cantidad"
            variant="underlined"
            hint="En caso de decimales, usar coma, p.e.: 1,50"
            persistent-hint
          >
            <template v-slot:append-inner>
              <v-icon icon="mdi-currency-eur" size="x-small"></v-icon>
            </template>
          </v-text-field>
          <v-textarea
            v-model="form.descripcion"
            label="Descripción de la multa"
            variant="outlined"
          ></v-textarea>
          <v-checkbox
            label="Multa pagada"
            v-model="form.pagado"
            color="green-darken-4"
            hide-details
          ></v-checkbox>
        </v-card-text>
        <v-card-actions>
          <v-btn @click="closeDialog"> Cancelar </v-btn>
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
import { useDatabaseStore } from "@/stores/database";
import { useMultaStore } from "~/store/multa";

const props = defineProps<{
  isOpen: boolean;
  action: "create" | "modify";
}>();

const { }

const closeDialog = () => {};

const handleModalForm = () => {
  //   multaStore.createMultaJugador(form.value);
  //   clearFormFields();
  //   closeDialog();
};

onMounted(() => {
  //   multaStore.getMultas();
  //   formatDate();
});
</script>
