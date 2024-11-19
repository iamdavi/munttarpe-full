import { defineStore } from "pinia";
import type { Equipo } from "~/interfaces/equipoInterfaces";
import type { Jugador, JugadorResponse } from "~/interfaces/jugadorInterfaces";
import { type Multa, type MultaResponse } from "~/interfaces/multaInterfaces";
import { createBlankMulta } from "~/interfaces/multaInterfaces";
import { useNotificacionStore } from "./notificacion";

export const useMultaStore = defineStore("multa", {
  state: () => ({
    multa: createBlankMulta() as Multa,
    multas: [] as Multa[],
    selectedMultas: [] as Number[],
    jugadoresMultas: [] as Jugador[],
  }),
  actions: {
    async getMultas(equipo: Equipo) {
      const res: JugadorResponse = await $api("/multa/list", {
        params: { equipoId: equipo.id },
      });
      if (Array.isArray(res)) {
        this.jugadoresMultas = res;
      }
    },
    async pagarMultas() {
      const res: JugadorResponse = await $api(`/multa/pagar`, {
        method: "post",
        body: { ids: this.selectedMultas },
      });
      if (res.status == "success") {
        this.jugadoresMultas = this.jugadoresMultas.map((j) => {
          j.multas = j.multas.map((m) => {
            if (this.selectedMultas.includes(m.id)) {
              m.pagada = true;
            }
            return m;
          });
          return j;
        });
        this.selectedMultas = [];
      } else {
      }
    },
    async deleteMultas() {
      const res: JugadorResponse = await $api(`/multa/delete`, {
        method: "post",
        body: { ids: this.selectedMultas },
      });
      if (res.status == "success") {
        this.jugadoresMultas = this.jugadoresMultas.map((j) => {
          j.multas = j.multas.filter(
            (m) => !this.selectedMultas.includes(m.id)
          );
          return j;
        });
        this.selectedMultas = [];
      } else {
      }
    },
    async createMulta() {
      try {
        const res: MultaResponse = await $api("/multa/create", {
          method: "post",
          body: this.multa,
        });
        if (res.status == "error") {
          throw new Error("Error al crear la denuncia");
        }
        this.multas.push(res.multa);
        this.jugadoresMultas = this.jugadoresMultas.map((j: Jugador) => {
          const jugadorId = this.multa.jugador?.id;
          if (jugadorId && j.id == jugadorId) {
            j.multas.push(this.multa);
          }
          return j;
        });
      } catch (e) {
        const { addError } = useNotificacionStore();
        addError(e);
      }
      this.clearMulta();
    },
    clearMulta() {
      return createBlankMulta();
    },
  },
});
