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

          <!-- Other Admin Links -->
          

          <RouterLink
            to="/admin/akun"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-slate-600 transition-all hover:bg-slate-50"
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
                  <i class="fa-solid fa-file-lines text-xl text-white"></i>
                </div>
                <div>
                  <p class="text-sm font-semibold uppercase tracking-[0.3em] text-orange-600">
                    Admin Report Center
                  </p>
                  <h1 class="text-2xl font-bold text-slate-900">
                    Report Team per Lokasi
                  </h1>
                  <p class="text-sm text-slate-500">
                    Pantau report hari ini, kemarin, atau tanggal tertentu dan buka PDF snapshot per lokasi.
                  </p>
                </div>
              </div>
              
              <button
                @click="syncReports"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="isSyncing"
              >
                <i class="fa-solid fa-rotate-right text-sm"></i>
                <span>{{ isSyncing ? "Sinkron..." : "Sinkron Report" }}</span>
              </button>
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
                  <i class="fa-solid fa-file-lines text-base text-white"></i>
                </div>
                <div>
                  <p class="text-xs font-semibold uppercase tracking-[0.3em] text-orange-600">
                    Admin Report Center
                  </p>
                  <h1 class="text-base font-bold text-slate-900">
                    Report Team per Lokasi
                  </h1>
                </div>
              </div>
            </div>
            
            <div class="mt-3">
              <p class="text-xs text-slate-500 mb-2">
                Pantau report hari ini, kemarin, atau tanggal tertentu dan buka PDF snapshot per lokasi.
              </p>
              <button
                @click="syncReports"
                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="isSyncing"
              >
                <i class="fa-solid fa-rotate-right text-sm" :class="{'fa-spin': isSyncing}"></i>
                <span>{{ isSyncing ? "Sinkron..." : "Sinkron Report" }}</span>
              </button>
            </div>
          </div>
        </div>
      </header>

      <main class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        <!-- Summary Cards -->
        <section class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 sm:gap-4">
          <article
            v-for="card in summaryCards"
            :key="card.label"
            class="rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm"
          >
            <div class="flex items-start justify-between gap-2 sm:gap-4">
              <div class="flex-1 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-slate-500">{{ card.label }}</p>
                <p class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold text-slate-900">
                  {{ formatNumber(card.value) }}
                </p>
                <p class="mt-1 sm:mt-2 text-xs text-slate-500">{{ card.caption }}</p>
              </div>
              <div
                class="flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-xl sm:rounded-2xl flex-shrink-0"
                :class="card.iconClass"
              >
                <i :class="[card.icon, 'text-sm sm:text-base']"></i>
              </div>
            </div>
          </article>
        </section>

        <!-- Filter Section -->
        <section class="mt-4 sm:mt-6 rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
          <div class="flex flex-col gap-4 sm:gap-5">
            <!-- Filter Buttons - Scrollable on mobile -->
            <div class="overflow-x-auto pb-2 -mx-1 px-1">
              <div class="flex gap-2 min-w-max">
                <button
                  v-for="option in filterOptions"
                  :key="option.value"
                  @click="setFilter(option.value)"
                  class="rounded-full px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold transition whitespace-nowrap"
                  :class="
                    filters.filter === option.value
                      ? 'bg-orange-500 text-white shadow-md shadow-orange-200'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                  "
                >
                  {{ option.label }}
                </button>
              </div>
            </div>

            <!-- Filter Inputs -->
            <div class="grid gap-3 sm:gap-4">
              <!-- Search Location -->
              <div>
                <label class="mb-1 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">
                  Cari lokasi
                </label>
                <div class="relative">
                  <input
                    ref="lokasiInput"
                    v-model="displayKodeLokasi"
                    @input="handleKodeLokasiInput"
                    type="text"
                    placeholder="Contoh: 1.11.111"
                    class="w-full rounded-xl sm:rounded-2xl border border-slate-200 bg-slate-50 px-3 sm:px-4 py-2.5 sm:py-3 pr-10 sm:pr-12 text-sm outline-none transition focus:border-orange-400 focus:bg-white font-mono"
                  />
                  <button
                    v-if="displayKodeLokasi"
                    @click="clearLokasiInput"
                    type="button"
                    class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition hover:scale-110"
                  >
                    <i class="fa-solid fa-circle-xmark text-base sm:text-xl"></i>
                  </button>
                </div>
              </div>

              <!-- Custom Date Range -->
              <div v-if="filters.filter === 'custom'" class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div>
                  <label class="mb-1 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">
                    Tanggal mulai
                  </label>
                  <div class="relative">
                    <input
                      v-model="filters.start_date"
                      type="date"
                      class="w-full rounded-xl sm:rounded-2xl border border-slate-200 bg-slate-50 px-3 sm:px-4 py-2.5 sm:py-3 text-sm outline-none transition focus:border-orange-400 focus:bg-white"
                    />
                    <i class="fa-solid fa-calendar absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                  </div>
                </div>

                <div>
                  <label class="mb-1 sm:mb-2 block text-xs sm:text-sm font-semibold text-slate-700">
                    Tanggal akhir
                  </label>
                  <div class="relative">
                    <input
                      v-model="filters.end_date"
                      type="date"
                      class="w-full rounded-xl sm:rounded-2xl border border-slate-200 bg-slate-50 px-3 sm:px-4 py-2.5 sm:py-3 text-sm outline-none transition focus:border-orange-400 focus:bg-white"
                    />
                    <i class="fa-solid fa-calendar absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                @click="refreshDashboardData"
                class="w-full rounded-xl sm:rounded-2xl bg-orange-500 px-4 py-2.5 sm:py-3 text-sm font-semibold text-white transition hover:bg-orange-600"
                :disabled="isLoading"
              >
                {{ isLoading ? "Memuat..." : "Tampilkan" }}
              </button>
            </div>
          </div>
        </section>

        <!-- Messages -->
        <section v-if="successMessage" class="mt-3 sm:mt-4 rounded-xl sm:rounded-2xl border border-emerald-200 bg-emerald-50 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-emerald-700">
          {{ successMessage }}
        </section>

        <section v-if="errorMessage" class="mt-3 sm:mt-4 rounded-xl sm:rounded-2xl border border-red-200 bg-red-50 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-red-600">
          {{ errorMessage }}
        </section>

        <!-- Reports Table -->
        <section class="mt-4 sm:mt-6 rounded-2xl sm:rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
          <div class="flex flex-col gap-2 sm:gap-3 border-b border-slate-200 px-4 sm:px-5 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
              <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Daftar Report</h2>
                <p class="text-xs text-slate-500 hidden sm:block">
                  Data diambil dari snapshot yang disimpan di backend admin.
                </p>
              </div>
              <div class="rounded-full bg-slate-100 px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm font-semibold text-slate-600 self-start sm:self-auto">
                {{ reports.length }} report
              </div>
            </div>
          </div>

          <div v-if="isLoading" class="px-4 sm:px-5 py-12 sm:py-16 text-center text-xs sm:text-sm text-slate-500">
            <i class="fa-solid fa-spinner fa-spin text-2xl sm:text-3xl mb-3 text-orange-500"></i>
            <p>Memuat data report...</p>
          </div>

          <div v-else-if="reports.length === 0" class="px-4 sm:px-5 py-12 sm:py-16 text-center text-xs sm:text-sm text-slate-500">
            <i class="fa-solid fa-folder-open text-3xl sm:text-4xl mb-3 text-slate-300"></i>
            <p>Belum ada report yang tersimpan untuk filter ini.</p>
          </div>

          <div v-else class="overflow-x-auto">
            <!-- Mobile Card View -->
            <div class="block lg:hidden divide-y divide-slate-100">
              <div v-for="report in reports" :key="report.id" class="p-4 space-y-3">
                <div class="flex justify-between items-start">
                  <div>
                    <div class="font-semibold text-slate-900 text-sm">
                      {{ formatDate(report.report_date) }}
                    </div>
                    <div class="text-xs text-slate-500">
                      {{ formatDateTime(report.report_datetime) }}
                    </div>
                  </div>
                  <button
                    @click="downloadPdf(report)"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-50 px-3 py-2 font-semibold text-red-600 transition hover:bg-red-100 text-sm"
                    :disabled="downloadingReportId === report.id"
                  >
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>{{ downloadingReportId === report.id ? "..." : "PDF" }}</span>
                  </button>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs">
                  <div>
                    <span class="text-slate-500">Lokasi:</span>
                    <div class="font-semibold text-slate-900">{{ report.kode_lokasi }}</div>
                  </div>
                  <div>
                    <span class="text-slate-500">Team:</span>
                    <div class="font-semibold text-slate-900">{{ report.team?.kode_team || "-" }}</div>
                  </div>
                  <div class="col-span-2">
                    <span class="text-slate-500">Penghitung:</span>
                    <div class="text-slate-800">{{ report.team?.penghitung_1 || "-" }} & {{ report.team?.penghitung_2 || "-" }}</div>
                  </div>
                  <div>
                    <span class="text-slate-500">Total Item:</span>
                    <div class="font-semibold">{{ formatNumber(report.total_items) }}</div>
                  </div>
                  <div>
                    <span class="text-slate-500">Update:</span>
                    <div class="text-slate-600">{{ formatDateTimeShort(report.source_updated_at) }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Desktop Table View -->
            <table class="hidden lg:table min-w-full divide-y divide-slate-200 text-sm">
              <thead class="bg-slate-50 text-slate-500">
                <tr>
                  <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                  <th class="px-5 py-3 text-left font-semibold">Lokasi</th>
                  <th class="px-5 py-3 text-left font-semibold">Team</th>
                  <th class="px-5 py-3 text-left font-semibold">Penghitung</th>
                  <th class="px-5 py-3 text-left font-semibold">Item</th>
                  <th class="px-5 py-3 text-left font-semibold">Update</th>
                  <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 bg-white">
                <tr v-for="report in reports" :key="report.id" class="align-top">
                  <td class="px-5 py-4">
                    <div class="font-semibold text-slate-900">
                      {{ formatDate(report.report_date) }}
                    </div>
                    <div class="text-xs text-slate-500">
                      {{ formatDateTime(report.report_datetime) }}
                    </div>
                  </td>
                  <td class="px-5 py-4">
                    <div class="font-semibold text-slate-900">
                      {{ report.kode_lokasi }}
                    </div>
                    <div class="text-xs text-slate-500">
                      Tahun {{ report.report_year || "-" }}
                    </div>
                  </td>
                  <td class="px-5 py-4">
                    <div class="font-semibold text-slate-900">
                      {{ report.team?.kode_team || report.team?.no_team || "-" }}
                    </div>
                    <div class="text-xs text-slate-500">
                      Team ID: {{ report.team?.stock_opname_team_id || "-" }}
                    </div>
                  </td>
                  <td class="px-5 py-4">
                    <div class="font-medium text-slate-800">
                      1. {{ report.team?.penghitung_1 || "-" }}
                    </div>
                    <div class="mt-1 text-slate-600">
                      2. {{ report.team?.penghitung_2 || "-" }}
                    </div>
                  </td>
                  <td class="px-5 py-4">
                    <div class="font-semibold text-slate-900">
                      {{ formatNumber(report.total_items) }} barang
                    </div>
                    <div class="text-xs text-slate-500">
                      {{ formatNumber(report.total_logs) }} log perubahan
                    </div>
                  </td>
                  <td class="px-5 py-4 text-slate-600">
                    {{ formatDateTime(report.source_updated_at) }}
                  </td>
                  <td class="px-5 py-4">
                    <button
                      @click="downloadPdf(report)"
                      class="inline-flex items-center gap-2 rounded-xl bg-red-50 px-3 py-2 font-semibold text-red-600 transition hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-60 text-sm"
                      :disabled="downloadingReportId === report.id"
                    >
                      <i class="fa-solid fa-file-pdf"></i>
                      <span>{{ downloadingReportId === report.id ? "Membuka..." : "Lihat PDF" }}</span>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
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
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref, watch } from "vue";
import axios from "axios";
import { RouterLink, useRouter, useRoute } from "vue-router";
import { clearAuthSession } from "../utils/auth";

