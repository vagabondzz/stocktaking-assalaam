<template>
  <div
    class="min-h-screen flex flex-col items-center justify-center bg-[#0B1220] py-3 px-3 sm:py-6 sm:px-4 lg:py-10 lg:px-6"
  >
    <!-- Background Effect -->
    <div id="vanta-bg" class="fixed inset-0 z-0"></div>

    <!-- Header Section - Lebih compact di mobile -->
    <div class="relative z-10 text-center mb-4 sm:mb-6 md:mb-8 lg:mb-10">
      <div class="space-y-1 sm:space-y-2">
        <h1
          class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-white font-poppins tracking-wider leading-tight"
        >
          Stock
        </h1>
        <div class="relative">
          <h1
            class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 font-poppins tracking-wider leading-tight"
          >
            Taking.
          </h1>
          <div
            class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-12 sm:w-16 md:w-20 lg:w-24 xl:w-32 h-1 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full"
          ></div>
        </div>
      </div>
    </div>

    <!-- Main Card -->
    <div
      class="relative z-10 w-full max-w-4xl xl:max-w-6xl mx-auto px-3 sm:px-4"
    >
      <div
        class="neo-glassmorphism rounded-lg sm:rounded-xl lg:rounded-2xl overflow-hidden shadow-lg sm:shadow-xl transform transition-all duration-300 border border-white/10"
      >
        <!-- Header Card dengan layout yang lebih compact -->
        <div
          class="bg-gradient-to-br from-blue-600/15 via-purple-600/10 to-indigo-600/15 backdrop-blur-md border-b border-white/20 px-3 sm:px-4 md:px-5 lg:px-6 py-2 sm:py-3 lg:py-4"
        >
          <!-- Layout utama - lebih sederhana untuk mobile -->
          <div class="flex flex-col gap-2 sm:gap-3">
            <!-- Baris 1: Lokasi -->
            <div
              class="flex items-center gap-2 bg-gradient-to-r from-blue-500/25 to-blue-600/20 text-white px-3 sm:px-4 py-2 rounded-xl border border-blue-400/40 backdrop-blur-sm shadow transition-all duration-300"
            >
              <div class="flex items-center gap-2 flex-1 min-w-0">
                <div
                  class="p-1.5 sm:p-2 bg-blue-500/30 rounded-lg flex-shrink-0"
                >
                  <svg
                    class="w-3 h-3 sm:w-4 sm:h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                  </svg>
                </div>
                <div class="min-w-0 flex-1">
                  <p
                    class="text-[10px] xs:text-xs text-blue-200/80 font-poppins mb-0.5"
                  >
                    Lokasi
                  </p>
                  <p
                    class="font-poppins text-xs sm:text-sm truncate font-medium"
                  >
                    {{ lokasi }}
                  </p>
                </div>
              </div>
              <button
                @click="openChangeLocationModal"
                :disabled="isLoading"
                class="ml-1 p-1.5 sm:p-2 bg-blue-600/50 hover:bg-blue-500/70 rounded-lg transition-all duration-200 border border-blue-400/30 flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed"
                title="Pindah Lokasi"
              >
                <div v-if="isLoading" class="flex items-center justify-center">
                  <div class="loading-spinner-small"></div>
                </div>
                <svg
                  v-else
                  class="w-2.5 h-2.5 sm:w-3 sm:h-3"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                  />
                </svg>
              </button>
            </div>

            <!-- Baris 2: Penghitung dalam satu baris horizontal di mobile -->
            <div class="grid grid-cols-2 gap-2">
              <!-- Penghitung 1 -->
              <div
                class="flex items-center gap-2 bg-gradient-to-r from-purple-500/25 to-purple-600/20 text-white px-3 py-2 rounded-xl border border-purple-400/40 backdrop-blur-sm shadow transition-all duration-300"
              >
                <div class="p-1.5 bg-purple-500/30 rounded-lg flex-shrink-0">
                  <svg
                    class="w-3 h-3 sm:w-4 sm:h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                  </svg>
                </div>
                <div class="min-w-0 flex-1">
                  <p
                    class="text-[10px] xs:text-xs text-purple-200/80 font-poppins mb-0.5"
                  >
                    Penghitung 1
                  </p>
                  <p class="font-poppins text-xs sm:text-sm truncate">
                    {{ penghitung1 }}
                  </p>
                </div>
              </div>

              <!-- Penghitung 2 -->
              <div
                class="flex items-center gap-2 bg-gradient-to-r from-indigo-500/25 to-indigo-600/20 text-white px-3 py-2 rounded-xl border border-indigo-400/40 backdrop-blur-sm shadow transition-all duration-300"
              >
                <div class="p-1.5 bg-indigo-500/30 rounded-lg flex-shrink-0">
                  <svg
                    class="w-3 h-3 sm:w-4 sm:h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                  </svg>
                </div>
                <div class="min-w-0 flex-1">
                  <p
                    class="text-[10px] xs:text-xs text-indigo-200/80 font-poppins mb-0.5"
                  >
                    Penghitung 2
                  </p>
                  <p class="font-poppins text-xs sm:text-sm truncate">
                    {{ penghitung2 }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Baris 3: Tombol Selesai Sesi full width di mobile -->
            <button
              @click="logout"
              :disabled="isLoading"
              class="w-full px-4 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-semibold rounded-xl shadow hover:shadow-md transform hover:scale-[1.01] transition-all duration-300 text-xs sm:text-sm lg:text-base text-center border border-white/20 hover:border-white/30 flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed mt-1"
            >
              <div v-if="isLoading" class="flex items-center justify-center">
                <div class="loading-spinner-small"></div>
              </div>
              <svg
                v-else
                class="w-3 h-3 sm:w-4 sm:h-4 transition-transform duration-300 group-hover:scale-110 flex-shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                />
              </svg>
              <span class="whitespace-nowrap font-poppins font-medium">{{
                isLoading ? "Memproses..." : "Selesai Sesi"
              }}</span>
            </button>
          </div>
        </div>

        <!-- Search Section - Lebih compact -->
        <div class="p-3 sm:p-4 lg:p-6 border-b border-white/10">
          <div class="relative group">
            <!-- Main input container -->
            <div
              class="relative flex items-center bg-gray-800/90 backdrop-blur-sm rounded-lg border border-gray-600/50 focus-within:ring-2 focus-within:ring-blue-500/70 focus-within:border-blue-400/50 transition-all duration-300 shadow"
            >
              <!-- Icon kacamata pembesar -->
              <div class="pl-3 sm:pl-4 pr-2 text-blue-400">
                <svg
                  class="w-4 h-4 sm:w-5 sm:h-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </div>

              <!-- Input field -->
              <input
                v-model="barcodeInput"
                type="text"
                placeholder="Scan atau ketik kode barcode/PLU"
                class="flex-1 bg-transparent text-white px-2 sm:px-3 py-2.5 sm:py-3 focus:outline-none placeholder-gray-400 text-sm sm:text-base min-w-0 font-poppins"
                @input="handleInputChange"
                @keyup.enter="handleSubmit"
                @keyup.esc="clearInput"
                ref="barcodeInputRef"
                :disabled="isLoading"
              />

              <!-- Scan button -->
              <div class="flex items-center pr-2 sm:pr-3 flex-shrink-0">
                <button
                  @click="toggleScanner"
                  :disabled="isLoading"
                  class="px-3 py-1.5 sm:px-4 sm:py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-medium rounded-lg shadow hover:shadow-md transform hover:scale-[1.02] transition-all duration-300 flex items-center gap-1 sm:gap-2 text-xs sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <div
                    v-if="isLoading"
                    class="flex items-center justify-center"
                  >
                    <div class="loading-spinner-small"></div>
                  </div>
                  <svg
                    v-else
                    class="w-3 h-3 sm:w-4 sm:h-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"
                    />
                  </svg>
                  <span class="hidden xs:inline">{{
                    isLoading ? "Memuat..." : "Scan"
                  }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Table Section - Lebih compact -->
        <div class="p-2 sm:p-3 md:p-4 lg:p-6">
          <div class="overflow-x-auto relative">
            <!-- Action Buttons - Layout lebih compact -->
            <div class="mb-3 flex flex-wrap gap-2 justify-between items-center">
              <button
                @click="fetchItemDetails"
                :disabled="loadingItemDetails || isLoading"
                class="px-3 py-1.5 sm:px-4 sm:py-2 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-500 hover:to-blue-500 text-white font-medium rounded-lg shadow hover:shadow-md transform hover:scale-[1.02] transition-all duration-300 flex items-center gap-2 text-xs sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <div
                  v-if="loadingItemDetails || isLoading"
                  class="flex items-center justify-center"
                >
                  <div class="loading-spinner-small"></div>
                </div>
                <svg
                  v-else
                  class="w-3 h-3 sm:w-4 sm:h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                  />
                </svg>
                {{
                  loadingItemDetails || isLoading ? "Memuat..." : "Refresh Data"
                }}
              </button>

              <div class="flex items-center gap-2">
                <div class="text-xs text-gray-400">
                  <span v-if="sortedItems.length > 0">
                    {{ sortedItems.length }} item
                  </span>
                  <span v-else>Belum ada data</span>
                </div>
                <div
                  class="text-xs text-gray-500 hidden sm:block"
                  v-if="lastUpdated"
                >
                  {{ lastUpdated }}
                </div>
              </div>
            </div>

            <!-- Tabel dengan scroll horizontal yang lebih baik di mobile -->
            <div class="overflow-x-auto">
              <div class="min-w-[540px] sm:min-w-0">
                <table
                  class="w-full text-left text-xs sm:text-sm text-white border-collapse"
                >
                  <thead
                    class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 border-b border-white/10"
                  >
                    <tr class="text-xs">
                      <th
                        class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap text-center"
                      >
                        SEQ
                      </th>
                      <th
                        class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap"
                      >
                        PLU
                      </th>
                      <th
                        class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap hidden sm:table-cell"
                      >
                        Barcode
                      </th>
                      <th
                        class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap"
                      >
                        Nama Barang
                      </th>
                      <th
                        class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap text-center"
                      >
                        UoM
                      </th>
                      <th
                        class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap text-center"
                      >
                        Qty
                      </th>
                      <!-- Note: Hanya tampil di desktop/tablet -->
                      <th
                        class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap text-center hidden md:table-cell"
                      >
                        Note
                      </th>
                      <th
                        class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap text-center"
                      >
                        Aksi
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-white/10">
                    <tr v-if="sortedItems.length === 0">
                      <td
                        colspan="8"
                        class="px-4 py-6 sm:py-8 text-center text-gray-400"
                      >
                        <div class="flex flex-col items-center gap-2">
                          <svg
                            class="w-6 h-6 sm:w-8 sm:h-8 text-gray-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                            />
                          </svg>
                          <span class="text-sm">Tidak ada data barang</span>
                          <button
                            @click="fetchItemDetails"
                            :disabled="loadingItemDetails || isLoading"
                            class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 text-xs mt-1 disabled:opacity-50 disabled:cursor-not-allowed"
                          >
                            <div
                              v-if="loadingItemDetails || isLoading"
                              class="flex items-center justify-center"
                            >
                              <div class="loading-spinner-small"></div>
                            </div>
                            {{
                              loadingItemDetails || isLoading
                                ? "Memuat..."
                                : "Muat Data"
                            }}
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr
                      v-for="(item, index) in sortedItems"
                      :key="item.ISITEAMITEMDETAIL_ID || `manual-${index}`"
                      :class="[
                        'hover:bg-white/5',
                        'transition-colors',
                        'duration-200',
                        'group',
                        {
                          'highlight-row': item.highlight,
                          'bg-yellow-500/5': item === editingItem,
                          'new-item-highlight': item.isNew,
                          'updated-item-highlight': item.isUpdated,
                        },
                      ]"
                    >
                      <!-- Kolom SEQ -->
                      <td
                        class="px-2 sm:px-3 py-2 font-poppins text-blue-300 whitespace-nowrap group-hover:text-blue-200 transition-colors text-center font-medium text-xs"
                      >
                        {{ index + 1 }}
                      </td>
                      <td
                        class="px-2 sm:px-3 py-2 font-poppins text-blue-300 whitespace-nowrap group-hover:text-blue-200 transition-colors text-xs"
                      >
                        {{ item.ISITEAMITEMDETAIL_BRG_CODE }}
                      </td>
                      <td
                        class="px-2 sm:px-3 py-2 font-poppins text-xs whitespace-nowrap truncate max-w-[80px] hidden sm:table-cell"
                      >
                        {{ item.ISITEAMITEMDETAIL_BRG_BARCODE || "-" }}
                      </td>
                      <td
                        class="px-2 sm:px-3 py-2 font-poppins whitespace-nowrap truncate max-w-[120px] sm:max-w-[150px] group-hover:text-gray-200 transition-colors text-xs"
                      >
                        {{ item.ISITEAMITEMDETAIL_BRG_NAME }}
                      </td>
                      <td class="px-2 sm:px-3 py-2 text-center">
                        <span
                          class="bg-gray-700/50 px-1.5 py-0.5 rounded text-xs font-poppins whitespace-nowrap inline-block min-w-[40px]"
                        >
                          {{ item.ISITEAMITEMDETAIL_BRG_UOM || "-" }}
                        </span>
                      </td>
                      <td class="px-2 sm:px-3 py-2 text-center">
                        <span
                          class="font-poppins text-white font-medium text-xs"
                        >
                          {{
                            formatQuantity(
                              item.ISITEAMITEMDETAIL_QTY,
                              item.IS_DECIMAL
                            )
                          }}
                        </span>
                      </td>
                      <!-- Note: Hanya tampil di desktop/tablet -->
                      <td
                        class="px-2 sm:px-3 py-2 text-center hidden md:table-cell"
                      >
                        <div
                          v-if="item.ISITEAMITEMDETAIL_DESC"
                          class="flex justify-center"
                        >
                          <span
                            class="bg-blue-500/20 font-poppins px-1.5 py-0.5 rounded text-xs max-w-[100px] truncate"
                          >
                            {{ item.ISITEAMITEMDETAIL_DESC }}
                          </span>
                        </div>
                        <span v-else class="text-gray-500 text-xs">-</span>
                      </td>
                      <td class="px-2 sm:px-3 py-2 text-center">
                        <div class="flex justify-center gap-1">
                          <button
                            @click="openArchiveModal(item)"
                            :disabled="isLoading"
                            class="p-1 bg-orange-600 hover:bg-orange-700 text-white rounded-lg shadow hover:shadow-md transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            title="Lihat Perubahan"
                          >
                            <div
                              v-if="isLoading"
                              class="flex items-center justify-center"
                            >
                              <div class="loading-spinner-small"></div>
                            </div>
                            <svg
                              v-else
                              class="w-3 h-3 sm:w-4 sm:h-4"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                              />
                            </svg>
                          </button>
                          <button
                            @click="handleEditItem(item)"
                            :disabled="isLoading"
                            class="p-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow hover:shadow-md transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            title="Edit Item"
                          >
                            <div
                              v-if="isLoading"
                              class="flex items-center justify-center"
                            >
                              <div class="loading-spinner-small"></div>
                            </div>
                            <svg
                              v-else
                              class="w-3 h-3 sm:w-4 sm:h-4"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                              />
                            </svg>
                          </button>
                          <button
                            @click="deleteSingleItem(item)"
                            :disabled="isLoading"
                            class="p-1 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow hover:shadow-md transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            title="Hapus Item"
                          >
                            <div
                              v-if="isLoading"
                              class="flex items-center justify-center"
                            >
                              <div class="loading-spinner-small"></div>
                            </div>
                            <svg
                              v-else
                              class="w-3 h-3 sm:w-4 sm:h-4"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24"
                            >
                              <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                              />
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Save Button -->
        <div class="p-3 sm:p-4 lg:p-6 border-t border-white/10">
          <div class="flex justify-center">
            <button
              @click="saveChanges"
              :disabled="isLoading"
              class="px-5 py-2.5 sm:px-6 sm:py-3 text-white font-semibold rounded-xl shadow hover:shadow-md transform transition-all duration-300 flex items-center gap-2 text-sm sm:text-base w-full justify-center group bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 hover:scale-[1.01] disabled:opacity-50 disabled:cursor-not-allowed max-w-sm mx-auto"
            >
              <div v-if="isLoading" class="flex items-center justify-center">
                <div class="loading-spinner-small"></div>
              </div>
              <span class="whitespace-nowrap font-poppins font-medium">{{
                isLoading ? "Menyimpan..." : "Save Changes"
              }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Archive Modal -->
    <div
      v-if="showArchiveModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 backdrop-blur-sm"
      @click.self="closeArchiveModal"
    >
      <div
        class="relative w-full max-w-4xl sm:max-w-5xl lg:max-w-6xl bg-gray-900/95 rounded-xl sm:rounded-2xl overflow-hidden shadow-xl sm:shadow-2xl border border-gray-700 neo-glassmorphism"
      >
        <div class="p-3 sm:p-4 lg:p-6 border-b border-gray-800">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-lg sm:text-xl font-semibold text-white">
                Data Log Per Barang
              </h3>
              <p class="text-gray-400 text-xs sm:text-sm mt-1">
                Riwayat perubahan quantity untuk barang ini
              </p>
            </div>
            <button
              @click="closeArchiveModal"
              class="text-gray-400 hover:text-white transition-colors duration-200 p-1.5 sm:p-2 rounded-lg hover:bg-gray-700/50"
            >
              <svg
                class="w-5 h-5 sm:w-6 sm:h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>

          <!-- Toggle untuk Local/Backend Data -->
          <div class="mt-3 flex items-center gap-2 sm:gap-4">
            <div class="flex items-center gap-2">
              <span class="text-xs sm:text-sm text-gray-300">Data dari:</span>
              <div class="flex bg-gray-800 rounded-lg p-1">
                <button
                  @click="archiveViewMode = 'local'"
                  :class="[
                    'px-2 py-1 text-xs font-medium rounded-md transition-colors',
                    archiveViewMode === 'local'
                      ? 'bg-blue-600 text-white'
                      : 'text-gray-400 hover:text-white',
                  ]"
                >
                  Local
                </button>
                <button
                  @click="loadBackendArchive"
                  :class="[
                    'px-2 py-1 text-xs font-medium rounded-md transition-colors',
                    archiveViewMode === 'backend'
                      ? 'bg-blue-600 text-white'
                      : 'text-gray-400 hover:text-white',
                  ]"
                >
                  Database
                </button>
              </div>
            </div>

            <!-- Indikator Data Source -->
            <div class="flex items-center gap-2">
              <div
                :class="[
                  'w-1.5 h-1.5 rounded-full',
                  archiveViewMode === 'local'
                    ? 'bg-yellow-500 animate-pulse'
                    : 'bg-green-500',
                ]"
              ></div>
              <span class="text-xs text-gray-400">
                {{
                  archiveViewMode === "local"
                    ? "Belum Disimpan"
                    : "Dari Database"
                }}
              </span>
            </div>
          </div>
        </div>

        <div
          class="p-3 sm:p-4 lg:p-6 max-h-[60vh] sm:max-h-[70vh] overflow-y-auto"
        >
          <!-- Loading State -->
          <div
            v-if="loadingArchive && archiveViewMode === 'backend'"
            class="flex justify-center items-center py-6"
          >
            <div class="loading-spinner"></div>
            <span class="ml-2 text-gray-400 text-sm">Memuat data...</span>
          </div>

          <!-- Info Data -->
          <div
            v-else
            class="mb-3 flex flex-wrap gap-3 justify-between items-center"
          >
            <div>
              <div class="text-xs sm:text-sm text-gray-400">
                <span v-if="archiveBackendData.length > 0">
                  {{ archiveBackendData.length }} log perubahan
                  <span
                    v-if="archiveViewMode === 'local'"
                    class="text-yellow-500 ml-1"
                  >
                    (Belum disimpan)
                  </span>
                  <span v-else class="text-green-500 ml-1"> (Tersimpan) </span>
                </span>
                <span v-else>Tidak ada data log</span>
              </div>
              <!-- Tampilkan statistik -->
              <div
                v-if="archiveViewMode === 'local'"
                class="text-xs text-gray-500 mt-1"
              >
                Data akan dikirim saat menekan "Save Changes"
              </div>
            </div>

            <div class="flex gap-2">
              <!-- Hanya tampilkan refresh untuk backend data -->
              <button
                v-if="archiveViewMode === 'backend'"
                @click="loadBackendArchive"
                :disabled="loadingArchive"
                class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1"
              >
                <svg
                  v-if="!loadingArchive"
                  class="w-3 h-3"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                  />
                </svg>
                {{ loadingArchive ? "Memuat..." : "Refresh" }}
              </button>
            </div>
          </div>

          <!-- Tabel Archive -->
          <div
            v-if="!loadingArchive || archiveViewMode === 'local'"
            class="overflow-hidden rounded-lg sm:rounded-xl border border-gray-700/50 bg-gray-800/30 backdrop-blur-sm"
          >
            <table
              class="w-full text-left text-xs sm:text-sm text-white border-collapse"
            >
              <thead
                :class="[
                  'border-b border-white/10',
                  archiveViewMode === 'local'
                    ? 'bg-gradient-to-r from-yellow-600/20 to-orange-600/20'
                    : 'bg-gradient-to-r from-orange-600/20 to-red-600/20',
                ]"
              >
                <tr>
                  <th
                    class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap"
                  >
                    Waktu
                  </th>
                  <th
                    class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap"
                  >
                    PLU
                  </th>
                  <th
                    class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap hidden sm:table-cell"
                  >
                    Barcode
                  </th>
                  <th
                    class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap"
                  >
                    Nama Barang
                  </th>
                  <th
                    class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap text-center"
                  >
                    Qty
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/10">
                <tr v-if="archiveBackendData.length === 0">
                  <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                    <div class="flex flex-col items-center gap-2">
                      <svg
                        class="w-6 h-6 sm:w-8 sm:h-8 text-gray-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                      </svg>
                      <span class="text-xs sm:text-sm">
                        {{
                          archiveViewMode === "local"
                            ? "Belum ada perubahan"
                            : "Tidak ada data log"
                        }}
                      </span>
                    </div>
                  </td>
                </tr>
                <tr
                  v-for="(log, index) in archiveBackendData"
                  :key="log.log_id || `local-${index}`"
                  class="hover:bg-white/5 transition-colors duration-200"
                  :class="{
                    'bg-red-500/10': log.delete_marker,
                    'bg-yellow-500/5': log.is_local,
                  }"
                >
                  <td
                    class="px-2 sm:px-3 py-2 font-poppins text-gray-300 whitespace-nowrap text-xs"
                  >
                    {{ formatArchiveDate(log.archived_at) }}
                  </td>
                  <td
                    class="px-2 sm:px-3 py-2 font-poppins text-blue-300 whitespace-nowrap text-xs"
                  >
                    {{ log.ISITEAMITEMDETAIL_BRG_CODE }}
                  </td>
                  <td
                    class="px-2 sm:px-3 py-2 font-poppins text-gray-300 whitespace-nowrap hidden sm:table-cell text-xs"
                  >
                    {{ log.ISITEAMITEMDETAIL_BRG_BARCODE || "-" }}
                  </td>
                  <td
                    class="px-2 sm:px-3 py-2 font-poppins text-gray-200 whitespace-nowrap max-w-[120px] sm:max-w-[150px] truncate text-xs"
                    :title="log.ISITEAMITEMDETAIL_BRG_NAME"
                  >
                    {{ log.ISITEAMITEMDETAIL_BRG_NAME }}
                  </td>
                  <td
                    class="px-2 sm:px-3 py-2 text-center font-poppins text-white"
                  >
                    <span
                      :class="[
                        'px-1.5 py-0.5 rounded text-xs',
                        log.is_local ? 'bg-yellow-500/30' : 'bg-blue-500/20',
                      ]"
                    >
                      {{
                        formatQuantity(
                          log.ISITEAMITEMDETAIL_QTY,
                          log.IS_DECIMAL
                        )
                      }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-between items-center mt-4">
            <div class="text-xs text-gray-500">
              <div>
                Total: {{ archiveBackendData.length }}
                <span
                  v-if="archiveViewMode === 'local'"
                  class="text-yellow-500"
                >
                  (Belum disimpan)
                </span>
              </div>
            </div>
            <div class="flex gap-2">
              <button
                @click="closeArchiveModal"
                :disabled="loadingArchive"
                class="px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 text-xs sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Tutup
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Item Modal - Lebih compact untuk mobile -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 backdrop-blur-sm"
      @click.self="closeEditModal"
    >
      <div
        class="relative w-full max-w-sm sm:max-w-md bg-gray-900/95 rounded-xl sm:rounded-2xl overflow-hidden shadow-xl sm:shadow-2xl border border-gray-700 neo-glassmorphism"
      >
        <div class="p-3 sm:p-4 lg:p-5 border-b border-gray-800">
          <h3 class="text-lg sm:text-xl font-poppins text-white mb-1">
            Input Quantity
          </h3>
          <p class="text-gray-400 text-xs sm:text-sm font-poppins">
            Masukkan jumlah quantity untuk barang berikut:
          </p>
        </div>

        <div class="p-3 sm:p-4 lg:p-5">
          <!-- Informasi Barang lebih compact -->
          <div
            class="bg-gray-800/50 rounded-lg p-3 mb-3 border border-gray-700"
          >
            <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm">
              <div>
                <p class="text-gray-400 font-poppins text-xs">PLU</p>
                <p class="text-white font-poppins font-medium">
                  {{ editingItem.ISITEAMITEMDETAIL_BRG_CODE }}
                </p>
              </div>
              <div>
                <p class="text-gray-400 font-poppins text-xs">Barcode</p>
                <p class="text-white font-poppins font-medium text-xs">
                  {{ editingItem.ISITEAMITEMDETAIL_BRG_BARCODE }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-400 font-poppins text-xs">Nama Barang</p>
                <p
                  class="text-white font-poppins font-medium text-xs sm:text-sm truncate"
                >
                  {{ editingItem.ISITEAMITEMDETAIL_BRG_NAME }}
                </p>
              </div>
              <div>
                <p class="text-gray-400 font-poppins text-xs">UoM</p>
                <p class="text-white font-poppins font-medium">
                  {{ editingItem.ISITEAMITEMDETAIL_BRG_UOM }}
                </p>
              </div>
            </div>
          </div>

          <!-- Input Quantity -->
          <div class="mb-3">
            <label
              class="block text-gray-400 text-xs sm:text-sm font-poppins mb-1"
            >
              Quantity *
            </label>
            <input
              v-model="editingItem.ISITEAMITEMDETAIL_QTY"
              :type="editingItem.IS_DECIMAL ? 'number' : 'number'"
              :step="editingItem.IS_DECIMAL ? '0.001' : '1'"
              :min="editingItem.IS_DECIMAL ? '0' : '1'"
              class="w-full bg-gray-800/50 border border-gray-600 rounded-lg text-white text-base sm:text-lg px-3 sm:px-4 py-2.5 sm:py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium"
              :placeholder="
                editingItem.IS_DECIMAL
                  ? 'Masukkan quantity'
                  : 'Masukkan quantity'
              "
              autofocus
              @keyup.enter="saveEditedItem"
              @keyup.esc="closeEditModal"
              @change="validateQuantity(editingItem)"
              :disabled="isLoading"
            />
          </div>

          <!-- Input Note -->
          <div class="mb-4">
            <label
              class="block text-gray-400 text-xs sm:text-sm font-poppins mb-1"
            >
              Catatan
            </label>
            <textarea
              v-model="editingItem.ISITEAMITEMDETAIL_DESC"
              class="w-full bg-gray-800/50 border font-poppins border-gray-600 rounded-lg text-white text-xs sm:text-sm px-3 sm:px-4 py-2.5 sm:py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
              placeholder="Tambah catatan..."
              rows="2"
              @keyup.esc="closeEditModal"
              :disabled="isLoading"
            ></textarea>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-2 sm:gap-3">
            <button
              @click="closeEditModal"
              :disabled="isLoading"
              class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-600 hover:bg-gray-700 text-white font-poppins rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
            >
              Batal
            </button>
            <button
              @click="saveEditedItem"
              :disabled="
                !editingItem.ISITEAMITEMDETAIL_QTY ||
                (editingItem.IS_DECIMAL
                  ? editingItem.ISITEAMITEMDETAIL_QTY < 0
                  : editingItem.ISITEAMITEMDETAIL_QTY < 1) ||
                isLoading
              "
              class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-poppins rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm"
            >
              <div v-if="isLoading" class="flex items-center justify-center">
                <div class="loading-spinner-small"></div>
              </div>
              {{ isLoading ? "Menyimpan..." : "Simpan" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Note View Modal untuk Mobile -->
    <div
      v-if="showNoteViewModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 backdrop-blur-sm"
      @click.self="closeNoteViewModal"
    >
      <div
        class="relative w-full max-w-xs sm:max-w-sm bg-gray-900/95 rounded-xl sm:rounded-2xl overflow-hidden shadow-xl sm:shadow-2xl border border-gray-700 neo-glassmorphism"
      >
        <div
          class="p-3 sm:p-4 flex justify-between items-center border-b border-gray-800"
        >
          <h3 class="text-base sm:text-lg font-semibold text-white">Catatan</h3>
          <button
            @click="closeNoteViewModal"
            class="text-gray-400 hover:text-white transition-colors duration-200 p-1 rounded"
          >
            <svg
              class="w-4 h-4 sm:w-5 sm:h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
        <div class="p-3 sm:p-4">
          <div class="bg-gray-800/50 rounded-lg p-3 min-h-[80px]">
            <p class="text-white text-xs sm:text-sm whitespace-pre-wrap">
              {{ currentNoteView }}
            </p>
          </div>
          <div class="flex justify-end mt-2 sm:mt-3">
            <button
              @click="closeNoteViewModal"
              class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200 text-xs sm:text-sm"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Scanner Modal untuk Barcode -->
    <div
      v-if="showScanner"
      class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-3 lg:p-4 backdrop-blur-sm"
      @click.self="closeScanner"
    >
      <div
        class="relative w-full max-w-xs sm:max-w-sm bg-gray-900/90 rounded-xl sm:rounded-2xl overflow-hidden shadow-xl sm:shadow-2xl border border-gray-700 neo-glassmorphism"
      >
        <!-- Modal Header -->
        <div
          class="p-3 sm:p-4 flex justify-between items-center border-b border-gray-800"
        >
          <h2 class="text-lg sm:text-xl font-semibold text-white">
            Scanner Barcode
          </h2>
          <button
            @click="closeScanner"
            class="text-gray-400 hover:text-white transition-colors duration-200 p-1.5 sm:p-2 rounded-lg hover:bg-gray-700/50"
          >
            <svg
              class="w-5 h-5 sm:w-6 sm:h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <div
          class="relative aspect-square bg-black flex items-center justify-center"
        >
          <video
            ref="scannerVideo"
            class="w-full h-full object-cover"
            autoplay
            playsinline
          ></video>
          <div class="absolute inset-0 flex items-center justify-center">
            <div
              class="relative w-48 sm:w-56 h-48 sm:h-56 border-2 border-blue-400 rounded-lg"
            >
              <div class="scan-animation"></div>
            </div>
          </div>
          <div class="absolute top-3 sm:top-4 left-0 right-0 text-center">
            <p
              class="text-white text-xs sm:text-sm bg-black/50 px-2 sm:px-3 py-1 rounded-full inline-block"
            >
              Arahkan kamera ke barcode 🎯
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Scanner Modal untuk Lokasi -->
    <div
      v-if="showLocationScanner"
      class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-3 lg:p-4 backdrop-blur-sm"
      @click.self="closeLocationScanner"
    >
      <div
        class="relative w-full max-w-xs sm:max-w-sm bg-gray-900/90 rounded-xl sm:rounded-2xl overflow-hidden shadow-xl sm:shadow-2xl border border-gray-700 neo-glassmorphism"
      >
        <!-- Modal Header -->
        <div
          class="p-3 sm:p-4 flex justify-between items-center border-b border-gray-800"
        >
          <h2 class="text-lg sm:text-xl font-semibold text-white">
            Scanner Lokasi
          </h2>
          <button
            @click="closeLocationScanner"
            class="text-gray-400 hover:text-white transition-colors duration-200 p-1.5 sm:p-2 rounded-lg hover:bg-gray-700/50"
          >
            <svg
              class="w-5 h-5 sm:w-6 sm:h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <div
          class="relative aspect-square bg-black flex items-center justify-center"
        >
          <video
            ref="locationScannerVideo"
            class="w-full h-full object-cover"
            autoplay
            playsinline
          ></video>
          <div class="absolute inset-0 flex items-center justify-center">
            <div
              class="relative w-48 sm:w-56 h-48 sm:h-56 border-2 border-green-400 rounded-lg"
            >
              <div class="scan-animation"></div>
            </div>
          </div>
          <div class="absolute top-3 sm:top-4 left-0 right-0 text-center">
            <p
              class="text-white text-xs sm:text-sm bg-black/50 px-2 sm:px-3 py-1 rounded-full inline-block"
            >
              Arahkan kamera ke barcode lokasi 🏷️
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Pindah Lokasi -->
    <div
      v-if="showChangeLocationModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 backdrop-blur-sm"
      @click.self="closeChangeLocationModal"
    >
      <div
        class="relative w-full max-w-sm sm:max-w-md bg-gray-900/95 rounded-xl sm:rounded-2xl overflow-hidden shadow-xl sm:shadow-2xl border border-gray-700 neo-glassmorphism"
      >
        <div class="p-3 sm:p-4 lg:p-5 border-b border-gray-800">
          <h3 class="text-lg sm:text-xl font-semibold text-white mb-1">
            Pindah Lokasi
          </h3>
          <p class="text-gray-400 text-xs sm:text-sm">
            Masukkan atau scan kode lokasi baru
          </p>
        </div>

        <div class="p-3 sm:p-4 lg:p-5">
          <!-- Input Lokasi -->
          <div class="mb-3">
            <label class="block text-white text-xs sm:text-sm font-medium mb-1">
              Kode Lokasi
            </label>
            <div class="relative">
              <input
                v-model="newLocationInput"
                type="text"
                class="w-full bg-gray-800/50 border border-gray-600 rounded-lg text-white text-base sm:text-lg px-3 sm:px-4 py-2.5 sm:py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-12 font-medium"
                placeholder="Masukkan Kode Lokasi"
                autofocus
                @input="formatLocationInput"
                @keydown="handleLocationKeydown"
                @keyup.enter="handleLocationSubmit"
                @keyup.esc="closeChangeLocationModal"
                maxlength="8"
                :disabled="isLoading"
              />
              <button
                @click="openLocationScanner"
                :disabled="isLoading"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1.5 sm:p-2 bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                title="Scan Lokasi"
              >
                <div v-if="isLoading" class="flex items-center justify-center">
                  <div class="loading-spinner-small"></div>
                </div>
                <svg
                  v-else
                  class="w-4 h-4 sm:w-5 sm:h-5 text-white"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
              </button>
            </div>
          </div>

          <!-- Informasi Lokasi Saat Ini -->
          <div
            class="bg-gray-800/50 rounded-lg p-3 mb-3 border border-gray-700"
          >
            <div class="text-xs sm:text-sm">
              <p class="text-gray-400 mb-1">Lokasi Saat Ini:</p>
              <p class="text-white font-medium">{{ lokasi }}</p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-2 sm:gap-3">
            <button
              @click="closeChangeLocationModal"
              :disabled="isLoading"
              class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
            >
              Batal
            </button>
            <button
              @click="handleLocationSubmit"
              :disabled="
                !newLocationInput.trim() ||
                newLocationInput.trim().length < 5 ||
                isLoading
              "
              class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-medium rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm"
            >
              <div v-if="isLoading" class="flex items-center justify-center">
                <div class="loading-spinner-small"></div>
              </div>
              {{ isLoading ? "Memproses..." : "Pindah Lokasi" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Qty Input Modal -->
    <div
      v-if="showQtyModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 backdrop-blur-sm"
      @click.self="closeQtyModal"
    >
      <div
        class="relative w-full max-w-sm sm:max-w-md bg-gray-900/95 rounded-xl sm:rounded-2xl overflow-hidden shadow-xl sm:shadow-2xl border border-gray-700 neo-glassmorphism"
      >
        <div class="p-3 sm:p-4 lg:p-5 border-b border-gray-800">
          <h3 class="text-lg sm:text-xl font-poppins text-white mb-1">
            Input Quantity
          </h3>
          <p class="text-gray-400 text-xs sm:text-sm font-poppins">
            Masukkan jumlah quantity untuk barang berikut:
          </p>
        </div>

        <div class="p-3 sm:p-4 lg:p-5">
          <!-- Informasi Barang -->
          <div
            class="bg-gray-800/50 rounded-lg p-3 mb-3 border border-gray-700"
          >
            <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm">
              <div>
                <p class="text-gray-400 font-poppins text-xs">PLU</p>
                <p class="text-white font-poppins font-medium">
                  {{ currentItem.plu }}
                </p>
              </div>
              <div>
                <p class="text-gray-400 font-poppins text-xs">Barcode</p>
                <p class="text-white font-poppins font-medium text-xs">
                  {{ currentItem.barcode }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-400 font-poppins text-xs">Nama Barang</p>
                <p
                  class="text-white font-poppins font-medium text-xs sm:text-sm truncate"
                >
                  {{ currentItem.nama }}
                </p>
              </div>
              <div>
                <p class="text-gray-400 font-poppins text-xs">UoM</p>
                <p class="text-white font-poppins font-medium">
                  {{ currentItem.uom }}
                </p>
              </div>
            </div>
          </div>

          <!-- Input Quantity -->
          <div class="mb-3">
            <label
              class="block text-gray-400 text-xs sm:text-sm font-poppins mb-1"
            >
              Quantity *
            </label>
            <input
              v-model="currentItem.qty"
              :type="currentItem.is_decimal ? 'number' : 'number'"
              :step="currentItem.is_decimal ? '0.001' : '1'"
              :min="currentItem.is_decimal ? '0' : '1'"
              class="w-full bg-gray-800/50 border border-gray-600 font-poppins rounded-lg text-white text-base sm:text-lg px-3 sm:px-4 py-2.5 sm:py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium"
              :placeholder="
                currentItem.is_decimal
                  ? 'Masukkan quantity'
                  : 'Masukkan quantity'
              "
              autofocus
              @keyup.enter="addItemToTable"
              @keyup.esc="closeQtyModal"
              @change="validateCurrentItemQuantity"
              :disabled="isLoading"
            />
          </div>

          <!-- Input Note (Opsional) -->
          <div class="mb-4">
            <label
              class="block text-gray-400 text-xs sm:text-sm font-poppins mb-1"
            >
              Catatan (Opsional)
            </label>
            <textarea
              v-model="currentItem.desc"
              class="w-full bg-gray-800/50 border border-gray-600 font-poppins rounded-lg text-white text-xs sm:text-sm px-3 sm:px-4 py-2.5 sm:py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
              placeholder="Tambah catatan..."
              rows="2"
              @keyup.esc="closeQtyModal"
              :disabled="isLoading"
            ></textarea>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-2 sm:gap-3">
            <button
              @click="closeQtyModal"
              :disabled="isLoading"
              class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-600 hover:bg-gray-700 text-white font-poppins rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
            >
              Batal
            </button>
            <button
              @click="addItemToTable"
              :disabled="
                !currentItem.qty ||
                (currentItem.is_decimal
                  ? currentItem.qty < 0
                  : currentItem.qty < 1) ||
                isLoading
              "
              class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-poppins rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm"
            >
              <div v-if="isLoading" class="flex items-center justify-center">
                <div class="loading-spinner-small"></div>
              </div>
              {{ isLoading ? "Memproses..." : "Tambah ke Tabel" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modern Loading Overlay -->
    <div
      v-if="isLoading"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xl"
    >
      <div class="relative">
        <!-- Main Spinner -->
        <div
          class="w-16 h-16 sm:w-20 sm:h-20 border-3 sm:border-4 border-blue-500/30 border-t-blue-500 rounded-full animate-spin"
        ></div>

        <!-- Pulsing Ring Effect -->
        <div
          class="absolute inset-0 border-3 sm:border-4 border-purple-500/20 rounded-full animate-ping"
        ></div>

        <!-- Content -->
        <div class="absolute inset-0 flex items-center justify-center">
          <div class="text-center">
            <!-- Animated Dots -->
            <div class="flex justify-center space-x-1 mb-2">
              <div
                class="w-1 h-1 bg-blue-400 rounded-full animate-bounce"
                style="animation-delay: 0s"
              ></div>
              <div
                class="w-1 h-1 bg-blue-400 rounded-full animate-bounce"
                style="animation-delay: 0.1s"
              ></div>
              <div
                class="w-1 h-1 bg-blue-400 rounded-full animate-bounce"
                style="animation-delay: 0.2s"
              ></div>
            </div>

            <!-- Text -->
            <p
              class="text-white text-xs sm:text-sm font-medium bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent"
            >
              Memproses...
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div
      v-if="showToast"
      class="fixed bottom-3 sm:bottom-4 lg:bottom-6 left-1/2 transform -translate-x-1/2 z-50 bg-gray-800/90 backdrop-blur-sm text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg shadow-lg border border-gray-700 flex items-center gap-1.5 sm:gap-2 animate-fade-in-up max-w-[90vw] sm:max-w-md mx-2"
    >
      <div
        class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-green-400 rounded-full animate-pulse flex-shrink-0"
      ></div>
      <svg
        class="w-3 h-3 sm:w-4 sm:h-4 text-green-400 flex-shrink-0"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
      <span
        class="text-xs sm:text-sm font-medium break-words whitespace-normal"
        >{{ toastMessage }}</span
      >
    </div>

    <!-- Footer -->
    <div class="relative z-10 mt-4 sm:mt-6 lg:mt-8 text-center px-2">
      <p class="text-gray-500 text-xs">
        &copy; 2025 Stock Taking System By Ubay • Kelola stok dengan mudah !
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick, computed } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { BrowserMultiFormatReader } from "@zxing/browser";
import { BarcodeFormat, DecodeHintType } from "@zxing/library";

// STATE
const items = ref([]);
const showScanner = ref(false);
const showLocationScanner = ref(false);
const barcodeInput = ref("");
const showToast = ref(false);
const toastMessage = ref("");
const scannerVideo = ref(null);
const locationScannerVideo = ref(null);
const router = useRouter();

// Tambah ref untuk input barcode
const barcodeInputRef = ref(null);

// Modal states
const showQtyModal = ref(false);
const showEditModal = ref(false);
const showNoteViewModal = ref(false);
const showChangeLocationModal = ref(false);
const showArchiveModal = ref(false);
const currentNoteView = ref("");
const editingItem = ref(null);
const currentItem = ref({
  plu: "",
  barcode: "",
  nama: "",
  uom: "",
  qty: 0,
  desc: "",
  is_decimal: 0,
});

// Location states
const newLocationInput = ref("");

const lokasi = ref("");
const penghitung1 = ref("");
const penghitung2 = ref("");

// STATE UNTUK DATA BARANG BACKEND
const itemDetails = ref([]);
const teamInfo = ref(null);
const loadingItemDetails = ref(false);
const lastUpdated = ref("");

// Archive states
const archiveData = ref([]);
const loadingArchive = ref(false);
const currentArchiveItem = ref(null);
const archiveViewMode = ref("local");
const localArchiveData = ref([]);

// LOADING STATE UNTUK SEMUA ACTION
const isLoading = ref(false);

// LOCAL STORAGE MANAGEMENT - BERDASARKAN KODE LOKASI

// Fungsi untuk mendapatkan storage key berdasarkan kode lokasi
function getStorageKey(locationCode = null) {
  // Gunakan lokasi saat ini jika tidak ada parameter
  const code = locationCode || lokasi.value;
  if (!code) {
    return null;
  }

  // Normalisasi kode lokasi untuk menghindari duplikasi
  const normalizedCode = code.replace(/[^\d.]/g, "");
  return `location_data_${normalizedCode}`;
}

// Fungsi untuk mendapatkan atau membuat location_data
function getLocationData(locationCode = null) {
  const storageKey = getStorageKey(locationCode);
  if (!storageKey) return null;

  const data = localStorage.getItem(storageKey);
  if (!data) {
    // Inisialisasi struktur data baru
    const initialData = {
      kode_lokasi: locationCode || lokasi.value,
      kode_team: "",
      penghitung_1: "",
      penghitung_2: "",
      token: localStorage.getItem("token") || "",
      backend_items: [],
      manual_items: [],
      archive_items: [],
      team_verified_at: null,
      last_sync: null,
      original_data: [],
      seq_order: {},
    };
    localStorage.setItem(storageKey, JSON.stringify(initialData));
    return initialData;
  }
  return JSON.parse(data);
}

// Fungsi untuk update location_data
function updateLocationData(updates, locationCode = null) {
  const storageKey = getStorageKey(locationCode);
  if (!storageKey) {
    return null;
  }

  const currentData = getLocationData(locationCode);
  if (!currentData) {
    return null;
  }

  const updatedData = { ...currentData, ...updates };

  // Pastikan kode lokasi selalu sesuai dengan storage key
  const codeFromStorage = storageKey.replace("location_data_", "");
  updatedData.kode_lokasi = codeFromStorage;

  localStorage.setItem(storageKey, JSON.stringify(updatedData));
  return updatedData;
}

// Fungsi untuk update bagian tertentu dari location_data
function updateLocationSubData(subKey, items, locationCode = null) {
  const storageKey = getStorageKey(locationCode);
  if (!storageKey) {
    return null;
  }

  const currentData = getLocationData(locationCode);
  if (!currentData) {
    return null;
  }

  const updatedData = {
    ...currentData,
    [subKey]: items,
  };

  // Update timestamp
  updatedData.last_sync = new Date().toISOString();

  localStorage.setItem(storageKey, JSON.stringify(updatedData));
  return updatedData;
}

// Fungsi untuk mendapatkan lokasi aktif dari sessionStorage
function getActiveLocation() {
  const activeLocationCode = sessionStorage.getItem("active_location");
  if (!activeLocationCode) {
    return null;
  }
  return activeLocationCode;
}

// Fungsi untuk set lokasi aktif di sessionStorage
function setActiveLocation(locationCode) {
  if (!locationCode) {
    return;
  }
  sessionStorage.setItem("active_location", locationCode);
}

// FUNGSI UNTUK UPDATE DAN SAVE SEQ ORDER

// Fungsi untuk update dan save SEQ order ke localStorage
function updateSeqOrder() {
  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode) return;

  const locationData = getLocationData(activeLocationCode);
  if (!locationData) return;

  // Ambil data dari itemDetails untuk mendapatkan urutan saat ini
  const seqOrder = {};

  // Berikan nomor SEQ berdasarkan urutan di itemDetails
  itemDetails.value.forEach((item, index) => {
    seqOrder[item.ISITEAMITEMDETAIL_BRG_CODE] = index + 1;
  });

  // Simpan ke location_data
  updateLocationData(
    {
      ...locationData,
      seq_order: seqOrder,
      last_seq_update: new Date().toISOString(),
    },
    activeLocationCode
  );
}

// COMPUTED PROPERTIES DENGAN SEQ ORDER

// Computed untuk sorted items dengan urutan berdasarkan SEQ order
const sortedItems = computed(() => {
  // Ambil data dari location_data untuk mendapatkan urutan SEQ
  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode) return [...itemDetails.value];

  const locationData = getLocationData(activeLocationCode);
  const storedSeqOrder = locationData?.seq_order || {};

  // Jika belum ada itemDetails, kembalikan array kosong
  if (!itemDetails.value || itemDetails.value.length === 0) {
    return [];
  }

  // Buat salinan array untuk di-sort
  const itemsToSort = [...itemDetails.value];

  // Jika ada SEQ order yang tersimpan, gunakan itu
  if (Object.keys(storedSeqOrder).length > 0) {
    itemsToSort.sort((a, b) => {
      const seqA = storedSeqOrder[a.ISITEAMITEMDETAIL_BRG_CODE];
      const seqB = storedSeqOrder[b.ISITEAMITEMDETAIL_BRG_CODE];

      // Item dengan SEQ diurutkan dulu
      if (seqA !== undefined && seqB !== undefined) {
        return seqA - seqB;
      }
      if (seqA !== undefined) return -1;
      if (seqB !== undefined) return 1;

      // Item baru (manual) di atas
      if (a.source === "manual" && b.source === "backend") return -1;
      if (a.source === "backend" && b.source === "manual") return 1;

      // Urut berdasarkan timestamp
      const timeA = new Date(a.timestamp || 0).getTime();
      const timeB = new Date(b.timestamp || 0).getTime();
      return timeB - timeA;
    });
  } else {
    // Jika belum ada SEQ order, buat urutan default
    itemsToSort.sort((a, b) => {
      // Prioritaskan item manual (baru) di atas
      if (a.source === "manual" && b.source === "backend") return -1;
      if (a.source === "backend" && b.source === "manual") return 1;

      // Untuk item dengan source sama, urutkan berdasarkan timestamp (terbaru dulu)
      const timeA = new Date(a.timestamp || 0).getTime();
      const timeB = new Date(b.timestamp || 0).getTime();
      return timeB - timeA;
    });

    // Simpan SEQ order untuk pertama kali
    const seqOrder = {};
    itemsToSort.forEach((item, index) => {
      seqOrder[item.ISITEAMITEMDETAIL_BRG_CODE] = index + 1;
    });

    if (locationData) {
      updateLocationData(
        {
          ...locationData,
          seq_order: seqOrder,
        },
        activeLocationCode
      );
    }
  }

  return itemsToSort;
});

// Archive data dari backend
const archiveBackendData = computed(() => {
  const dataSource =
    archiveViewMode.value === "local"
      ? localArchiveData.value
      : archiveData.value;

  return dataSource.map((log) => {
    return {
      log_id: log.log_id,
      item_detail_id: log.item_detail_id,
      ISITEAMITEMDETAIL_BRG_CODE:
        log.kode_plu || log.ISITEAMITEMDETAIL_BRG_CODE || "-",
      ISITEAMITEMDETAIL_BRG_NAME:
        log.nama_barang ||
        log.ISITEAMITEMDETAIL_BRG_NAME ||
        "Data tidak tersedia",
      ISITEAMITEMDETAIL_BRG_BARCODE:
        log.barcode || log.ISITEAMITEMDETAIL_BRG_BARCODE || "-",
      ISITEAMITEMDETAIL_QTY: log.qty || log.ISITEAMITEMDETAIL_QTY || 0,
      archived_at: log.date_created || log.archived_at,
      user_create: log.user_create || "-",
      IS_DECIMAL: currentArchiveItem.value?.IS_DECIMAL || 0,
      source: log.source || "backend",
      is_local: log.is_local || false,
      delete_marker: log.delete_marker || false,
      change_type: log.change_type || "UPDATE",
    };
  });
});

// FUNGSI UNTUK MEMUAT DATA DARI LOCATION_DATA
function loadDataFromLocationData() {
  // Ambil lokasi aktif dari sessionStorage
  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode) {
    showToastMessage(
      "⚠️ Tidak ada lokasi aktif. Silakan scan lokasi terlebih dahulu."
    );
    return;
  }

  // Update lokasi.value dengan kode lokasi aktif
  lokasi.value = activeLocationCode;

  // Ambil data dari localStorage berdasarkan kode lokasi
  const locationData = getLocationData(activeLocationCode);

  if (!locationData) {
    // Inisialisasi data baru
    updateLocationData(
      {
        kode_lokasi: activeLocationCode,
        kode_team: "",
        penghitung_1: "",
        penghitung_2: "",
        token: localStorage.getItem("token") || "",
        backend_items: [],
        manual_items: [],
        archive_items: [],
        team_verified_at: null,
        last_sync: null,
        original_data: [],
        seq_order: {},
      },
      activeLocationCode
    );

    itemDetails.value = [];
    return;
  }

  // Filter out items yang ditandai untuk dihapus
  const backendData = (locationData.backend_items || []).filter(
    (item) => !item.marked_for_deletion
  );
  const manualData = locationData.manual_items || [];

  // Set penghitung dari data yang tersimpan
  penghitung1.value = locationData.penghitung_1 || "";
  penghitung2.value = locationData.penghitung_2 || "";

  // Validasi kode lokasi dalam data
  if (locationData.kode_lokasi !== activeLocationCode) {
    // Perbaiki data yang salah
    locationData.kode_lokasi = activeLocationCode;
    localStorage.setItem(
      getStorageKey(activeLocationCode),
      JSON.stringify(locationData)
    );
  }

  // Gabungkan data untuk tampilan
  const combinedData = [
    ...manualData.map((item) => ({
      ...item,
      source: "manual",
      timestamp: item.timestamp || new Date().toISOString(),
    })),
    ...backendData.map((item) => ({
      ...item,
      source: "backend",
      timestamp: item.timestamp || new Date().toISOString(),
    })),
  ];

  // Langsung set ke itemDetails tanpa pengecekan tambahan
  itemDetails.value = combinedData;

  // Update timestamp cache
  if (combinedData.length > 0) {
    const now = new Date();
    lastUpdated.value =
      now.toLocaleString("id-ID", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
      }) + " (cached)";
  }
}

// FUNGSI BARU: MERGE DATA BACKEND DENGAN LOCATION_DATA - DENGAN SEQ ORDER
function mergeBackendWithLocationData(backendData) {
  // Ambil data lokasi aktif
  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode) {
    showToastMessage("⚠️ Tidak bisa merge data: tidak ada lokasi aktif");
    return;
  }

  const locationData = getLocationData(activeLocationCode);
  if (!locationData) {
    showToastMessage("⚠️ Tidak bisa merge data: location_data tidak ditemukan");
    return;
  }

  // Filter out items yang ditandai untuk dihapus
  const backendStorageData = (locationData.backend_items || []).filter(
    (item) => !item.marked_for_deletion
  );
  const manualData = locationData.manual_items || [];
  const storedSeqOrder = locationData.seq_order || {};

  // Merge strategy: gabungkan data backend dengan data manual
  const mergedData = [];

  // 2a. Tambahkan data dari backend (prioritaskan data dari API)
  backendData.forEach((backendItem) => {
    const storageItem = backendStorageData.find(
      (storage) =>
        storage.ISITEAMITEMDETAIL_BRG_CODE ===
        backendItem.ISITEAMITEMDETAIL_BRG_CODE
    );

    if (storageItem) {
      // Gunakan nilai dari storage (yang sudah diedit user)
      let formattedQty;
      const isDecimal = Number(backendItem.IS_DECIMAL) || 0;

      if (isDecimal === 1) {
        const num = parseFloat(storageItem.ISITEAMITEMDETAIL_QTY || 0);
        formattedQty = isNaN(num) ? 0 : num;
      } else {
        const num = parseInt(storageItem.ISITEAMITEMDETAIL_QTY || 0, 10);
        formattedQty = isNaN(num) ? 0 : num;
      }

      mergedData.push({
        ...backendItem,
        ISITEAMITEMDETAIL_QTY: formattedQty,
        ISITEAMITEMDETAIL_DESC: storageItem.ISITEAMITEMDETAIL_DESC,
        IS_DECIMAL: isDecimal,
        source: "backend",
        timestamp: storageItem.timestamp || new Date().toISOString(),
      });
    } else {
      // Gunakan data baru dari backend
      let formattedQty;
      const isDecimal = Number(backendItem.IS_DECIMAL) || 0;

      if (isDecimal === 1) {
        const num = parseFloat(backendItem.ISITEAMITEMDETAIL_QTY || 0);
        formattedQty = isNaN(num) ? 0 : num;
      } else {
        const num = parseInt(backendItem.ISITEAMITEMDETAIL_QTY || 0, 10);
        formattedQty = isNaN(num) ? 0 : num;
      }

      mergedData.push({
        ...backendItem,
        ISITEAMITEMDETAIL_QTY: formattedQty,
        IS_DECIMAL: isDecimal,
        source: "backend",
        timestamp: new Date().toISOString(),
      });
    }
  });

  // 2b. Tambahkan data manual - hanya yang belum ada di backend
  manualData.forEach((newItem) => {
    const existsInBackend = backendData.some(
      (backend) =>
        backend.ISITEAMITEMDETAIL_BRG_CODE ===
        newItem.ISITEAMITEMDETAIL_BRG_CODE
    );

    if (!existsInBackend) {
      let formattedQty;
      const isDecimal = Number(newItem.IS_DECIMAL) || 0;

      if (isDecimal === 1) {
        const num = parseFloat(newItem.ISITEAMITEMDETAIL_QTY || 0);
        formattedQty = isNaN(num) ? 0 : num;
      } else {
        const num = parseInt(newItem.ISITEAMITEMDETAIL_QTY || 0, 10);
        formattedQty = isNaN(num) ? 0 : num;
      }

      mergedData.unshift({
        ...newItem,
        ISITEAMITEMDETAIL_QTY: formattedQty,
        source: "manual",
        timestamp: newItem.timestamp || new Date().toISOString(),
      });
    }
  });

  // 3. Urutkan berdasarkan SEQ order jika ada
  if (Object.keys(storedSeqOrder).length > 0) {
    mergedData.sort((a, b) => {
      const seqA = storedSeqOrder[a.ISITEAMITEMDETAIL_BRG_CODE];
      const seqB = storedSeqOrder[b.ISITEAMITEMDETAIL_BRG_CODE];

      // Item dengan SEQ diurutkan dulu
      if (seqA !== undefined && seqB !== undefined) {
        return seqA - seqB;
      }
      if (seqA !== undefined) return -1;
      if (seqB !== undefined) return 1;

      // Item baru (manual) di atas
      if (a.source === "manual" && b.source === "backend") return -1;
      if (a.source === "backend" && b.source === "manual") return 1;

      // Urut berdasarkan timestamp
      const timeA = new Date(a.timestamp || 0).getTime();
      const timeB = new Date(b.timestamp || 0).getTime();
      return timeB - timeA;
    });
  }

  // Langsung update itemDetails.value
  itemDetails.value = mergedData;

  // 5. Update backend_items di location_data (tanpa yang ditandai delete)
  const updatedBackendItems = backendData.map((backendItem) => {
    const existingStorage = backendStorageData.find(
      (storage) =>
        storage.ISITEAMITEMDETAIL_BRG_CODE ===
        backendItem.ISITEAMITEMDETAIL_BRG_CODE
    );

    let formattedQty;
    const isDecimal = Number(backendItem.IS_DECIMAL) || 0;

    if (existingStorage) {
      if (isDecimal === 1) {
        const num = parseFloat(existingStorage.ISITEAMITEMDETAIL_QTY || 0);
        formattedQty = isNaN(num) ? 0 : num;
      } else {
        const num = parseInt(existingStorage.ISITEAMITEMDETAIL_QTY || 0, 10);
        formattedQty = isNaN(num) ? 0 : num;
      }
    } else {
      if (isDecimal === 1) {
        const num = parseFloat(backendItem.ISITEAMITEMDETAIL_QTY || 0);
        formattedQty = isNaN(num) ? 0 : num;
      } else {
        const num = parseInt(backendItem.ISITEAMITEMDETAIL_QTY || 0, 10);
        formattedQty = isNaN(num) ? 0 : num;
      }
    }

    return {
      ...backendItem,
      ISITEAMITEMDETAIL_QTY: formattedQty,
      ISITEAMITEMDETAIL_DESC:
        existingStorage?.ISITEAMITEMDETAIL_DESC ||
        backendItem.ISITEAMITEMDETAIL_DESC,
      IS_DECIMAL: isDecimal,
      source: "backend",
      timestamp: existingStorage?.timestamp || new Date().toISOString(),
    };
  });

  // Update juga original_data untuk komparasi di saveChanges
  updateLocationSubData(
    "backend_items",
    updatedBackendItems,
    activeLocationCode
  );
  updateLocationSubData(
    "original_data",
    backendData.map((item) => ({ ...item })),
    activeLocationCode
  );
}

// FUNGSI UNTUK MENGAMBIL DATA ARCHIVE DARI BACKEND
async function fetchArchiveData(item = null) {
  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode) {
    showToastMessage("⚠️ Tidak ada lokasi aktif");
    return;
  }

  const locationData = getLocationData(activeLocationCode);
  if (!locationData) {
    showToastMessage("⚠️ Data lokasi tidak ditemukan");
    return;
  }

  const token = locationData.token;
  const kodeLokasi = activeLocationCode;
  const kodeTeam = locationData.kode_team;

  if (!token || !kodeLokasi || !kodeTeam) {
    showToastMessage("⚠️ Data tidak lengkap untuk mengambil archive");
    return;
  }

  // Pastikan ada item yang dipilih untuk melihat log
  if (!item) {
    showToastMessage("⚠️ Pilih item terlebih dahulu untuk melihat log");
    return;
  }

  const kodePlu = item.ISITEAMITEMDETAIL_BRG_CODE;

  if (!kodePlu) {
    showToastMessage("⚠️ Tidak ada kode PLU untuk mengambil log");
    return;
  }

  loadingArchive.value = true;

  try {
    const response = await axios.get(
      `${import.meta.env.VITE_BACKEND}/api/lokasi/take-log`,
      {
        params: {
          kode_lokasi: kodeLokasi,
          kode_team: kodeTeam,
          kode_plu: kodePlu,
        },
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    if (response.data.success) {
      // Mapping data sesuai dengan format response backend
      archiveData.value = response.data.data.map((log) => ({
        log_id: log.log_id,
        item_detail_id: null,
        ISITEAMITEMDETAIL_BRG_CODE: log.kode_plu || "-",
        ISITEAMITEMDETAIL_BRG_NAME: log.nama_barang || "Data tidak tersedia",
        ISITEAMITEMDETAIL_BRG_BARCODE: log.barcode || "-",
        ISITEAMITEMDETAIL_QTY: log.qty,
        ISITEAMITEMDETAIL_DESC: "",
        change_type: "UPDATE",
        archived_at: log.date_created,
        user_create: log.user_create,
        IS_DECIMAL: item.IS_DECIMAL || 0,
        lokasi: log.lokasi,
        no_team: log.no_team,
        barang: {
          kode_plu: log.kode_plu,
          nama_barang: log.nama_barang,
          barcode: log.barcode,
        },
        source: "backend",
        is_local: false,
      }));

      showToastMessage(
        `✅ Berhasil memuat ${archiveData.value.length} data log untuk PLU: ${kodePlu}`
      );
    } else {
      showToastMessage(
        "❌ Gagal memuat data archive: " +
          (response.data.message || "Unknown error")
      );
    }
  } catch (error) {
    if (error.response?.status === 404) {
      showToastMessage("ℹ️ Tidak ada data log untuk barang ini");
      archiveData.value = [];
    } else {
      const errorMsg =
        error.response?.data?.message || "Gagal terhubung ke server";
      showToastMessage(`❌ ${errorMsg}`);
    }
  } finally {
    loadingArchive.value = false;
  }
}

// ARCHIVE MODAL FUNCTIONS
async function openArchiveModal(item = null) {
  if (item) {
    currentArchiveItem.value = item;
  }

  showArchiveModal.value = true;

  // Reset data
  archiveData.value = [];
  localArchiveData.value = [];

  // Tampilkan archive dari localStorage terlebih dahulu
  loadLocalArchiveData(item);
}

// Fungsi untuk memuat archive dari localStorage
function loadLocalArchiveData(item = null) {
  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode || !item) return;

  const locationData = getLocationData(activeLocationCode);
  if (!locationData) return;

  const archiveItems = locationData.archive_items || [];

  // Filter berdasarkan item yang dipilih
  const itemLogs = archiveItems.filter(
    (log) =>
      log.ISITEAMITEMDETAIL_BRG_CODE === item.ISITEAMITEMDETAIL_BRG_CODE ||
      log.barang_data?.ISITEAMITEMDETAIL_BRG_CODE ===
        item.ISITEAMITEMDETAIL_BRG_CODE
  );

  // Mapping data lokal
  localArchiveData.value = itemLogs.map((log) => ({
    log_id: `local-${Date.now()}-${Math.random()}`,
    item_detail_id: log.ISITEAMITEMDETAIL_ID || log.LOG_ISITEAMITEMDETAIL_ID,
    ISITEAMITEMDETAIL_BRG_CODE:
      log.barang_data?.ISITEAMITEMDETAIL_BRG_CODE ||
      log.ISITEAMITEMDETAIL_BRG_CODE ||
      item.ISITEAMITEMDETAIL_BRG_CODE,
    ISITEAMITEMDETAIL_BRG_NAME:
      log.barang_data?.ISITEAMITEMDETAIL_BRG_NAME ||
      log.ISITEAMITEMDETAIL_BRG_NAME ||
      item.ISITEAMITEMDETAIL_BRG_NAME,
    ISITEAMITEMDETAIL_BRG_BARCODE:
      log.barang_data?.ISITEAMITEMDETAIL_BRG_BARCODE ||
      log.ISITEAMITEMDETAIL_BRG_BARCODE ||
      item.ISITEAMITEMDETAIL_BRG_BARCODE,
    ISITEAMITEMDETAIL_QTY: log.LOG_NEWQTY || log._local_new_qty || 0,
    ISITEAMITEMDETAIL_DESC: log.barang_data?.ISITEAMITEMDETAIL_DESC || "",
    change_type: log.action_type || log.change_type || "UPDATE",
    archived_at: log.DATE_CREATE || log.timestamp || new Date().toISOString(),
    user_create: log.USER_CREATE || "system",
    IS_DECIMAL: item.IS_DECIMAL || 0,
    lokasi: log.lokasi || activeLocationCode,
    no_team: log.kode_team || "",
    barang: {
      kode_plu: item.ISITEAMITEMDETAIL_BRG_CODE,
      nama_barang: item.ISITEAMITEMDETAIL_BRG_NAME,
      barcode: item.ISITEAMITEMDETAIL_BRG_BARCODE,
    },
    source: "local",
    is_local: true,
    delete_marker: log.delete_marker || false,
  }));

  // Set view mode ke local
  archiveViewMode.value = "local";
}

// Fungsi untuk memuat data dari backend
async function loadBackendArchive() {
  if (currentArchiveItem.value) {
    await fetchArchiveData(currentArchiveItem.value);
    archiveViewMode.value = "backend";
  }
}

function closeArchiveModal() {
  showArchiveModal.value = false;
  archiveData.value = [];
  localArchiveData.value = [];
  currentArchiveItem.value = null;
  archiveViewMode.value = "local";
}

// Format tanggal untuk archive
function formatArchiveDate(dateString) {
  if (!dateString) return "-";
  const date = new Date(dateString);
  return date.toLocaleString("id-ID", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
  });
}

// FORMAT QUANTITY FUNCTION - DENGAN DUKUNGAN IS_DECIMAL
function formatQuantity(qty, isDecimal = 0) {
  if (qty === null || qty === undefined || qty === "") return "0";

  // Pastikan IS_DECIMAL adalah number
  const isDecimalNum = Number(isDecimal);

  if (isDecimalNum === 1) {
    // Untuk desimal: tampilkan dengan 3 digit
    const number = parseFloat(qty);
    return isNaN(number) ? "0.000" : number.toFixed(3);
  } else {
    // Untuk non-desimal: tampilkan sebagai integer
    const number = parseInt(qty, 10);
    return isNaN(number) ? "0" : number.toString();
  }
}

// Helper function untuk format quantity ke backend
function formatQuantityForBackend(qty, isDecimal = 0) {
  const isDecimalNum = Number(isDecimal);

  if (isDecimalNum === 1) {
    const num = parseFloat(qty);
    return isNaN(num) ? 0 : num;
  } else {
    const num = parseInt(qty, 10);
    return isNaN(num) ? 1 : num;
  }
}

// FUNGSI VALIDASI QUANTITY
function validateQuantity(item) {
  if (!item) return;

  const isDecimal = Number(item.IS_DECIMAL) || 0;
  let qty = item.ISITEAMITEMDETAIL_QTY;

  if (isDecimal === 0) {
    // Untuk non-desimal: pastikan integer dan >= 1
    if (qty !== undefined && qty !== null && qty !== "") {
      const num = parseInt(qty, 10);
      if (!isNaN(num)) {
        item.ISITEAMITEMDETAIL_QTY = Math.max(1, num);
      }
    }
  } else {
    // Untuk desimal: pastikan 3 digit desimal
    if (qty !== undefined && qty !== null && qty !== "") {
      const num = parseFloat(qty);
      if (!isNaN(num)) {
        item.ISITEAMITEMDETAIL_QTY = Math.max(0, num).toFixed(3);
      }
    }
  }
}

function validateCurrentItemQuantity() {
  const isDecimal = Number(currentItem.value.is_decimal) || 0;
  let qty = currentItem.value.qty;

  if (isDecimal === 0) {
    // Untuk non-desimal: pastikan integer
    if (qty !== undefined && qty !== null && qty !== "") {
      const num = parseInt(qty, 10);
      if (!isNaN(num)) {
        currentItem.value.qty = Math.max(1, num);
      }
    }
  } else {
    // Untuk desimal: pastikan 3 digit desimal
    if (qty !== undefined && qty !== null && qty !== "") {
      const num = parseFloat(qty);
      if (!isNaN(num)) {
        currentItem.value.qty = Math.max(0, num).toFixed(3);
      }
    }
  }
}

// Fungsi untuk mengambil data barang dari backend
async function fetchItemDetails() {
  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode) {
    showToastMessage(
      "⚠️ Tidak ada lokasi aktif. Silakan scan lokasi terlebih dahulu."
    );
    return;
  }

  const locationData = getLocationData(activeLocationCode);
  if (!locationData) {
    showToastMessage("⚠️ Data lokasi tidak ditemukan");
    return;
  }

  const token = locationData.token;
  const kodeLokasi = activeLocationCode;
  const kodeTeam = locationData.kode_team;

  if (!token) {
    showToastMessage("⚠️ Sesi login sudah berakhir. Silakan login kembali.");
    return;
  }

  if (!kodeLokasi || !kodeTeam) {
    showToastMessage("⚠️ Data lokasi atau team tidak ditemukan.");
    return;
  }

  loadingItemDetails.value = true;
  isLoading.value = true;

  try {
    const response = await axios.get(
      `${import.meta.env.VITE_BACKEND}/api/lokasi/item`,
      {
        params: {
          lokasi: kodeLokasi,
          kode_team: kodeTeam,
        },
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    if (response.data.success) {
      const backendData = response.data.data || [];

      // Update team info dari response
      if (response.data.team) {
        teamInfo.value = response.data.team;
        // Update penghitung dari data team
        if (
          response.data.team.SOPT_PENGHITUNG ||
          response.data.team.SOPT_HELPER
        ) {
          penghitung1.value = response.data.team.SOPT_PENGHITUNG || "";
          penghitung2.value = response.data.team.SOPT_HELPER || "";

          // Update location_data dengan data team
          updateLocationData(
            {
              kode_team: locationData.kode_team,
              penghitung_1: penghitung1.value,
              penghitung_2: penghitung2.value,
              team_verified_at: new Date().toISOString(),
            },
            activeLocationCode
          );
        }
      }

      // GUNAKAN FUNGSI MERGE DENGAN SEQ ORDER
      mergeBackendWithLocationData(backendData);

      // Pastikan tampilan langsung update
      if (itemDetails.value.length === 0 && backendData.length > 0) {
        showToastMessage(`✅ ${backendData.length} barang dimuat dari server`);
      } else if (itemDetails.value.length > 0) {
        showToastMessage(
          `✅ Data barang diperbarui: ${itemDetails.value.length} item`
        );
      }

      // Update timestamp
      const now = new Date();
      lastUpdated.value = now.toLocaleString("id-ID", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
      });
    } else {
      showToastMessage(
        "❌ Gagal memuat data barang: " +
          (response.data.message || "Unknown error")
      );
    }
  } catch (error) {
    if (error.response?.status === 404) {
      showToastMessage("ℹ️ Belum ada data barang yang diinput di lokasi ini");
    } else {
      const errorMsg =
        error.response?.data?.message || "Gagal terhubung ke server";
      showToastMessage(`❌ ${errorMsg}`);
    }
  } finally {
    loadingItemDetails.value = false;
    isLoading.value = false;
  }
}

// EDIT MODE FUNCTIONS

// Fungsi utama untuk membuka modal edit
function openEditModal(item) {
  editingItem.value = {
    ...item,
  };
  showEditModal.value = true;
}

function closeEditModal() {
  showEditModal.value = false;
  editingItem.value = null;
}

// Fungsi untuk handle edit dari berbagai sumber
function handleEditItem(item = null) {
  if (item) {
    openEditModal(item);
    return;
  }

  saveEditedItem();
}

// Fungsi untuk save/edit item dengan versioning
function saveEditedItem() {
  if (
    !editingItem.value.ISITEAMITEMDETAIL_QTY ||
    (editingItem.value.IS_DECIMAL === 1
      ? editingItem.value.ISITEAMITEMDETAIL_QTY < 0
      : editingItem.value.ISITEAMITEMDETAIL_QTY < 1)
  ) {
    showToastMessage("⚠️ Masukkan quantity yang valid");
    return;
  }

  isLoading.value = true;

  try {
    const activeLocationCode = getActiveLocation();
    if (!activeLocationCode) {
      showToastMessage("⚠️ Tidak ada lokasi aktif");
      return;
    }

    if (editingItem.value.source === "manual") {
      updateManualItem(editingItem.value, activeLocationCode);
    } else {
      updateBackendItem(editingItem.value, activeLocationCode);
    }

    // UPDATE SEQ ORDER SETELAH EDIT
    updateSeqOrder();

    showToastMessage(
      `✅ Perubahan berhasil disimpan: ${editingItem.value.ISITEAMITEMDETAIL_QTY}`
    );
  } catch (error) {
    showToastMessage("❌ Gagal menyimpan perubahan");
  } finally {
    setTimeout(() => {
      isLoading.value = false;
      closeEditModal();
    }, 1000);
  }
}

function updateManualItem(editedItem, locationCode) {
  const locationData = getLocationData(locationCode);
  if (!locationData) return;

  const currentData = locationData.manual_items || [];

  const itemIndex = currentData.findIndex(
    (item) => item.ISITEAMITEMDETAIL_ID === editedItem.ISITEAMITEMDETAIL_ID
  );

  if (itemIndex !== -1) {
    const oldItem = { ...currentData[itemIndex] };

    // NILAI LAMA untuk LOG
    const oldQty = parseFloat(oldItem.ISITEAMITEMDETAIL_QTY) || 0;

    // NILAI BARU untuk update
    let newQty;
    const isDecimal = Number(editedItem.IS_DECIMAL) || 0;

    if (isDecimal === 1) {
      const num = parseFloat(editedItem.ISITEAMITEMDETAIL_QTY);
      newQty = isNaN(num) ? 0 : num;
    } else {
      const num = parseInt(editedItem.ISITEAMITEMDETAIL_QTY, 10);
      newQty = isNaN(num) ? 0 : num;
    }

    // SIMPAN ARCHIVE - LOG_NEWQTY = OLDQTY
    if (parseFloat(oldQty) !== parseFloat(newQty)) {
      const existingArchive = locationData.archive_items || [];

      const archiveItem = {
        ISITEAMITEMDETAILLOG_ID: "",
        LOG_BRG_ID: editedItem.BARANG_ID || "",
        LOG_ISITEAMITEMDETAIL_ID: editedItem.ISITEAMITEMDETAIL_ID,
        LOG_NEWQTY: oldQty,
        DATE_CREATE: new Date().toISOString(),
        DATE_MODIFY: null,
        USER_CREATE: locationData.user_id || "system",
        USER_MODIFY: null,
        barang_data: {
          ISITEAMITEMDETAIL_BRG_CODE: editedItem.ISITEAMITEMDETAIL_BRG_CODE,
          ISITEAMITEMDETAIL_BRG_NAME: editedItem.ISITEAMITEMDETAIL_BRG_NAME,
          ISITEAMITEMDETAIL_BRG_BARCODE:
            editedItem.ISITEAMITEMDETAIL_BRG_BARCODE,
          ISITEAMITEMDETAIL_BRG_UOM: editedItem.ISITEAMITEMDETAIL_BRG_UOM,
          IS_DECIMAL: isDecimal,
        },
        _local_new_qty: newQty,
      };

      updateLocationSubData(
        "archive_items",
        [...existingArchive, archiveItem],
        locationCode
      );
    }

    // Update storage dengan nilai baru
    currentData[itemIndex].ISITEAMITEMDETAIL_QTY = newQty;
    currentData[itemIndex].ISITEAMITEMDETAIL_DESC =
      editedItem.ISITEAMITEMDETAIL_DESC || "";
    currentData[itemIndex].timestamp = new Date().toISOString();

    updateLocationSubData("manual_items", currentData, locationCode);

    // Update tampilan dengan nilai baru
    const detailsIndex = itemDetails.value.findIndex(
      (item) => item.ISITEAMITEMDETAIL_ID === editedItem.ISITEAMITEMDETAIL_ID
    );

    if (detailsIndex !== -1) {
      itemDetails.value[detailsIndex].ISITEAMITEMDETAIL_QTY = newQty;
      itemDetails.value[detailsIndex].ISITEAMITEMDETAIL_DESC =
        editedItem.ISITEAMITEMDETAIL_DESC || "";
      itemDetails.value[detailsIndex].timestamp = new Date().toISOString();
      itemDetails.value[detailsIndex].isUpdated = true;

      setTimeout(() => {
        if (itemDetails.value[detailsIndex]) {
          itemDetails.value[detailsIndex].isUpdated = false;
        }
      }, 3000);
    }
  }
}

function updateBackendItem(editedItem, locationCode) {
  const locationData = getLocationData(locationCode);
  if (!locationData) return;

  const currentData = locationData.backend_items || [];

  const itemIndex = currentData.findIndex(
    (item) => item.ISITEAMITEMDETAIL_ID === editedItem.ISITEAMITEMDETAIL_ID
  );

  if (itemIndex !== -1) {
    const oldItem = { ...currentData[itemIndex] };

    // NILAI LAMA dari oldItem - untuk LOG
    const oldQty = parseFloat(oldItem.ISITEAMITEMDETAIL_QTY) || 0;

    // NILAI BARU dari editedItem - untuk update database
    let newQty;
    const isDecimal = Number(editedItem.IS_DECIMAL) || 0;

    if (isDecimal === 1) {
      const num = parseFloat(editedItem.ISITEAMITEMDETAIL_QTY);
      newQty = isNaN(num) ? 0 : num;
    } else {
      const num = parseInt(editedItem.ISITEAMITEMDETAIL_QTY, 10);
      newQty = isNaN(num) ? 0 : num;
    }

    // SIMPAN KE ARCHIVE - LOG_NEWQTY = OLDQTY
    if (parseFloat(oldQty) !== parseFloat(newQty)) {
      const existingArchive = locationData.archive_items || [];

      const archiveItem = {
        ISITEAMITEMDETAILLOG_ID: "",
        LOG_BRG_ID: editedItem.BARANG_ID || "",
        LOG_ISITEAMITEMDETAIL_ID: editedItem.ISITEAMITEMDETAIL_ID,
        LOG_NEWQTY: oldQty,
        DATE_CREATE: new Date().toISOString(),
        DATE_MODIFY: null,
        USER_CREATE: locationData.user_id || "system",
        USER_MODIFY: null,
        barang_data: {
          ISITEAMITEMDETAIL_BRG_CODE: editedItem.ISITEAMITEMDETAIL_BRG_CODE,
          ISITEAMITEMDETAIL_BRG_NAME: editedItem.ISITEAMITEMDETAIL_BRG_NAME,
          ISITEAMITEMDETAIL_BRG_BARCODE:
            editedItem.ISITEAMITEMDETAIL_BRG_BARCODE,
          ISITEAMITEMDETAIL_BRG_UOM: editedItem.ISITEAMITEMDETAIL_BRG_UOM,
          IS_DECIMAL: isDecimal,
        },
        _local_new_qty: newQty,
      };

      updateLocationSubData(
        "archive_items",
        [...existingArchive, archiveItem],
        locationCode
      );
    }

    // UPDATE DATABASE DENGAN NILAI BARU
    currentData[itemIndex].ISITEAMITEMDETAIL_QTY = newQty;
    currentData[itemIndex].ISITEAMITEMDETAIL_DESC =
      editedItem.ISITEAMITEMDETAIL_DESC || "";
    currentData[itemIndex].last_updated = new Date().toISOString();
    currentData[itemIndex].timestamp = new Date().toISOString();

    updateLocationSubData("backend_items", currentData, locationCode);

    // Update tampilan dengan nilai baru
    const detailsIndex = itemDetails.value.findIndex(
      (item) => item.ISITEAMITEMDETAIL_ID === editedItem.ISITEAMITEMDETAIL_ID
    );

    if (detailsIndex !== -1) {
      itemDetails.value[detailsIndex].ISITEAMITEMDETAIL_QTY = newQty;
      itemDetails.value[detailsIndex].ISITEAMITEMDETAIL_DESC =
        editedItem.ISITEAMITEMDETAIL_DESC || "";
      itemDetails.value[detailsIndex].timestamp = new Date().toISOString();
      itemDetails.value[detailsIndex].isUpdated = true;

      setTimeout(() => {
        if (itemDetails.value[detailsIndex]) {
          itemDetails.value[detailsIndex].isUpdated = false;
        }
      }, 3000);
    }
  }
}

// DELETE MODE FUNCTIONS - LANGSUNG UPDATE TAMPILAN
async function deleteSingleItem(item) {
  const confirmDelete = confirm(
    `Apakah Anda yakin ingin menghapus item "${item.ISITEAMITEMDETAIL_BRG_NAME}"?\n\nPenghapusan akan efektif setelah menekan "Save Changes".`
  );
  if (!confirmDelete) return;

  isLoading.value = true;

  try {
    const activeLocationCode = getActiveLocation();
    if (!activeLocationCode) {
      showToastMessage("⚠️ Tidak ada lokasi aktif");
      return;
    }

    // Hapus dari tampilan dan tandai untuk dihapus nanti
    if (item.source === "manual") {
      deleteManualItemFromLocal(item, activeLocationCode);
    } else {
      markBackendItemForDeletion(item, activeLocationCode);
    }

    // UPDATE SEQ ORDER SETELAH HAPUS
    updateSeqOrder();

    showToastMessage(
      `✅ Item "${item.ISITEAMITEMDETAIL_BRG_NAME}" ditandai untuk dihapus. Tekan "Save Changes" untuk mengirim ke server.`
    );
  } catch (error) {
    showToastMessage(
      `❌ Gagal menandai item "${item.ISITEAMITEMDETAIL_BRG_NAME}" untuk dihapus`
    );
  } finally {
    setTimeout(() => {
      isLoading.value = false;
    }, 1000);
  }
}

// FUNGSI: HAPUS ITEM MANUAL DARI LOCAL STORAGE DENGAN UPDATE TABEL LANGSUNG
function deleteManualItemFromLocal(item, locationCode) {
  const locationData = getLocationData(locationCode);
  if (!locationData) return;

  const currentData = locationData.manual_items || [];
  const existingArchive = locationData.archive_items || [];

  // Simpan ke archive sebagai DELETE
  const oldQty = parseFloat(item.ISITEAMITEMDETAIL_QTY) || 0;

  const archiveItem = {
    ISITEAMITEMDETAILLOG_ID: "",
    LOG_BRG_ID: item.BARANG_ID || "",
    LOG_ISITEAMITEMDETAIL_ID: item.ISITEAMITEMDETAIL_ID,
    LOG_NEWQTY: oldQty,
    DATE_CREATE: new Date().toISOString(),
    DATE_MODIFY: null,
    USER_CREATE: locationData.user_id || "system",
    USER_MODIFY: null,
    barang_data: {
      ISITEAMITEMDETAIL_BRG_CODE: item.ISITEAMITEMDETAIL_BRG_CODE,
      ISITEAMITEMDETAIL_BRG_NAME: item.ISITEAMITEMDETAIL_BRG_NAME,
      ISITEAMITEMDETAIL_BRG_BARCODE: item.ISITEAMITEMDETAIL_BRG_BARCODE,
      ISITEAMITEMDETAIL_BRG_UOM: item.ISITEAMITEMDETAIL_BRG_UOM,
      IS_DECIMAL: Number(item.IS_DECIMAL) || 0,
    },
    timestamp: new Date().toISOString(),
    lokasi: lokasi.value,
    kode_team: locationData.kode_team,
    source: "manual",
    action_type: "DELETE",
    delete_marker: true,
    deleted_item_id: item.ISITEAMITEMDETAIL_ID,
  };

  // Update archive
  updateLocationSubData(
    "archive_items",
    [...existingArchive, archiveItem],
    locationCode
  );

  // Hapus dari manual_items
  const filteredData = currentData.filter(
    (manualItem) =>
      manualItem.ISITEAMITEMDETAIL_ID !== item.ISITEAMITEMDETAIL_ID
  );

  updateLocationSubData("manual_items", filteredData, locationCode);

  // Langsung update itemDetails.value - Hapus dari tampilan
  itemDetails.value = itemDetails.value.filter(
    (detailItem) =>
      detailItem.ISITEAMITEMDETAIL_ID !== item.ISITEAMITEMDETAIL_ID
  );

  // UPDATE SEQ ORDER SETELAH HAPUS
  setTimeout(() => {
    updateSeqOrder();
  }, 100);
}

// FUNGSI: TANDAI ITEM BACKEND UNTUK DIHAPUS DENGAN UPDATE TABEL LANGSUNG
function markBackendItemForDeletion(item, locationCode) {
  const locationData = getLocationData(locationCode);
  if (!locationData) return;

  const existingArchive = locationData.archive_items || [];

  // Simpan ke archive sebagai DELETE
  const oldQty = parseFloat(item.ISITEAMITEMDETAIL_QTY) || 0;

  const archiveItem = {
    ISITEAMITEMDETAILLOG_ID: "",
    LOG_BRG_ID: item.BARANG_ID || "",
    LOG_ISITEAMITEMDETAIL_ID: item.ISITEAMITEMDETAIL_ID,
    LOG_NEWQTY: oldQty,
    DATE_CREATE: new Date().toISOString(),
    DATE_MODIFY: null,
    USER_CREATE: locationData.user_id || "system",
    USER_MODIFY: null,
    barang_data: {
      ISITEAMITEMDETAIL_BRG_CODE: item.ISITEAMITEMDETAIL_BRG_CODE,
      ISITEAMITEMDETAIL_BRG_NAME: item.ISITEAMITEMDETAIL_BRG_NAME,
      ISITEAMITEMDETAIL_BRG_BARCODE: item.ISITEAMITEMDETAIL_BRG_BARCODE,
      ISITEAMITEMDETAIL_BRG_UOM: item.ISITEAMITEMDETAIL_BRG_UOM,
      IS_DECIMAL: Number(item.IS_DECIMAL) || 0,
    },
    timestamp: new Date().toISOString(),
    lokasi: lokasi.value,
    kode_team: locationData.kode_team,
    source: "backend",
    action_type: "DELETE",
    delete_marker: true,
    deleted_item_id: item.ISITEAMITEMDETAIL_ID,
  };

  // Update archive
  updateLocationSubData(
    "archive_items",
    [...existingArchive, archiveItem],
    locationCode
  );

  // Langsung update itemDetails.value - Hapus dari tampilan
  itemDetails.value = itemDetails.value.filter(
    (detailItem) =>
      detailItem.ISITEAMITEMDETAIL_ID !== item.ISITEAMITEMDETAIL_ID
  );

  // Tandai item sebagai dihapus di backend_items
  const currentBackendData = locationData.backend_items || [];
  const updatedBackendData = currentBackendData.map((backendItem) => {
    if (backendItem.ISITEAMITEMDETAIL_ID === item.ISITEAMITEMDETAIL_ID) {
      return {
        ...backendItem,
        marked_for_deletion: true,
        deletion_timestamp: new Date().toISOString(),
      };
    }
    return backendItem;
  });

  updateLocationSubData("backend_items", updatedBackendData, locationCode);

  // UPDATE SEQ ORDER SETELAH HAPUS
  setTimeout(() => {
    updateSeqOrder();
  }, 100);
}

// NOTE VIEW MODAL
function openNoteViewModal(item) {
  currentNoteView.value = item.ISITEAMITEMDETAIL_DESC || "";
  showNoteViewModal.value = true;
}

function closeNoteViewModal() {
  showNoteViewModal.value = false;
  currentNoteView.value = "";
}

// CHANGE LOCATION FUNCTIONS
function formatLocationInput(event) {
  const input = event.target.value;

  let cleanInput = input.replace(/[^\d.]/g, "");

  if (input.length < newLocationInput.value.length) {
    newLocationInput.value = cleanInput;
    return;
  }

  const numbersOnly = cleanInput.replace(/\./g, "");

  let formatted = "";

  if (numbersOnly.length > 0) {
    formatted = numbersOnly.substring(0, 1);
  }
  if (numbersOnly.length > 1) {
    formatted += "." + numbersOnly.substring(1, 3);
  }
  if (numbersOnly.length > 3) {
    formatted += "." + numbersOnly.substring(3, 6);
  }

  newLocationInput.value = formatted;
}

function handleLocationKeydown(event) {
  if (event.key === "Backspace" || event.key === "Delete") {
    setTimeout(() => {
      const currentValue = newLocationInput.value;
      const numbersOnly = currentValue.replace(/\./g, "");

      if (numbersOnly.length > 0) {
        let formatted = "";

        if (numbersOnly.length > 0) {
          formatted = numbersOnly.substring(0, 1);
        }
        if (numbersOnly.length > 1) {
          formatted += "." + numbersOnly.substring(1, 3);
        }
        if (numbersOnly.length > 3) {
          formatted += "." + numbersOnly.substring(3, 6);
        }

        newLocationInput.value = formatted;
      }
    }, 10);
  }
}

function openChangeLocationModal() {
  newLocationInput.value = "";
  showChangeLocationModal.value = true;

  nextTick(() => {
    const input = document.querySelector('input[v-model="newLocationInput"]');
    if (input) {
      input.focus();
    }
  });
}

function closeChangeLocationModal() {
  showChangeLocationModal.value = false;
  newLocationInput.value = "";
}

async function handleLocationSubmit() {
  const locationCode = newLocationInput.value.trim();

  if (!locationCode) {
    showToastMessage("⚠️ Masukkan kode lokasi terlebih dahulu");
    return;
  }

  if (!locationCode.match(/^\d\.\d{2}(\.\d{1,3})?$/)) {
    showToastMessage("⚠️ Format lokasi tidak valid. Contoh: 1.04.060");
    return;
  }

  await changeLocation(locationCode);
}

// FUNGSI CHANGE LOCATION
async function changeLocation(newLocationCode) {
  // Ambil data lokasi aktif saat ini untuk mendapatkan token
  const currentLocationCode = getActiveLocation();
  if (!currentLocationCode) {
    showToastMessage("⚠️ Tidak ada lokasi aktif");
    return;
  }

  const currentLocationData = getLocationData(currentLocationCode);
  if (!currentLocationData) {
    showToastMessage("⚠️ Data lokasi saat ini tidak ditemukan");
    return;
  }

  const token = currentLocationData.token;

  if (!token) {
    showToastMessage("⚠️ Sesi login sudah berakhir. Silakan login kembali.");
    window.location.href = "/";
    return;
  }

  if (!currentLocationData.kode_team) {
    showToastMessage("⚠️ Kode team tidak ditemukan. Silakan login ulang.");
    return;
  }

  isLoading.value = true;

  try {
    const response = await axios.post(
      `${import.meta.env.VITE_BACKEND}/api/lokasi/password`,
      {
        kode_team: currentLocationData.kode_team,
        kode_lokasi: newLocationCode,
      },
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    );

    const data = response.data;

    if (!data.success) {
      showToastMessage("❌ " + (data.message || "Kode lokasi atau team salah"));
      return;
    }

    const { penghitung_1, penghitung_2, kode_team } = data.data;

    // Set lokasi aktif baru di sessionStorage
    setActiveLocation(newLocationCode);

    // Buat data baru untuk lokasi ini
    updateLocationData(
      {
        kode_lokasi: newLocationCode,
        kode_team: kode_team,
        penghitung_1: penghitung_1,
        penghitung_2: penghitung_2,
        token: token,
        backend_items: [],
        manual_items: [],
        archive_items: [],
        original_data: [],
        seq_order: {},
        team_verified_at: new Date().toISOString(),
        login_time: new Date().toISOString(),
      },
      newLocationCode
    );

    // Langsung update state dan hapus data lama dari tampilan
    lokasi.value = newLocationCode;
    penghitung1.value = penghitung_1;
    penghitung2.value = penghitung_2;

    // Kosongkan itemDetails.value agar tabel langsung kosong
    itemDetails.value = [];

    showToastMessage(`✅ Berhasil pindah ke lokasi: ${newLocationCode}`);

    // Tunggu sejenak lalu fetch data baru
    setTimeout(() => {
      fetchItemDetails();
      closeChangeLocationModal();
    }, 500);
  } catch (error) {
    showToastMessage(
      "❌ Gagal memvalidasi lokasi. Pastikan kode lokasi benar."
    );
    throw error;
  } finally {
    isLoading.value = false;
  }
}

// LOCATION SCANNER FUNCTIONS
function openLocationScanner() {
  showLocationScanner.value = true;
  startLocationCamera();
}

function closeLocationScanner() {
  showLocationScanner.value = false;
  stopLocationCamera();
}

async function startLocationCamera() {
  try {
    locationStream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: "environment" },
    });
    if (locationScannerVideo.value) {
      locationScannerVideo.value.srcObject = locationStream;
    }
    startLocationAutoScan();
  } catch (err) {
    showToastMessage(
      "❌ Tidak bisa mengakses kamera. Pastikan izin kamera diaktifkan."
    );
  }
}

