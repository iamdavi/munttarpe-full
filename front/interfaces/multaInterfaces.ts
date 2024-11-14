import type { Concepto } from "./conceptoInterfaces";
import type { Jugador } from "./jugadorInterfaces";

export interface Multa {
  id: number;
  jugador: Jugador | null;
  concepto: Concepto | null;
  descripcion: string;
  precio: number | null;
  precioPagado: number | null;
  pagada: boolean;
  fecha: Date;
  fechaPagada: Date;
}

export interface MultaApiSuccess {
  status: "success" | "error";
  multa: Multa;
}

export interface MultaApiError {
  status: "success" | "error";
  msg: string;
}

export function createBlankMulta(): Multa {
  return {
    id: 0,
    jugador: null,
    concepto: null,
    precio: null,
    precioPagado: null,
    descripcion: "",
    pagada: false,
    fecha: new Date(),
    fechaPagada: new Date(),
  };
}

// Unión de tipos
export type MultaResponse = MultaApiSuccess & MultaApiError;
