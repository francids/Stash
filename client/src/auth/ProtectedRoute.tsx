import { Navigate, Outlet } from "react-router";
import { useAuth } from "./AuthProvider";
import { useEffect, useState } from "react";

export default function ProtectedRoute() {
  const { isAuthenticated, logout } = useAuth();
  const [authChecked, setAuthChecked] = useState(false);
  const [authenticated, setAuthenticated] = useState(false);

  useEffect(() => {
    const checkAuth = async () => {
      const isAuth = await isAuthenticated();
      setAuthenticated(isAuth);
      setAuthChecked(true);

      if (!isAuth) {
        await logout();
      }
    };
    checkAuth();
  }, [isAuthenticated, logout]);

  if (!authChecked) return null;
  if (!authenticated) return <Navigate to="/auth" replace />;
  return <Outlet />;
}