async function stopLocationCamera() {
  try {
    if (locationCodeReader?.controls) locationCodeReader.controls.stop();
    locationCodeReader = null;

    if (locationStream) {
      locationStream.getTracks().forEach((track) => track.stop());
      locationStream = null;
    }

    if (locationScannerVideo.value) {
      const video = locationScannerVideo.value;
      video.pause();
      video.srcObject = null;
      video.removeAttribute("src");
      video.load();
    }

    locationScanning = false;
  } catch (err) {
    // Error handling
  }
}

async function startLocationAutoScan() {
  if (locationScanning) return;
  locationScanning = true;

  locationCodeReader = new BrowserMultiFormatReader();
  locationCodeReader.setHints(hints);

  try {
    const controls = await locationCodeReader.decodeFromVideoDevice(
      undefined,
      locationScannerVideo.value,
      (result, err) => {
        if (result) {
          const scannedLocation = result.getText().trim();
          newLocationInput.value = scannedLocation;
          showToastMessage(
            "✅ Kode lokasi berhasil di-scan: " + scannedLocation
          );
          controls.stop();
          locationScanning = false;
          closeLocationScanner();
        }
      }
    );
    locationCodeReader.controls = controls;
  } catch (error) {
    showToastMessage("❌ Tidak dapat memulai scanner lokasi.");
    locationScanning = false;
  }
}

