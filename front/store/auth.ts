import { defineStore } from "pinia";

import type { UserPayloadInterface } from "~/interfaces/user";
import type {
  AuthResponse,
  AuthResponseMsg,
} from "~/interfaces/authInterfaces";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    authenticated: false,
    token: "",
    refreshToken: "",
    loading: false,
    user: null,
  }),
  actions: {
    async authenticateUser({ email, password }: UserPayloadInterface) {
      const res: AuthResponse = await $api("/auth/login", {
        method: "post",
        body: { email, password },
      });
      if (res.status == "success") {
        const token = useCookie("token"); // useCookie new hook in nuxt 3
        token.value = res.token; // set token to cookie
        this.token = res.token;
        this.refreshToken = res.refreshToken;
        this.authenticated = true; // set authenticated  state value to true
      }
    },
    async registerUser({ email, password }: UserPayloadInterface) {
      const { status, msg }: AuthResponseMsg = await $api("/auth/register", {
        method: "post",
        body: { email, password },
      });

      if (status == "success") {
        this.authenticateUser({ email, password });
      }
      // @TODO => Usar el msg
    },
    async getUserData() {
      const res: any = await $api("/user/me");
      console.log(res);
    },
    logUserOut() {
      const token = useCookie("token"); // useCookie new hook in nuxt 3
      token.value = ""; // clear the token cookie
      this.authenticated = false; // set authenticated  state value to false
      this.token = "";
      this.refreshToken = "";
    },
    setToken(token: string) {
      this.token = token;
    },
  },
});
