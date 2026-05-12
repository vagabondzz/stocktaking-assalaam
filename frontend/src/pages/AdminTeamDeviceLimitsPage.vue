<template>
  <div class="min-h-screen bg-slate-100 text-slate-900 flex">
    <aside
      class="fixed left-0 top-0 z-40 h-full bg-white shadow-xl transition-all duration-300 ease-in-out border-r border-slate-200"
      :class="[
        isSidebarOpen ? 'w-72 translate-x-0' : 'w-72 -translate-x-full',
        'lg:w-80 lg:translate-x-0',
      ]"
    >
      <div class="flex h-full flex-col">
        <div
          class="flex items-center justify-between border-b border-slate-200 p-4"
        >
          <div class="flex items-center gap-3">
            <div
              class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500"
            >
              <i class="fa-solid fa-chart-line text-sm text-white"></i>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-sm lg:text-base">
                Admin Panel
              </h3>
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

        <nav class="flex-1 space-y-1 p-3 lg:p-4 overflow-y-auto">
          <RouterLink
            to="/admin"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all"
            :class="{
              'bg-orange-50 text-orange-600': route.path === '/admin',
              'text-slate-600 hover:bg-slate-50': route.path !== '/admin',
            }"
            @click="closeSidebar"
          >
            <i class="fa-solid fa-gauge-high w-5 text-base lg:text-lg"></i>
            <span class="text-sm">Dashboard Report</span>
          </RouterLink>

          <RouterLink
            to="/admin/open-locations"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all"
            :class="{
              'bg-orange-50 text-orange-600':
                route.path === '/admin/open-locations',
              'text-slate-600 hover:bg-slate-50':
                route.path !== '/admin/open-locations',
            }"
            @click="closeSidebar"
          >
            <i class="fa-solid fa-unlock-keyhole w-5 text-base lg:text-lg"></i>
            <span class="text-sm">Lokasi Aktif</span>
          </RouterLink>

          <RouterLink
            to="/admin/team-device-limits"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all"
            :class="{
              'bg-orange-50 text-orange-600':
                route.path === '/admin/team-device-limits',
              'text-slate-600 hover:bg-slate-50':
                route.path !== '/admin/team-device-limits',
            }"
            @click="closeSidebar"
          >
            <i
              class="fa-solid fa-mobile-screen-button w-5 text-base lg:text-lg"
            ></i>
            <span class="text-sm">Batas Device Team</span>
          </RouterLink>

          <div class="my-3 lg:my-4 border-t border-slate-100"></div>

          <RouterLink
            to="/admin/akun"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all"
            :class="{
              'bg-orange-50 text-orange-600': route.path === '/admin/akun',
              'text-slate-600 hover:bg-slate-50': route.path !== '/admin/akun',
            }"
            @click="closeSidebar"
          >
            <i class="fa-solid fa-users w-5 text-base lg:text-lg"></i>
            <span class="text-sm">Manajemen User</span>
          </RouterLink>
        </nav>

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

    <div
      v-if="isSidebarOpen"
      class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm transition-all duration-300 lg:hidden"
      @click="closeSidebar"
    ></div>

    <div class="flex-1 lg:ml-80 min-w-0">
      <header class="border-b border-slate-200 bg-white sticky top-0 z-20">
        <div class="px-4 py-3 sm:px-6 lg:px-8">
          <div class="hidden sm:block">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div
                  class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-500 shadow-lg shadow-sky-200"
                >
                  <i
                    class="fa-solid fa-mobile-screen-button text-xl text-white"
                  ></i>
                </div>
                <div>
                  <p
                    class="text-sm font-semibold uppercase tracking-[0.3em] text-sky-600"
                  >
                    Team Device Control
                  </p>
                  <h1 class="text-2xl font-bold text-slate-900">
                    Batas Device per Team
                  </h1>
                  <p class="text-sm text-slate-500">
                    Atur berapa device yang boleh login untuk setiap team dan
                    pantau device aktifnya.
                  </p>
                </div>
              </div>

              <button
                @click="loadTeams"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="isLoading"
              >
                <i
                  class="fa-solid fa-rotate-right"
                  :class="{ 'fa-spin': isLoading }"
                ></i>
                <span>{{ isLoading ? "Memuat..." : "Refresh" }}</span>
              </button>
            </div>
          </div>

          <div class="block sm:hidden">
            <div class="flex items-center gap-3">
              <button
                @click="toggleSidebar"
                class="rounded-xl p-2 text-slate-600 transition hover:bg-slate-100"
              >
                <i class="fa-solid fa-bars text-xl"></i>
              </button>
              <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500 shadow-lg shadow-sky-200"
              >
                <i
                  class="fa-solid fa-mobile-screen-button text-base text-white"
                ></i>
              </div>
              <div>
                <p
                  class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-600"
                >
                  Team Device Control
                </p>
                <h1 class="text-base font-bold text-slate-900">
                  Batas Device Team
                </h1>
              </div>
            </div>
            <p class="mt-3 text-xs text-slate-500">
              Atur jumlah device login untuk setiap team.
            </p>
          </div>
        </div>
      </header>

      <main class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8 space-y-4 sm:space-y-6">
        <section class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
          <article
            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
          >
            <p class="text-sm font-medium text-slate-500">Total Team</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">
              {{ formatNumber(summary.total_teams) }}
            </p>
          </article>
          <article
            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
          >
            <p class="text-sm font-medium text-slate-500">Device Aktif</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">
              {{ formatNumber(summary.total_active_devices) }}
            </p>
          </article>
          <article
            class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
          >
            <p class="text-sm font-medium text-slate-500">Rata-rata Batas</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">
              {{ averageLimit }}
            </p>
          </article>
        </section>

        <section
          class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm"
        >
          <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto]">
            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700"
                >Cari team</label
              >
              <input
                v-model="filters.keyword"
                @keyup.enter="loadTeams"
                type="text"
                placeholder="Cari No Team, kode team, atau nama penghitung"
                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-sky-400 focus:bg-white"
              />
            </div>
            <div class="flex items-end gap-2">
              <button
                @click="loadTeams"
                class="w-full rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-600 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="isLoading"
              >
                Tampilkan
              </button>
            </div>
          </div>
        </section>

        <section
          v-if="successMessage"
          class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700"
        >
          {{ successMessage }}
        </section>

        <section
          v-if="errorMessage"
          class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600"
        >
          {{ errorMessage }}
        </section>

        <section
          class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
        >
          <div class="border-b border-slate-200 px-4 py-4 sm:px-5">
            <div class="flex items-center justify-between gap-3">
              <div>
                <h2 class="text-lg font-bold text-slate-900">Daftar Team</h2>
                <p class="text-xs text-slate-500">
                  Atur limit device per team dan lihat device yang sedang aktif.
                </p>
              </div>
              <div
                class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600"
              >
                {{ teams.length }} team
              </div>
            </div>
          </div>

          <div
            v-if="isLoading && teams.length === 0"
            class="px-5 py-16 text-center text-sm text-slate-500"
          >
            <i
              class="fa-solid fa-spinner fa-spin text-3xl mb-3 text-sky-500"
            ></i>
            <p>Memuat data batas device team...</p>
          </div>

          <div
            v-else-if="teams.length === 0"
            class="px-5 py-16 text-center text-sm text-slate-500"
          >
            <i
              class="fa-solid fa-mobile-screen text-4xl mb-3 text-slate-300"
            ></i>
            <p>Belum ada data team yang cocok dengan filter ini.</p>
          </div>

          <div v-else class="divide-y divide-slate-100">
            <article
              v-for="team in paginatedTeams"
              :key="team.no_team"
              class="p-4 sm:p-5"
            >
              <div
                class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between"
              >
                <div class="space-y-2">
                  <div class="flex flex-wrap items-center gap-2">
                    <span
                      class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700"
                    >
                      No Team {{ team.no_team }}
                    </span>
                    <span
                      class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"
                    >
                      Kode Team {{ team.kode_team || "-" }}
                    </span>
                    <span
                      class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700"
                    >
                      {{ team.active_devices_count }} device aktif
                    </span>
                  </div>
                  <h3 class="text-base font-bold text-slate-900">
                    {{ team.penghitung_1 || "-" }} &
                    {{ team.penghitung_2 || "-" }}
                  </h3>
                  <p class="text-sm text-slate-500">
                    Batas saat ini {{ formatNumber(team.max_devices) }} device
                    untuk team ini.
                  </p>
                  <div class="flex flex-wrap gap-2">
                    <span
                      v-for="device in team.active_devices"
                      :key="`${team.no_team}-${device.device_id}`"
                      class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600"
                    >
                      {{ device.device_name }}
                    </span>
                    <span
                      v-if="team.active_devices.length === 0"
                      class="rounded-xl border border-dashed border-slate-200 px-3 py-2 text-xs text-slate-400"
                    >
                      Belum ada device aktif
                    </span>
                  </div>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                  <div>
                    <label
                      class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500"
                    >
                      Batas Device
                    </label>
                    <input
                      v-model.number="editableLimits[team.no_team]"
                      type="number"
                      min="1"
                      max="100"
                      class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-sky-400 focus:bg-white sm:w-28"
                    />
                  </div>
                  <button
                    @click="saveLimit(team)"
                    class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="savingTeams[team.no_team]"
                  >
                    {{ savingTeams[team.no_team] ? "Menyimpan..." : "Simpan" }}
                  </button>
                </div>
              </div>
            </article>
          </div>

          <div
            v-if="totalPages > 1"
            class="flex flex-col gap-3 border-t border-slate-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5"
          >
            <p class="text-xs text-slate-500 sm:text-sm">
              Menampilkan {{ paginationStart }}-{{ paginationEnd }} dari
              {{ teams.length }} team
            </p>
            <div class="flex items-center gap-2">
              <button
                @click="goToPreviousPage"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="currentPage === 1"
              >
                Sebelumnya
              </button>
              <div
                class="rounded-xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700"
              >
                {{ currentPage }} / {{ totalPages }}
              </div>
              <button
                @click="goToNextPage"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="currentPage === totalPages"
              >
                Berikutnya
              </button>
            </div>
          </div>
        </section>
      </main>
    </div>

    <div
      v-if="showLogoutModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    >
      <div class="w-full max-w-sm rounded-2xl bg-white shadow-2xl">
        <div class="p-6">
          <div class="flex items-center gap-4">
            <div
              class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100"
            >
              <i
                class="fa-solid fa-right-from-bracket text-xl text-red-600"
              ></i>
            </div>
            <div>
              <h3 class="text-lg font-bold text-slate-900">
                Konfirmasi Logout
              </h3>
              <p class="text-sm text-slate-500">
                Apakah Anda yakin ingin keluar?
              </p>
            </div>
          </div>
          <div class="mt-6 flex gap-3">
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
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from "vue";
import axios from "axios";
import { RouterLink, useRoute, useRouter } from "vue-router";
import { clearAuthSession } from "../utils/auth";

