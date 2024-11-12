import type { Equipo } from "./equipoInterfaces";

export interface Concepto {
  id: number;
  texto: string;
  valor: number | null;
  equipo: Equipo | null;
}

export interface ConceptoApiSuccess {
  status: "success" | "error";
  concepto: Concepto;
}

export interface ConceptoApiError {
  status: "success" | "error";
  msg: string;
}

export function createBlankConcepto() {
  return {
    id: 0,
    texto: "",
    valor: null,
  };
}

// Unión de tipos
export type ConceptoResponse = ConceptoApiSuccess & ConceptoApiError;
