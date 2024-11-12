import { defineStore } from "pinia";

export const useNotificacionStore = defineStore("notificacion", {
  state: () => ({
    message: "",
    showSnackbar: false,
    type: "error",
  }),
  actions: {
    showNotification(message: string, type: string = "error") {
      this.hideSnackbar();
      this.message = message;
      this.type = type;
      this.showSnackbar = true;
    },
    hideSnackbar() {
      this.showSnackbar = false;
      this.message = "";
    },
    addError(error: any) {
      this.hideSnackbar();
      const status = error?.response?.status;
      this.type = "error";
      if (status == "401") {
        this.showNotification("Necesitas estar logeado");
      } else if (status == "403") {
        this.showNotification("No puedes acceder");
      } else if (status == "500") {
        this.showNotification("Error en el servidor");
      }
    },
  },
});
