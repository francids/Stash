import { createRoot } from "react-dom/client";
import { createBrowserRouter } from "react-router";
import { RouterProvider } from "react-router/dom";
import "./index.css";

import AuthPage from "./pages/AuthPage.tsx";
import AppPage from "./pages/AppPage.tsx";
import IndexPage from "./pages/IndexPage.tsx";
import AuthProvider from "./auth/AuthProvider.tsx";
import ProtectedRoute from "./auth/ProtectedRoute.tsx";

const router = createBrowserRouter([
  {
    path: "/auth",
    Component: AuthPage,
  },
  {
    path: "/app",
    Component: ProtectedRoute,
    children: [
      {
        index: true,
        Component: AppPage,
      },
    ],
  },
  {
    path: "*",
    element: <IndexPage />,
  },
]);

createRoot(document.getElementById("root")!).render(
  <AuthProvider>
    <RouterProvider router={router} />
  </AuthProvider>
);