// QTY MODAL FUNCTIONS
function openQtyModal(itemData) {
  const existingItem = sortedItems.value.find(
    (i) => i.ISITEAMITEMDETAIL_BRG_CODE === itemData.originalPlu
  );

  if (existingItem) {
    currentItem.value = {
      plu: itemData.plu,
      originalPlu: itemData.originalPlu,
      barcode: itemData.barcode || "-",
      nama: itemData.nama || "Barang Tidak Dikenal",
      uom: itemData.uom || "",
      qty:
        existingItem.ISITEAMITEMDETAIL_QTY ||
        (itemData.is_decimal === 1 ? "0.000" : "1"),
      desc: existingItem.ISITEAMITEMDETAIL_DESC || "",
      is_decimal: itemData.is_decimal || 0,
    };
    showToastMessage(
      "📦 Barang sudah ada di tabel, quantity: " +
        existingItem.ISITEAMITEMDETAIL_QTY
    );
  } else {
    currentItem.value = {
      plu: itemData.plu,
      originalPlu: itemData.originalPlu,
      barcode: itemData.barcode || "-",
      nama: itemData.nama || "Barang Tidak Dikenal",
      uom: itemData.uom || "",
      qty: itemData.is_decimal === 1 ? "0.000" : "1",
      desc: "",
      is_decimal: itemData.is_decimal || 0,
    };
  }
  showQtyModal.value = true;
}

