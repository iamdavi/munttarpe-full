import { defineStore } from "pinia";
import {
  createBlankEquipo,
  type Equipo,
  type EquipoResponse,
} from "~/interfaces/equipoInterfaces";

export const useEquipoStore = defineStore("equipo", {
  state: () => ({
    equipos: [] as Equipo[],
    equipo: createBlankEquipo() as Equipo,
    equipoSelected: null as Equipo | null,
  }),
  actions: {
    async getEquipos() {
      if (this.equipos.length !== 0) return;
      const res: any = await $api("/equipo/list");
      if (Array.isArray(res)) {
        this.equipos = res;
      }
    },
    async createEquipo() {
      const res: EquipoResponse = await $api("/equipo/create", {
        method: "post",
        body: this.equipo,
      });
      if (res.status == "success") {
        this.equipos.push(res.equipo);
      } else {
      }
      this.clearEquipo();
    },
    async deleteEquipo(id: number) {
      const res: EquipoResponse = await $api(`/equipo/delete/${id}`, {
        method: "delete",
      });
      if (res.status == "success") {
        this.equipos = this.equipos.filter((e) => {
          return e.id !== id;
        });
      } else {
      }
    },
    async editEquipo() {
      const res: EquipoResponse = await $api(`/equipo/edit/${this.equipo.id}`, {
        method: "post",
        body: this.equipo,
      });
      if (res.status == "success") {
        this.equipos = this.equipos.map((e) => {
          return e.id == this.equipo.id ? this.equipo : e;
        });
      } else {
      }
    },
    setEquipo(equipoData: Equipo) {
      this.equipo = equipoData;
    },
    clearEquipo() {
      this.equipo = createBlankEquipo();
    },
  },
});
