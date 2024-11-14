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
    jugadoresMultas: [] as Jugador[],
  }),
  actions: {
    async getMultas(equipo: Equipo) {
      console.log(equipo);
      const res: JugadorResponse = await $api("/multa/list", {
        params: { equipoId: equipo.id },
      });
      if (Array.isArray(res)) {
        console.log(res);
        this.jugadoresMultas = res;
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
