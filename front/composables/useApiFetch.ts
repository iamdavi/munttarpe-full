import { useAuthStore } from "~/store/auth";

export function $api<T>(
  request: Parameters<typeof $fetch<T>>[0],
  opts?: Parameters<typeof $fetch<T>>[1]
) {
  const auth = useAuthStore();
  const config = useRuntimeConfig();

  return $fetch<T>(request, {
    ...opts,
    baseURL: config.public.baseURL,
    headers: {
      "Content-Type": "application/json",
      Authorization: auth.authenticated ? `Bearer ${auth.accessToken}` : "",
      ...opts?.headers,
    },
  });
}
