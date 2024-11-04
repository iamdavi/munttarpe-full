<template>
  <v-dialog v-model="props.isOpen" :width="500">
    <v-form @submit.prevent="handleForm">
      <v-card
        :disabled="loading"
        :loading="loading"
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

const { createEquipo, editEquipo } = useEquipoStore();
const { equipo } = storeToRefs(useEquipoStore());

const emit = defineEmits(["closeDialog"]);
interface Props {
  isOpen: boolean;
  actionType: "crear" | "edit";
}
const props = defineProps<Props>();

const loading = ref(false);

const cambioGenero = () => {
  equipo.value.color = equipo.value.genero == "male" ? "#03c03c" : "#5e35b1";
};

const title = computed(() => {
  return props.actionType == "crear" ? "Crear equipo" : "Editar equipo";
});

const handleForm = async () => {
  loading.value = true;
  if (props.actionType == "crear") {
    await createEquipo();
  } else {
    await editEquipo();
  }
  loading.value = false;
  emit("closeDialog");
};
</script>
