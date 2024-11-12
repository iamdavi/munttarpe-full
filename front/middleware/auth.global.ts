import { useAuthStore } from "@/store/auth";
import { useNotificacionStore } from "~/store/notificacion";

export default defineNuxtRouteMiddleware((to, from) => {
  const { authenticated, token } = storeToRefs(useAuthStore());
  const cookieToken = useCookie("token");
  if (cookieToken.value) {
    authenticated.value = true;
    token.value = cookieToken.value;
  }
  const publicRoutes = ["/login", "/register", "/"];
  if (!authenticated.value && !publicRoutes.includes(to.path)) {
    const { showNotification } = useNotificacionStore();
    showNotification("No tienes acceso a esta ruta");
    return navigateTo("/login");
  }
});