function closeQtyModal() {
  showQtyModal.value = false;
  currentItem.value = {
    plu: "",
    barcode: "",
    nama: "",
    uom: "",
    qty: 1,
    desc: "",
    is_decimal: 0,
  };
  clearInput();
}

// FUNGSI: ADD ITEM TO TABLE DENGAN ARCHIVE CREATE DAN UPDATE SEQ
function addItemToTable() {
  // Validasi berdasarkan IS_DECIMAL
  if (!currentItem.value.qty) {
    showToastMessage("⚠️ Masukkan quantity yang valid");
    return;
  }

  const isDecimal = Number(currentItem.value.is_decimal) || 0;

  if (isDecimal === 1) {
    // Untuk desimal: tidak boleh negatif
    if (currentItem.value.qty < 0) {
      showToastMessage("⚠️ Quantity tidak boleh negatif untuk barang desimal");
      return;
    }
  } else {
    // Untuk non-desimal: minimal 1
    if (currentItem.value.qty < 1) {
      showToastMessage("⚠️ Quantity minimal 1 untuk barang non-desimal");
      return;
    }
  }

  isLoading.value = true;

  try {
    const activeLocationCode = getActiveLocation();
    if (!activeLocationCode) {
      showToastMessage("⚠️ Tidak ada lokasi aktif");
      return;
    }

    // Cek apakah item sudah ada
    const existingIndex = sortedItems.value.findIndex(
      (i) => i.ISITEAMITEMDETAIL_BRG_CODE === currentItem.value.originalPlu
    );

    if (existingIndex !== -1) {
      // Item sudah ada - update berdasarkan source
      const existingItem = sortedItems.value[existingIndex];

      if (existingItem.source === "manual") {
        updateExistingManualItem(existingItem, activeLocationCode);
      } else {
        updateExistingBackendItem(existingItem, activeLocationCode);
      }
    } else {
      // Item baru - tambahkan ke storage manual dengan CREATE archive
      addNewManualItemWithSeq(activeLocationCode);
    }

    // UPDATE SEQ ORDER SETELAH MENAMBAH ITEM BARU
    updateSeqOrder();

    showToastMessage(`✅ Barang "${currentItem.value.nama}" berhasil diproses`);
  } catch (error) {
    showToastMessage("❌ Gagal menambahkan item ke tabel");
  } finally {
    setTimeout(() => {
      isLoading.value = false;
      closeQtyModal();
    }, 1000);
  }
}

