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

          <!-- Active Locations Link (Active) -->
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

          <RouterLink
            to="/admin/team-device-limits"
            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all"
            :class="{
              'bg-orange-50 text-orange-600': $route.path === '/admin/team-device-limits',
              'text-slate-600 hover:bg-slate-50': $route.path !== '/admin/team-device-limits',
            }"
            @click="closeSidebar"
          >
            <i class="fa-solid fa-mobile-screen-button w-5 text-base lg:text-lg"></i>
            <span class="text-sm">Batas Device Team</span>
          </RouterLink>

          <!-- Divider -->
          <div class="my-3 lg:my-4 border-t border-slate-100"></div>

          <!-- Manajemen User Link -->
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
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500 shadow-lg shadow-emerald-200">
                  <i class="fa-solid fa-unlock-keyhole text-xl text-white"></i>
                </div>
                <div>
                  <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">
                    Admin Location Monitor
                  </p>
                  <h1 class="text-2xl font-bold text-slate-900">
                    Lokasi yang Sedang Dibuka
                  </h1>
                  <p class="text-sm text-slate-500">
                    Pantau lokasi yang sedang aktif dan lihat tim yang sedang membuka.
                  </p>
                </div>
              </div>

              <div class="flex items-center gap-3">
                <button
                  @click="refreshPageData"
                  class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                  :disabled="isLoading"
                >
                <i class="fa-solid fa-rotate-right" :class="{'fa-spin': isLoading}"></i>
                  <span>{{ isLoading ? "Memuat..." : "Refresh" }}</span>
                </button>
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
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500 shadow-lg shadow-emerald-200">
                  <i class="fa-solid fa-unlock-keyhole text-base text-white"></i>
                </div>
                <div>
                  <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-600">
                    Admin Location Monitor
                  </p>
                  <h1 class="text-base font-bold text-slate-900">
                    Lokasi Aktif
                  </h1>
                </div>
              </div>
            </div>
            
            <div class="mt-3">
              <p class="text-xs text-slate-500">
                Pantau lokasi yang sedang aktif dan lihat tim yang sedang membuka.
              </p>
              <button
                @click="refreshPageData"
                class="mt-2 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="isLoading"
              >
                <i class="fa-solid fa-rotate-right" :class="{'fa-spin': isLoading}"></i>
                <span>{{ isLoading ? "Memuat..." : "Refresh" }}</span>
              </button>
            </div>
          </div>
        </div>
      </header>

      <main class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        <!-- Stats Cards -->
        <section class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4 mb-4 sm:mb-6">
          <article class="rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
            <p class="text-xs sm:text-sm font-medium text-slate-500">Total Lokasi Aktif</p>
            <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-slate-900">
              {{ formatNumber(openLocations.length) }}
            </p>
          </article>

          <article class="rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
            <p class="text-xs sm:text-sm font-medium text-slate-500">Total Team Aktif</p>
            <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-slate-900">
              {{ formatNumber(uniqueTeamsCount) }}
            </p>
          </article>

          <article class="rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
            <p class="text-xs sm:text-sm font-medium text-slate-500">Pencarian Lokasi</p>
            <div class="mt-3 flex gap-2">
              <div class="relative flex-1">
                <input
                  ref="lokasiInput"
                  :value="displayKodeLokasi"
                  @input="handleKodeLokasiInput"
                  @keyup.enter="searchLocation"
                  type="text"
                  placeholder="Contoh: 1.11.111"
                  class="w-full rounded-xl sm:rounded-2xl border border-slate-200 bg-slate-50 px-3 sm:px-4 py-2 sm:py-3 pr-8 sm:pr-12 text-sm font-mono outline-none transition focus:border-emerald-400 focus:bg-white"
                />
                <button
                  v-if="displayKodeLokasi"
                  @click="clearLokasiInput"
                  type="button"
                  class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 text-slate-400 transition hover:scale-110 hover:text-slate-600"
                >
                  <i class="fa-solid fa-circle-xmark text-base sm:text-xl"></i>
                </button>
              </div>
              <button
                @click="searchLocation"
                class="inline-flex items-center gap-2 rounded-xl sm:rounded-2xl bg-emerald-500 px-4 sm:px-6 py-2 sm:py-3 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="isLoading"
              >
                <i class="fa-solid fa-magnifying-glass"></i>
                <span class="hidden sm:inline">Cari</span>
              </button>
            </div>
          </article>

          <article class="rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
            <p class="text-xs sm:text-sm font-medium text-slate-500">Rata-rata Progress</p>
            <p class="mt-2 sm:mt-3 text-2xl sm:text-3xl font-bold text-slate-900">
              {{ averageProgressLabel }}
            </p>
          </article>
        </section>

        <section class="mb-4 sm:mb-6 rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
          <div class="flex flex-col gap-4">
            <div class="overflow-x-auto pb-2 -mx-1 px-1">
              <div class="flex gap-2 min-w-max">
                <button
                  v-for="option in filterOptions"
                  :key="option.value"
                  @click="setFilter(option.value)"
                  class="rounded-full px-4 py-2 text-xs sm:text-sm font-semibold transition whitespace-nowrap"
                  :class="
                    filters.filter === option.value
                      ? 'bg-emerald-500 text-white shadow-md shadow-emerald-200'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                  "
                >
                  {{ option.label }}
                </button>
              </div>
            </div>

            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
              <div v-if="filters.filter === 'custom'">
                <label class="mb-2 block text-xs sm:text-sm font-semibold text-slate-700">
                  Tanggal mulai
                </label>
                <input
                  v-model="filters.start_date"
                  type="date"
                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-400 focus:bg-white"
                />
              </div>

              <div v-if="filters.filter === 'custom'">
                <label class="mb-2 block text-xs sm:text-sm font-semibold text-slate-700">
                  Tanggal akhir
                </label>
                <input
                  v-model="filters.end_date"
                  type="date"
                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-400 focus:bg-white"
                />
              </div>

              <div>
                <label class="mb-2 block text-xs sm:text-sm font-semibold text-slate-700">
                  Tahun Visualisasi Bulanan
                </label>
                <input
                  v-model.number="filters.selected_year"
                  type="number"
                  min="2020"
                  max="2100"
                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-400 focus:bg-white"
                />
              </div>
            </div>
          </div>
        </section>

        <!-- Chart Section -->
        <section
          v-if="isVisualizationLoading"
          class="mb-4 sm:mb-6 rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 text-center shadow-sm"
        >
          <i class="fa-solid fa-spinner fa-spin text-2xl sm:text-3xl text-emerald-500"></i>
          <p class="mt-3 text-sm font-semibold text-slate-700">
            Memuat visualisasi progress...
          </p>
        </section>

        <section
          v-else-if="progressLocations.length > 0"
          class="mb-4 sm:mb-6 rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm"
        >
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
              <h2 class="text-base sm:text-lg font-bold text-slate-900">Visualisasi Harian</h2>
              <p class="text-xs text-slate-500">
                Pilih tampilan bar chart atau diagram untuk melihat progres harian.
              </p>
            </div>
            <div class="flex gap-2">
              <button
                @click="dailyViewMode = 'bar'"
                class="rounded-full px-4 py-2 text-xs font-semibold transition"
                :class="
                  dailyViewMode === 'bar'
                    ? 'bg-emerald-500 text-white'
                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                "
              >
                Bar
              </button>
              <button
                @click="dailyViewMode = 'diagram'"
                class="rounded-full px-4 py-2 text-xs font-semibold transition"
                :class="
                  dailyViewMode === 'diagram'
                    ? 'bg-emerald-500 text-white'
                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                "
              >
                Diagram
              </button>
            </div>
          </div>
          <div v-if="dailyViewMode === 'bar'" class="h-64 sm:h-80">
            <canvas ref="dailyChartCanvas"></canvas>
          </div>
          <div
            v-else
            class="rounded-2xl border border-slate-100 bg-slate-50 p-4"
          >
            <h3 class="text-sm font-semibold text-slate-900">Diagram Harian</h3>
            <p class="mt-1 text-xs text-slate-500">
              Proporsi total PLU yang sudah dihitung dibanding sisa barang.
            </p>
            <div class="mt-4 h-56 sm:h-72">
              <canvas ref="dailyDiagramCanvas"></canvas>
            </div>
          </div>
        </section>

        <section
          v-else
          class="mb-4 sm:mb-6 rounded-2xl sm:rounded-3xl border border-dashed border-slate-200 bg-white p-6 sm:p-8 text-center shadow-sm"
        >
          <i class="fa-solid fa-chart-column text-3xl text-slate-300"></i>
          <p class="mt-3 text-sm font-semibold text-slate-700">
            Belum ada progress harian
          </p>
          <p class="mt-1 text-xs sm:text-sm text-slate-500">
            Visualisasi akan muncul otomatis saat penghitung mulai mencatat barang hari ini.
          </p>
        </section>

        <section
          v-if="selectedLocationCode"
          class="mb-4 sm:mb-6 rounded-2xl sm:rounded-3xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm"
        >
          <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <h2 class="text-base sm:text-lg font-bold text-slate-900">
                Visualisasi Bulanan {{ selectedLocationCode }}
              </h2>
              <p class="text-xs text-slate-500">
                Pilih tampilan bar chart atau diagram untuk melihat ringkasan bulanan.
              </p>
            </div>
            <div class="flex items-center gap-2">
              <div class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                {{ filters.selected_year }}
              </div>
              <button
                @click="monthlyViewMode = 'bar'"
                class="rounded-full px-4 py-2 text-xs font-semibold transition"
                :class="
                  monthlyViewMode === 'bar'
                    ? 'bg-emerald-500 text-white'
                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                "
              >
                Bar
              </button>
              <button
                @click="monthlyViewMode = 'diagram'"
                class="rounded-full px-4 py-2 text-xs font-semibold transition"
                :class="
                  monthlyViewMode === 'diagram'
                    ? 'bg-emerald-500 text-white'
                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                "
              >
                Diagram
              </button>
            </div>
          </div>

          <div v-if="monthlyViewMode === 'bar'" class="mt-4 h-72 sm:h-80">
            <canvas ref="monthlyChartCanvas"></canvas>
          </div>

          <div
            v-else
            class="mt-4 rounded-2xl border border-slate-100 bg-slate-50 p-4"
          >
            <h3 class="text-sm font-semibold text-slate-900">Diagram Bulanan</h3>
            <p class="mt-1 text-xs text-slate-500">
              Kontribusi jumlah PLU yang dihitung pada tiap bulan dalam tahun terpilih.
            </p>
            <div class="mt-4 h-64 sm:h-72">
              <canvas ref="monthlyDiagramCanvas"></canvas>
            </div>
          </div>

          <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
              <thead class="bg-slate-50 text-slate-500">
                <tr>
                  <th class="px-4 py-3 text-left font-semibold">Bulan</th>
                  <th class="px-4 py-3 text-left font-semibold">Rata-rata Progress</th>
                  <th class="px-4 py-3 text-left font-semibold">Jumlah PLU</th>
                  <th class="px-4 py-3 text-left font-semibold">Hari Ada Data</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 bg-white">
                <tr v-for="month in monthlySummary" :key="month.month_number">
                  <td class="px-4 py-3 font-semibold text-slate-900">{{ month.month_label }}</td>
                  <td class="px-4 py-3 text-slate-700">{{ formatPercent(month.average_progress_percent) }}</td>
                  <td class="px-4 py-3 text-slate-700">{{ formatNumber(month.counted_plu_total) }}</td>
                  <td class="px-4 py-3 text-slate-700">{{ formatNumber(month.days_with_data) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Messages -->
        <section v-if="errorMessage" class="mb-4 rounded-xl sm:rounded-2xl border border-red-200 bg-red-50 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-red-600">
          {{ errorMessage }}
        </section>

        <section v-if="successMessage" class="mb-4 rounded-xl sm:rounded-2xl border border-emerald-200 bg-emerald-50 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-emerald-700">
          {{ successMessage }}
        </section>

        <!-- Locations Table -->
        <section class="rounded-2xl sm:rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-200 px-4 sm:px-5 py-3 sm:py-4">
            <div>
              <h2 class="text-base sm:text-lg font-bold text-slate-900">Lokasi Sedang Dibuka</h2>
              <p class="text-xs text-slate-500 hidden sm:block">
                Data real-time dari backend team melalui backend admin.
              </p>
            </div>
            <div class="rounded-full bg-slate-100 px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm font-semibold text-slate-600 self-start sm:self-auto">
              {{ openLocations.length }} lokasi
            </div>
          </div>

          <div v-if="isOpenLocationsLoading" class="px-4 sm:px-5 py-12 sm:py-16 text-center text-xs sm:text-sm text-slate-500">
            <i class="fa-solid fa-spinner fa-spin text-2xl sm:text-3xl mb-3 text-emerald-500"></i>
            <p>Memuat lokasi aktif...</p>
          </div>

          <div v-else-if="openLocations.length === 0" class="px-4 sm:px-5 py-12 sm:py-16 text-center text-xs sm:text-sm text-slate-500">
            <i class="fa-solid fa-unlock-keyhole text-3xl sm:text-4xl mb-3 text-slate-300"></i>
            <p>Tidak ada lokasi yang sedang dibuka.</p>
          </div>

          <div v-else class="overflow-x-auto">
            <!-- Mobile Card View -->
            <div class="block lg:hidden divide-y divide-slate-100">
              <div v-for="location in openLocations" :key="location.item_id" class="p-4 space-y-3">
                <div class="flex justify-between items-start">
                  <div>
                    <div class="font-semibold text-slate-900 text-sm">
                      {{ location.kode_lokasi }}
                    </div>
                    <span class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 mt-1">
                      {{ location.status || "OPEN" }}
                    </span>
                  </div>
                  <button
                    @click="closeLocation(location)"
                    class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="closingLocationCode === location.kode_lokasi"
                  >
                    <i class="fa-solid fa-xmark"></i>
                    <span>{{ closingLocationCode === location.kode_lokasi ? "..." : "Tutup" }}</span>
                  </button>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs">
                  <div>
                    <span class="text-slate-500">Team:</span>
                    <div class="font-semibold text-slate-900">
                      {{ location.team?.kode_team || location.team?.no_team || "-" }}
                    </div>
                  </div>
                  <div>
                    <span class="text-slate-500">Team ID:</span>
                    <div class="text-slate-600">{{ location.team?.stock_opname_team_id || "-" }}</div>
                  </div>
                  <div class="col-span-2">
                    <span class="text-slate-500">Penghitung:</span>
                    <div class="text-slate-800">1. {{ location.team?.penghitung_1 || "-" }}</div>
                    <div class="text-slate-800">2. {{ location.team?.penghitung_2 || "-" }}</div>
                  </div>
                  <div>
                    <span class="text-slate-500">Progress:</span>
                    <div class="font-semibold text-emerald-700">
                      {{ formatPercent(location.progress?.progress_percent) }}
                    </div>
                  </div>
                  <div>
                    <span class="text-slate-500">Rasio:</span>
                    <div class="font-semibold">
                      {{ formatNumber(location.progress?.counted_items) }}/{{ formatNumber(location.progress?.total_items) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Desktop Table View -->
            <table class="hidden lg:table min-w-full divide-y divide-slate-200 text-sm">
              <thead class="bg-slate-50 text-slate-500">
                <tr>
                  <th class="px-5 py-3 text-left font-semibold">Lokasi</th>
                  <th class="px-5 py-3 text-left font-semibold">Tim</th>
                  <th class="px-5 py-3 text-left font-semibold">Penghitung</th>
                  <th class="px-5 py-3 text-left font-semibold">Dibuka</th>
                  <th class="px-5 py-3 text-left font-semibold">Progress</th>
                  <th class="px-5 py-3 text-left font-semibold">Status</th>
                  <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 bg-white">
                <tr v-for="location in openLocations" :key="location.item_id">
                  <td class="px-5 py-4 font-semibold text-slate-900">
                    {{ location.kode_lokasi }}
                  </td>
                  <td class="px-5 py-4">
                    <div class="font-semibold text-slate-900">
                      {{ location.team?.kode_team || location.team?.no_team || "-" }}
                    </div>
                    <div class="text-xs text-slate-500">
                      Team ID: {{ location.team?.stock_opname_team_id || "-" }}
                    </div>
                  </td>
                  <td class="px-5 py-4">
                    <div>1. {{ location.team?.penghitung_1 || "-" }}</div>
                    <div class="mt-1 text-slate-600">
                      2. {{ location.team?.penghitung_2 || "-" }}
                    </div>
                   </td>
                  <td class="px-5 py-4 text-slate-600">
                    {{ formatDateTime(location.opened_at) }}
                   </td>
                  <td class="px-5 py-4">
                    <div class="font-semibold text-emerald-700">
                      {{ formatPercent(location.progress?.progress_percent) }}
                    </div>
                    <div class="text-xs text-slate-500">
                      {{ formatNumber(location.progress?.counted_items) }}/{{ formatNumber(location.progress?.total_items) }}
                    </div>
                   </td>
                  <td class="px-5 py-4">
                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                      {{ location.status || "OPEN" }}
                    </span>
                   </td>
                  <td class="px-5 py-4">
                    <button
                      @click="closeLocation(location)"
                      class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-60"
                      :disabled="closingLocationCode === location.kode_lokasi"
                    >
                      <i class="fa-solid fa-xmark"></i>
                      <span>{{ closingLocationCode === location.kode_lokasi ? "Closing..." : "Close Lokasi" }}</span>
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
import {
  computed,
  nextTick,
  onBeforeUnmount,
  onMounted,
  reactive,
  ref,
  watch,
} from "vue";
import axios from "axios";
import { RouterLink, useRouter } from "vue-router";
import { clearAuthSession } from "../utils/auth";
import Chart from "chart.js/auto";

const router = useRouter();
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
  { value: "custom", label: "Tanggal Tertentu" },
  { value: "all", label: "Semua" },
];

// Sidebar state
const isSidebarOpen = ref(false);

// Filter untuk pencarian
const filters = reactive({
  kode_lokasi_raw: "",
  filter: "today",
  start_date: todayValue,
  end_date: todayValue,
  selected_year: today.getFullYear(),
});

const openLocations = ref([]);
const progressLocations = ref([]);
const monthlySummary = ref([]);
const isOpenLocationsLoading = ref(false);
const isVisualizationLoading = ref(false);
const closingLocationCode = ref("");
const errorMessage = ref("");
const successMessage = ref("");
const lokasiInput = ref(null);
const showLogoutModal = ref(false);
const dailyChartCanvas = ref(null);
const monthlyChartCanvas = ref(null);
const dailyDiagramCanvas = ref(null);
const monthlyDiagramCanvas = ref(null);
let dailyChartInstance = null;
let monthlyChartInstance = null;
let dailyDiagramInstance = null;
let monthlyDiagramInstance = null;
let openLocationsPollingTimer = null;
const dailyViewMode = ref("bar");
const monthlyViewMode = ref("bar");
const isLoading = computed(
  () => isOpenLocationsLoading.value || isVisualizationLoading.value,
);

// Sidebar functions
function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
}

function closeSidebar() {
  isSidebarOpen.value = false;
}

// Format kode lokasi
function formatKodeLokasi(value) {
  if (!value) return "";
  const digits = value.replace(/\D/g, "");
  if (digits.length === 0) return "";
  
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
}

const displayKodeLokasi = computed(() => {
  return formatKodeLokasi(filters.kode_lokasi_raw);
});

function handleKodeLokasiInput(event) {
  const inputValue = event.target.value;
  const digits = inputValue.replace(/\D/g, "");
  const limitedDigits = digits.substring(0, 6);
  filters.kode_lokasi_raw = limitedDigits;
  
  const formatted = formatKodeLokasi(limitedDigits);
  if (event.target.value !== formatted) {
    event.target.value = formatted;
  }
}

function clearLokasiInput() {
  filters.kode_lokasi_raw = "";
  if (lokasiInput.value) {
    lokasiInput.value.value = "";
  }
  refreshPageData();
}

function searchLocation() {
  refreshPageData();
}

const uniqueTeamsCount = computed(() => {
  return new Set(
    openLocations.value
      .map((location) => location.team?.stock_opname_team_id || location.team?.kode_team)
      .filter(Boolean),
  ).size;
});

const averageProgressLabel = computed(() => {
  if (progressLocations.value.length === 0) {
    return "0%";
  }

  const totalProgress = progressLocations.value.reduce(
    (sum, location) => sum + Number(location.progress_percent || 0),
    0,
  );

  return `${Math.round(totalProgress / progressLocations.value.length)}%`;
});

const selectedLocationCode = computed(() => displayKodeLokasi.value.trim());

const dailyChartData = computed(() => {
  return {
    labels: progressLocations.value.map((location) => {
      const parts = [location.kode_lokasi || "-"];

      if (location.draft_date) {
        parts.push(formatDate(location.draft_date));
      }

      return parts.join(" • ");
    }),
    pluData: progressLocations.value.map((location) =>
      Number(location.counted_items_today || 0)
    ),
    progressData: progressLocations.value.map((location) =>
      Number(location.progress_percent || 0)
    ),
  };
});

const monthlyChartData = computed(() => {
  return {
    labels: monthlySummary.value.map((item) => item.month_label || "-"),
    pluData: monthlySummary.value.map((item) =>
      Number(item.counted_plu_total || 0)
    ),
    progressData: monthlySummary.value.map((item) =>
      Number(item.average_progress_percent || 0)
    ),
  };
});

const dailyDiagramData = computed(() => {
  const totalCounted = progressLocations.value.reduce(
    (sum, location) => sum + Number(location.counted_items_today || 0),
    0,
  );
  const totalItems = progressLocations.value.reduce(
    (sum, location) => sum + Number(location.total_items || 0),
    0,
  );

  return {
    labels: ["Sudah Dihitung", "Sisa Barang"],
    data: [totalCounted, Math.max(totalItems - totalCounted, 0)],
  };
});

const monthlyDiagramData = computed(() => ({
  labels: monthlySummary.value.map((item) => item.month_label || "-"),
  data: monthlySummary.value.map((item) => Number(item.counted_plu_total || 0)),
}));

async function renderDailyChart() {
  await nextTick();

  if (dailyChartInstance) {
    dailyChartInstance.destroy();
    dailyChartInstance = null;
  }

  if (!dailyChartCanvas.value) return;

  const data = dailyChartData.value;
  if (data.labels.length === 0) {
    return;
  }

  const ctx = dailyChartCanvas.value.getContext("2d");

  dailyChartInstance = new Chart(ctx, {
    type: "bar",
    data: {
      labels: data.labels,
      datasets: [
        {
          type: "bar",
          label: "Kode PLU Dihitung",
          data: data.pluData,
          backgroundColor: "#10b981",
          borderColor: "#059669",
          borderWidth: 1,
          yAxisID: "y",
        },
        {
          type: "line",
          label: "Progress (%)",
          data: data.progressData,
          borderColor: "#f97316",
          backgroundColor: "#f97316",
          borderWidth: 2,
          tension: 0.35,
          yAxisID: "y1",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "top",
        },
        tooltip: {
          callbacks: {
            label(context) {
              if (context.dataset.label === "Kode PLU Dihitung") {
                return `${context.dataset.label}: ${formatNumber(context.parsed.y)} PLU`;
              }

              return `${context.dataset.label}: ${formatPercent(context.parsed.y)}`;
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Jumlah Kode PLU Dihitung",
          },
        },
        y1: {
          beginAtZero: true,
          position: "right",
          grid: {
            drawOnChartArea: false,
          },
          title: {
            display: true,
            text: "Progress (%)",
          },
        },
        x: {
          ticks: {
            maxRotation: 45,
            minRotation: 20,
          },
        },
      },
    },
  });
}

async function renderMonthlyChart() {
  await nextTick();

  if (monthlyChartInstance) {
    monthlyChartInstance.destroy();
    monthlyChartInstance = null;
  }

  if (!monthlyChartCanvas.value || !selectedLocationCode.value) return;

  const data = monthlyChartData.value;
  if (data.labels.length === 0) {
    return;
  }

  const ctx = monthlyChartCanvas.value.getContext("2d");

  monthlyChartInstance = new Chart(ctx, {
    type: "bar",
    data: {
      labels: data.labels,
      datasets: [
        {
          type: "bar",
          label: "Jumlah PLU per Bulan",
          data: data.pluData,
          backgroundColor: "#3b82f6",
          borderColor: "#1d4ed8",
          borderWidth: 1,
          yAxisID: "y",
        },
        {
          type: "line",
          label: "Rata-rata Progress (%)",
          data: data.progressData,
          borderColor: "#f97316",
          backgroundColor: "#f97316",
          borderWidth: 2,
          tension: 0.35,
          yAxisID: "y1",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "top",
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Jumlah PLU",
          },
        },
        y1: {
          beginAtZero: true,
          position: "right",
          grid: {
            drawOnChartArea: false,
          },
          title: {
            display: true,
            text: "Rata-rata Progress (%)",
          },
        },
      },
    },
  });
}

async function renderDailyDiagram() {
  await nextTick();

  if (dailyDiagramInstance) {
    dailyDiagramInstance.destroy();
    dailyDiagramInstance = null;
  }

  if (!dailyDiagramCanvas.value) return;

  const data = dailyDiagramData.value;
  if (data.data.every((value) => value === 0)) {
    return;
  }

  dailyDiagramInstance = new Chart(dailyDiagramCanvas.value.getContext("2d"), {
    type: "doughnut",
    data: {
      labels: data.labels,
      datasets: [
        {
          data: data.data,
          backgroundColor: ["#10b981", "#e2e8f0"],
          borderColor: ["#059669", "#cbd5e1"],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "bottom",
        },
        tooltip: {
          callbacks: {
            label(context) {
              return `${context.label}: ${formatNumber(context.parsed)} PLU`;
            },
          },
        },
      },
    },
  });
}

async function renderMonthlyDiagram() {
  await nextTick();

  if (monthlyDiagramInstance) {
    monthlyDiagramInstance.destroy();
    monthlyDiagramInstance = null;
  }

  if (!monthlyDiagramCanvas.value || !selectedLocationCode.value) return;

  const data = monthlyDiagramData.value;
  if (data.data.every((value) => value === 0)) {
    return;
  }

  monthlyDiagramInstance = new Chart(
    monthlyDiagramCanvas.value.getContext("2d"),
    {
      type: "doughnut",
      data: {
        labels: data.labels,
        datasets: [
          {
            data: data.data,
            backgroundColor: [
              "#0f766e",
              "#14b8a6",
              "#22c55e",
              "#84cc16",
              "#eab308",
              "#f59e0b",
              "#f97316",
              "#ef4444",
              "#ec4899",
              "#8b5cf6",
              "#6366f1",
              "#3b82f6",
            ],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
          },
          tooltip: {
            callbacks: {
              label(context) {
                return `${context.label}: ${formatNumber(context.parsed)} PLU`;
              },
            },
          },
        },
      },
    },
  );
}

watch([dailyChartData], () => {
  renderDailyChart();
});

watch([monthlyChartData, selectedLocationCode], () => {
  renderMonthlyChart();
});

watch([dailyDiagramData], () => {
  renderDailyDiagram();
});

watch([monthlyDiagramData, selectedLocationCode], () => {
  renderMonthlyDiagram();
});

let filterRefreshTimeout = null;
watch(
  [() => filters.start_date, () => filters.end_date, () => filters.selected_year],
  () => {
    clearTimeout(filterRefreshTimeout);
    filterRefreshTimeout = setTimeout(() => {
      refreshVisualizationData();
    }, 400);
  },
);

function buildQueryParams() {
  const params = {
    filter: filters.filter,
    year: filters.selected_year,
  };

  if (filters.kode_lokasi_raw && filters.kode_lokasi_raw.trim()) {
    params.kode_lokasi = formatKodeLokasi(filters.kode_lokasi_raw);
  }

  if (filters.filter === "custom") {
    params.start_date = filters.start_date || todayValue;
    params.end_date = filters.end_date || filters.start_date || todayValue;
  }

  return params;
}

function buildOpenLocationParams() {
  const params = {};

  if (filters.kode_lokasi_raw && filters.kode_lokasi_raw.trim()) {
    params.kode_lokasi = formatKodeLokasi(filters.kode_lokasi_raw);
  }

  return params;
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

  refreshVisualizationData();
}

async function loadOpenLocations({ silent = false } = {}) {
  if (!silent) {
    isOpenLocationsLoading.value = true;
    errorMessage.value = "";
    successMessage.value = "";
  }

  try {
    const params = buildOpenLocationParams();

    const response = await axios.get(`${adminBackendUrl}/api/admin/open-locations`, {
      params: params,
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token") || ""}`,
      },
    });

    openLocations.value = response.data?.data?.locations || [];
    
    if (!silent && params.kode_lokasi && openLocations.value.length === 0) {
      successMessage.value = `Tidak ditemukan lokasi dengan kode ${params.kode_lokasi}`;
    }
  } catch (error) {
    console.error("Gagal mengambil lokasi aktif:", error);
    if (!silent) {
      errorMessage.value =
        error.response?.data?.message || "Gagal mengambil lokasi aktif";
    }
  } finally {
    if (!silent) {
      isOpenLocationsLoading.value = false;
    }
  }
}

async function loadLocationProgress({ silent = false } = {}) {
  if (!silent) {
    isVisualizationLoading.value = true;
    errorMessage.value = "";
    successMessage.value = "";
  }

  try {
    const response = await axios.get(`${adminBackendUrl}/api/admin/location-progress`, {
      params: buildQueryParams(),
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token") || ""}`,
      },
    });

    progressLocations.value = response.data?.data?.locations || [];
    monthlySummary.value = response.data?.data?.monthly_summary || [];
    await Promise.all([
      renderDailyChart(),
      renderMonthlyChart(),
      renderDailyDiagram(),
      renderMonthlyDiagram(),
    ]);
  } catch (error) {
    console.error("Gagal mengambil progress harian:", error);
    if (!silent) {
      errorMessage.value =
        error.response?.data?.message || "Gagal mengambil progress harian";
    }
  } finally {
    if (!silent) {
      isVisualizationLoading.value = false;
    }
  }
}