const router = useRouter();
const route = useRoute();
const adminBackendUrl =
  import.meta.env.VITE_BACKEND_1 || import.meta.env.VITE_BACKEND;

const isSidebarOpen = ref(false);
const showLogoutModal = ref(false);
const isLoading = ref(false);
const teams = ref([]);
const currentPage = ref(1);
const successMessage = ref("");
const errorMessage = ref("");
const summary = reactive({
  total_teams: 0,
  total_active_devices: 0,
});
const filters = reactive({
  keyword: "",
});
const editableLimits = reactive({});
const savingTeams = reactive({});
const itemsPerPage = 5;
let pollingTimer = null;

const averageLimit = computed(() => {
  if (teams.value.length === 0) return "0";

  const total = teams.value.reduce(
    (sum, team) => sum + Number(team.max_devices || 0),
    0,
  );

  return (total / teams.value.length).toFixed(1);
});

const totalPages = computed(() =>
  Math.max(1, Math.ceil(teams.value.length / itemsPerPage)),
);

const paginatedTeams = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;

  return teams.value.slice(start, end);
});

const paginationStart = computed(() => {
  if (teams.value.length === 0) return 0;
  return (currentPage.value - 1) * itemsPerPage + 1;
});

const paginationEnd = computed(() => {
  if (teams.value.length === 0) return 0;
  return Math.min(currentPage.value * itemsPerPage, teams.value.length);
});

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
}

