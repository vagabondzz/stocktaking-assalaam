<template>
  <div class="min-h-screen bg-slate-100 text-slate-900 flex">
    <!-- Sidebar - Selalu di kiri -->
    <aside 
      class="fixed left-0 top-0 z-40 h-full bg-white shadow-xl transition-all duration-300 ease-in-out border-r border-slate-200"
      :class="[
        isSidebarOpen ? 'w-72 translate-x-0' : 'w-72 -translate-x-full',
        'lg:w-80 lg:translate-x-0'
      ]"
    >
      <div class="flex h-full flex-col">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between border-b border-slate-200 p-4">
          <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500">
              <i class="fa-solid fa-chart-line text-sm text-white"></i>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-sm lg:text-base">Admin Panel</h3>
              <p class="text-xs text-slate-500">Menu Navigasi</p>
            </div>
          </div>
          <button
            @click="closeSidebar"
            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 lg:hidden"
          >
            <i class="fa-solid fa-times text-lg"></i>
          </button>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 space-y-1 p-3 lg:p-4 overflow-y-auto">
          <!-- Dashboard Link -->
          <RouterLink
            to="/admin"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all"
            :class="{
              'bg-orange-50 text-orange-600': $route.path === '/admin',
              'text-slate-600 hover:bg-slate-50': $route.path !== '/admin',
            }"
            @click="closeSidebar"
          >
            <i class="fa-solid fa-gauge-high w-5 text-base lg:text-lg"></i>
            <span class="text-sm">Dashboard Report</span>
          </RouterLink>

          <!-- Active Locations Link -->
          <RouterLink
            to="/admin/open-locations"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all"
            :class="{
              'bg-orange-50 text-orange-600': $route.path === '/admin/open-locations',
              'text-slate-600 hover:bg-slate-50': $route.path !== '/admin/open-locations',
            }"
            @click="closeSidebar"
          >
            <i class="fa-solid fa-unlock-keyhole w-5 text-base lg:text-lg"></i>
            <span class="text-sm">Lokasi Aktif</span>
          </RouterLink>

          <!-- Divider -->
          <div class="my-3 lg:my-4 border-t border-slate-100"></div>

          <!-- Manajemen User Link (Active) -->
          <RouterLink
            to="/admin/akun"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all"
            :class="{
              'bg-orange-50 text-orange-600': $route.path === '/admin/akun',
              'text-slate-600 hover:bg-slate-50': $route.path !== '/admin/akun',
            }"
            @click="closeSidebar"
          >
            <i class="fa-solid fa-users w-5 text-base lg:text-lg"></i>
            <span class="text-sm">Manajemen User</span>
          </RouterLink>

          
          
        </nav>

        <!-- Sidebar Footer dengan Tombol Logout -->
        <div class="border-t border-slate-200 p-3 lg:p-4">
          <button
            @click="confirmLogout"
            class="w-full flex items-center gap-3 rounded-xl bg-red-50 px-3 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-100"
          >
            <i class="fa-solid fa-right-from-bracket text-base lg:text-lg"></i>
            <span class="text-sm">Logout</span>
          </button>
        </div>
      </div>
    </aside>

    <!-- Overlay untuk mobile -->
    <div
      v-if="isSidebarOpen"
      class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm transition-all duration-300 lg:hidden"
      @click="closeSidebar"
    ></div>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-80 min-w-0">
      <header class="border-b border-slate-200 bg-white sticky top-0 z-20">
        <div class="px-4 py-3 sm:px-6 lg:px-8">
          <!-- Desktop Layout -->
          <div class="hidden sm:block">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-500 shadow-lg shadow-orange-200">
                  <i class="fa-solid fa-user-plus text-xl text-white"></i>
                </div>
                <div>
                  <p class="text-sm font-semibold uppercase tracking-[0.3em] text-orange-600">
                    Admin Management
                  </p>
                  <h1 class="text-2xl font-bold text-slate-900">
                    Manajemen User Admin
                  </h1>
                  <p class="text-sm text-slate-500">
                    Buat akun admin baru untuk mengakses panel administrasi
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Mobile Layout -->
          <div class="block sm:hidden">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <button
                  @click="toggleSidebar"
                  class="rounded-xl p-2 text-slate-600 transition hover:bg-slate-100"
                >
                  <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500 shadow-lg shadow-orange-200">
                  <i class="fa-solid fa-user-plus text-base text-white"></i>
                </div>
                <div>
                  <p class="text-xs font-semibold uppercase tracking-[0.3em] text-orange-600">
                    Admin Management
                  </p>
                  <h1 class="text-base font-bold text-slate-900">
                    Manajemen User
                  </h1>
                </div>
              </div>
            </div>
            
            <div class="mt-3">
              <p class="text-xs text-slate-500">
                Buat akun admin baru untuk mengakses panel administrasi
              </p>
            </div>
          </div>
        </div>
      </header>

      <main class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        <!-- Form Register Card -->
        <div class="max-w-md mx-auto">
          <!-- Logo dan Judul -->
          <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
              <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-500 shadow-lg shadow-orange-200">
                <i class="fa-solid fa-user-plus text-2xl text-white"></i>
              </div>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Buat Akun Admin</h1>
            <p class="text-sm text-slate-600">Daftar untuk mengakses admin panel</p>
          </div>

          <!-- Form Register -->
          <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
            <form @submit.prevent="handleRegister" class="space-y-5">
              <!-- Username Field -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                  Username
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-user text-slate-400 text-sm"></i>
                  </div>
                  <input
                    v-model="form.username"
                    type="text"
                    class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none transition focus:border-orange-400 focus:bg-white"
                    :class="{
                      'border-red-300 focus:border-red-400': errors.username,
                      'border-slate-200 focus:border-orange-400': !errors.username
                    }"
                    placeholder="Masukkan username"
                    autocomplete="off"
                  />
                </div>
                <p v-if="errors.username" class="mt-1 text-xs text-red-600">
                  {{ errors.username }}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                  Username harus unik dan maksimal 50 karakter
                </p>
              </div>

              <!-- Password Field -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                  Password
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-slate-400 text-sm"></i>
                  </div>
                  <input
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none transition focus:border-orange-400 focus:bg-white"
                    :class="{
                      'border-red-300 focus:border-red-400': errors.password,
                      'border-slate-200 focus:border-orange-400': !errors.password
                    }"
                    placeholder="Masukkan password"
                  />
                  <button
                    type="button"
                    @click="togglePassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                  >
                    <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                  </button>
                </div>
                <p v-if="errors.password" class="mt-1 text-xs text-red-600">
                  {{ errors.password }}
                </p>
                <p class="mt-1 text-xs text-slate-500">
                  Password minimal 8 karakter
                </p>
              </div>

              <!-- Confirm Password Field -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                  Konfirmasi Password
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-slate-400 text-sm"></i>
                  </div>
                  <input
                    v-model="form.confirmPassword"
                    :type="showConfirmPassword ? 'text' : 'password'"
                    class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm outline-none transition focus:border-orange-400 focus:bg-white"
                    :class="{
                      'border-red-300 focus:border-red-400': errors.confirmPassword,
                      'border-slate-200 focus:border-orange-400': !errors.confirmPassword
                    }"
                    placeholder="Konfirmasi password"
                  />
                  <button
                    type="button"
                    @click="toggleConfirmPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                  >
                    <i :class="showConfirmPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                  </button>
                </div>
                <p v-if="errors.confirmPassword" class="mt-1 text-xs text-red-600">
                  {{ errors.confirmPassword }}
                </p>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                :disabled="isLoading"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-xl transition disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
              >
                <i v-if="isLoading" class="fa-solid fa-spinner fa-spin"></i>
                <i v-else class="fa-solid fa-user-plus"></i>
                <span>{{ isLoading ? "Memproses..." : "Buat Akun" }}</span>
              </button>
            </form>
          </div>
        </div>
      </main>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div v-if="showLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
      <div class="w-full max-w-sm sm:max-w-md rounded-2xl bg-white shadow-2xl animate-in fade-in zoom-in duration-200">
        <div class="p-5 sm:p-6">
          <div class="flex items-center gap-3 sm:gap-4">
            <div class="flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-red-100 flex-shrink-0">
              <i class="fa-solid fa-right-from-bracket text-base sm:text-xl text-red-600"></i>
            </div>
            <div>
              <h3 class="text-base sm:text-lg font-bold text-slate-900">Konfirmasi Logout</h3>
              <p class="text-xs sm:text-sm text-slate-500">Apakah Anda yakin ingin keluar?</p>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 flex gap-3">
            <button
              @click="cancelLogout"
              class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
              Batal
            </button>
            <button
              @click="logout"
              class="flex-1 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700"
            >
              Ya, Logout
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert Modal -->
    <div v-if="alert.show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
      <div class="w-full max-w-sm rounded-2xl bg-white shadow-2xl animate-in fade-in zoom-in duration-200">
        <div class="p-6">
          <div class="flex items-center gap-4">
            <div 
              class="flex h-12 w-12 items-center justify-center rounded-full flex-shrink-0"
              :class="alert.type === 'success' ? 'bg-emerald-100' : 'bg-red-100'"
            >
              <i 
                :class="alert.type === 'success' ? 'fa-solid fa-check-circle text-emerald-600' : 'fa-solid fa-exclamation-triangle text-red-600'"
                class="text-xl"
              ></i>
            </div>
            <div>
              <h3 class="text-lg font-bold text-slate-900">
                {{ alert.type === 'success' ? 'Berhasil!' : 'Gagal!' }}
              </h3>
              <p class="text-sm text-slate-500">{{ alert.message }}</p>
            </div>
          </div>
          <div class="mt-6">
            <button
              @click="closeAlert"
              class="w-full rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { clearAuthSession } from "../utils/auth";