const router = useRouter();
const route = useRoute();
const adminBackendUrl =
  import.meta.env.VITE_BACKEND_1 || import.meta.env.VITE_BACKEND;

const today = new Date();
const todayValue = formatInputDate(today);
const yesterdayValue = formatInputDate(
  new Date(today.getTime() - 24 * 60 * 60 * 1000),
);

const filterOptions = [
  { value: "today", label: "Hari Ini" },
  { value: "yesterday", label: "Kemarin" },
  { value: "custom", label: "Pilih Tanggal" },
  { value: "all", label: "Semua" },
];

const filters = reactive({
  filter: "today",
  kode_lokasi_raw: "",
  start_date: todayValue,
  end_date: todayValue,
});

const reports = ref([]);
const stats = ref({
  total_reports: 0,
  total_locations: 0,
  reports_today: 0,
  reports_yesterday: 0,
});
const isLoading = ref(false);
const isSyncing = ref(false);
const downloadingReportId = ref(null);
const successMessage = ref("");
const errorMessage = ref("");
const lokasiInput = ref(null);
const showLogoutModal = ref(false);
const isSidebarOpen = ref(false);

const displayKodeLokasi = computed({
  get() {
    if (!filters.kode_lokasi_raw) return "";
    
    const digits = filters.kode_lokasi_raw;
    let formatted = "";
    
    if (digits.length >= 1) {
      formatted = digits.substring(0, 1);
    }
    
    if (digits.length >= 2) {
      const secondPart = digits.substring(1, Math.min(3, digits.length));
      if (secondPart) {
        formatted += "." + secondPart;
      }
    }
    
    if (digits.length >= 4) {
      const thirdPart = digits.substring(3, Math.min(6, digits.length));
      if (thirdPart) {
        formatted += "." + thirdPart;
      }
    }
    
    return formatted;
  },
  set(value) {
    const digits = value.replace(/\D/g, "");
    filters.kode_lokasi_raw = digits.substring(0, 6);
  }
});

