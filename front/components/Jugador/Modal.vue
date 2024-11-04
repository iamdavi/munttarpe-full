<template>
  <v-dialog v-model="props.isOpen" persistent>
    <v-form @submit.prevent="handleForm">
      <v-card
        :prepend-icon="
          props.actionType == 'crear' ? 'mdi-handball' : 'mdi-pencil-outline'
        "
        :title="title"
      >
        <template v-slot:append>
          <v-btn
            icon="mdi-close"
            variant="text"
            @click="$emit('closeDialog')"
          ></v-btn>
        </template>
        <v-container class="pt-0" fluid>
          <v-row>
            <v-col class="pt-0">
              <v-card-text class="pb-0" :loading="loading" :disabled="loading">
                <v-row>
                  <v-col cols="12" md="6">
                    <v-select
                      v-model="jugador.equipo"
                      :items="equipos"
                      item-text="nombre"
                      label="Equipo *"
                      :rules="[(v) => !!v || 'Debes seleccionar 1 equipo']"
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
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-select
                      label="Rol"
                      :items="jugadorRoles"
                      v-model="jugador.rol"
                      variant="underlined"
                      @update:modelValue="rolChange"
                    ></v-select>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      label="Nombre *"
                      variant="underlined"
                      v-model="jugador.nombre"
                      :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      label="Apellidos *"
                      variant="underlined"
                      v-model="jugador.apellidos"
                      :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      label="Mote *"
                      hint="Mote del jugador o nombre de camiseta"
                      variant="underlined"
                      v-model="jugador.mote"
                      :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-file-input
                      prepend-icon="mdi-image"
                      variant="underlined"
                      accept="image/*"
                      label="Imagen del jugador"
                      v-model="jugador.image"
                    ></v-file-input>
                  </v-col>
                  <v-col cols="12" md="6" :class="{ 'd-none': isNotJugador }">
                    <v-select
                      label="Posicion"
                      :items="jugadorPositions"
                      v-model="jugador.posicion"
                      variant="underlined"
                    ></v-select>
                  </v-col>
                  <v-col cols="12" md="6" :class="{ 'd-none': isNotJugador }">
                    <v-text-field
                      label="Dorsal"
                      type="number"
                      variant="underlined"
                      v-model="jugador.dorsal"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-col>
            <v-col class="pt-0 h-auto">
              <v-card
                variant="text"
                title="Previsualicación"
                prepend-icon="mdi-eye"
                class="h-100"
              >
                <v-card-text class="d-flex align-center">
                  <JugadorCard :jugador="jugador" :isPreview="true" />
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
        <template v-slot:actions>
          <v-btn
            v-if="props.actionType == 'edit'"
            class="ms-5 mb-2"
            @click="deleteHandler"
            :loading="loading"
            prepend-icon="mdi-trash-can-outline"
            color="red"
            variant="outlined"
          >
            Eliminar
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            class="me-5 mb-2"
            type="submit"
            variant="outlined"
            color="green-darken-1"
          >
            Guardar
          </v-btn>
        </template>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script setup lang="ts">
import { jugadorPositions, jugadorRoles } from "~/data/jugadorData";
import { ref } from "vue";
import { storeToRefs } from "pinia";
import { useJugadorStore } from "~/store/jugador";
import { useEquipoStore } from "~/store/equipo";

interface Props {
  isOpen: boolean;
  actionType: "crear" | "edit" | string;
}
const props = defineProps<Props>();

const emit = defineEmits(["closeDialog"]);

const loading = ref(false);
const { createJugador, clearJugador, deleteJugador, editJugador } =
  useJugadorStore();
const { jugador } = storeToRefs(useJugadorStore());
const { equipos } = storeToRefs(useEquipoStore());
const rules = {
  required: (value: string) => !!value || "Este campo es requerido",
};

const title = computed(() => {
  return props.actionType == "crear" ? "Crear jugador" : "Editar jugador";
});

const isNotJugador = computed(() => {
  return jugador.value.rol !== "jugador";
});

const rolChange = () => {
  jugador.value.dorsal = null;
  jugador.value.posicion = null;
};

const handleForm = async (e: any) => {
  loading.value = true;
  try {
    const results = await e;
    if (!results.valid) throw new Error("Formulario inválido");
    if (props.actionType == "crear") {
      await createJugador();
    } else {
      await editJugador();
    }
  } catch (err) {
    console.log(err);
  }
  clearJugador();
  loading.value = false;
  emit("closeDialog");
};

const deleteHandler = () => {
  loading.value = true;
  deleteJugador(jugador.value.id);
  loading.value = false;
  clearJugador();
  emit("closeDialog");
};
</script>
