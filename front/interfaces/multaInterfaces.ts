import type { Concepto } from "./conceptoInterfaces";
import type { Jugador } from "./jugadorInterfaces";

export interface Multa {
  id: number;
  jugador: Jugador | null;
  concepto: Concepto | null;
  precio: number | null;
  pagada: boolean;
  fechaPagada: string;
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
    precio: 0,
    pagada: false,
    fechaPagada: "",
  };
}

// Unión de tipos
export type MultaResponse = MultaApiSuccess & MultaApiError;
