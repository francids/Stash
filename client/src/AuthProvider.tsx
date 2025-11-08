import { createContext, useCallback, useContext } from "react";
import { backendUrl } from "./config.ts";

const AuthContext = createContext<{
  register: (name: string, email: string, pass: string) => Promise<void>;
  login: (email: string, pass: string) => Promise<void>;
  logout: () => Promise<void>;
  isAuthenticated: () => Promise<boolean>;
} | null>(null);

export default function AuthProvider({
  children,
}: {
  children: React.ReactNode;
}) {
  async function register(name: string, email: string, pass: string) {
    const response = await fetch(`${backendUrl}/register`, {
      method: "POST",
      body: JSON.stringify({ name, email, password: pass }),
    });
    if (!response.ok) throw new Error("Registration failed");
    const data = await response.json();
    await cookieStore.set("token", data.token);
  }
  async function login(email: string, pass: string) {
    const response = await fetch(`${backendUrl}/login`, {
      method: "POST",
      body: JSON.stringify({ email, password: pass }),
    });
    if (!response.ok) throw new Error("Login failed");
    const data = await response.json();
    await cookieStore.set("token", data.token);
  }
  async function logout() {
    await cookieStore.delete("token");
  }
  const isAuthenticated = useCallback(async function () {
    const token = await cookieStore.get("token");
    return !!token;
  }, []);

  return (
    <AuthContext.Provider value={{ login, logout, register, isAuthenticated }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
}