function closeSidebar() {
  isSidebarOpen.value = false;
}

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

function resetMessages() {
  successMessage.value = "";
  errorMessage.value = "";
}

function goToPreviousPage() {
  if (currentPage.value > 1) {
    currentPage.value -= 1;
  }
}

function goToNextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value += 1;
  }
}

function formatNumber(value) {
  return new Intl.NumberFormat("id-ID").format(Number(value || 0));
}

async function loadTeams({ silent = false, preservePage = false } = {}) {
  if (!silent) {
    isLoading.value = true;
    resetMessages();
  }

  try {
    const response = await axios.get(
      `${adminBackendUrl}/api/admin/team-device-limits`,
      {
        params: {
          keyword: filters.keyword || undefined,
        },
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token") || ""}`,
        },
      },
    );

    const previousPage = currentPage.value;
    teams.value = response.data?.data?.teams || [];
    summary.total_teams = Number(
      response.data?.data?.summary?.total_teams || 0,
    );
    summary.total_active_devices = Number(
      response.data?.data?.summary?.total_active_devices || 0,
    );

    teams.value.forEach((team) => {
      editableLimits[team.no_team] = Number(team.max_devices || 1);
    });

    if (preservePage) {
      currentPage.value = Math.min(
        Math.max(previousPage, 1),
        Math.max(1, Math.ceil(teams.value.length / itemsPerPage)),
      );
    } else {
      currentPage.value = 1;
    }
  } catch (error) {
    console.error("Gagal mengambil batas device team:", error);
    if (!silent) {
      errorMessage.value =
        error.response?.data?.message ||
        "Gagal mengambil data batas device team";
    }
  } finally {
    if (!silent) {
      isLoading.value = false;
    }
  }
}