// FUNGSI: UPDATE ITEM MANUAL YANG SUDAH ADA DENGAN ARCHIVE BARU
function updateExistingManualItem(existingItem, locationCode) {
  const locationData = getLocationData(locationCode);
  if (!locationData) return;

  const currentData = locationData.manual_items || [];
  const itemIndexInStorage = currentData.findIndex(
    (item) => item.ISITEAMITEMDETAIL_ID === existingItem.ISITEAMITEMDETAIL_ID
  );

  if (itemIndexInStorage !== -1) {
    const oldItem = { ...currentData[itemIndexInStorage] };

    // Format quantity berdasarkan IS_DECIMAL dengan lebih ketat
    let newQty;
    const isDecimal = Number(currentItem.value.is_decimal) || 0;

    if (isDecimal === 1) {
      const num = parseFloat(currentItem.value.qty);
      newQty = isNaN(num) ? 0 : num;
    } else {
      const num = parseInt(currentItem.value.qty, 10);
      newQty = isNaN(num) ? 0 : num;
    }

    // Validasi minimal qty
    if (isDecimal === 1 && newQty < 0) {
      showToastMessage("⚠️ Quantity tidak boleh negatif untuk barang desimal");
      return;
    } else if (isDecimal === 0 && newQty < 1) {
      showToastMessage("⚠️ Quantity minimal 1 untuk barang non-desimal");
      return;
    }

    const oldQty = parseFloat(oldItem.ISITEAMITEMDETAIL_QTY) || 0;

    // SIMPAN PERUBAHAN KE ARCHIVE DENGAN STRUKTUR BARU
    if (oldQty > 0 && oldQty !== newQty) {
      const existingArchive = locationData.archive_items || [];

      const archiveItem = {
        ISITEAMITEMDETAILLOG_ID: "",
        LOG_BRG_ID: oldItem.BARANG_ID || "",
        LOG_ISITEAMITEMDETAIL_ID: oldItem.ISITEAMITEMDETAIL_ID,
        LOG_NEWQTY: newQty,
        DATE_CREATE: new Date().toISOString(),
        DATE_MODIFY: null,
        USER_CREATE: locationData.user_id || "system",
        USER_MODIFY: null,
        barang_data: {
          ISITEAMITEMDETAIL_BRG_CODE: oldItem.ISITEAMITEMDETAIL_BRG_CODE,
          ISITEAMITEMDETAIL_BRG_NAME: oldItem.ISITEAMITEMDETAIL_BRG_NAME,
          ISITEAMITEMDETAIL_BRG_BARCODE: oldItem.ISITEAMITEMDETAIL_BRG_BARCODE,
          ISITEAMITEMDETAIL_BRG_UOM: oldItem.ISITEAMITEMDETAIL_BRG_UOM,
          IS_DECIMAL: oldItem.IS_DECIMAL || 0,
        },
        old_data: {
          ISITEAMITEMDETAIL_QTY: oldQty,
          ISITEAMITEMDETAIL_DESC: oldItem.ISITEAMITEMDETAIL_DESC || "",
        },
        timestamp: new Date().toISOString(),
        lokasi: lokasi.value,
        kode_team: locationData.kode_team,
        source: "manual",
        change_type: "updated",
      };

      updateLocationSubData(
        "archive_items",
        [...existingArchive, archiveItem],
        locationCode
      );
    }

    const oldQtyDisplay = currentData[itemIndexInStorage].ISITEAMITEMDETAIL_QTY;

    // Update data di manual_items
    currentData[itemIndexInStorage].ISITEAMITEMDETAIL_QTY = newQty;
    currentData[itemIndexInStorage].ISITEAMITEMDETAIL_DESC =
      currentItem.value.desc ||
      currentData[itemIndexInStorage].ISITEAMITEMDETAIL_DESC;
    currentData[itemIndexInStorage].timestamp = new Date().toISOString();
    updateLocationSubData("manual_items", currentData, locationCode);

    // Update tampilan
    const itemIndex = itemDetails.value.findIndex(
      (item) => item.ISITEAMITEMDETAIL_ID === existingItem.ISITEAMITEMDETAIL_ID
    );

    if (itemIndex !== -1) {
      itemDetails.value[itemIndex].ISITEAMITEMDETAIL_QTY = newQty;
      itemDetails.value[itemIndex].ISITEAMITEMDETAIL_DESC =
        currentItem.value.desc ||
        itemDetails.value[itemIndex].ISITEAMITEMDETAIL_DESC;
      itemDetails.value[itemIndex].timestamp = new Date().toISOString();
      itemDetails.value[itemIndex].isUpdated = true;

      setTimeout(() => {
        if (itemDetails.value[itemIndex]) {
          itemDetails.value[itemIndex].isUpdated = false;
        }
      }, 3000);
    }

    showToastMessage(
      `📦 Qty barang "${currentItem.value.nama}" diperbarui: ${oldQtyDisplay} → ${currentItem.value.qty}`
    );
  }

  // UPDATE SEQ ORDER SETELAH EDIT
  setTimeout(() => {
    updateSeqOrder();
  }, 100);
}