function handleKodeLokasiInput(event) {
  const inputValue = event.target.value;
  const digits = inputValue.replace(/\D/g, "");
  const limitedDigits = digits.substring(0, 6);
  filters.kode_lokasi_raw = limitedDigits;
  
  const formatted = displayKodeLokasi.value;
  if (event.target.value !== formatted) {
    event.target.value = formatted;
  }
}

function clearLokasiInput() {
  filters.kode_lokasi_raw = "";
  if (lokasiInput.value) {
    lokasiInput.value.value = "";
  }
  refreshDashboardData();
}

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
}

function closeSidebar() {
  isSidebarOpen.value = false;
}

const summaryCards = computed(() => [
  {
    label: "Total Report",
    value: stats.value.total_reports,
    caption: "Jumlah snapshot untuk filter aktif",
    icon: "fa-solid fa-folder-open text-blue-600",
    iconClass: "bg-blue-100",
  },
  {
    label: "Total Lokasi",
    value: stats.value.total_locations,
    caption: "Lokasi yang punya report",
    icon: "fa-solid fa-location-dot text-emerald-600",
    iconClass: "bg-emerald-100",
  },
  {
    label: "Report Hari Ini",
    value: stats.value.reports_today,
    caption: "Snapshot hari ini",
    icon: "fa-solid fa-calendar-day text-orange-600",
    iconClass: "bg-orange-100",
  },
  {
    label: "Report Kemarin",
    value: stats.value.reports_yesterday,
    caption: "Snapshot kemarin",
    icon: "fa-solid fa-clock-rotate-left text-violet-600",
    iconClass: "bg-violet-100",
  },
]);

