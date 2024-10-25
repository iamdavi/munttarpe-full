export interface AuthResponseToken {
  status: "success" | "error";
  token: string;
}

export interface AuthResponseMsg {
  status: "success" | "error";
  msg: string;
}

// Unión de tipos
export type AuthResponse = AuthResponseToken & AuthResponseMsg;
