import { createRouter, createWebHistory } from "vue-router";
import LoginPage from "../pages/LoginPage.vue";
import LokasiPage from "../pages/LokasiPage.vue";
import DasboardAdmin from "../pages/DasboardAdmin.vue";
import AdminOpenLocationsPage from "../pages/AdminOpenLocationsPage.vue";
import ListsoPage from "../pages/ListsoPage.vue";
import { clearAuthSession, getAuthRole, isAuthenticated } from "../utils/auth";
import TambahAkun from "../pages/TambahAkun.vue";

const routes = [
  {
    path: "/",
    component: LoginPage,
    meta: { requiresAuth: false },
  },
  {
    path: "/lokasi",
    component: LokasiPage,
    meta: { requiresAuth: true, role: "team" },
  },
  {
    path: "/admin",
    component: DasboardAdmin,
    meta: { requiresAuth: true, role: "admin" },
  },
  {
    path: "/admin/open-locations",
    component: AdminOpenLocationsPage,
    meta: { requiresAuth: true, role: "admin" },
  },
  {
    path: "/admin/akun",
    component: TambahAkun,
    meta: { requiresAuth: true, role: "admin" },
  },
  {
    path: "/listso",
    component: ListsoPage,
    meta: { requiresAuth: true, role: "team" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to) => {
  if (!to.meta.requiresAuth) {
    return true;
  }

  if (isAuthenticated()) {
    const requiredRole = to.meta.role;

    if (!requiredRole || getAuthRole() === requiredRole) {
      return true;
    }

    return { path: getAuthRole() === "admin" ? "/admin" : "/lokasi" };
  }

  clearAuthSession();
  return { path: "/" };
});

export default router;
