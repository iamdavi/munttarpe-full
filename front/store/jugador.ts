import { defineStore } from "pinia";
import type { JugadorResponse, Jugador } from "~/interfaces/jugadorInterfaces";

export const useJugadorStore = defineStore("jugador", {
  state: () => ({
    jugadores: [] as Jugador[],
    jugador: {
      id: 0,
      equipo: null,
      nombre: "",
      apellidos: "",
      mote: "",
      image: null,
      posicion: "central",
      dorsal: null,
      rol: "jugador",
    } as Jugador,
  }),
  actions: {
    async getJugadores() {
      if (this.jugadores.length !== 0) return;
      const res: any = await $api("/jugador/list");
      if (Array.isArray(res)) {
        this.jugadores = res;
      }
    },
    async createJugador() {
      const res: JugadorResponse = await $api("/jugador/create", {
        method: "post",
        body: this.jugador,
      });
      if (res.status == "success") {
        this.jugadores.push(res.jugador);
      } else {
      }
      this.clearJugador();
    },
    async deleteJugador(id: number) {
      const res: JugadorResponse = await $api(`/jugador/delete/${id}`, {
        method: "delete",
      });
      if (res.status == "success") {
        this.jugadores = this.jugadores.filter((e) => {
          return e.id !== id;
        });
      } else {
      }
    },
    async editJugador() {
      const res: JugadorResponse = await $api(
        `/jugador/edit/${this.jugador.id}`,
        {
          method: "post",
          body: this.jugador,
        }
      );
      if (res.status == "success") {
        this.jugadores = this.jugadores.map((e) => {
          return e.id == this.jugador.id ? this.jugador : e;
        });
      } else {
      }
    },
    setJugador(jugadorData: Jugador) {
      this.jugador = jugadorData;
    },
    clearJugador() {
      this.jugador = {
        id: 0,
        equipo: null,
        nombre: "",
        apellidos: "",
        mote: "",
        image: null,
        posicion: "central",
        dorsal: null,
        rol: "jugador",
      };
    },
  },
});
