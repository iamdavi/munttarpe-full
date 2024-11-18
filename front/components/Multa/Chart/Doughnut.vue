<template>
  <div>
    <DoughnutChart :chart-data="chartData" :chart-options="chartOptions" />
  </div>
</template>

<script setup>
import { reactive } from "vue";
import { Doughnut } from "vue-chartjs";
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from "chart.js";

// Registrar los módulos necesarios de Chart.js
ChartJS.register(Title, Tooltip, Legend, ArcElement);

// Datos reactivos del gráfico
const chartData = reactive({
  labels: ["Red", "Blue", "Yellow", "Green", "Purple"],
  datasets: [
    {
      label: "Random Data",
      data: [12, 19, 3, 5, 2],
      backgroundColor: [
        "rgba(255, 99, 132, 0.5)",
        "rgba(54, 162, 235, 0.5)",
        "rgba(255, 206, 86, 0.5)",
        "rgba(75, 192, 192, 0.5)",
        "rgba(153, 102, 255, 0.5)",
      ],
      borderColor: [
        "rgba(255, 99, 132, 1)",
        "rgba(54, 162, 235, 1)",
        "rgba(255, 206, 86, 1)",
        "rgba(75, 192, 192, 1)",
        "rgba(153, 102, 255, 1)",
      ],
      borderWidth: 1,
    },
  ],
});

// Opciones del gráfico
const chartOptions = reactive({
  responsive: true,
  plugins: {
    legend: {
      position: "top",
    },
    title: {
      display: true,
      text: "Doughnut Chart Example",
    },
  },
});
</script>

<style scoped>
/* Opcional: ajusta el diseño del contenedor si lo necesitas */
div {
  max-width: 500px;
  margin: auto;
}
</style>
