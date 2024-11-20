<template>
  <v-app-bar :flat="!drawer" :color="isTransparent ? 'transparent' : undefined">
    <!-- ICON & TEXT -->
    <router-link to="/" v-if="!authStore.authenticated">
      <v-img
        class="mx-2"
        to="/"
        src="/munttarpe_logo.svg"
        height="30"
        width="30"
        contain
      ></v-img>
    </router-link>
    <v-app-bar-nav-icon
      v-else
      variant="text"
      @click="drawer = !drawer"
    ></v-app-bar-nav-icon>
    <!-- /ICON -->

    <!-- TITLE -->
    <v-app-bar-title :to="localePath('index')"> Munttarpe </v-app-bar-title>
    <!-- /TITLE -->
    <v-menu location="bottom end">
      <template v-slot:activator="{ props }">
        <v-btn icon="mdi-translate" v-bind="props"></v-btn>
      </template>

      <v-list density="compact" nav>
        <v-list-subheader class="font-weight-black">IDIOMAS</v-list-subheader>
        <v-list-item
          v-for="item in locales"
          :key="item.code"
          :value="item.code"
          color="primary"
          :active="item.code == locale"
          class="py-0"
          @click="setLocale(item.code)"
        >
          <v-list-item-title v-text="item.name"></v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>

    <v-btn
      :icon="
        theme.global.current.value.dark
          ? 'mdi-weather-night'
          : 'mdi-weather-sunny'
      "
      @click="changeTheme"
    ></v-btn>
    <v-btn
      v-if="!authStore.authenticated"
      icon="mdi-login"
      :to="localePath('login')"
    ></v-btn>
    <v-btn v-else icon="mdi-logout" @click="authStore.logUserOut"></v-btn>
  </v-app-bar>
  <!-- LOGGED IN SECTIONS -->
  <v-navigation-drawer
    v-if="authStore.authenticated"
    class="h-screen"
    v-model="drawer"
    temporary
    :width="300"
  >
    <!--
    <v-list>
      <v-list-item :title="userStore.userData.email">
        <template v-slot:prepend>
          <v-avatar color="surface-variant">
            {{ userStore.userData.email.charAt(0).toUpperCase() }}
          </v-avatar>
        </template>
      </v-list-item>
    </v-list>
-->

    <v-divider></v-divider>

    <v-list>
      <v-list-item
        v-for="(item, i) in items"
        :key="i"
        :value="item.value"
        color="green-darken-1"
        :to="localePath(item.link)"
      >
        <template v-slot:prepend>
          <v-icon :icon="item.icon"></v-icon>
        </template>

        <v-list-item-title v-text="item.title"></v-list-item-title>
      </v-list-item>
      <v-list-group value="Multas">
        <template v-slot:activator="{ props }">
          <v-list-item
            v-bind="props"
            prepend-icon="mdi-invoice-text-outline"
            title="Multas"
          ></v-list-item>
        </template>

        <v-list-item
          v-for="multaItem in multasItems"
          :key="multaItem.value"
          :to="multaItem.link"
          :title="multaItem.title"
          :value="multaItem.value"
        >
          <template v-slot:prepend>
            <v-icon :icon="multaItem.icon" size="small"></v-icon>
          </template>
        </v-list-item>
      </v-list-group>
    </v-list>
  </v-navigation-drawer>
  <!-- /LOGGED IN SECTIONS -->
</template>

<script setup lang="ts">
import { ref, watch, onBeforeMount } from "vue";
import { useAuthStore } from "~/store/auth";
import { useTheme } from "vuetify";

const { locale, locales, setLocale } = useI18n();
const localePath = useLocalePath();
const authStore = useAuthStore();
const theme = useTheme();

const isTransparent = ref(true);
const drawer = ref(false);
const group = ref(null);
const items = [
  {
    title: "Inicio",
    value: "inicio",
    link: "/",
    icon: "mdi-home",
  },
  {
    title: "Equipos",
    value: "equipos",
    link: "/equipos",
    icon: "mdi-account-group",
  },
  {
    title: "Jugadores",
    value: "jugadores",
    link: "/jugadores",
    icon: "mdi-handball",
  },
  {
    title: "Calendario",
    value: "calendario",
    link: "/calendario",
    icon: "mdi-calendar-multiselect",
  },
  {
    title: "Partidos",
    value: "partidos",
    link: "/partidos",
    icon: "mdi-trophy-outline",
  },
];

const multasItems = [
  {
    title: "Pendientes",
    value: "pendientes",
    link: "/multas/pendientes",
    icon: "mdi-invoice-clock-outline",
  },
  {
    title: "Pagadas",
    value: "pagadas",
    link: "/multas/pagadas",
    icon: "mdi-invoice-check-outline",
  },
  {
    title: "Estadísticas",
    value: "estadisticas",
    link: "/multas/estadisticas",
    icon: "mdi-chart-line",
  },
];

watch(group, () => {
  drawer.value = false;
});

function onScrollTrans(e: any) {
  isTransparent.value = !(e.target.documentElement.scrollTop > 50);
}

onBeforeMount(() => {
  window.addEventListener("scroll", onScrollTrans);
});

const changeTheme = () => {
  theme.global.name.value = theme.global.current.value.dark ? "light" : "dark";
};
</script>
