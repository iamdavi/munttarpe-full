export interface Equipo {
  id: number;
  nombre: string;
  genero: "male" | "female";
  color: string;
}

export interface EquipoApiSuccess {
  status: "success" | "error";
  equipo: Equipo;
}

export interface EquipoApiError {
  status: "success" | "error";
  msg: string;
}

export function createBlankEquipo(): Equipo {
  return {
    id: 0,
    nombre: "",
    genero: "male",
    color: "#03c03c",
  };
}

// Unión de tipos
export type EquipoResponse = EquipoApiSuccess & EquipoApiError;
