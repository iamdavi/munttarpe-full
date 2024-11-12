import { defineStore } from "pinia";
import {
  createBlankConcepto,
  type Concepto,
  type ConceptoResponse,
} from "~/interfaces/conceptoInterfaces";
import { type Equipo } from "~/interfaces/equipoInterfaces";
import { useNotificacionStore } from "~/store/notificacion";

export const useConceptoStore = defineStore("concepto", {
  state: () => ({
    concepto: createBlankConcepto() as Concepto,
    conceptos: [] as Concepto[],
    loading: false,
  }),
  actions: {
    async getConceptos() {
      const { addError } = useNotificacionStore();
      try {
        this.loading = true;
        const res: any = await $api(
          `/concepto/list/${this.concepto.equipo?.id}`
        );
        if (Array.isArray(res)) {
          this.conceptos = res;
        }
      } catch (e) {
        addError(e);
      } finally {
        this.loading = false;
      }
    },
    async createConcepto() {
      const { showNotification, addError } = useNotificacionStore();
      try {
        this.loading = true;
        const res: ConceptoResponse = await $api("/concepto/create", {
          method: "post",
          body: this.concepto,
        });
        if (res.status == "error") {
          throw new Error(res.msg);
        }
        this.conceptos.push(res.concepto);
        showNotification("¡Concepto creado!", "success");
      } catch (error) {
        addError(error);
      } finally {
        this.loading = false;
        this.clearConcepto();
      }
    },
    async deleteConcepto(id: number) {
      const { showNotification, addError } = useNotificacionStore();
      try {
        this.loading = true;
        const res: ConceptoResponse = await $api(`/concepto/delete/${id}`, {
          method: "delete",
        });
        if (res.status == "error") {
          showNotification("Error al eliminar el concepto");
        }
        this.conceptos = this.conceptos.filter((e) => {
          return e.id !== id;
        });
        showNotification("Concepto eliminado", "success");
      } catch (error) {
        addError(error);
      } finally {
        this.loading = false;
      }
    },
    clearConcepto() {
      this.concepto = { ...createBlankConcepto(), ...this.concepto };
    },
  },
});