const router = useRouter();
const adminBackendUrl = import.meta.env.VITE_BACKEND_1 || import.meta.env.VITE_BACKEND;

// Sidebar state
const isSidebarOpen = ref(false);
const showLogoutModal = ref(false);

// Form data
const form = reactive({
  username: "",
  password: "",
  confirmPassword: ""
});

// Error handling
const errors = reactive({
  username: "",
  password: "",
  confirmPassword: ""
});

// UI state
const isLoading = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const alert = reactive({
  show: false,
  type: "success",
  message: ""
});

// Sidebar functions
function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
}

function closeSidebar() {
  isSidebarOpen.value = false;
}

// Toggle password visibility
function togglePassword() {
  showPassword.value = !showPassword.value;
}

function toggleConfirmPassword() {
  showConfirmPassword.value = !showConfirmPassword.value;
}

// Validate form
function validateForm() {
  let isValid = true;
  
  // Reset errors
  errors.username = "";
  errors.password = "";
  errors.confirmPassword = "";
  
  // Validate username
  if (!form.username.trim()) {
    errors.username = "Username wajib diisi";
    isValid = false;
  } else if (form.username.length > 50) {
    errors.username = "Username maksimal 50 karakter";
    isValid = false;
  }
  
  // Validate password
  if (!form.password) {
    errors.password = "Password wajib diisi";
    isValid = false;
  } else if (form.password.length < 6) {
    errors.password = "Password minimal 6 karakter";
    isValid = false;
  }
  
  // Validate confirm password
  if (!form.confirmPassword) {
    errors.confirmPassword = "Konfirmasi password wajib diisi";
    isValid = false;
  } else if (form.password !== form.confirmPassword) {
    errors.confirmPassword = "Password dan konfirmasi password tidak cocok";
    isValid = false;
  }
  
  return isValid;
}