// FUNGSI: UPDATE ITEM BACKEND YANG SUDAH ADA DENGAN ARCHIVE BARU
function updateExistingBackendItem(existingItem, locationCode) {
  const locationData = getLocationData(locationCode);
  if (!locationData) return;

  const currentData = locationData.backend_items || [];
  const itemIndexInStorage = currentData.findIndex(
    (item) => item.ISITEAMITEMDETAIL_ID === existingItem.ISITEAMITEMDETAIL_ID
  );

  if (itemIndexInStorage !== -1) {
    const oldItem = { ...currentData[itemIndexInStorage] };

    // Format quantity berdasarkan IS_DECIMAL dengan lebih ketat
    let newQty;
    const isDecimal = Number(currentItem.value.is_decimal) || 0;

    if (isDecimal === 1) {
      const num = parseFloat(currentItem.value.qty);
      newQty = isNaN(num) ? 0 : num;
    } else {
      const num = parseInt(currentItem.value.qty, 10);
      newQty = isNaN(num) ? 0 : num;
    }

    // Validasi minimal qty
    if (isDecimal === 1 && newQty < 0) {
      showToastMessage("⚠️ Quantity tidak boleh negatif untuk barang desimal");
      return;
    } else if (isDecimal === 0 && newQty < 1) {
      showToastMessage("⚠️ Quantity minimal 1 untuk barang non-desimal");
      return;
    }

    const oldQty = parseFloat(oldItem.ISITEAMITEMDETAIL_QTY) || 0;

    // SIMPAN KE ARCHIVE DENGAN STRUKTUR BARU
    if (oldQty > 0 && oldQty !== newQty) {
      const existingArchive = locationData.archive_items || [];

      const archiveItem = {
        ISITEAMITEMDETAILLOG_ID: "",
        LOG_BRG_ID: oldItem.BARANG_ID || "",
        LOG_ISITEAMITEMDETAIL_ID: oldItem.ISITEAMITEMDETAIL_ID,
        LOG_NEWQTY: newQty,
        DATE_CREATE: new Date().toISOString(),
        DATE_MODIFY: null,
        USER_CREATE: locationData.user_id || "system",
        USER_MODIFY: null,
        barang_data: {
          ISITEAMITEMDETAIL_BRG_CODE: oldItem.ISITEAMITEMDETAIL_BRG_CODE,
          ISITEAMITEMDETAIL_BRG_NAME: oldItem.ISITEAMITEMDETAIL_BRG_NAME,
          ISITEAMITEMDETAIL_BRG_BARCODE: oldItem.ISITEAMITEMDETAIL_BRG_BARCODE,
          ISITEAMITEMDETAIL_BRG_UOM: oldItem.ISITEAMITEMDETAIL_BRG_UOM,
          IS_DECIMAL: oldItem.IS_DECIMAL || 0,
        },
        old_data: {
          ISITEAMITEMDETAIL_QTY: oldQty,
          ISITEAMITEMDETAIL_DESC: oldItem.ISITEAMITEMDETAIL_DESC || "",
        },
        timestamp: new Date().toISOString(),
        lokasi: lokasi.value,
        kode_team: locationData.kode_team,
        source: "backend",
        change_type: "updated",
      };

      updateLocationSubData(
        "archive_items",
        [...existingArchive, archiveItem],
        locationCode
      );
    }

    currentData[itemIndexInStorage].ISITEAMITEMDETAIL_QTY = newQty;
    currentData[itemIndexInStorage].ISITEAMITEMDETAIL_DESC =
      currentItem.value.desc ||
      currentData[itemIndexInStorage].ISITEAMITEMDETAIL_DESC;
    currentData[itemIndexInStorage].timestamp = new Date().toISOString();
    updateLocationSubData("backend_items", currentData, locationCode);

    const itemIndex = itemDetails.value.findIndex(
      (item) => item.ISITEAMITEMDETAIL_ID === existingItem.ISITEAMITEMDETAIL_ID
    );

    if (itemIndex !== -1) {
      itemDetails.value[itemIndex].ISITEAMITEMDETAIL_QTY = newQty;
      itemDetails.value[itemIndex].ISITEAMITEMDETAIL_DESC =
        currentItem.value.desc ||
        itemDetails.value[itemIndex].ISITEAMITEMDETAIL_DESC;
      itemDetails.value[itemIndex].timestamp = new Date().toISOString();
      itemDetails.value[itemIndex].isUpdated = true;

      setTimeout(() => {
        if (itemDetails.value[itemIndex]) {
          itemDetails.value[itemIndex].isUpdated = false;
        }
      }, 3000);
    }

    showToastMessage(
      `📦 Qty barang "${currentItem.value.nama}" diperbarui: ${oldQty} → ${currentItem.value.qty}`
    );
  }

  // UPDATE SEQ ORDER SETELAH EDIT
  setTimeout(() => {
    updateSeqOrder();
  }, 100);
}

// FUNGSI: TAMBAH ITEM BARU KE MANUAL_ITEMS DENGAN UPDATE TABEL LANGSUNG
function addNewManualItemWithSeq(locationCode) {
  const locationData = getLocationData(locationCode);
  if (!locationData) return;

  // Format quantity berdasarkan IS_DECIMAL dengan lebih ketat
  let quantity;
  const isDecimal = Number(currentItem.value.is_decimal) || 0;

  if (isDecimal === 1) {
    // Untuk desimal: parseFloat dan pastikan 3 digit
    const num = parseFloat(currentItem.value.qty);
    quantity = isNaN(num) ? 0 : num.toFixed(3);
  } else {
    // Untuk non-desimal: parseInt (bulat)
    const num = parseInt(currentItem.value.qty, 10);
    quantity = isNaN(num) ? 0 : num;
  }

  const newItemId = `manual-${Date.now()}`;
  const timestamp = new Date().toISOString();

  const newItem = {
    ISITEAMITEMDETAIL_ID: newItemId,
    ISITEAMITEMDETAIL_BRG_CODE: currentItem.value.originalPlu,
    ISITEAMITEMDETAIL_BRG_BARCODE: currentItem.value.barcode,
    ISITEAMITEMDETAIL_BRG_NAME: currentItem.value.nama,
    ISITEAMITEMDETAIL_BRG_UOM: currentItem.value.uom,
    ISITEAMITEMDETAIL_QTY: quantity,
    ISITEAMITEMDETAIL_DESC: currentItem.value.desc || "",
    ISITEAMITEMDETAIL_STATUS: "active",
    IS_DECIMAL: currentItem.value.is_decimal,
    timestamp: timestamp,
    source: "manual",
    isNew: true,
  };

  // Simpan ke manual_items
  const existingManualItems = locationData.manual_items || [];
  const updatedManualItems = [newItem, ...existingManualItems];
  updateLocationSubData("manual_items", updatedManualItems, locationCode);

  // Langsung tambahkan ke itemDetails untuk tampilan
  itemDetails.value.unshift(newItem);

  // Update SEQ order setelah menambah item
  updateSeqOrder();

  // Highlight item baru
  setTimeout(() => {
    const itemIndex = itemDetails.value.findIndex(
      (item) => item.ISITEAMITEMDETAIL_ID === newItemId
    );
    if (itemIndex !== -1) {
      itemDetails.value[itemIndex].isNew = false;
    }
  }, 3000);

  showToastMessage(
    `✅ Barang "${newItem.ISITEAMITEMDETAIL_BRG_NAME}" berhasil ditambahkan (Data Manual)`
  );
}

