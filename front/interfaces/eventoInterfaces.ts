import type { Equipo } from "./equipoInterfaces";

export interface Evento {
  id: number;
  equipo: Equipo[] | null;
  tipo: "entrenamiento" | "evento" | "partido";
  recurrente: boolean;
  fecha: string;
  hora: string;
  dias: number[];
}

export interface EventoApiSuccess {
  status: "success" | "error";
  evento: Evento;
}

export interface EventoApiError {
  status: "success" | "error";
  msg: string;
}

// Unión de tipos
export type EventoResponse = EventoApiSuccess & EventoApiError;