// Handle register
async function handleRegister() {
  if (!validateForm()) {
    return;
  }
  
  isLoading.value = true;
  
  try {
    const response = await axios.post(
      `${adminBackendUrl}/api/auth/admin/register`,
      {
        username: form.username.trim(),
        password: form.password
      },
      {
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          Authorization: `Bearer ${localStorage.getItem("token") || ""}`
        }
      }
    );
    
    if (response.data && response.data.message) {
      // Show success message
      alert.type = "success";
      alert.message = response.data.message || "Admin berhasil dibuat!";
      alert.show = true;
      
      // Clear form
      form.username = "";
      form.password = "";
      form.confirmPassword = "";
      
      // Auto close alert after 2 seconds
      setTimeout(() => {
        closeAlert();
      }, 2000);
    }
  } catch (error) {
    console.error("Register error:", error);
    
    if (error.response) {
      // Handle validation errors from backend
      if (error.response.status === 422 && error.response.data.errors) {
        const backendErrors = error.response.data.errors;
        if (backendErrors.username) {
          errors.username = backendErrors.username[0];
        }
        if (backendErrors.password) {
          errors.password = backendErrors.password[0];
        }
      } 
      // Handle username already exists
      else if (error.response.status === 409 || 
               (error.response.data && error.response.data.message && error.response.data.message.includes("already exists"))) {
        errors.username = "Username sudah digunakan. Silakan pilih username lain.";
      }
      // Handle unauthorized (token expired, etc)
      else if (error.response.status === 401) {
        alert.type = "error";
        alert.message = "Sesi Anda telah berakhir. Silakan login kembali.";
        alert.show = true;
        setTimeout(() => {
          clearAuthSession();
          router.push("/");
        }, 2000);
      }
      // Handle other errors
      else {
        alert.type = "error";
        alert.message = error.response.data?.message || "Gagal mendaftar. Silakan coba lagi.";
        alert.show = true;
      }
    } else if (error.request) {
      alert.type = "error";
      alert.message = "Tidak dapat terhubung ke server. Periksa koneksi Anda.";
      alert.show = true;
    } else {
      alert.type = "error";
      alert.message = "Terjadi kesalahan. Silakan coba lagi.";
      alert.show = true;
    }
  } finally {
    isLoading.value = false;
  }
}

// Close alert modal
function closeAlert() {
  alert.show = false;
}

// Logout functions
function confirmLogout() {
  showLogoutModal.value = true;
}

function cancelLogout() {
  showLogoutModal.value = false;
}

function logout() {
  showLogoutModal.value = false;
  clearAuthSession();
  router.push("/");
}
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes zoom-in {
  from {
    transform: scale(0.95);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

.animate-in {
  animation-duration: 0.2s;
  animation-fill-mode: both;
}

.fade-in {
  animation-name: fade-in;
}

.zoom-in {
  animation-name: zoom-in;
}

/* Input focus styles */
input:focus {
  box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
}

/* Disable button styles */
button:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}

/* Custom scrollbar for mobile */
.overflow-x-auto::-webkit-scrollbar {
  height: 4px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}
</style>