// KEYBOARD SHORTCUTS
function handleGlobalKeydown(event) {
  const isInModal =
    event.target.closest(".modal") ||
    showEditModal.value ||
    showQtyModal.value ||
    showChangeLocationModal.value ||
    showArchiveModal.value;

  if (isInModal) return;

  if (event.target.tagName === "INPUT" || event.target.tagName === "TEXTAREA") {
    return;
  }

  if ((event.ctrlKey && event.key === "k") || event.key === "/") {
    event.preventDefault();
    if (barcodeInputRef.value) {
      barcodeInputRef.value.focus();
      showToastMessage("⌨️ Fokus ke input barcode");
    }
  }
}

// TOAST MESSAGE
function showToastMessage(message) {
  toastMessage.value = message;
  showToast.value = true;

  setTimeout(() => {
    showToast.value = false;
  }, 4000);
}

// AXIOS INTERCEPTOR - CEK JWT EXPIRED
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      showToastMessage(
        "⚠️ Sesi login Anda telah berakhir. Silakan login kembali."
      );

      // Hapus semua location_data yang terkait dengan user ini
      const activeLocationCode = getActiveLocation();
      if (activeLocationCode) {
        clearLocationData(activeLocationCode);
      }

      // Hapus sessionStorage
      sessionStorage.removeItem("active_location");

      // Reset state
      lokasi.value = "";
      penghitung1.value = "";
      penghitung2.value = "";
      itemDetails.value = [];

      setTimeout(() => {
        window.location.href = "/";
      }, 1500);
    }
    return Promise.reject(error);
  }
);

// FUNGSI: HAPUS DATA LOKASI
function clearLocationData(locationCode) {
  const storageKey = getStorageKey(locationCode);
  if (!storageKey) return;

  localStorage.removeItem(storageKey);
}

// INPUT HANDLERS
function handleInputChange() {
  nextTick(() => {});
}

function clearInput() {
  barcodeInput.value = "";
  if (barcodeInputRef.value) {
    barcodeInputRef.value.focus();
  }
  showToastMessage("🗑️ Input berhasil dibersihkan");
}

// HANDLE SUBMIT BARCODE / PLU
async function handleSubmit() {
  const kode = barcodeInput.value.trim();

  if (!kode) {
    showToastMessage("📝 Masukkan kode barcode atau PLU terlebih dahulu");
    return;
  }

  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode) {
    showToastMessage("⚠️ Tidak ada lokasi aktif");
    return;
  }

  const locationData = getLocationData(activeLocationCode);
  if (!locationData) {
    showToastMessage("⚠️ Data lokasi tidak ditemukan");
    return;
  }

  const token = locationData.token;

  if (!token) {
    showToastMessage("⚠️ Sesi login sudah berakhir. Silakan login kembali.");
    window.location.href = "/";
    return;
  }

  isLoading.value = true;

  try {
    showToastMessage("⏳ Mencari data barang...");

    let searchCode = kode;
    if (/^\d+$/.test(kode)) {
      const formattedPLU = kode.padStart(6, "0");
      searchCode = formattedPLU;
    }

    const res = await axios.post(
      `${import.meta.env.VITE_BACKEND}/api/lokasi/barang`,
      { kode: searchCode },
      { headers: { Authorization: `Bearer ${token}` } }
    );

    if (res.data && res.data.success) {
      const data = res.data.data;

      barcodeInput.value = "";

      const formattedPLU = formatPLUForDisplay(data.kode_plu);

      openQtyModal({
        plu: formattedPLU,
        originalPlu: data.kode_plu,
        barcode: data.kode_barcode || "-",
        nama: data.nama_barang || "Barang Tidak Dikenal",
        uom: data.uom || "",
        is_decimal: data.is_decimal || 0,
      });
    } else {
      const msg = res.data?.message || "⚠️ Barang tidak ditemukan di sistem";
      showToastMessage(`⚠️ ${msg}`);
    }
  } catch (err) {
    if (err.response?.status === 404) {
      showToastMessage("⚠️ Barang tersebut tidak ditemukan.");
    }

    if (
      err.response?.status === 404 &&
      /^\d+$/.test(kode) &&
      kode !== searchCode
    ) {
      try {
        const retryRes = await axios.post(
          `${import.meta.env.VITE_BACKEND}/api/lokasi/barang`,
          { kode: kode },
          { headers: { Authorization: `Bearer ${token}` } }
        );

        if (retryRes.data && retryRes.data.success) {
          const data = retryRes.data.data;

          barcodeInput.value = "";

          const formattedPLU = formatPLUForDisplay(data.kode_plu);

          openQtyModal({
            plu: formattedPLU,
            originalPlu: data.kode_plu,
            barcode: data.kode_barcode || "-",
            nama: data.nama_barang || "Barang Tidak Dikenal",
            uom: data.uom || "",
            is_decimal: data.is_decimal || 0,
          });
          return;
        }
      } catch (retryErr) {
        // Gagal juga dengan kode asli
      }
    }

    if (err.response?.status === 403) {
      const msg = err.response.data?.message || "Barang tidak boleh diupdate";
      showToastMessage(`⚠️ ${msg}`);
    } else {
      const msg =
        err.response?.data?.message ||
        "Terjadi kesalahan saat mengambil data barang.";
      showToastMessage(`❌ ${msg}`);
    }
  } finally {
    isLoading.value = false;
  }
}

// FORMAT PLU UNTUK TAMPILAN DI TABEL
function formatPLUForDisplay(plu) {
  if (!plu) return plu;

  if (/^\d+$/.test(plu) && plu.length <= 6) {
    return plu.padStart(6, "0");
  }

  return plu;
}

// SCANNER CONTROLS
function toggleScanner() {
  if (showScanner.value) closeScanner();
  else openScanner();
}

function openScanner() {
  showScanner.value = true;
  startCamera();
}

function closeScanner() {
  showScanner.value = false;
  stopCamera();
}

// CAMERA CONTROLS
let stream = null;
let locationStream = null;
let codeReader = null;
let locationCodeReader = null;
let scanning = false;
let locationScanning = false;

async function startCamera() {
  try {
    stream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: "environment" },
    });
    if (scannerVideo.value) {
      scannerVideo.value.srcObject = stream;
    }
    startAutoScan();
  } catch (err) {
    showToastMessage(
      "❌ Tidak bisa mengakses kamera. Pastikan izin kamera diaktifkan."
    );
  }
}

async function stopCamera() {
  try {
    if (codeReader?.controls) codeReader.controls.stop();
    codeReader = null;

    if (stream) {
      stream.getTracks().forEach((track) => track.stop());
      stream = null;
    }

    if (scannerVideo.value) {
      const video = scannerVideo.value;
      video.pause();
      video.srcObject = null;
      video.removeAttribute("src");
      video.load();
    }

    scanning = false;
  } catch (err) {
    // Error handling
  }
}

// AUTO SCAN BARCODE
const hints = new Map();
hints.set(DecodeHintType.POSSIBLE_FORMATS, [
  BarcodeFormat.CODE_128,
  BarcodeFormat.EAN_13,
  BarcodeFormat.EAN_8,
  BarcodeFormat.UPC_A,
  BarcodeFormat.UPC_E,
  BarcodeFormat.CODE_39,
  BarcodeFormat.CODE_93,
  BarcodeFormat.CODABAR,
  BarcodeFormat.ITF,
  BarcodeFormat.RSS_14,
  BarcodeFormat.RSS_EXPANDED,
  BarcodeFormat.UPC_EAN_EXTENSION,
  BarcodeFormat.AZTEC,
  BarcodeFormat.DATA_MATRIX,
  BarcodeFormat.MAXICODE,
  BarcodeFormat.PDF_417,
  BarcodeFormat.QR_CODE,
]);

async function startAutoScan() {
  if (scanning) return;
  scanning = true;

  codeReader = new BrowserMultiFormatReader();
  codeReader.setHints(hints);

  try {
    const controls = await codeReader.decodeFromVideoDevice(
      undefined,
      scannerVideo.value,
      (result, err) => {
        if (result) {
          barcodeInput.value = result.getText().trim();
          showToastMessage(
            "✅ Barcode berhasil di-scan: " + barcodeInput.value
          );
          handleSubmit();
          controls.stop();
          scanning = false;
          closeScanner();
        }
      }
    );
    codeReader.controls = controls;
  } catch (error) {
    showToastMessage("❌ Tidak dapat memulai scanner.");
    scanning = false;
  }
}

// FETCH DATA DARI API (JWT)
function fetchData() {
  // Ambil lokasi aktif dari sessionStorage
  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode) {
    showToastMessage(
      "⚠️ Tidak ada lokasi aktif. Silakan scan lokasi terlebih dahulu."
    );
    setTimeout(() => {
      window.location.href = "/";
    }, 1000);
    return;
  }

  // Update lokasi.value dengan kode lokasi aktif
  lokasi.value = activeLocationCode;

  // Load data dari localStorage untuk lokasi ini
  loadDataFromLocationData();

  // Ambil data dari backend
  fetchItemDetails();

  showToastMessage(
    `📍 Lokasi: ${lokasi.value} | 👥 Penghitung: ${penghitung1.value} & ${penghitung2.value}`
  );
}

// LOGOUT
async function logout() {
  const confirmLogout = confirm("Apakah Anda yakin ingin mengakhiri sesi ini?");
  if (!confirmLogout) return;

  isLoading.value = true;

  // Ambil lokasi aktif
  const activeLocationCode = getActiveLocation();

  try {
    if (activeLocationCode) {
      // Ambil data lokasi untuk mendapatkan token
      const locationData = getLocationData(activeLocationCode);
      const token = locationData?.token;

      if (token) {
        try {
          await axios.post(
            `${import.meta.env.VITE_BACKEND}/api/lokasi/logout`,
            {},
            { headers: { Authorization: `Bearer ${token}` } }
          );
        } catch (err) {
          // Gagal logout dari server
        }

        // HAPUS TOKEN DARI LOCATION_DATA
        removeTokenFromLocation(activeLocationCode);
      }
    }

    // Hapus sessionStorage
    sessionStorage.removeItem("active_location");

    // Reset state
    itemDetails.value = [];
    lokasi.value = "";
    penghitung1.value = "";
    penghitung2.value = "";

    showToastMessage("✅ Anda berhasil logout dari sistem.");

    setTimeout(() => {
      router.push("/");
    }, 400);
  } finally {
    isLoading.value = false;
  }
}

// FUNGSI: HAPUS TOKEN DARI LOCATION_DATA
function removeTokenFromLocation(locationCode) {
  try {
    const storageKey = getStorageKey(locationCode);
    if (!storageKey) {
      return;
    }

    const locationData = getLocationData(locationCode);
    if (!locationData) {
      return;
    }

    // Hapus token dari location_data
    delete locationData.token;
    delete locationData.kode_team;
    delete locationData.kode_lokasi;
    delete locationData.penghitung_1;
    delete locationData.penghitung_2;

    // Simpan kembali data tanpa token
    localStorage.setItem(storageKey, JSON.stringify(locationData));
  } catch (error) {
    // Error handling
  }
}

