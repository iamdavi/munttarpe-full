<template>
  <div>
    <h1>Registrar Nuevo Usuario</h1>
    <form @submit.prevent="register">
      <input
        v-model="email"
        type="email"
        placeholder="Correo electrónico"
        required
      />
      <input
        v-model="password"
        type="password"
        placeholder="Contraseña"
        required
      />
      <button type="submit">Registrar</button>
    </form>
    <p v-if="errorMessage">{{ errorMessage }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: "",
      errorMessage: "",
    };
  },
  methods: {
    async register() {
      try {
        const response = await fetch("http://tu-backend.com/api/register", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            email: this.email,
            password: this.password,
          }),
        });

        if (response.ok) {
          this.$router.push("/login"); // Redirigir después de un registro exitoso
        } else {
          const errorData = await response.json();
          this.errorMessage = errorData.error || "Error desconocido";
        }
      } catch (error) {
        this.errorMessage = "Error en el registro: " + error.message;
      }
    },
  },
};
</script>