function buildQueryParams() {
  const params = {
    filter: filters.filter,
  };

  if (filters.kode_lokasi_raw && filters.kode_lokasi_raw.trim()) {
    params.kode_lokasi = displayKodeLokasi.value;
  }

  if (filters.filter === "custom") {
    params.start_date = filters.start_date || todayValue;
    params.end_date = filters.end_date || filters.start_date || todayValue;
  }

  return params;
}

function resetMessages() {
  successMessage.value = "";
  errorMessage.value = "";
}

async function loadReports() {
  isLoading.value = true;
  resetMessages();

  try {
    const params = buildQueryParams();
    const response = await axios.get(`${adminBackendUrl}/api/admin/reports`, {
      params: params,
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token") || ""}`,
      },
    });

    reports.value = response.data?.data?.reports || [];
    stats.value = response.data?.data?.stats || stats.value;
  } catch (error) {
    console.error("Gagal mengambil report admin:", error);
    errorMessage.value =
      error.response?.data?.message || "Gagal mengambil data report";
  } finally {
    isLoading.value = false;
  }
}

async function refreshDashboardData() {
  await loadReports();
}

async function syncReports() {
  isSyncing.value = true;
  resetMessages();

  try {
    const response = await axios.post(
      `${adminBackendUrl}/api/admin/reports/sync`,
      buildQueryParams(),
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token") || ""}`,
        },
      },
    );

    successMessage.value =
      response.data?.message || "Snapshot report berhasil disinkronkan";
    await refreshDashboardData();
  } catch (error) {
    console.error("Gagal sinkron report:", error);
    errorMessage.value =
      error.response?.data?.message || "Gagal sinkron report";
  } finally {
    isSyncing.value = false;
  }
}

