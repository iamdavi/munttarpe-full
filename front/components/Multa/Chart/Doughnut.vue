<template>
  <Doughnut ref="doughnutChart" :data="chartData" :options="chartOptions" />
  <v-btn @click="getPorcentajeJugadoresData"></v-btn>
</template>

<script setup lang="ts">
import { reactive, ref } from "vue";
import { Doughnut } from "vue-chartjs";
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from "chart.js";
import { useMultaStore } from "~/store/multa";
import type { Jugador } from "~/interfaces/jugadorInterfaces";
import type { Multa } from "~/interfaces/multaInterfaces";

const props = defineProps<{
  type: "porcentajeJugadores";
}>();

// Registrar los módulos necesarios de Chart.js
ChartJS.register(Title, Tooltip, Legend, ArcElement);

const { jugadoresMultas } = storeToRefs(useMultaStore());

interface PorcentajeJugadorData {
  labelNombre: String;
  total: number;
}

// Referencia para el componente Doughnut
const doughnutChart = ref<InstanceType<typeof Doughnut> | null>(null);

// Referencia para la instancia de Chart.js
const chartInstance = ref<ChartJS | null>(null);

// Datos reactivos del gráfico
const chartData = reactive({
  labels: ["a"],
  datasets: [
    {
      data: [1],
      backgroundColor: ["rgba(255, 99, 132, 0.5)"],
      borderColor: ["rgba(255, 99, 132, 1)"],
      borderWidth: 1,
    },
  ],
});

const getPorcentajeJugadoresData = () => {
  let data: PorcentajeJugadorData[] = [];
  let totalPagado = 0;
  jugadoresMultas.value.forEach((j: Jugador) => {
    let labelNombre = `${j.mote} (${j.dorsal})`;
    let totalJugador = 0;
    j.multas.forEach((m: Multa) => {
      if (m.precioPagado) totalJugador += m.precioPagado;
    });
    totalPagado += totalJugador;
    data.push({ labelNombre: labelNombre, total: totalJugador });
  });

  const labels = [] as string[];
  const datasetData = [] as number[];

  data.map((e: PorcentajeJugadorData) => {
    const porc = (e.total * 100) / totalPagado;
    labels.push(`${e.labelNombre} [${porc} %]`);
    datasetData.push(e.total);
  });

  if (chartInstance.value) {
    chartData.labels = [...labels];
    chartData.datasets[0].data = [...datasetData];
    // chartInstance.value.data.labels = [...labels];
    // chartInstance.value.data.datasets[0].data = [...datasetData];
    chartInstance.value.update();
  }
};

// Opciones del gráfico
const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: {
      position: "top",
      align: "start",
    },
  },
  layout: {
    padding: 0, // Asegúrate de definir un valor
  },
});

onMounted(async () => {
  await nextTick();
  if (doughnutChart.value) {
    chartInstance.value = doughnutChart.value.chart;
  }
  getPorcentajeJugadoresData();
});
</script>
