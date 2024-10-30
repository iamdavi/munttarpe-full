import { defineStore } from "pinia";
import type { Equipo, EquipoResponse } from "~/interfaces/equipoInterfaces";

export const useEquipoStore = defineStore("equipo", {
  state: () => ({
    equipos: [],
    equipo: {
      id: null,
      nombre: "",
      genero: "male",
      color: "",
    } as Equipo,
  }),
  actions: {
    async getEquipos() {
      const res: any = await $api("/equipo/list");
      console.log(res);
    },
    async createEquipo() {
      const res: EquipoResponse = await $api("/equipo/create", {
        method: "post",
        body: this.equipo,
      });
      console.log(res);
    },
  },
});