async function refreshPageData({ silent = false } = {}) {
  if (!silent) {
    isOpenLocationsLoading.value = true;
    isVisualizationLoading.value = true;
    errorMessage.value = "";
    successMessage.value = "";
  }

  try {
    await Promise.all([
      loadOpenLocations({ silent: true }),
      loadLocationProgress({ silent: true }),
    ]);

    if (!silent) {
      const kodeLokasi = buildOpenLocationParams().kode_lokasi;

      if (
        kodeLokasi &&
        openLocations.value.length === 0 &&
        progressLocations.value.length === 0
      ) {
        successMessage.value = `Tidak ditemukan lokasi dengan kode ${kodeLokasi}`;
      }
    }
  } finally {
    if (!silent) {
      isOpenLocationsLoading.value = false;
      isVisualizationLoading.value = false;
    }
  }
}

async function refreshVisualizationData({ silent = false } = {}) {
  await loadLocationProgress({ silent });
}

async function closeLocation(location) {
  if (!location?.kode_lokasi) {
    return;
  }

  const shouldClose = window.confirm(
    `Tutup lokasi ${location.kode_lokasi} yang sedang dibuka oleh team ${location.team?.kode_team || location.team?.no_team || "-" }?`,
  );

  if (!shouldClose) {
    return;
  }

  closingLocationCode.value = location.kode_lokasi;
  errorMessage.value = "";
  successMessage.value = "";

  try {
    const response = await axios.post(
      `${adminBackendUrl}/api/admin/logout/location`,
      {
        kode_lokasi: location.kode_lokasi,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token") || ""}`,
        },
      },
    );

    if (response.data?.success === false) {
      throw new Error(response.data?.message || "Gagal close lokasi");
    }

    successMessage.value = response.data?.message || "Lokasi berhasil ditutup";
    await refreshPageData({ silent: true });
  } catch (error) {
    console.error("Gagal close lokasi:", error);
    errorMessage.value =
      error.response?.data?.message || error.message || "Gagal close lokasi";
  } finally {
    closingLocationCode.value = "";
  }
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
    month: "short",
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
  await refreshPageData();

  openLocationsPollingTimer = window.setInterval(() => {
    refreshPageData({ silent: true });
  }, 10000);
});

onBeforeUnmount(() => {
  if (filterRefreshTimeout) {
    clearTimeout(filterRefreshTimeout);
    filterRefreshTimeout = null;
  }

  if (openLocationsPollingTimer) {
    clearInterval(openLocationsPollingTimer);
    openLocationsPollingTimer = null;
  }

  if (dailyChartInstance) {
    dailyChartInstance.destroy();
    dailyChartInstance = null;
  }

  if (monthlyChartInstance) {
    monthlyChartInstance.destroy();
    monthlyChartInstance = null;
  }

  if (dailyDiagramInstance) {
    dailyDiagramInstance.destroy();
    dailyDiagramInstance = null;
  }

  if (monthlyDiagramInstance) {
    monthlyDiagramInstance.destroy();
    monthlyDiagramInstance = null;
  }
});
</script>

<style scoped>
/* Styling untuk input lokasi */
input[type="text"] {
  font-family: monospace;
  letter-spacing: 0.5px;
}

/* Animasi untuk modal */
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

/* Custom scrollbar */
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