async function downloadPdf(report) {
  downloadingReportId.value = report.id;
  resetMessages();

  try {
    const response = await axios.get(
      `${adminBackendUrl}/api/admin/reports/${report.id}/pdf`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token") || ""}`,
        },
        responseType: "blob",
      },
    );

    const blob = new Blob([response.data], { type: "application/pdf" });
    const fileUrl = window.URL.createObjectURL(blob);
    window.open(fileUrl, "_blank", "noopener,noreferrer");
    window.setTimeout(() => window.URL.revokeObjectURL(fileUrl), 3000);
  } catch (error) {
    console.error("Gagal membuka PDF:", error);
    errorMessage.value =
      error.response?.data?.message || "Gagal membuka PDF report";
  } finally {
    downloadingReportId.value = null;
  }
}

function setFilter(value) {
  filters.filter = value;

  if (value === "today") {
    filters.start_date = todayValue;
    filters.end_date = todayValue;
  }

  if (value === "yesterday") {
    filters.start_date = yesterdayValue;
    filters.end_date = yesterdayValue;
  }

  if (value === "custom" && !filters.start_date) {
    filters.start_date = todayValue;
    filters.end_date = todayValue;
  }
  
  refreshDashboardData();
}

let searchTimeout;
watch([() => filters.start_date, () => filters.end_date], () => {
  if (filters.filter === "custom") {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      refreshDashboardData();
    }, 500);
  }
});

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

function formatNumber(value) {
  return new Intl.NumberFormat("id-ID").format(Number(value || 0));
}

function formatPercent(value) {
  return `${new Intl.NumberFormat("id-ID", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 1,
  }).format(Number(value || 0))}%`;
}

function formatDate(value) {
  if (!value) return "-";
  return new Intl.DateTimeFormat("id-ID", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  }).format(new Date(value));
}

function formatDateTime(value) {
  if (!value) return "-";
  return new Intl.DateTimeFormat("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  }).format(new Date(value));
}

function formatDateTimeShort(value) {
  if (!value) return "-";
  return new Intl.DateTimeFormat("id-ID", {
    day: "2-digit",
    month: "short",
    hour: "2-digit",
    minute: "2-digit",
  }).format(new Date(value));
}

function formatInputDate(value) {
  return new Date(value.getTime() - value.getTimezoneOffset() * 60000)
    .toISOString()
    .slice(0, 10);
}

onMounted(async () => {
  await refreshDashboardData();
});

watch(() => route.path, () => {
  if (window.innerWidth < 1024) {
    closeSidebar();
  }
});

onBeforeUnmount(() => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
});
</script>

<style scoped>
input[type="date"] {
  color-scheme: light;
}

input[type="date"]::-webkit-calendar-picker-indicator {
  opacity: 0;
  position: absolute;
  right: 0;
  left: 0;
  top: 0;
  bottom: 0;
  width: auto;
  height: auto;
  cursor: pointer;
}

input[type="date"] {
  position: relative;
}

input[type="date"]:hover {
  background-color: #f8fafc;
}

input[type="text"] {
  font-family: monospace;
  letter-spacing: 0.5px;
}

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

.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
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
</style>
