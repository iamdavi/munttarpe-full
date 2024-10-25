import { useAuthStore } from "@/store/auth";

export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore();
  const isAuthenticated = authStore.authenticated;
  const publicRoutes = ["/login", "/register", "/"];
  if (!isAuthenticated && !publicRoutes.includes(to.path)) {
    console.log("401 Unautorized");
    return navigateTo("/login");
  }
});