// SAVE PERUBAHAN KE SERVER DENGAN ARCHIVE BARU
async function saveChanges() {
  const activeLocationCode = getActiveLocation();
  if (!activeLocationCode) {
    showToastMessage("⚠️ Tidak ada lokasi aktif");
    return;
  }

  const locationData = getLocationData(activeLocationCode);
  if (!locationData) {
    showToastMessage("⚠️ Data lokasi tidak ditemukan");
    return;
  }

  const token = locationData.token;

  if (!token) {
    showToastMessage("⚠️ Sesi login sudah berakhir. Silakan login kembali.");
    window.location.href = "/";
    return;
  }

  isLoading.value = true;

  try {
    // STEP 1: AMBIL DATA DARI LOCATION_DATA
    const backendData = locationData.backend_items || [];
    const manualData = locationData.manual_items || [];
    const existingArchive = locationData.archive_items || [];
    const originalData = locationData.original_data || [];

    // Helper function untuk membandingkan dua item
    function hasItemChanged(newItem, originalItem) {
      if (!originalItem) return true;

      const newQty = formatQuantityForBackend(
        newItem.ISITEAMITEMDETAIL_QTY,
        newItem.IS_DECIMAL
      );
      const originalQty = formatQuantityForBackend(
        originalItem.ISITEAMITEMDETAIL_QTY,
        originalItem.IS_DECIMAL
      );

      // Bandingkan quantity dan description
      return (
        newQty !== originalQty ||
        newItem.ISITEAMITEMDETAIL_DESC !== originalItem.ISITEAMITEMDETAIL_DESC
      );
    }

    // STEP 2: IDENTIFIKASI HANYA DATA YANG BERUBAH
    // Data baru (manual) selalu dikirim
    const newItemsToProcess = manualData.map((item) => ({
      ...item,
      source: "manual",
      isChanged: true,
    }));

    // Data existing dari backend - filter hanya yang berubah
    const changedBackendItems = [];

    for (const item of backendData) {
      // Skip item yang ditandai untuk dihapus
      if (item.marked_for_deletion) continue;

      // Cari item asli untuk komparasi
      const originalItem = originalData.find(
        (original) =>
          original.ISITEAMITEMDETAIL_BRG_CODE ===
          item.ISITEAMITEMDETAIL_BRG_CODE
      );

      // Periksa apakah ada perubahan
      if (hasItemChanged(item, originalItem)) {
        changedBackendItems.push({
          ...item,
          source: "backend",
          isChanged: true,
          originalItem,
        });
      }
    }

    // STEP 2B: IDENTIFIKASI ITEM YANG DITANDAI UNTUK DIHAPUS
    const itemsToDelete = existingArchive.filter(
      (archive) => archive.delete_marker === true
    );

    // Gabungkan semua item yang perlu diproses
    const allItemsToProcess = [...newItemsToProcess, ...changedBackendItems];
    const hasArchiveData = existingArchive.length > 0;
    const hasDeleteOperations = itemsToDelete.length > 0;

    if (
      allItemsToProcess.length === 0 &&
      !hasArchiveData &&
      !hasDeleteOperations
    ) {
      showToastMessage("ℹ️ Tidak ada data yang perlu disimpan.");
      return;
    }

    const successItems = [];
    const failedItems = [];
    const createdManualItems = [];
    const deletedItems = [];

    // STEP 3: PROSES DELETE OPERATIONS TERLEBIH DAHULU
    if (hasDeleteOperations) {
      for (const deleteItem of itemsToDelete) {
        try {
          // Panggil API DELETE untuk item backend
          if (deleteItem.source === "backend" && deleteItem.deleted_item_id) {
            const response = await axios.delete(
              `${import.meta.env.VITE_BACKEND}/api/lokasi/item-delete`,
              {
                data: {
                  item_detail_id: deleteItem.deleted_item_id,
                  kode_team: locationData.kode_team,
                },
                headers: {
                  Authorization: `Bearer ${token}`,
                },
              }
            );

            if (response.data.success) {
              deletedItems.push({
                item_id: deleteItem.deleted_item_id,
                name:
                  deleteItem.barang_data?.ISITEAMITEMDETAIL_BRG_NAME ||
                  "Unknown",
                type: "backend",
              });
            } else {
              throw new Error(response.data.message || "Gagal menghapus item");
            }
          }

          // Untuk item manual, cukup hapus dari archive (tidak perlu API call)
          else if (deleteItem.source === "manual") {
            deletedItems.push({
              item_id: deleteItem.deleted_item_id,
              name:
                deleteItem.barang_data?.ISITEAMITEMDETAIL_BRG_NAME || "Unknown",
              type: "manual",
            });
          }
        } catch (err) {
          const errorMsg = err.response?.data?.message || err.message;
          showToastMessage(`❌ Gagal menghapus item: ${errorMsg}`);
        }
      }
    }

    // STEP 4: PROSES CREATE/UPDATE ITEMS
    if (allItemsToProcess.length > 0) {
      // FUNGSI: CREATE/UPDATE ITEMS KE DATABASE
      async function saveItemToBackend(item) {
        try {
          const locationData = getLocationData(activeLocationCode);
          const storedSeqOrder = locationData?.seq_order || {};
          const seqOrder = storedSeqOrder[item.ISITEAMITEMDETAIL_BRG_CODE] || 0;

          const payload = {
            kode: item.ISITEAMITEMDETAIL_BRG_CODE,
            qty: formatQuantityForBackend(
              item.ISITEAMITEMDETAIL_QTY,
              item.IS_DECIMAL
            ),
            desc: item.ISITEAMITEMDETAIL_DESC || "",
            kode_team: locationData.kode_team,
            kode_lokasi: activeLocationCode,
            seq_order: seqOrder,
          };

          const response = await axios.put(
            `${import.meta.env.VITE_BACKEND}/api/lokasi/update`,
            payload,
            { headers: { Authorization: `Bearer ${token}` } }
          );

          if (response.data.success) {
            return {
              success: true,
              data: response.data.data,
              backendId: response.data.data.item_detail_id,
              item: item,
            };
          } else {
            throw new Error(response.data.message || "Gagal menyimpan item");
          }
        } catch (err) {
          throw err;
        }
      }

      for (let i = 0; i < allItemsToProcess.length; i++) {
        const item = allItemsToProcess[i];

        try {
          const result = await saveItemToBackend(item);

          if (result.success) {
            if (
              item.source === "manual" &&
              item.ISITEAMITEMDETAIL_ID.startsWith("manual-")
            ) {
              createdManualItems.push({
                oldId: item.ISITEAMITEMDETAIL_ID,
                newId: result.backendId,
                item: result.item,
              });
            }

            successItems.push(item);
          }
        } catch (err) {
          const status = err.response?.status;
          const msg =
            err.response?.data?.message ||
            "Terjadi kesalahan saat menyimpan data.";

          if (status === 403) {
            failedItems.push({ ...item, warning: msg });
            showToastMessage(`⚠️ ${msg}`);
          } else {
            showToastMessage(
              `❌ Gagal menyimpan item ${item.ISITEAMITEMDETAIL_BRG_NAME}: ${msg}`
            );
            failedItems.push(item);
          }
        }
      }
    }

    // STEP 5B: UPDATE SEQ ORDER KE BACKEND
    try {
      // Ambil urutan SEQ dari localStorage
      const locationData = getLocationData(activeLocationCode);
      const storedSeqOrder = locationData?.seq_order || {};

      // Hanya kirim jika ada perubahan sequence
      if (Object.keys(storedSeqOrder).length > 0) {
        const response = await axios.post(
          `${import.meta.env.VITE_BACKEND}/api/lokasi/update-seq`,
          {
            kode_team: locationData.kode_team,
            kode_lokasi: activeLocationCode,
            sequences: storedSeqOrder,
          },
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.data.success) {
          showToastMessage(
            `✅ Urutan SEQ berhasil diperbarui: ${response.data.data.updated_count} item`
          );
        } else {
          showToastMessage(`⚠️ SEQ gagal diperbarui: ${response.data.message}`);
        }
      }
    } catch (seqError) {
      // Hanya warning, bukan error fatal
      const seqErrorMsg =
        seqError.response?.data?.message || "Gagal update SEQ";
      showToastMessage(`⚠️ Update SEQ: ${seqErrorMsg}`);
    }

    // STEP 5: UPDATE LOCATION_DATA SETELAH OPERASI DELETE
    if (deletedItems.length > 0) {
      // Hapus item yang berhasil didelete dari backend_items
      const updatedBackendData = backendData.filter((backendItem) => {
        return !deletedItems.some(
          (deleted) =>
            deleted.item_id === backendItem.ISITEAMITEMDETAIL_ID &&
            deleted.type === "backend"
        );
      });

      // Hapus archive yang sudah diproses (baik delete maupun update)
      const remainingArchive = existingArchive.filter((archiveItem) => {
        // Jangan hapus jika item ini gagal didelete
        const wasSuccessfullyDeleted = deletedItems.some(
          (deleted) => deleted.item_id === archiveItem.deleted_item_id
        );

        // Hapus dari archive jika sudah diproses (baik delete maupun update)
        // atau jika adalah item manual yang sudah didelete
        if (archiveItem.delete_marker) {
          return !wasSuccessfullyDeleted;
        }
        return true; // Keep other archive items
      });

      updateLocationSubData(
        "backend_items",
        updatedBackendData,
        activeLocationCode
      );
      updateLocationSubData(
        "archive_items",
        remainingArchive,
        activeLocationCode
      );

      // Juga hapus dari original_data
      const updatedOriginalData = originalData.filter((originalItem) => {
        return !deletedItems.some(
          (deleted) =>
            deleted.item_id === originalItem.ISITEAMITEMDETAIL_ID &&
            deleted.type === "backend"
        );
      });

      updateLocationSubData(
        "original_data",
        updatedOriginalData,
        activeLocationCode
      );
    }

    // STEP 6: KIRIM LOG PERUBAHAN (TERMASUK DELETE)
    try {
      const allArchiveToSend = [...existingArchive];

      if (allArchiveToSend.length > 0) {
        const archiveDataToSend = allArchiveToSend.map((archiveItem) => {
          // Cari ID yang baru untuk item manual
          let finalId =
            archiveItem.ISITEAMITEMDETAIL_ID ||
            archiveItem.LOG_ISITEAMITEMDETAIL_ID;

          if (finalId?.startsWith("manual-")) {
            const createdItem = createdManualItems.find(
              (created) => created.oldId === finalId
            );
            if (createdItem) {
              finalId = createdItem.newId;
            }
          }

          // Tentukan action_type berdasarkan metadata
          const actionType = archiveItem.delete_marker
            ? "DELETE"
            : archiveItem.action_type || "UPDATE";

          // KONVERSI KE STRUKTUR YANG SESUAI DENGAN MODEL DetailLog
          const logItem = {
            ISITEAMITEMDETAILLOG_ID: archiveItem.ISITEAMITEMDETAILLOG_ID || "",
            LOG_BRG_ID: archiveItem.LOG_BRG_ID || "",
            LOG_ISITEAMITEMDETAIL_ID: finalId || "",
            LOG_NEWQTY: archiveItem.LOG_NEWQTY,
            DATE_CREATE: archiveItem.DATE_CREATE || new Date().toISOString(),
            DATE_MODIFY: archiveItem.DATE_MODIFY || new Date().toISOString(),
            USER_CREATE:
              archiveItem.USER_CREATE || locationData.user_id || "system",
            USER_MODIFY:
              archiveItem.USER_MODIFY || locationData.user_id || "system",
            barang_data: archiveItem.barang_data || {},
            old_data: archiveItem.old_data || {},
            timestamp: archiveItem.timestamp || new Date().toISOString(),
            lokasi: archiveItem.lokasi || activeLocationCode,
            kode_team: archiveItem.kode_team || locationData.kode_team,
            FLAG: actionType,
            delete_marker: archiveItem.delete_marker || false,
            deleted_item_id: archiveItem.deleted_item_id || "",
            source: archiveItem.source || "backend",
            ISITEAMITEMDETAIL_BRG_CODE:
              archiveItem.barang_data?.ISITEAMITEMDETAIL_BRG_CODE ||
              archiveItem.ISITEAMITEMDETAIL_BRG_CODE ||
              "",
            ISITEAMITEMDETAIL_BRG_NAME:
              archiveItem.barang_data?.ISITEAMITEMDETAIL_BRG_NAME ||
              archiveItem.ISITEAMITEMDETAIL_BRG_NAME ||
              "",
            ISITEAMITEMDETAIL_BRG_BARCODE:
              archiveItem.barang_data?.ISITEAMITEMDETAIL_BRG_BARCODE ||
              archiveItem.ISITEAMITEMDETAIL_BRG_BARCODE ||
              "",
          };

          return logItem;
        });

        if (archiveDataToSend.length > 0) {
          const archiveResponse = await axios.post(
            `${import.meta.env.VITE_BACKEND}/api/lokasi/update-log`,
            {
              logs: archiveDataToSend,
              kode_team: locationData.kode_team,
              kode_lokasi: activeLocationCode,
              include_deleted: true,
            },
            { headers: { Authorization: `Bearer ${token}` } }
          );

          if (archiveResponse.data.success) {
            // Hapus SEMUA archive yang sudah dikirim (termasuk yang delete)
            updateLocationSubData("archive_items", [], activeLocationCode);

            showToastMessage(
              `✅ Log berhasil dikirim: ${allArchiveToSend.length} item (termasuk ${itemsToDelete.length} delete)`
            );
          } else {
            throw new Error(
              archiveResponse.data.message || "Gagal menyimpan batch log"
            );
          }
        }
      }
    } catch (logErr) {
      const archiveMsg =
        logErr.response?.data?.message ||
        "Terjadi kesalahan saat mengirim log perubahan.";
      showToastMessage(`⚠️ Log perubahan gagal dikirim: ${archiveMsg}`);
    }

    // STEP 7: UPDATE SETELAH BERHASIL CREATE/UPDATE
    if (successItems.length > 0) {
      if (createdManualItems.length > 0) {
        let currentBackendData = [...backendData];
        let currentManualData = [...manualData];

        createdManualItems.forEach((created) => {
          const itemIndexInManual = currentManualData.findIndex(
            (manualItem) => manualItem.ISITEAMITEMDETAIL_ID === created.oldId
          );

          if (itemIndexInManual !== -1) {
            const updatedItem = {
              ...currentManualData[itemIndexInManual],
              ISITEAMITEMDETAIL_ID: created.newId,
              source: "backend",
            };

            currentManualData.splice(itemIndexInManual, 1);

            const existsInBackend = currentBackendData.some(
              (backendItem) =>
                backendItem.ISITEAMITEMDETAIL_BRG_CODE ===
                updatedItem.ISITEAMITEMDETAIL_BRG_CODE
            );

            if (!existsInBackend) {
              currentBackendData.push(updatedItem);
            }

            const itemIndexInView = itemDetails.value.findIndex(
              (viewItem) => viewItem.ISITEAMITEMDETAIL_ID === created.oldId
            );

            if (itemIndexInView !== -1) {
              itemDetails.value[itemIndexInView] = updatedItem;
            }
          }
        });

        updateLocationSubData(
          "backend_items",
          currentBackendData,
          activeLocationCode
        );
        updateLocationSubData(
          "manual_items",
          currentManualData,
          activeLocationCode
        );
      }

      // Update original_data untuk data yang berhasil diupdate
      const updatedOriginalData = [...originalData];

      successItems.forEach((successItem) => {
        if (successItem.source === "backend") {
          const index = updatedOriginalData.findIndex(
            (original) =>
              original.ISITEAMITEMDETAIL_BRG_CODE ===
              successItem.ISITEAMITEMDETAIL_BRG_CODE
          );

          if (index !== -1) {
            updatedOriginalData[index] = {
              ...successItem,
              isChanged: undefined,
              originalItem: undefined,
            };
          }
        }
      });

      // Tambahkan data baru ke original_data
      createdManualItems.forEach((created) => {
        const updatedItem = {
          ...created.item,
          ISITEAMITEMDETAIL_ID: created.newId,
          source: "backend",
          isChanged: undefined,
          originalItem: undefined,
        };

        updatedOriginalData.push(updatedItem);
      });

      updateLocationSubData(
        "original_data",
        updatedOriginalData,
        activeLocationCode
      );

      // Update timestamp lastUpdated
      const now = new Date();
      lastUpdated.value =
        now.toLocaleString("id-ID", {
          day: "2-digit",
          month: "2-digit",
          year: "numeric",
          hour: "2-digit",
          minute: "2-digit",
          second: "2-digit",
        }) + " (saved)";
    }

    // STEP 8: TAMPILKAN HASIL AKHIR
    const totalSuccess = successItems.length;
    const manualSuccessCount = successItems.filter(
      (item) => item.source === "manual"
    ).length;
    const backendSuccessCount = successItems.filter(
      (item) => item.source === "backend"
    ).length;

    let successMessage = "✅ Perubahan berhasil disimpan: ";

    if (deletedItems.length > 0) {
      successMessage += `${deletedItems.length} item dihapus`;
      if (totalSuccess > 0) successMessage += ", ";
    }

    if (totalSuccess > 0) {
      successMessage += `${totalSuccess} item (${manualSuccessCount} baru, ${backendSuccessCount} diupdate)`;
    }

    if (successMessage !== "✅ Perubahan berhasil disimpan: ") {
      showToastMessage(successMessage);

      const confirmRefresh = confirm(
        `${successMessage}\n\nIngin refresh data dari server untuk melihat perubahan terbaru?`
      );

      if (confirmRefresh) {
        await fetchItemDetails();
      }
    }

    if (failedItems.length > 0) {
      showToastMessage(`⚠️ ${failedItems.length} item gagal diproses.`);
    }
  } catch (error) {
    showToastMessage("❌ Gagal menyimpan perubahan ke server");
  } finally {
    isLoading.value = false;
  }
}

// MOUNTED LIFECYCLE
onMounted(() => {
  fetchData();

  if (typeof VANTA !== "undefined") {
    VANTA.NET({
      el: "#vanta-bg",
      mouseControls: true,
      touchControls: true,
      gyroControls: false,
      minHeight: 200.0,
      minWidth: 200.0,
      scale: 1.0,
      scaleMobile: 1.0,
      color: 0x3a86ff,
      backgroundColor: 0x0a0a0a,
      points: 14.0,
      maxDistance: 24.0,
      spacing: 20.0,
    });
  }

  document.addEventListener("keydown", handleGlobalKeydown);

  setTimeout(() => {
    if (barcodeInputRef.value) {
      barcodeInputRef.value.focus();
    }

    const activeLocationCode = getActiveLocation();
    if (activeLocationCode) {
      showToastMessage(`👋 Selamat datang! Lokasi: ${activeLocationCode}`);
    } else {
      showToastMessage(
        "👋 Selamat datang! Silakan scan lokasi terlebih dahulu."
      );
    }
  }, 1000);
});

onBeforeUnmount(() => {
  stopCamera();
  stopLocationCamera();
  document.removeEventListener("keydown", handleGlobalKeydown);
});
</script>

<style scoped>
/* Semua style yang sudah ada tetap sama */
@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translate(-50%, 20px);
  }
  100% {
    opacity: 1;
    transform: translate(-50%, 0);
  }
}

@keyframes scan {
  0% {
    transform: translateY(0);
  }
  100% {
    transform: translateY(100%);
  }
}

@keyframes pulse-gold {
  0% {
    box-shadow: 0 0 10px rgba(255, 215, 0, 0.5),
      inset 0 1px 0 rgba(255, 255, 255, 0.2);
  }
  50% {
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.8),
      inset 0 1px 0 rgba(255, 255, 255, 0.3);
  }
  100% {
    box-shadow: 0 0 10px rgba(255, 215, 0, 0.5),
      inset 0 1px 0 rgba(255, 255, 255, 0.2);
  }
}

/* Modern Loading Spinner */
@keyframes morph {
  0%,
  100% {
    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
  }
  50% {
    border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
  }
}

@keyframes pulse-glow {
  0%,
  100% {
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
    transform: scale(1);
  }
  50% {
    box-shadow: 0 0 30px rgba(59, 130, 246, 0.8),
      0 0 40px rgba(139, 92, 246, 0.4);
    transform: scale(1.05);
  }
}

@keyframes float-particle {
  0%,
  100% {
    transform: translate(0, 0) rotate(0deg);
    opacity: 1;
  }
  25% {
    transform: translate(10px, -10px) rotate(90deg);
    opacity: 0.7;
  }
  50% {
    transform: translate(0, -20px) rotate(180deg);
    opacity: 0.4;
  }
  75% {
    transform: translate(-10px, -10px) rotate(270deg);
    opacity: 0.7;
  }
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

@keyframes orbit {
  0% {
    transform: rotate(0deg) translateX(20px) rotate(0deg);
  }
  100% {
    transform: rotate(360deg) translateX(20px) rotate(-360deg);
  }
}

.loading-spinner-small {
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

.animate-fade-in-up {
  animation: fadeInUp 0.5s ease-out;
}

.animate-spin-slow {
  animation: spin-slow 3s linear infinite;
}

.scan-animation {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(
    90deg,
    rgba(255, 255, 255, 0),
    rgba(59, 130, 246, 0.9),
    rgba(255, 255, 255, 0)
  );
  animation: scan 1.5s linear infinite;
  border-radius: 2px;
}

.highlight-row {
  background: linear-gradient(
    135deg,
    rgba(255, 215, 0, 0.15) 0%,
    rgba(255, 193, 7, 0.25) 50%,
    rgba(255, 152, 0, 0.15) 100%
  );
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 215, 0, 0.3);
  border-left: 3px solid #ffd700;
  box-shadow: 0 4px 16px rgba(255, 193, 7, 0.2),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
  position: relative;
  overflow: hidden;
  transition: all 0.4s ease;
}

.highlight-row::after {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.4),
    transparent
  );
  transition: left 0.5s ease;
}

.highlight-row:hover::after {
  left: 100%;
}

/* Highlight untuk item baru dan update (warna emas) */
.new-item-highlight,
.updated-item-highlight {
  background: linear-gradient(
    135deg,
    rgba(255, 215, 0, 0.15) 0%,
    rgba(255, 193, 7, 0.25) 50%,
    rgba(255, 152, 0, 0.15) 100%
  ) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 215, 0, 0.3) !important;
  border-left: 3px solid #ffd700 !important;
  animation: pulse-gold 2s ease-in-out infinite;
  position: relative;
  overflow: hidden;
}

.new-item-highlight::before {
  content: "BARU";
  position: absolute;
  top: 2px;
  right: 2px;
  background: linear-gradient(135deg, #ffd700, #ffa500);
  color: white;
  font-size: 7px;
  font-weight: bold;
  padding: 1px 4px;
  border-radius: 3px;
  z-index: 10;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.updated-item-highlight::before {
  content: "UPDATE";
  position: absolute;
  top: 2px;
  right: 2px;
  background: linear-gradient(135deg, #ffd700, #ffa500);
  color: white;
  font-size: 7px;
  font-weight: bold;
  padding: 1px 4px;
  border-radius: 3px;
  z-index: 10;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.neo-glassmorphism {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.3),
    inset 0 1px 0 0 rgba(255, 255, 255, 0.1);
}

.font-poppins {
  font-family: "Poppins", system-ui, -apple-system, sans-serif;
}

/* Custom breakpoint untuk very small screens */
@media (max-width: 475px) {
  .xs\:inline {
    display: inline !important;
  }
  .xs\:table-cell {
    display: table-cell !important;
  }
  .xs\:hidden {
    display: none !important;
  }
}

/* Custom breakpoint untuk extra large screens */
@media (min-width: 1280px) {
  .xl\:max-w-6xl {
    max-width: 72rem !important;
  }
  .xl\:text-6xl {
    font-size: 3.75rem !important;
    line-height: 1 !important;
  }
  .xl\:w-32 {
    width: 8rem !important;
  }
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
  width: 4px;
  height: 4px;
}

::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
}

::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 2px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* Smooth transitions untuk semua elemen */
* {
  transition: all 0.2s ease-in-out;
}

/* Hover effects untuk desktop */
@media (min-width: 1024px) {
  .group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
  }

  .group:hover .group-hover\:text-blue-200 {
    color: rgb(191 219 254);
  }

  .group:hover .group-hover\:text-gray-200 {
    color: rgb(229 231 235);
  }

  .group:hover .group-hover\:bg-green-500\/30 {
    background-color: rgba(34, 197, 94, 0.3);
  }
}

/* Better touch targets untuk mobile */
@media (max-width: 640px) {
  button,
  input[type="submit"],
  input[type="button"] {
    min-height: 40px;
  }

  .table-input {
    width: 100%;
    min-width: 60px;
  }

  /* Increase touch target for table action buttons */
  td:last-child button {
    min-width: 32px;
    min-height: 32px;
  }
}

/* Optimasi untuk tablet */
@media (min-width: 640px) and (max-width: 1024px) {
  .sm\:table-cell {
    display: table-cell !important;
  }
}

/* Font size yang lebih konsisten untuk mobile */
@media (max-width: 640px) {
  h1 {
    font-size: 1.5rem !important;
  }

  h2 {
    font-size: 1.25rem !important;
  }

  h3 {
    font-size: 1rem !important;
  }

  p,
  span,
  td,
  th {
    font-size: 0.75rem !important;
  }
}
</style>
