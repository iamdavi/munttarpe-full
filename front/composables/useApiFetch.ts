import { useAuthStore } from "~/store/auth";

export async function $api<T>(
  request: Parameters<typeof $fetch<T>>[0],
  opts?: Parameters<typeof $fetch<T>>[1]
) {
  const auth = useAuthStore();
  const config = useRuntimeConfig();

  try {
    // Realiza la llamada a la API
    return await $fetch<T>(request, {
      ...opts,
      baseURL: config.public.baseURL,
      headers: {
        "Content-Type": "application/json",
        Authorization: auth.authenticated ? `Bearer ${auth.token}` : "",
        ...opts?.headers,
      },
    });
  } catch (error: any) {
    // Verifica si el error es un 401 (token expirado)
    if (
      error?.response?.status === 401 &&
      error?.response?._data?.message === "Expired JWT Token"
    ) {
      try {
        // Llama al endpoint de refresh token
        const refreshResponse = await $fetch<{ token: string }>(
          "/auth/refresh",
          {
            method: "POST",
            baseURL: config.public.baseURL,
            headers: {
              "Content-Type": "application/json",
            },
            body: {
              refreshToken: auth.refreshToken,
            },
          }
        );

        // Actualiza el token en el store y reintenta la solicitud original
        auth.setToken(refreshResponse.token);

        // Reintenta la solicitud original con el nuevo token
        return await $fetch<T>(request, {
          ...opts,
          baseURL: config.public.baseURL,
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${refreshResponse.token}`,
            ...opts?.headers,
          },
        });
      } catch (refreshError) {
        console.log(refreshError);
        // Si la renovación falla, desconecta al usuario
        auth.logUserOut();
        return Promise.reject(refreshError);
      }
    }

    // Si el error no es un 401, simplemente lanza el error
    return Promise.reject(error);
  }
}
