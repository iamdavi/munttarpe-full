import { useAuthStore } from "@/store/auth";

export default defineNuxtRouteMiddleware((to, from) => {
  const { authenticated } = storeToRefs(useAuthStore());
  const token = useCookie("token");
  if (token.value) {
    authenticated.value = true; // update the state to authenticated
  }
  const publicRoutes = ["/login", "/register", "/"];
  if (!authenticated && !publicRoutes.includes(to.path)) {
    console.log("401 Unautorized");
    return navigateTo("/login");
  }
});
