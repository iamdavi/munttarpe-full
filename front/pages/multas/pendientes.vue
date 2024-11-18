<template>
  <v-row class="mt-container">
    <v-col>
      <div class="d-flex justify-space-between">
        <div class="d-flex flex-column">
          <div class="d-flex align-center ga-2">
            <v-icon icon="mdi-invoice-clock-outline" size="large"></v-icon>
            <h1>Multas pendientes</h1>
          </div>
        </div>
        <v-btn
          v-if="concepto.equipo"
          class="float-right"
          color="green-darken-1"
          prepend-icon="mdi-plus"
          variant="outlined"
          @click="crearEvent"
        >
          Crear
        </v-btn>
      </div>
    </v-col>
  </v-row>
  <v-row>
    <v-col cols="12" md="4">
      <v-row>
        <v-col cols="12">
          <MultaEquipoSelect />
        </v-col>
        <v-col cols="12" v-if="concepto.equipo?.id">
          <MultaConceptosList />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" md="8" v-if="!concepto.equipo || !jugadoresMultas.length">
      <div class="text-center">
        <v-img
          class="ma-auto"
          aspect-ratio="16/9"
          width="300"
          position="top"
          src="/img/empty_state/multas_equipo.svg"
          cover
        />
        <div v-if="!concepto.equipo">
          <p class="text-subtitle-2 font-italic mt-3">
            <i> ~ Selecciona un equipo para cargar los datos ~ </i>
          </p>
        </div>
        <div v-if="concepto.equipo && !jugadoresMultas.length">
          <p class="text-subtitle-2 font-italic mt-3">
            <i> ~ Prueba a crear tu primera multa ~ </i>
          </p>
          <p class="d-flex justify-center mt-3">
            <v-btn
              class="float-right"
              color="green-darken-1"
              prepend-icon="mdi-plus"
              variant="outlined"
              @click="crearEvent"
            >
              Crear primera multa
            </v-btn>
          </p>
        </div>
      </div>
    </v-col>
    <v-col cols="12" md="8" v-else>
      <v-row>
        <v-col cols="12">
          <v-btn-toggle
            v-model="onlyPendientes"
            density="compact"
            class="w-100"
          >
            <v-btn width="50%" icon="mdi-format-align-left" :value="true">
              <v-icon start>mdi-invoice-clock-outline</v-icon>
              Ver pendientes
            </v-btn>
            <v-btn width="50%" icon="mdi-format-align-justify" :value="false">
              Ver todas
              <v-icon end>mdi-invoice-text-multiple-outline</v-icon>
            </v-btn>
          </v-btn-toggle>
        </v-col>
        <v-col
          cols="12"
          md="6"
          lg="4"
          v-for="jugadorMultas in jugadoresMultas"
          :key="jugadorMultas.id"
        >
          <MultaJugadorList
            v-if="jugadoresMultas.length"
            :jugador="jugadorMultas"
          />
        </v-col>
        <v-col cols="12">
          <v-expand-transition>
            <v-row v-if="selectedMultas.length != 0">
              <v-col>
                <v-btn
                  @click="multasSeleccionadasAction('eliminar')"
                  variant="outlined"
                  color="red-darken-4"
                  prepend-icon="mdi-trash-can-outline"
                  block
                >
                  Eliminar
                </v-btn>
              </v-col>
              <v-col>
                <v-btn
                  @click="multasSeleccionadasAction('pagar')"
                  color="green-darken-4"
                  prepend-icon="mdi-hand-coin-outline"
                  block
                >
                  Pagar
                </v-btn>
              </v-col>
            </v-row>
          </v-expand-transition>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
  <MultaModal :isOpen="open" :action="action" @closeDialog="open = false" />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useEquipoStore } from "~/store/equipo";
import { useMultaStore } from "~/store/multa";
import { useConceptoStore } from "~/store/concepto";

const { getEquipos } = useEquipoStore();
const { concepto } = storeToRefs(useConceptoStore());
const { deleteMultas, pagarMultas } = useMultaStore();
const { selectedMultas, jugadoresMultas, onlyPendientes } = storeToRefs(
  useMultaStore()
);

const action = ref<"create" | "modify">("create");
const open = ref<boolean>(false);

const multasSeleccionadasAction = (action: "pagar" | "eliminar") => {
  if (action == "eliminar") {
    deleteMultas();
  } else {
    pagarMultas();
  }
};

const crearEvent = () => {
  action.value = "create";
  open.value = true;
};

onMounted(() => {
  getEquipos();
});
</script>
