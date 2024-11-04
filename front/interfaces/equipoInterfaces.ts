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

// Unión de tipos
export type EquipoResponse = EquipoApiSuccess & EquipoApiError;
