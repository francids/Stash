import { createRoot } from "react-dom/client";
import { createBrowserRouter } from "react-router";
import { RouterProvider } from "react-router/dom";
import "./index.css";

import AuthPage from "./AuthPage.tsx";
import AppPage from "./AppPage.tsx";
import IndexPage from "./IndexPage.tsx";
import AuthProvider from "./AuthProvider.tsx";
import ProtectedRoute from "./ProtectedRoute.tsx";

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
