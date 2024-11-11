import { defineStore } from "pinia";

export const useNotificacionStore = defineStore("notificacion", {
  state: () => ({
    message: "",
    showSnackbar: false,
    type: "error",
  }),
  actions: {
    showError(message: string) {
      this.message = message;
      this.type = "error";
      this.showSnackbar = true;
    },
    hideSnackbar() {
      this.showSnackbar = false;
      this.message = "";
    },
    addError(error: any) {
      const status = error?.response?.status;
      this.type = "error";
      if (status == "401") {
        this.showError("Necesitas estar logeado");
      } else if (status == "403") {
        this.showError("No puedes acceder");
      } else if (status == "500") {
        this.showError("Error en el servidor");
      }
    },
  },
});
