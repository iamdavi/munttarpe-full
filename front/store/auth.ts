import { defineStore } from "pinia";

import type { UserPayloadInterface } from "~/interfaces/user";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    authenticated: false,
    accessToken: "",
    loading: false,
  }),
  actions: {
    async authenticateUser({ email, password }: UserPayloadInterface) {
      const { data, pending }: any = await $api("/auth/login", {
        method: "post",
        body: { email, password },
      });
      this.loading = pending;

      if (data.value) {
        const token = useCookie("token"); // useCookie new hook in nuxt 3
        token.value = data?.value?.token; // set token to cookie
        this.accessToken = data?.value?.token;
        this.authenticated = true; // set authenticated  state value to true
      }
    },
    async registerUser({ email, password }: UserPayloadInterface) {
      const { data, pending }: any = await $api("/auth/register", {
        method: "post",
        body: { email, password },
      });
      this.loading = pending;

      if (data.value) {
        this.authenticateUser({ email, password });
      }
    },
    logUserOut() {
      const token = useCookie("token"); // useCookie new hook in nuxt 3
      this.authenticated = false; // set authenticated  state value to false
      token.value = null; // clear the token cookie
    },
  },
});
