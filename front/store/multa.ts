import { defineStore } from "pinia";
import { type Multa } from "~/interfaces/multaInterfaces";
import { createBlankMulta } from "~/interfaces/multaInterfaces";

export const useMultaStore = defineStore("multa", {
  state: () => ({
    multa: createBlankMulta() as Multa,
    multas: [] as Multa[],
  }),
  actions: {},
});
