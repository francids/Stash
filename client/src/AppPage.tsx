import { useAuth } from "./AuthProvider";

export default function AppPage() {
  const { logout } = useAuth();

  return (
    <div className="min-h-screen bg-base-200">
      <div className="navbar bg-base-100 shadow-sm">
        <div className="flex-1">
          <a className="btn btn-ghost text-xl">SaveIt</a>
        </div>
        <div className="flex-none">
          <button
            className="btn btn-soft btn-accent"
            onClick={async function () {
              await logout();
              window.location.reload();
            }}
          >
            Logout
          </button>
        </div>
      </div>
    </div>
  );
}
