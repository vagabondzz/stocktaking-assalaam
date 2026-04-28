<template>
  <div
    class="min-h-screen flex items-center justify-center p-4 bg-gray-200 !bg-gray-200 text-black"
  >
    <div class="text-center w-full max-w-md">
      <!-- Logo -->
      <div class="mb-6 grid place-items-center">
        <img src="../assets/logoassalam.png" alt="Logo Assalaam" class="w-40" />
        <p class="mt-2 font-bold tracking-widest text-black">STOCK TAKING</p>
      </div>

      <!-- Card -->
      <div class="bg-white p-6 rounded-xl shadow-xl w-full">
        <form @submit.prevent="handleLogin" class="space-y-4">
          <!-- Nomor Penghitung -->
          <div>
            <input
              type="text"
              placeholder="Username"
              class="w-full border rounded-md px-4 py-2 bg-white text-black focus:outline-none focus:ring-2 focus:ring-orange-400"
              v-model="formData.nomorPenghitung"
            />
            <p
              v-if="errors.nomorPenghitung"
              class="text-red-500 text-xs mt-1 text-left"
            >
              {{ errors.nomorPenghitung }}
            </p>
          </div>

          <!-- Kode Penghitung -->
          <div class="relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              placeholder="Password / Kode Tim"
              class="w-full border rounded-md px-4 py-2 bg-white text-black focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10"
              v-model="formData.kodePenghitung"
            />
            <button
              type="button"
              @click="togglePassword"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"
            >
              <!-- Eye (show password) -->
              <svg
                v-if="!showPassword"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0"
                />

                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                  c4.477 0 8.268 2.943 9.542 7
                  -1.274 4.057-5.065 7-9.542 7
                  -4.477 0-8.268-2.943-9.542-7z"
                />
              </svg>

              <!-- Eye Off (hide password) -->
              <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19
                  c-4.478 0-8.268-2.943-9.542-7
                  a9.956 9.956 0 012.042-3.368M6.223 6.223
                  A9.953 9.953 0 0112 5c4.478 0
                  8.268 2.943 9.542 7a9.97 9.97
                  0 01-4.132 5.411M15 12a3 3 0
                  11-6 0 3 3 0 016 0zm6 6L3 3"
                />
              </svg>
            </button>
            <p
              v-if="errors.kodePenghitung"
              class="text-red-500 text-xs mt-1 text-left"
            >
              {{ errors.kodePenghitung }}
            </p>
          </div>

          <!-- Button Login -->
          <button
            type="submit"
            class="w-full bg-orange-500 text-white py-2 rounded-md font-semibold hover:bg-orange-600 transition"
            :disabled="isLoading"
          >
            <span v-if="!isLoading"> Masuk </span>
            <span v-else class="flex items-center justify-center">
              <svg
                class="animate-spin mr-2 h-4 w-4 text-white"
                viewBox="0 0 24 24"
              >
                <circle
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="white"
                  stroke-width="4"
                  fill="none"
                />
              </svg>
              Loading...
            </span>
          </button>
        </form>

        <!-- Error Message -->
        <div v-if="errorMessage" class="mt-3 text-red-500 text-sm">
          {{ errorMessage }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { clearAuthSession, setAuthSession } from "../utils/auth";

const router = useRouter();
const adminBackendUrl =
  import.meta.env.VITE_BACKEND_1 || import.meta.env.VITE_BACKEND;
const teamBackendUrl =
  import.meta.env.VITE_BACKEND_2 || import.meta.env.VITE_BACKEND;

const showPassword = ref(false);
const isLoading = ref(false);
const errorMessage = ref("");

const formData = reactive({
  nomorPenghitung: "",
  kodePenghitung: "",
});

const errors = reactive({
  nomorPenghitung: "",
  kodePenghitung: "",
});

function togglePassword() {
  showPassword.value = !showPassword.value;
}

function validateForm() {
  errors.nomorPenghitung = "";
  errors.kodePenghitung = "";
  let valid = true;

  if (!formData.nomorPenghitung) {
    errors.nomorPenghitung = "Nomor penghitung wajib diisi";
    valid = false;
  }

  if (!formData.kodePenghitung) {
    errors.kodePenghitung = "Password atau kode tim wajib diisi";
    valid = false;
  }

  return valid;
}

function formatTeamPassword(password) {
  const numericPassword = String(password || "").replace(/\D/g, "");

  if (numericPassword.length !== 6) {
    return null;
  }

  return numericPassword.slice(0, 3) + "." + numericPassword.slice(3, 6);
}

function extractToken(payload) {
  return (
    payload?.token ||
    payload?.access_token ||
    payload?.data?.token ||
    payload?.data?.access_token ||
    payload?.data?.data?.token ||
    payload?.data?.data?.access_token ||
    ""
  );
}

function isSuccessfulResponse(payload) {
  if (typeof payload?.success === "boolean") {
    return payload.success;
  }

  return Boolean(extractToken(payload));
}

async function handleLogin() {
  if (!validateForm()) return;

  isLoading.value = true;
  errorMessage.value = "";
  clearAuthSession();

  const username = formData.nomorPenghitung.trim();
  const rawPassword = String(formData.kodePenghitung || "").trim();
  const formattedTeamPassword = formatTeamPassword(rawPassword);
  const isTeamCredential = Boolean(formattedTeamPassword);

  try {
    if (adminBackendUrl) {
      try {
        const adminResponse = await axios.post(
          `${adminBackendUrl}/api/auth/admin/login`,
          {
            username,
            password: rawPassword,
          },
        );

        const adminToken = extractToken(adminResponse.data);

        if (isSuccessfulResponse(adminResponse.data) && adminToken) {
          setAuthSession(adminToken, "admin");
          await router.push("/admin");
          return;
        }

        if (isSuccessfulResponse(adminResponse.data) && !adminToken) {
          errorMessage.value =
            adminResponse.data?.message ||
            "Login admin berhasil, tetapi token admin tidak ditemukan";
          return;
        }

        if (!isTeamCredential) {
          errorMessage.value = adminResponse.data?.message || "Login admin gagal";
          return;
        }
      } catch (adminError) {
        const adminStatus = adminError.response?.status;
        const isInvalidAdminCredential =
          adminStatus === 401 || adminStatus === 422 || adminStatus === 404;

        if (!isInvalidAdminCredential) {
          throw adminError;
        }

        if (!isTeamCredential) {
          errorMessage.value =
            adminError.response?.data?.message || "Login admin gagal";
          return;
        }
      }
    }

    if (!isTeamCredential) {
      errorMessage.value = "Kode tim harus 6 digit angka, contoh: 001010";
      return;
    }

    const teamResponse = await axios.post(
      `${teamBackendUrl}/api/auth/login/team`,
      {
        username,
        password: formattedTeamPassword,
      },
    );

    const teamToken = extractToken(teamResponse.data);

    if (isSuccessfulResponse(teamResponse.data) && teamToken) {
      setAuthSession(teamToken, "team");
      await router.push("/lokasi");
      return;
    }

    if (isSuccessfulResponse(teamResponse.data) && !teamToken) {
      errorMessage.value =
        teamResponse.data?.message ||
        "Login team berhasil, tetapi token tidak ditemukan";
      return;
    }

    errorMessage.value = teamResponse.data?.message || "Login gagal";
  } catch (err) {
    console.error(err);
    errorMessage.value = err.response?.data?.message || "Login gagal";
  } finally {
    isLoading.value = false;
  }
}
</script>