async function saveLimit(team) {
  const nextLimit = Number(editableLimits[team.no_team] || 0);

  if (nextLimit < 1) {
    errorMessage.value = "Batas device minimal 1";
    return;
  }

  savingTeams[team.no_team] = true;
  resetMessages();

  try {
    const response = await axios.put(
      `${adminBackendUrl}/api/admin/team-device-limits/${team.no_team}`,
      {
        max_devices: nextLimit,
        team_id: team.team_id,
        kode_team: team.kode_team,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token") || ""}`,
        },
      },
    );

    successMessage.value =
      response.data?.message || "Batas device team berhasil diperbarui";

    const target = teams.value.find((item) => item.no_team === team.no_team);
    if (target) {
      target.max_devices = nextLimit;
    }

    if (currentPage.value > totalPages.value) {
      currentPage.value = totalPages.value;
    }
  } catch (error) {
    console.error("Gagal menyimpan batas device team:", error);
    errorMessage.value =
      error.response?.data?.message || "Gagal menyimpan batas device team";
  } finally {
    savingTeams[team.no_team] = false;
  }
}

onMounted(() => {
  loadTeams();
  pollingTimer = window.setInterval(() => {
    loadTeams({ silent: true, preservePage: true });
  }, 10000);
});

onBeforeUnmount(() => {
  if (pollingTimer) {
    clearInterval(pollingTimer);
    pollingTimer = null;
  }
});
</script>
