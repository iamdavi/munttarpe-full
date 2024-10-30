<template>
  <v-dialog v-model="props.isOpen" :width="500">
    <v-form @submit.prevent="createEquipo">
      <v-card
        :prepend-icon="
          props.actionType == 'crear'
            ? 'mdi-account-multiple-plus-outline'
            : 'mdi-pencil-outline'
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
        <v-card-text class="pb-0">
          <v-text-field
            label="Nombre"
            variant="underlined"
            v-model="equipo.nombre"
          ></v-text-field>
          <p>Género del equipo</p>
          <v-radio-group
            inline
            v-model="equipo.genero"
            class="text-center"
            @change="cambioGenero"
          >
            <v-radio value="male" color="green-darken-1">
              <template v-slot:label>
                <div class="pe-3">
                  <v-icon icon="mdi-gender-male"></v-icon>
                  Masculino
                </div>
              </template>
            </v-radio>
            <v-radio value="female" color="deep-purple-darken-1">
              <template v-slot:label>
                <div class="pe-3">
                  <v-icon icon="mdi-gender-female"></v-icon>
                  Femenino
                </div>
              </template>
            </v-radio>
          </v-radio-group>
        </v-card-text>
        <v-color-picker
          v-model="equipo.color"
          elevation="0"
          class="mx-auto mb-3"
          hide-inputs
          show-swatches
          swatches-max-height="250px"
        ></v-color-picker>
        <template v-slot:actions>
          <v-btn @click="$emit('closeDialog')"> Cancelar </v-btn>
          <v-spacer></v-spacer>
          <v-btn type="submit" variant="outlined" color="green-darken-1">
            Guardar
          </v-btn>
        </template>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script setup lang="ts">
import { storeToRefs } from "pinia";
import { useEquipoStore } from "~/store/equipo";
import { ref, computed } from "vue";

const props = defineProps({
  isOpen: Boolean,
  actionType: String,
});

const { createEquipo } = useEquipoStore(); // use authenticateUser action from  auth store
const { equipo } = storeToRefs(useEquipoStore());

const cambioGenero = () => {
  console.log(equipo.value.genero);
  equipo.value.color = equipo.value.genero == "male" ? "#03c03c" : "#5e35b1";
};

const title = computed(() => {
  return props.actionType == "crear" ? "Crear equipo" : "Editar equipo";
});
</script>
