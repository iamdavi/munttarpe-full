export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.provide("formatDate", (dateObject: any) => {
    const dateString = dateObject?.date;
    if (!dateString) return;

    // Convierte la cadena de fecha a un objeto Date
    const date = new Date(dateString);
    // Obtener los componentes de la fecha
    const dia = String(date.getDate()).padStart(2, "0"); // Obtener el día
    const mes = String(date.getMonth() + 1).padStart(2, "0"); // Obtener el mes (0-11)
    const anio = date.getFullYear(); // Obtener el año
    // Formatear la fecha en 'DD/MM/AAAA'
    return `${dia}/${mes}/${anio}`;
  });
});
