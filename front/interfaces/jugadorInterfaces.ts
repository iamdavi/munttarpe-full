import type { Equipo } from "./equipoInterfaces";
import type { Multa } from "./multaInterfaces";

export interface Jugador {
  id: number;
  equipo: Equipo | null;
  nombre: string;
  apellidos: string;
  mote: string;
  posicion:
    | null
    | "portero"
    | "central"
    | "pivote"
    | "latde"
    | "latiz"
    | "extde"
    | "extiz";
  dorsal: null | number;
  rol: "jugador" | "entrenador";
  image: any;
  multas: Multa[];
}

export interface JugadorApiSuccess {
  status: "success" | "error";
  jugador: Jugador;
}

export interface JugadorApiError {
  status: "success" | "error";
  msg: string;
}

// Unión de tipos
export type JugadorResponse = JugadorApiSuccess & JugadorApiError;
