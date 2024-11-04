import { defineStore } from "pinia";
import type { EventoResponse, Evento } from "~/interfaces/eventoInterfaces";

export const useEventoStore = defineStore("evento", {
  state: () => ({
    eventos: [] as Evento[],
    evento: {
      id: 0,
      equipo: [],
      tipo: "entrenamiento",
      recurrente: false,
      fecha: "",
      hora: "",
      dias: [],
    } as Evento,
  }),
  actions: {
    async getEventos() {
      if (this.eventos.length !== 0) return;
      const res: any = await $api("/evento/list");
      if (Array.isArray(res)) {
        this.eventos = res;
      }
    },
    async createEvento() {
      const res: EventoResponse = await $api("/evento/create", {
        method: "post",
        body: this.evento,
      });
      if (res.status == "success") {
        this.eventos.push(res.evento);
      } else {
      }
      this.clearJugador();
    },
    async deleteEvento(id: number) {
      const res: EventoResponse = await $api(`/evento/delete/${id}`, {
        method: "delete",
      });
      if (res.status == "success") {
        this.eventos = this.eventos.filter((e) => {
          return e.id !== id;
        });
      } else {
      }
    },
    async editEvento() {
      const res: EventoResponse = await $api(`/evento/edit/${this.evento.id}`, {
        method: "post",
        body: this.evento,
      });
      if (res.status == "success") {
        this.eventos = this.eventos.map((e) => {
          return e.id == this.evento.id ? this.evento : e;
        });
      } else {
      }
    },
    setJugador(jugadorData: Evento) {
      this.evento = jugadorData;
    },
    clearJugador() {
      this.evento = {
        id: 0,
        equipo: [],
        tipo: "entrenamiento",
        recurrente: false,
        fecha: "",
        hora: "",
        dias: [],
      };
    },
  },
});
