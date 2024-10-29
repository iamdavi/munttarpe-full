import { useAuthStore } from "@/store/auth";

export default defineNuxtRouteMiddleware((to, from) => {
  const { authenticated, token } = storeToRefs(useAuthStore());
  const cookieToken = useCookie("token");
  if (cookieToken.value) {
    authenticated.value = true;
    token.value = cookieToken.value;
  }
  const publicRoutes = ["/login", "/register", "/"];
  if (!authenticated && !publicRoutes.includes(to.path)) {
    console.log("401 Unautorized");
    return navigateTo("/login");
  }
});
