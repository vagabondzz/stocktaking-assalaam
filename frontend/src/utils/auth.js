export function clearAuthSession() {
  localStorage.removeItem("token");
  localStorage.removeItem("auth_role");
}

export function setAuthSession(token, role = "") {
  localStorage.setItem("token", token);
  localStorage.setItem("auth_role", role);
}

export function getAuthRole() {
  return localStorage.getItem("auth_role") || "";
}

export function decodeJwtPayload(token) {
  try {
    const payload = token?.split(".")?.[1];

    if (!payload) return null;

    const normalizedPayload = payload.replace(/-/g, "+").replace(/_/g, "/");
    const paddedPayload =
      normalizedPayload +
      "=".repeat((4 - (normalizedPayload.length % 4)) % 4);

    return JSON.parse(atob(paddedPayload));
  } catch (error) {
    console.error("Gagal decode token:", error);
    return null;
  }
}

export function isTokenExpired(token) {
  const payload = decodeJwtPayload(token);

  if (!payload?.exp) {
    return false;
  }

  return payload.exp <= Math.floor(Date.now() / 1000);
}

export function hasExpiredTokenMessage(message) {
  const normalizedMessage = String(message || "").toLowerCase();

  return (
    normalizedMessage.includes("token expired") ||
    normalizedMessage.includes("expired token") ||
    normalizedMessage.includes("unauthorized") ||
    normalizedMessage.includes("token tidak valid") ||
    normalizedMessage.includes("invalid token") ||
    normalizedMessage.includes("jwt expired")
  );
}

export function isAuthenticated() {
  const token = localStorage.getItem("token");

  if (!token) {
    return false;
  }

  if (isTokenExpired(token)) {
    clearAuthSession();
    return false;
  }

  return true;
}
