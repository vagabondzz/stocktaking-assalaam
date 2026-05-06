<template>
  <div
    v-if="showModal"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
  >
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-4">
      <!-- HEADER -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-lg text-gray-800">Scan Lokasi</h2>
        <button @click="showModal = false" class="text-gray-500">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="bg-white text-black p-6 rounded-xl shadow-xl w-full border">
        <div class="flex gap-2">
          <!-- Input kode -->
          <input
            :value="kodeLokasi"
            @input="formatKodeLokasi"
            type="text"
            placeholder="Kode Lokasi"
            class="flex-1 border rounded-md px-3 py-2 bg-white text-black focus:outline-none focus:ring-2 focus:ring-orange-400"
          />
          <!-- Button Scan -->
          <button
            @click="openScannerPopup"
            class="bg-orange-500 text-white px-4 rounded-md hover:bg-orange-600"
          >
            <i class="fa-solid fa-qrcode"></i>
          </button>
        </div>

        <!-- Button Masuk -->
        <button
          @click="submitScan"
          class="mt-4 w-full bg-orange-500 text-white py-2 rounded-md font-semibold hover:bg-orange-600"
          :disabled="isLoading || hasCurrentPendingChanges"
        >
          <span v-if="!isLoading">Masuk</span>
          <span v-else>Loading...</span>
        </button>

        <p v-if="errorMessage" class="mt-2 text-red-500 text-sm">
          {{ errorMessage }}
        </p>
        <p v-if="successMessage" class="mt-2 text-green-500 text-sm">
          {{ successMessage }}
        </p>
      </div>

      <!-- POPUP SCANNER -->
      <div
        v-if="showScanner"
        class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50"
      >
        <div class="bg-white text-black p-4 rounded-lg w-full max-w-md">
          <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-lg">Scan QR Code</h3>
            <button @click="closeScannerPopup" class="text-xl">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
          <div id="reader" class="w-full"></div>
          <div class="flex gap-2 mt-4">
            <button
              @click="toggleCamera"
              class="flex-1 bg-blue-500 text-white py-2 rounded-md"
            >
              <i class="fa-solid fa-rotate mr-2"></i>Balik Kamera
            </button>
            <button
              @click="toggleFlash"
              :disabled="!flashAvailable"
              class="flex-1 bg-yellow-500 text-white py-2 rounded-md disabled:opacity-50"
            >
              <i
                :class="
                  flashOn
                    ? 'fa-solid fa-bolt mr-2'
                    : 'fa-solid fa-lightbulb mr-2'
                "
              ></i>
              {{ flashOn ? "Flash Off" : "Flash On" }}
            </button>
          </div>
          <button
            @click="closeScannerPopup"
            class="mt-2 w-full bg-red-500 text-white py-2 rounded-md"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- POPUP DETAIL BARANG -->
  <div
    v-if="showItemDetail"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
    @click.self="closeItemDetail"
  >
    <div class="bg-white rounded-lg w-full max-w-md mx-4 animate-fadeInUp">
      <div class="flex justify-between items-center p-4 border-b">
        <h3 class="font-bold text-lg text-gray-800">Detail Barang</h3>
        <button @click="closeItemDetail" class="text-gray-500 text-xl">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="p-4 space-y-3">
        <div class="grid grid-cols-3 gap-2">
          <div class="font-semibold text-gray-600">Kode PLU:</div>
          <div class="col-span-2 text-gray-800">
            {{ selectedItem.kode_plu || "-" }}
          </div>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <div class="font-semibold text-gray-600">Kode Barcode:</div>
          <div class="col-span-2 text-gray-800">
            {{ selectedItem.kode_barcode || "-" }}
          </div>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <div class="font-semibold text-gray-600">Nama Barang:</div>
          <div class="col-span-2 text-gray-800">
            {{ selectedItem.nama_barang || "-" }}
          </div>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <div class="font-semibold text-gray-600">UoM:</div>
          <div class="col-span-2 text-gray-800">
            {{ selectedItem.uom || "-" }}
          </div>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <div class="font-semibold text-gray-600">Tipe Barang:</div>
          <div class="col-span-2 text-gray-800">
            {{ selectedItem.tipe_barang || "-" }}
          </div>
        </div>

        <div class="grid grid-cols-3 gap-2 items-center">
          <div class="font-semibold text-gray-600">Qty:</div>
          <div class="col-span-2 flex items-center gap-2">
            <button
              @click="itemQuantity--"
              :disabled="itemQuantity <= 0"
              class="bg-red-500 text-white w-8 h-8 rounded-full disabled:opacity-50"
            >
              -
            </button>
            <input
              v-model.number="itemQuantity"
              type="number"
              min="0"
              :step="selectedItem.is_decimal ? '0.001' : '1'"
              class="w-20 text-center border rounded px-2 py-1"
            />
            <button
              @click="itemQuantity++"
              class="bg-green-500 text-white w-8 h-8 rounded-full"
            >
              +
            </button>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-2">
          <div class="font-semibold text-gray-600">Note:</div>
          <div class="col-span-2">
            <textarea
              v-model="itemNote"
              rows="3"
              class="w-full border rounded px-2 py-1"
              placeholder="Tambahkan catatan (opsional)..."
            ></textarea>
          </div>
        </div>
      </div>

      <div class="p-4 border-t flex gap-2">
        <button
          @click="closeItemDetail"
          class="flex-1 bg-gray-500 text-white py-2 rounded hover:bg-gray-600"
        >
          Batal
        </button>
        <button
          @click="tambahKeDaftar"
          class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700"
        >
          Tambahkan
        </button>
      </div>
    </div>
  </div>

  <div
    v-if="showSearchResultPopup"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
    @click.self="closeSearchResultPopup"
  >
    <div class="bg-white rounded-lg w-full max-w-2xl mx-4 max-h-[85vh] flex flex-col">
      <div class="flex justify-between items-center p-4 border-b">
        <div>
          <h3 class="font-bold text-lg text-gray-800">Pilih Varian Barang</h3>
          <p class="text-sm text-gray-500">
            Ditemukan beberapa barang untuk pencarian
            <span class="font-semibold">{{ pendingSearchKeyword }}</span>
          </p>
        </div>
        <button @click="closeSearchResultPopup" class="text-gray-500 text-xl">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-4 space-y-3">
        <button
          v-for="(item, index) in searchResultItems"
          :key="`${item.barang_id || item.kode_plu}-${index}`"
          @click="selectSearchResultItem(item)"
          class="w-full rounded-lg border border-slate-200 p-4 text-left transition hover:border-orange-300 hover:bg-orange-50"
        >
          <div class="flex items-start justify-between gap-3">
            <div>
              <div class="font-semibold text-slate-900">
                {{ item.nama_barang || "-" }}
              </div>
              <div class="mt-1 text-sm text-slate-600">
                PLU: {{ item.kode_plu || "-" }} • Barcode:
                {{ item.kode_barcode || "-" }}
              </div>
              <div class="mt-1 text-xs text-slate-500">
                UoM: {{ item.uom || "-" }} • Tipe: {{ item.tipe_barang || "-" }}
              </div>
            </div>
            <span
              class="rounded-full px-3 py-1 text-xs font-semibold"
              :class="
                item.form_mode === 'edit'
                  ? 'bg-blue-100 text-blue-700'
                  : 'bg-emerald-100 text-emerald-700'
              "
            >
              {{ item.form_mode === "edit" ? "Sudah Ada" : "Barang Baru" }}
            </span>
          </div>
        </button>
      </div>

      <div class="border-t p-4">
        <button
          @click="closeSearchResultPopup"
          class="w-full rounded-lg bg-gray-600 py-2 font-semibold text-white hover:bg-gray-700"
        >
          Tutup
        </button>
      </div>
    </div>
  </div>

  <!-- POPUP BARANG TIDAK DITEMUKAN -->
  <div
    v-if="showNotFoundPopup"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
    @click.self="closeNotFoundPopup"
  >
    <div class="bg-white rounded-lg w-full max-w-sm mx-4 animate-shake">
      <div class="text-center p-6">
        <div
          class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4 animate-pulse"
        >
          <svg
            class="w-12 h-12 text-red-600"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
        </div>
        <h3 class="font-bold text-xl text-gray-800 mb-2">
          Informasi Barang
        </h3>
        <p class="text-gray-600 mb-4">
          {{ notFoundMessage }}
        </p>
        <div
          class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4 text-left"
        >
          <p class="hidden text-sm text-yellow-700">
            <span class="font-semibold">Detail Barang:</span><br />
            • Periksa kembali kode barcode/PLU yang Anda masukkan<br />
            • Pastikan barang sudah terdaftar di sistem<br />
            • Coba scan ulang barcode barang
          </p>
          <div class="text-sm text-yellow-800 space-y-1">
            <p class="font-semibold">Detail Barang</p>
            <p>
              <span class="font-semibold">Nama Barang:</span>
              {{ notFoundItemDetail.nama_barang || "-" }}
            </p>
            <p>
              <span class="font-semibold">Kode PLU:</span>
              {{ notFoundItemDetail.kode_plu || notFoundCode || "-" }}
            </p>
            <p>
              <span class="font-semibold">Barcode:</span>
              {{ notFoundItemDetail.kode_barcode || "-" }}
            </p>
            <p>
              <span class="font-semibold">Satuan:</span>
              {{ notFoundItemDetail.uom || "-" }}
            </p>
          </div>
        </div>
        <button
          @click="closeNotFoundPopup"
          class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition-colors"
        >
          Tutup
        </button>
      </div>
    </div>
  </div>

  <div
    v-if="showPendingSavePopup"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
    @click.self="closePendingSavePopup"
  >
    <div class="bg-white rounded-lg w-full max-w-sm mx-4 animate-fadeInUp">
      <div class="p-6 text-center">
        <div
          class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-amber-100"
        >
          <i class="fa-solid fa-triangle-exclamation text-2xl text-amber-600"></i>
        </div>
        <h3 class="mb-2 text-xl font-bold text-gray-800">
          Simpan Data Dulu
        </h3>
        <p class="text-sm text-gray-600">
          Anda harus menyimpan ke database dulu dengan menekan tombol
          <span class="font-semibold text-green-700">LIST SO</span> sebelum
          {{ pendingSaveActionLabel }}.
        </p>
      </div>

      <div class="border-t p-4">
        <button
          @click="closePendingSavePopup"
          class="w-full rounded-lg bg-amber-500 py-2 font-semibold text-white hover:bg-amber-600"
        >
          Mengerti
        </button>
      </div>
    </div>
  </div>

  <div
    v-if="showItemLogPopup"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
    @click.self="closeItemLogPopup"
  >
    <div
      class="bg-white rounded-lg w-full max-w-3xl mx-4 max-h-[85vh] flex flex-col"
    >
      <div class="flex justify-between items-center p-4 border-b">
        <div>
          <h3 class="font-bold text-lg text-gray-800">
            Riwayat Perubahan Barang
          </h3>
          <p class="text-sm text-gray-500">
            {{ itemLogItem.nama_barang || "-" }} •
            {{ itemLogItem.kode_plu || itemLogItem.kode_barcode || "-" }}
          </p>
        </div>
        <button @click="closeItemLogPopup" class="text-gray-500 text-xl">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="p-4 overflow-y-auto flex-1">
        <div v-if="itemLogLoading" class="text-center text-gray-500 py-8">
          Memuat riwayat perubahan...
        </div>

        <div
          v-else-if="itemLogError"
          class="bg-red-50 text-red-600 border border-red-200 rounded-lg px-4 py-3"
        >
          {{ itemLogError }}
        </div>

        <div
          v-else-if="groupedItemLogs.length === 0"
          class="text-center text-gray-500 py-8"
        >
          Belum ada riwayat perubahan untuk barang ini.
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="group in groupedItemLogs"
            :key="group.dateKey"
            class="border rounded-lg overflow-hidden"
          >
            <div class="bg-gray-100 px-4 py-2 font-semibold text-gray-700">
              {{ group.dateLabel }}
            </div>

            <div class="divide-y">
              <div
                v-for="(log, logIndex) in group.logs"
                :key="`${group.dateKey}-${logIndex}`"
                class="px-4 py-3"
              >
                <div class="text-sm text-gray-700 space-y-1">
                  <div>
                    <span class="font-semibold">Jam:</span>
                    {{ log.timeLabel }}
                  </div>
                  <div>
                    <span class="font-semibold">Qty:</span>
                    {{ log.qtyLabel }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="p-4 border-t">
        <button
          @click="closeItemLogPopup"
          class="w-full bg-gray-600 hover:bg-gray-700 text-white py-2 rounded"
        >
          Tutup
        </button>
      </div>
    </div>
  </div>

  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- HEADER -->
    <div class="bg-white shadow p-4 flex justify-center">
      <div class="flex flex-col items-center gap-1">
        <img src="../assets/logoassalam.png" class="w-40" />
        <h1 class="font-bold text-xl text-gray-800 tracking-wide">
          STOCK OPNAME
        </h1>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="p-4 flex-1 overflow-auto">
      <h2 class="text-gray-700 font-bold text-lg mb-3">LIST ITEM</h2>

      <!-- SESSION CARD -->
      <div class="bg-white shadow rounded-lg p-4 mb-4">
        <div class="grid grid-cols-2 gap-3 mb-3">
          <div>
            <div class="text-sm text-gray-500">Penghitung 1</div>
            <div class="font-semibold text-gray-700">
              {{ penghitung1Name }}
            </div>
          </div>

          <div>
            <div class="text-sm text-gray-500">Penghitung 2</div>
            <div class="font-semibold text-gray-700">
              {{ penghitung2Name }}
            </div>
          </div>
        </div>

        <div class="flex justify-between items-center">
          <div>
            <div class="text-sm text-gray-500">Lokasi</div>
            <div class="font-semibold text-blue-600">
              {{ currentKodeLokasi }}
            </div>
          </div>

          <button
            @click="openChangeLocationModal"
            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm shadow disabled:opacity-70"
            :disabled="hasCurrentPendingChanges || isLoading"
          >
            <i class="fa-solid fa-location-dot mr-2"></i>Ganti Lokasi
          </button>
        </div>
      </div>

      <div
        v-if="hasCurrentPendingChanges"
        class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"
      >
        Ada perubahan barang yang belum tersimpan. Tekan tombol
        <span class="font-semibold">LIST SO</span> dulu sebelum logout atau
        pindah lokasi.
      </div>

      <!-- SCAN BARCODE -->
      <div class="bg-white shadow rounded-lg p-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-2">
          <!-- Input -->
          <input
            v-model="barcodeInput"
            type="text"
            placeholder="Scan atau ketik barcode / PLU"
            class="border border-gray-300 rounded px-3 py-2 bg-white w-full text-black focus:outline-none focus:ring-2 focus:ring-orange-400"
            @keyup.enter="cariBarang"
          />

          <!-- Button group -->
          <div class="flex gap-2 sm:w-auto w-full">
            <button
              @click="cariBarang"
              class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded w-full sm:w-auto"
            >
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            <button
              @click="openItemScanner"
              class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full sm:w-auto"
            >
              <i class="fa-solid fa-qrcode"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- SCANNER ITEM -->
      <div
        v-if="showItemScanner"
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
        @click.self="closeItemScanner"
      >
        <div class="bg-white p-4 rounded-lg w-full max-w-md">
          <div class="flex justify-between mb-3">
            <h3 class="font-bold">Scan Barang</h3>
            <button @click="closeItemScanner">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
          <div id="reader-item" class="w-full"></div>
          <button
            @click="closeItemScanner"
            class="mt-3 w-full bg-red-500 text-white py-2 rounded"
          >
            Tutup
          </button>
        </div>
      </div>

      <!-- ACTION BUTTON -->
      <div class="mb-4">
        <button
          @click="logoutLokasi"
          class="bg-red-500 hover:bg-red-600 text-white py-3 rounded w-full font-semibold shadow disabled:opacity-70"
          :disabled="isLoading"
        >
          <span v-if="!isLoading">Logout</span>
          <span v-else>Loading...</span>
        </button>
      </div>

      <div class="flex justify-between items-center mb-3">
        <div class="text-sm text-gray-600">
          Total Item : <span class="font-semibold">{{ items.length }}</span>
        </div>
        <div class="flex items-center gap-2">
          <button
            @click="exportStocktakingPdf"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center gap-2 disabled:opacity-70"
            :disabled="isLoading || isExportingPdf"
          >
            <i class="fa-solid fa-file-pdf"></i>
            <span>{{ isExportingPdf ? "Exporting..." : "Export " }}</span>
          </button>
          <button
            @click="refreshData"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded flex items-center gap-2 disabled:opacity-70"
            :disabled="isLoading || isExportingPdf"
          >
            <i class="fa-solid fa-rotate-right"></i>
            <span>{{ isLoading ? "Loading..." : "Refresh" }}</span>
          </button>
        </div>
      </div>

      <!-- TABLE -->
      <!-- DESKTOP TABLE -->
      <div class="hidden md:block bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-green-600 text-white">
            <tr>
              <th class="p-2">SEQ</th>
              <th class="p-2">PLU</th>
              <th class="p-2">Barcode</th>
              <th class="p-2 text-left">Nama Barang</th>
              <th class="p-2">UoM</th>
              <th class="p-2">Qty</th>
              <th class="p-2">Note</th>
              <th class="p-2">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-black">
            <tr
              v-for="(item, index) in items"
              :key="index"
              class="border-b hover:bg-gray-50"
            >
              <td class="p-2 text-center">{{ item.sec }}</td>
              <td class="p-2">{{ item.kode_plu }}</td>
              <td class="p-2">{{ item.kode_barcode }}</td>
              <td class="p-2">{{ item.nama_barang }}</td>
              <td class="p-2 text-center">{{ item.uom }}</td>
              <td class="p-2 text-center">{{ item.qty }}</td>
              <td class="p-2 text-center max-w-[150px] truncate">
                {{ item.note || "-" }}
              </td>
              <td class="p-2 text-center">
                <button
                  @click="openItemLogPopup(item)"
                  class="bg-amber-500 hover:bg-amber-600 text-white w-8 h-8 rounded-full mr-1"
                >
                  <i class="fa-solid fa-eye"></i>
                </button>
                <button
                  @click="editItem(index)"
                  class="bg-blue-500 hover:bg-blue-600 text-white w-8 h-8 rounded-full mr-1"
                >
                  <i class="fa-solid fa-pen"></i>
                </button>
                <button
                  @click="hapusItem(index)"
                  class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-full"
                >
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- MOBILE CARD -->
      <div class="md:hidden space-y-3">
        <div
          v-for="(item, index) in items"
          :key="index"
          class="bg-white shadow rounded-lg p-3"
        >
          <div class="flex justify-between items-center mb-2">
            <div class="font-semibold text-green-700">
              #{{ item.sec }} - {{ item.kode_plu }}
            </div>
            <div class="flex gap-1">
              <button
                @click="openItemLogPopup(item)"
                class="bg-amber-500 text-white w-8 h-8 rounded-full mr-1"
              >
                <i class="fa-solid fa-eye"></i>
              </button>
              <button
                @click="editItem(index)"
                class="bg-blue-500 text-white w-8 h-8 rounded-full"
              >
                <i class="fa-solid fa-pen"></i>
              </button>
              <button
                @click="hapusItem(index)"
                class="bg-red-500 text-white w-8 h-8 rounded-full"
              >
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </div>
          <div class="text-sm text-gray-600">
            <div>
              <span class="font-semibold">Barcode:</span>
              {{ item.kode_barcode }}
            </div>
            <div>
              <span class="font-semibold">Nama:</span> {{ item.nama_barang }}
            </div>
            <div><span class="font-semibold">UoM:</span> {{ item.uom }}</div>
            <div><span class="font-semibold">Qty:</span> {{ item.qty }}</div>
            <div v-if="item.note">
              <span class="font-semibold">Note:</span> {{ item.note }}
            </div>
          </div>
        </div>
      </div>

      <div class="text-center text-gray-400 mt-3 text-sm">End of list</div>
    </div>

    <!-- FOOTER -->
    <div class="bg-white shadow p-4 flex justify-center">
      <button
        @click="goToListSO"
        class="bg-green-600 hover:bg-green-700 text-white px-8 py-2 rounded shadow font-semibold disabled:opacity-70"
        :disabled="isLoading"
      >
        <span v-if="!isLoading">LIST SO</span>
        <span v-else>Loading...</span>
      </button>
    </div>
  </div>
</template>

<script>
import { Html5Qrcode } from "html5-qrcode";
import axios from "axios";

const teamBackendUrl =
  import.meta.env.VITE_BACKEND_2 || import.meta.env.VITE_BACKEND;
const TOKEN_CLOSE_BUFFER_MS = 15000;

export default {
  name: "StockOpname",
  data() {
    return {
      items: [],
      barcodeInput: "",
      showModal: false,
      showItemScanner: false,
      showItemDetail: false,
      showSearchResultPopup: false,
      showNotFoundPopup: false,
      showPendingSavePopup: false,
      pendingSaveActionLabel: "logout",
      showItemLogPopup: false,
      notFoundCode: "",
      notFoundMessage: "",
      notFoundItemDetail: {
        nama_barang: "",
        kode_plu: "",
        kode_barcode: "",
        uom: "",
      },
      // ===== SCANNER =====
      showScanner: false,
      scanner: null,
      cameras: [],
      cameraId: "",
      flashOn: false,
      flashAvailable: false,
      isSwitchingCamera: false,
      // ===== INPUT =====
      kodeLokasi: "",
      scanResult: "",
      // ===== STATUS =====
      isLoading: false,
      errorMessage: "",
      successMessage: "",
      // ===== DATA DARI LOCAL STORAGE =====
      currentKodeLokasi: "",
      penghitung1Name: "",
      penghitung2Name: "",
      teamData: null,
      backendItems: [],
      itemLogItem: {
        kode_plu: "",
        kode_barcode: "",
        nama_barang: "",
        is_decimal: 0,
      },
      itemLogs: [],
      itemLogLoading: false,
      itemLogError: "",
      // ===== DETAIL ITEM =====
      selectedItem: {
        item_detail_id: "",
        barang_id: "",
        kode_plu: "",
        kode_barcode: "",
        nama_barang: "",
        uom: "",
        tipe_barang: "",
        is_decimal: 0,
      },
      itemQuantity: 1,
      itemNote: "",
      editIndex: null,
      searchResultItems: [],
      pendingSearchKeyword: "",
      pendingSearchCommand: null,
      isExportingPdf: false,
      tokenExpiryInterval: null,
      locationStatusInterval: null,
      tokenExpiryTimeout: null,
      draftSyncTimeout: null,
      isClosingExpiredSession: false,
      forceAllowRouteLeave: false,
    };
  },
  mounted() {
    if (!this.verifyTokenSession()) {
      return;
    }

    this.loadDataFromLocalStorage();
    this.loadItemsFromLocalStorage();
    this.monitorActiveLocationStatus();
    this.fetchItemsByLocationTeam();
    this.tokenExpiryInterval = window.setInterval(() => {
      this.verifyTokenSession();
    }, 30000);
    this.locationStatusInterval = window.setInterval(() => {
      this.monitorActiveLocationStatus();
    }, 15000);
    this.scheduleTokenExpiryHandling();
  },
  beforeUnmount() {
    if (this.tokenExpiryInterval) {
      clearInterval(this.tokenExpiryInterval);
      this.tokenExpiryInterval = null;
    }

    if (this.locationStatusInterval) {
      clearInterval(this.locationStatusInterval);
      this.locationStatusInterval = null;
    }

    if (this.tokenExpiryTimeout) {
      clearTimeout(this.tokenExpiryTimeout);
      this.tokenExpiryTimeout = null;
    }

    if (this.draftSyncTimeout) {
      clearTimeout(this.draftSyncTimeout);
      this.draftSyncTimeout = null;
    }
  },
  computed: {
    hasCurrentPendingChanges() {
      return this.hasPendingLocationChanges();
    },

    groupedItemLogs() {
      const grouped = new Map();

      this.itemLogs.forEach((log, index) => {
        const timestamp = this.getItemLogTimestamp(log);
        const dateKey = timestamp
          ? `${timestamp.getFullYear()}-${String(timestamp.getMonth() + 1).padStart(2, "0")}-${String(timestamp.getDate()).padStart(2, "0")}`
          : `unknown-${index}`;

        if (!grouped.has(dateKey)) {
          grouped.set(dateKey, {
            dateKey,
            dateLabel: timestamp
              ? this.formatItemLogDate(timestamp)
              : "Tanggal tidak diketahui",
            sortValue: timestamp ? timestamp.getTime() : 0,
            logs: [],
          });
        }

        grouped.get(dateKey).logs.push({
          ...log,
          timeLabel: timestamp ? this.formatItemLogTime(timestamp) : "-",
          qtyLabel: this.getItemLogQty(log),
          sortValue: timestamp ? timestamp.getTime() : 0,
        });
      });

      return Array.from(grouped.values())
        .map((group) => ({
          ...group,
          logs: group.logs.sort((a, b) => b.sortValue - a.sortValue),
        }))
        .sort((a, b) => b.sortValue - a.sortValue);
    },
  },
  methods: {
    openChangeLocationModal() {
      if (!this.ensureNoPendingLocationChanges("berpindah lokasi")) {
        return;
      }

      this.showModal = true;
    },

    decodeJwtPayload(token) {
      try {
        const payload = token.split(".")[1];

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
    },

    isTokenExpired(token) {
      const payload = this.decodeJwtPayload(token);

      if (!payload?.exp) {
        return false;
      }

      const currentTimestamp = Math.floor(Date.now() / 1000);
      return payload.exp <= currentTimestamp;
    },

    getTokenExpiryDelay(token, bufferMs = TOKEN_CLOSE_BUFFER_MS) {
      const payload = this.decodeJwtPayload(token);

      if (!payload?.exp) {
        return null;
      }

      return payload.exp * 1000 - Date.now() - bufferMs;
    },

    scheduleTokenExpiryHandling() {
      if (this.tokenExpiryTimeout) {
        clearTimeout(this.tokenExpiryTimeout);
        this.tokenExpiryTimeout = null;
      }

      const token = localStorage.getItem("token");

      if (!token) {
        return;
      }

      const expiryDelay = this.getTokenExpiryDelay(token);

      if (expiryDelay === null) {
        return;
      }

      if (expiryDelay <= 0) {
        this.clearSessionAndRedirectToLogin(
          "Sesi login akan berakhir, lokasi ditutup otomatis",
        );
        return;
      }

      this.tokenExpiryTimeout = window.setTimeout(() => {
        this.clearSessionAndRedirectToLogin(
          "Sesi login akan berakhir, lokasi ditutup otomatis",
        );
      }, expiryDelay);
    },

    verifyTokenSession() {
      const token = localStorage.getItem("token");

      if (!token) {
        this.$router.push("/");
        return false;
      }

      if (this.isTokenExpired(token)) {
        this.clearSessionAndRedirectToLogin();
        return false;
      }

      this.scheduleTokenExpiryHandling();
      return true;
    },

    async closeActiveLocationBeforeRedirect(token, kodeLokasi) {
      if (!token || !kodeLokasi) {
        return;
      }

      try {
        await axios.post(
          `${teamBackendUrl}/api/auth/logout/location`,
          { kode_lokasi: kodeLokasi },
          {
            headers: {
              Authorization: "Bearer " + token,
            },
          },
        );
      } catch (error) {
        console.warn("Gagal menutup lokasi saat sesi berakhir:", error);
      }
    },

    async clearSessionAndRedirectToLogin(
      message = "Sesi login berakhir, silakan login kembali",
    ) {
      if (this.isClosingExpiredSession) {
        return;
      }

      this.isClosingExpiredSession = true;

      if (this.tokenExpiryTimeout) {
        clearTimeout(this.tokenExpiryTimeout);
        this.tokenExpiryTimeout = null;
      }

      const token = localStorage.getItem("token");
      const kodeLokasi =
        this.currentKodeLokasi ||
        localStorage.getItem("current_kode_lokasi") ||
        "";

      await this.closeActiveLocationBeforeRedirect(token, kodeLokasi);

      localStorage.removeItem("token");
      localStorage.removeItem("current_kode_lokasi");
      this.errorMessage = message;
      this.showModal = false;
      this.showScanner = false;
      this.showItemScanner = false;
      this.showItemDetail = false;
      this.showItemLogPopup = false;
      this.showNotFoundPopup = false;
      this.showPendingSavePopup = false;

      setTimeout(() => {
        this.$router.push("/");
        this.isClosingExpiredSession = false;
      }, 300);
    },

    hasExpiredTokenMessage(message) {
      const normalizedMessage = String(message || "").toLowerCase();

      return (
        normalizedMessage.includes("token expired") ||
        normalizedMessage.includes("expired token") ||
        normalizedMessage.includes("unauthorized") ||
        normalizedMessage.includes("token tidak valid") ||
        normalizedMessage.includes("invalid token") ||
        normalizedMessage.includes("jwt expired")
      );
    },

    handleExpiredToken(error) {
      const statusCode = error?.response?.status;
      const responseMessage =
        error?.response?.data?.message || error?.message || "";

      const isExpiredToken =
        statusCode === 401 || this.hasExpiredTokenMessage(responseMessage);

      if (!isExpiredToken) {
        return false;
      }

      this.clearSessionAndRedirectToLogin();
      return true;
    },

    handleExpiredTokenResponse(responseData) {
      if (
        responseData?.success === false &&
        this.hasExpiredTokenMessage(responseData?.message)
      ) {
        this.clearSessionAndRedirectToLogin();
        return true;
      }

      return false;
    },

    getItemLogRawTimestamp(log = {}) {
      return (
        log.DATE_CREATE ||
        log.DATE_UPDATE ||
        log.updatedAt ||
        log.updated_at ||
        log.createdAt ||
        log.created_at ||
        log.log_date ||
        log.LOG_DATE ||
        log.tanggal ||
        log.datetime ||
        log.timestamp ||
        ""
      );
    },

    getItemLogTimestamp(log = {}) {
      const rawTimestamp = this.getItemLogRawTimestamp(log);

      if (!rawTimestamp) {
        return null;
      }

      const parsedDate = new Date(rawTimestamp);
      return Number.isNaN(parsedDate.getTime()) ? null : parsedDate;
    },

    formatItemLogDate(date) {
      return new Intl.DateTimeFormat("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
      }).format(date);
    },

    formatItemLogTime(date) {
      return new Intl.DateTimeFormat("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
      }).format(date);
    },

    formatItemLogQtyValue(value, isDecimal = 0) {
      const numericValue = Number(String(value ?? "0").replace(",", "."));

      if (!Number.isFinite(numericValue)) {
        return String(value ?? "-");
      }

      if (Number(isDecimal) === 0) {
        return String(Math.trunc(numericValue));
      }

      return numericValue.toLocaleString("id-ID", {
        minimumFractionDigits: 3,
        maximumFractionDigits: 3,
      });
    },

    getItemLogQty(log = {}) {
      const rawQty =
        log.qty ??
        log.QTY ??
        log.qty_baru ??
        log.LOG_NEWQTY ??
        log.new_qty ??
        "-";

      if (rawQty === "-") {
        return "-";
      }

      return this.formatItemLogQtyValue(
        rawQty,
        this.itemLogItem?.is_decimal ?? 0,
      );
    },

    // =======================
    // FORMAT KODE LOKASI
    // =======================
    formatKodeLokasi(event) {
      let value = event.target.value.replace(/\./g, ""); // Hapus semua titik
      if (value.length > 6) value = value.slice(0, 6); // Batasi maksimal 6 digit

      let formattedValue = value;
      if (value.length > 3) {
        formattedValue =
          value.slice(0, 1) + "." + value.slice(1, 3) + "." + value.slice(3);
      } else if (value.length > 1) {
        formattedValue = value.slice(0, 1) + "." + value.slice(1);
      }

      event.target.value = formattedValue;
      this.kodeLokasi = formattedValue;
    },

    // =======================
    // LOAD DATA DARI LOCAL STORAGE
    // =======================
    loadDataFromLocalStorage() {
      this.currentKodeLokasi =
        localStorage.getItem("current_kode_lokasi") || "";
      this.teamData = null;
      this.penghitung1Name = "-";
      this.penghitung2Name = "-";
      this.backendItems = [];

      if (this.currentKodeLokasi) {
        const storageKey = `lokasi_${this.currentKodeLokasi}`;
        const locationData = localStorage.getItem(storageKey);

        if (locationData) {
          const parsedData = JSON.parse(locationData);
          this.teamData = parsedData.team;
          const teamMembers = this.extractTeamMemberNames(parsedData);
          this.penghitung1Name = teamMembers.penghitung1Name;
          this.penghitung2Name = teamMembers.penghitung2Name;
          this.backendItems = Array.isArray(parsedData.backend_items)
            ? parsedData.backend_items.map((item, index) =>
                this.mapBackendLocationItem(item, index),
              )
            : [];
        }
      }
    },

    extractTeamMemberNames(locationData = {}) {
      const team = locationData?.team || {};

      const penghitung1Name =
        team?.penghitung_1 ||
        team?.SOPT_PENGHITUNG ||
        team?.penghitung1 ||
        locationData?.penghitung_1 ||
        "-";

      const penghitung2Name =
        team?.penghitung_2 ||
        team?.SOPT_HELPER ||
        team?.penghitung2 ||
        locationData?.penghitung_2 ||
        "-";

      return {
        penghitung1Name,
        penghitung2Name,
      };
    },

    buildStoredTeamData(teamData = {}, locationData = {}) {
      const currentTeam =
        teamData && typeof teamData === "object" && !Array.isArray(teamData)
          ? teamData
          : {};
      const fallbackTeam =
        locationData?.team &&
        typeof locationData.team === "object" &&
        !Array.isArray(locationData.team)
          ? locationData.team
          : {};

      const penghitung_1 =
        currentTeam.penghitung_1 ||
        currentTeam.SOPT_PENGHITUNG ||
        currentTeam.penghitung1 ||
        fallbackTeam.penghitung_1 ||
        fallbackTeam.SOPT_PENGHITUNG ||
        fallbackTeam.penghitung1 ||
        locationData?.penghitung_1 ||
        this.penghitung1Name ||
        "-";

      const penghitung_2 =
        currentTeam.penghitung_2 ||
        currentTeam.SOPT_HELPER ||
        currentTeam.penghitung2 ||
        fallbackTeam.penghitung_2 ||
        fallbackTeam.SOPT_HELPER ||
        fallbackTeam.penghitung2 ||
        locationData?.penghitung_2 ||
        this.penghitung2Name ||
        "-";

      const kode_team =
        currentTeam.kode_team ||
        currentTeam.KODE_TEAM ||
        currentTeam.kodeTeam ||
        fallbackTeam.kode_team ||
        fallbackTeam.KODE_TEAM ||
        fallbackTeam.kodeTeam ||
        locationData?.kode_team ||
        "";

      return {
        ...fallbackTeam,
        ...currentTeam,
        ...(kode_team ? { kode_team } : {}),
        penghitung_1,
        penghitung_2,
        loginTimestamp:
          currentTeam.loginTimestamp ||
          fallbackTeam.loginTimestamp ||
          locationData?.metadata?.loginTimestamp ||
          locationData?.loginTimestamp ||
          new Date().toISOString(),
        lastUpdated: new Date().toISOString(),
      };
    },

    buildActiveTeamMessage(activeTeam) {
      if (!activeTeam || typeof activeTeam !== "object") {
        return "Lokasi tersebut sedang digunakan oleh team yang lain";
      }

      const teamLabel =
        activeTeam.kode_team || activeTeam.no_team || "Team lain";
      const penghitung1 = activeTeam.penghitung_1 || "-";
      const penghitung2 = activeTeam.penghitung_2 || "-";

      return `Lokasi tersebut sedang digunakan oleh ${teamLabel} (${penghitung1} & ${penghitung2})`;
    },

    // =======================
    // LOAD ITEMS DARI LOCAL STORAGE
    // =======================
    loadItemsFromLocalStorage() {
      if (this.currentKodeLokasi) {
        const storageKey = `lokasi_${this.currentKodeLokasi}`;
        const locationData = localStorage.getItem(storageKey);

        if (locationData) {
          const parsedData = JSON.parse(locationData);
          if (parsedData.items && Array.isArray(parsedData.items)) {
            this.items = this.reindexItemsSec(parsedData.items);
          } else {
            this.items = [];
          }
        }
      }
    },

    reindexItemsSec(items = []) {
      if (!Array.isArray(items)) {
        return [];
      }

      return items.map((item, index) => ({
        ...item,
        sec: index + 1,
      }));
    },

    mapBackendLocationItem(item, index) {
      const kodePlu =
        item.kode_plu || item.ISITEAMITEMDETAIL_BRG_CODE || item.plu || "";
      const kodeBarcode =
        item.kode_barcode ||
        item.ISITEAMITEMDETAIL_BRG_BARCODE ||
        item.barcode ||
        "";
      const namaBarang =
        item.nama_barang || item.ISITEAMITEMDETAIL_BRG_NAME || item.nama || "";
      const uom = item.uom || item.ISITEAMITEMDETAIL_BRG_UOM || "-";
      const tipeBarang =
        item.tipe_barang || item.ISITEAMITEMDETAIL_BRG_TYPE || "";
      const isDecimal =
        Number(
          item.is_decimal ??
            item.IS_DECIMAL ??
            item.ISITEAMITEMDETAIL_IS_DECIMAL,
        ) || 0;
      const rawQty =
        item.qty ?? item.ISITEAMITEMDETAIL_QTY ?? item.stock_qty ?? 0;

      return {
        item_detail_id:
          item.item_detail_id ||
          item.ISITEAMITEMDETAIL_ID ||
          item.LOG_ISITEAMITEMDETAIL_ID ||
          "",
        barang_id:
          item.barang_id ||
          item.ISITEAMITEMDETAIL_BRG_ID ||
          item.BRG_ID ||
          item.id ||
          "",
        kode_plu: this.normalizeItemCode(kodePlu),
        kode_barcode: String(kodeBarcode || "").trim(),
        nama_barang: namaBarang,
        uom,
        tipe_barang: tipeBarang,
        is_decimal: isDecimal,
        sec:
          Number(item.sec ?? item.SEQ ?? item.seq) > 0
            ? Number(item.sec ?? item.SEQ ?? item.seq)
            : index + 1,
        qty: this.formatStoredQuantity(rawQty, isDecimal),
        note: item.note || item.ISITEAMITEMDETAIL_DESC || "",
        createdAt:
          item.createdAt ||
          item.DATE_CREATE ||
          item.created_at ||
          new Date().toISOString(),
        backendIndex: index,
      };
    },

    getItemIdentityKey(item = {}) {
      return (
        String(item.barang_id || "").trim() ||
        String(item.kode_barcode || "").trim() ||
        this.normalizeItemCode(item.kode_plu || "")
      );
    },

    getEditedItemsSnapshot() {
      if (!this.currentKodeLokasi) {
        return [];
      }

      const storageKey = `lokasi_${this.currentKodeLokasi}`;
      const locationData = localStorage.getItem(storageKey);

      if (!locationData) {
        return [];
      }

      try {
        const parsedData = JSON.parse(locationData);
        return Array.isArray(parsedData.edited_items)
          ? parsedData.edited_items
          : [];
      } catch (error) {
        console.error("Gagal membaca edited_items dari localStorage:", error);
        return [];
      }
    },

    getLatestEditedItemsByKey() {
      const editedItems = this.getEditedItemsSnapshot();
      const latestEdits = new Map();

      editedItems.forEach((editedItem, index) => {
        const itemKey = this.getItemIdentityKey(editedItem);

        if (!itemKey) {
          return;
        }

        latestEdits.set(itemKey, {
          ...editedItem,
          __order: index,
        });
      });

      return Array.from(latestEdits.values()).sort(
        (a, b) => (a.__order || 0) - (b.__order || 0),
      );
    },

    buildDraftProgressPayload() {
      if (!this.currentKodeLokasi) {
        return null;
      }

      const latestEditedItems = this.getLatestEditedItemsByKey();

      return {
        kode_lokasi: this.currentKodeLokasi,
        total_items: Array.isArray(this.items) ? this.items.length : 0,
        items: latestEditedItems
          .map((item) => {
            const itemIdentityKey = this.getItemIdentityKey(item);

            if (!itemIdentityKey) {
              return null;
            }

            return {
              item_identity_key: itemIdentityKey,
              item_detail_id: item.item_detail_id || null,
              barang_id: item.barang_id || null,
              kode_plu: item.kode_plu || null,
              kode_barcode: item.kode_barcode || null,
              nama_barang: item.nama_barang || null,
              uom: item.uom || null,
              draft_action: item.terhapus
                ? "deleted"
                : item.is_new_item
                  ? "added"
                  : "edited",
              is_counted: !item.terhapus,
              draft_qty: item.terhapus
                ? null
                : this.parseStoredQuantity(
                    item.qty_baru ?? item.qty ?? item.qty_sebelumnya ?? 0,
                    item.is_decimal,
                  ),
              draft_note:
                item.note_baru ?? item.note ?? item.note_sebelumnya ?? null,
              source_updated_at:
                item.updatedAt || item.deletedAt || new Date().toISOString(),
            };
          })
          .filter(Boolean),
      };
    },

    scheduleDraftProgressSync(force = false) {
      if (!this.currentKodeLokasi) {
        return;
      }

      if (this.draftSyncTimeout) {
        clearTimeout(this.draftSyncTimeout);
      }

      this.draftSyncTimeout = window.setTimeout(() => {
        this.syncDraftProgressToBackend({ force });
      }, 500);
    },

    async syncDraftProgressToBackend({ force = false } = {}) {
      if (
        !this.currentKodeLokasi ||
        (!force && !this.hasPendingLocationChanges())
      ) {
        return;
      }

      const payload = this.buildDraftProgressPayload();

      if (!payload) {
        return;
      }

      try {
        const response = await axios.post(
          `${teamBackendUrl}/api/report/progress/draft`,
          payload,
          {
            headers: {
              Authorization: "Bearer " + (localStorage.getItem("token") || ""),
            },
          },
        );

        if (this.handleExpiredTokenResponse(response.data)) {
          return;
        }

        if (response.data?.success === false) {
          throw new Error(
            response.data?.message || "Gagal sinkron draft progress",
          );
        }
      } catch (error) {
        console.error("Gagal sinkron draft progress:", error);
      }
    },

    hasPendingLocationChanges(kodeLokasi = this.currentKodeLokasi) {
      if (!kodeLokasi) {
        return false;
      }

      const storageKey = `lokasi_${kodeLokasi}`;
      const locationData = localStorage.getItem(storageKey);

      if (!locationData) {
        return false;
      }

      try {
        const parsedData = JSON.parse(locationData);
        return (
          Array.isArray(parsedData.edited_items) &&
          parsedData.edited_items.length > 0
        );
      } catch (error) {
        console.error("Gagal membaca perubahan lokasi:", error);
        return false;
      }
    },

    showPendingLocationChangesMessage(actionLabel = "berpindah lokasi") {
      if (actionLabel === "logout" || actionLabel === "berpindah lokasi") {
        this.pendingSaveActionLabel = actionLabel;
        this.showPendingSavePopup = true;
      } else {
        this.errorMessage = `Masih ada perubahan pada lokasi ${this.currentKodeLokasi}. Tekan tombol LIST SO dulu agar data terkirim ke database sebelum ${actionLabel}.`;
        setTimeout(() => {
          this.errorMessage = "";
        }, 3000);
      }
    },

    closePendingSavePopup() {
      this.showPendingSavePopup = false;
      this.pendingSaveActionLabel = "logout";
    },

    ensureNoPendingLocationChanges(actionLabel = "berpindah lokasi") {
      if (!this.hasPendingLocationChanges()) {
        return true;
      }

      this.showPendingLocationChangesMessage(actionLabel);
      return false;
    },

    extractLocationOpenStatus(responseData = {}) {
      const candidates = [
        responseData?.ISITEAMITEM_IS_OPEN,
        responseData?.data?.ISITEAMITEM_IS_OPEN,
        responseData?.data?.lokasi?.ISITEAMITEM_IS_OPEN,
        responseData?.lokasi?.ISITEAMITEM_IS_OPEN,
        Array.isArray(responseData?.data)
          ? responseData.data[0]?.ISITEAMITEM_IS_OPEN
          : undefined,
        Array.isArray(responseData?.items)
          ? responseData.items[0]?.ISITEAMITEM_IS_OPEN
          : undefined,
      ];

      const matchedStatus = candidates.find(
        (value) => value !== undefined && value !== null && value !== "",
      );

      if (matchedStatus === undefined) {
        return null;
      }

      const numericStatus = Number(matchedStatus);
      return Number.isNaN(numericStatus) ? null : numericStatus;
    },

    isLocationOpenStatus(status) {
      return Number(status) === 0;
    },

    isClosedLocationResponse(payload, statusCode = null) {
      const message = String(payload?.message || "").toLowerCase();

      if (statusCode === 403 || statusCode === 404) {
        return true;
      }

      return (
        message.includes("sudah ditutup") ||
        message.includes("tidak tersedia untuk team ini") ||
        message.includes("kode lokasi tidak ditemukan")
      );
    },

    async checkLocationLoginStatus(kodeLokasi) {
      try {
        const response = await axios.post(
          `${teamBackendUrl}/api/item/location/team`,
          { kode_lokasi: kodeLokasi },
          {
            headers: {
              Authorization: "Bearer " + (localStorage.getItem("token") || ""),
            },
          },
        );

        if (this.handleExpiredTokenResponse(response.data)) {
          return {
            aborted: true,
            isOpen: false,
            status: null,
          };
        }

        if (
          response.data?.success === false &&
          this.isClosedLocationResponse(response.data, response.status)
        ) {
          return {
            aborted: false,
            isOpen: false,
            status: 1,
          };
        }

        const status = this.extractLocationOpenStatus(response.data);

        return {
          aborted: false,
          isOpen: status === null ? true : this.isLocationOpenStatus(status),
          status,
        };
      } catch (error) {
        if (this.handleExpiredToken(error)) {
          return {
            aborted: true,
            isOpen: false,
            status: null,
          };
        }

        const statusCode = error.response?.status ?? null;
        const payload = error.response?.data ?? null;

        if (this.isClosedLocationResponse(payload, statusCode)) {
          return {
            aborted: false,
            isOpen: false,
            status: 1,
          };
        }

        throw error;
      }
    },

    async monitorActiveLocationStatus() {
      if (this.isLoading || this.isClosingExpiredSession) {
        return;
      }

      const kodeLokasi =
        this.currentKodeLokasi ||
        localStorage.getItem("current_kode_lokasi") ||
        "";

      if (!kodeLokasi) {
        return;
      }

      try {
        const loginStatus = await this.checkLocationLoginStatus(kodeLokasi);

        if (loginStatus.aborted) {
          return;
        }

        if (loginStatus.status !== null && !loginStatus.isOpen) {
          this.forceAllowRouteLeave = true;
          this.resetLocationState({
            preserveToken: true,
          });
          this.errorMessage =
            "Lokasi telah ditutup oleh admin. Silakan login ulang lokasi.";
          setTimeout(() => {
            this.errorMessage = "";
            this.$router.push("/lokasi");
          }, 1200);
        }
      } catch (error) {
        console.error("Gagal memeriksa status lokasi aktif:", error);
      }
    },

    redirectToLokasiBecauseClosedByAdmin() {
      this.forceAllowRouteLeave = true;
      this.resetLocationState({
        preserveToken: true,
      });
      this.errorMessage =
        "Lokasi telah ditutup oleh admin. Silakan login ulang lokasi.";
      setTimeout(() => {
        this.errorMessage = "";
        this.$router.push("/lokasi");
      }, 1200);
    },

    resetLocationState({
      preserveToken = false,
      preserveModal = false,
      preserveKodeLokasi = "",
    } = {}) {
      if (!preserveToken) {
        localStorage.removeItem("token");
      }

      localStorage.removeItem("current_kode_lokasi");
      this.currentKodeLokasi = "";
      this.teamData = null;
      this.penghitung1Name = "";
      this.penghitung2Name = "";
      this.items = [];
      this.backendItems = [];
      this.barcodeInput = "";
      this.kodeLokasi = preserveKodeLokasi;
      this.showModal = preserveModal;
      this.showScanner = false;
      this.showItemScanner = false;
      this.showItemDetail = false;
      this.showItemLogPopup = false;
      this.showNotFoundPopup = false;
      this.showPendingSavePopup = false;
      this.notFoundMessage = "";
      this.notFoundItemDetail = {
        nama_barang: "",
        kode_plu: "",
        kode_barcode: "",
        uom: "",
      };
      this.itemLogs = [];
      this.itemLogError = "";
      this.itemLogLoading = false;
    },

    buildUpdateQtyPayload() {
      return {
        kode_lokasi: this.currentKodeLokasi,
        items: this.reindexItemsSec(this.items).map((item) => ({
          kode: this.normalizeItemCode(
            item.kode_plu || item.kode_barcode || "",
          ),
          sec: Number(item.sec) > 0 ? Number(item.sec) : 1,
          qty: this.parseStoredQuantity(item.qty, item.is_decimal),
          desc: item.note || "",
        })),
      };
    },

    buildUpdateLogsPayload() {
      const logs = this.getEditedItemsSnapshot()
        .filter((item) => !item.terhapus && item.item_detail_id)
        .map((item) => ({
          LOG_ISITEAMITEMDETAIL_ID: item.item_detail_id,
          LOG_NEWQTY: this.parseStoredQuantity(
            item.qty_sebelumnya ?? item.qty ?? 0,
            item.is_decimal,
          ),
          barang_data: {
            ISITEAMITEMDETAIL_BRG_CODE: this.normalizeItemCode(
              item.kode_plu || "",
            ),
          },
        }));

      return {
        kode_lokasi: this.currentKodeLokasi,
        logs,
      };
    },

    buildDeleteItemsPayload() {
      return this.getLatestEditedItemsByKey()
        .filter((item) => item.terhapus && item.item_detail_id)
        .map((item) => ({
          item_detail_id: item.item_detail_id,
          kode_lokasi: this.currentKodeLokasi,
        }));
    },

    buildDeletedItemsReportPayload() {
      return this.getLatestEditedItemsByKey()
        .filter((item) => item.terhapus)
        .map((item) => ({
          item_detail_id: item.item_detail_id || null,
          barang_id: item.barang_id || null,
          kode_plu: item.kode_plu || null,
          kode_barcode: item.kode_barcode || null,
          nama_barang: item.nama_barang || null,
          uom: item.uom || null,
          qty_sebelumnya: item.qty_sebelumnya ?? item.qty ?? null,
          note_sebelumnya: item.note_sebelumnya ?? item.note ?? null,
          deletedAt: item.deletedAt || null,
        }));
    },

    async syncItemsToBackend() {
      const payload = this.buildUpdateQtyPayload();

      if (payload.items.length === 0) {
        return;
      }

      const response = await axios.post(
        `${teamBackendUrl}/api/item/update/qty`,
        payload,
        {
          headers: {
            Authorization: "Bearer " + (localStorage.getItem("token") || ""),
          },
        },
      );

      if (this.handleExpiredTokenResponse(response.data)) {
        return;
      }

      if (response.data?.success === false) {
        throw new Error(response.data?.message || "Gagal update qty");
      }
    },

    async syncLogsToBackend() {
      const payload = this.buildUpdateLogsPayload();

      if (payload.logs.length === 0) {
        return;
      }

      const response = await axios.post(
        `${teamBackendUrl}/api/log/update/itemLog`,
        payload,
        {
          headers: {
            Authorization: "Bearer " + (localStorage.getItem("token") || ""),
          },
        },
      );

      if (this.handleExpiredTokenResponse(response.data)) {
        return;
      }

      if (response.data?.success === false) {
        throw new Error(response.data?.message || "Gagal update log");
      }
    },

    async syncDeletedItemsToBackend() {
      const payloads = this.buildDeleteItemsPayload();

      if (payloads.length === 0) {
        return;
      }

      await Promise.all(
        payloads.map((payload) =>
          axios
            .post(`${teamBackendUrl}/api/item/delete`, payload, {
              headers: {
                Authorization:
                  "Bearer " + (localStorage.getItem("token") || ""),
              },
            })
            .then((response) => {
              if (this.handleExpiredTokenResponse(response.data)) {
                return;
              }

              if (response.data?.success === false) {
                throw new Error(response.data?.message || "Gagal hapus item");
              }
            }),
        ),
      );
    },

    mergeItemsWithBackendItems(mappedBackendItems) {
      if (
        !Array.isArray(mappedBackendItems) ||
        mappedBackendItems.length === 0
      ) {
        return Array.isArray(this.items) ? this.items : [];
      }

      const editedItems = this.getEditedItemsSnapshot();
      const deletedItemKeys = new Set();

      editedItems.forEach((editedItem) => {
        const itemKey = this.getItemIdentityKey(editedItem);

        if (!itemKey) {
          return;
        }

        if (editedItem.terhapus) {
          deletedItemKeys.add(itemKey);
        } else {
          deletedItemKeys.delete(itemKey);
        }
      });

      const filteredBackendItems = mappedBackendItems.filter((backendItem) => {
        const itemKey = this.getItemIdentityKey(backendItem);
        return itemKey ? !deletedItemKeys.has(itemKey) : true;
      });

      const mergedItems = filteredBackendItems.map((backendItem) => {
        const existingItem = this.items.find(
          (item) =>
            item.kode_barcode === backendItem.kode_barcode ||
            item.kode_plu === backendItem.kode_plu,
        );

        if (!existingItem) {
          return { ...backendItem };
        }

        return {
          ...backendItem,
          item_detail_id:
            existingItem.item_detail_id || backendItem.item_detail_id || "",
          qty: existingItem.qty ?? backendItem.qty,
          note: existingItem.note ?? backendItem.note,
          createdAt: existingItem.createdAt || backendItem.createdAt,
          updatedAt: existingItem.updatedAt || backendItem.updatedAt,
        };
      });

      const localOnlyItems = this.items.filter(
        (item) =>
          !filteredBackendItems.some(
            (backendItem) =>
              backendItem.kode_barcode === item.kode_barcode ||
              backendItem.kode_plu === item.kode_plu,
          ),
      );

      return this.reindexItemsSec([...mergedItems, ...localOnlyItems]);
    },

    async saveLocationData(patch = {}) {
      if (!this.currentKodeLokasi) return;

      const storageKey = `lokasi_${this.currentKodeLokasi}`;
      const existingData = localStorage.getItem(storageKey);
      const parsedData = existingData ? JSON.parse(existingData) : {};
      const storedTeam = this.buildStoredTeamData(
        patch.team ?? parsedData.team ?? this.teamData ?? {},
        parsedData,
      );

      const updatedData = {
        kode_lokasi: this.currentKodeLokasi,
        team: storedTeam,
        items: this.reindexItemsSec(
          patch.items ?? parsedData.items ?? this.items,
        ),
        backend_items: Array.isArray(patch.backend_items)
          ? patch.backend_items.map((item, index) =>
              this.mapBackendLocationItem(item, index),
            )
          : Array.isArray(parsedData.backend_items)
            ? parsedData.backend_items.map((item, index) =>
                this.mapBackendLocationItem(item, index),
              )
            : Array.isArray(this.backendItems)
              ? this.backendItems.map((item, index) =>
                  this.mapBackendLocationItem(item, index),
                )
              : [],
        edited_items: patch.edited_items ?? parsedData.edited_items ?? [],
      };

      this.teamData = storedTeam;
      this.penghitung1Name = storedTeam.penghitung_1 || "-";
      this.penghitung2Name = storedTeam.penghitung_2 || "-";

      localStorage.setItem(storageKey, JSON.stringify(updatedData));
    },

    async fetchItemsByLocationTeam(kodeLokasi = this.currentKodeLokasi) {
      if (!kodeLokasi) return;

      try {
        const response = await axios.post(
          `${teamBackendUrl}/api/item/location/team`,
          { kode_lokasi: kodeLokasi },
          {
            headers: {
              Authorization: "Bearer " + (localStorage.getItem("token") || ""),
            },
          },
        );

        if (this.handleExpiredTokenResponse(response.data)) {
          return;
        }

        if (
          response.data?.success === false &&
          this.isClosedLocationResponse(response.data, response.status)
        ) {
          this.redirectToLokasiBecauseClosedByAdmin();
          return;
        }

        if (!response.data?.success) {
          return;
        }

        const backendItems = Array.isArray(response.data.data)
          ? response.data.data
          : Array.isArray(response.data.items)
            ? response.data.items
            : [];

        const mappedBackendItems = backendItems.map((item, index) =>
          this.mapBackendLocationItem(item, index),
        );

        this.backendItems = mappedBackendItems;
        this.items = this.reindexItemsSec(
          this.mergeItemsWithBackendItems(mappedBackendItems),
        );
        await this.saveLocationData({
          backend_items: mappedBackendItems,
          items: this.items,
        });
        await this.syncDraftProgressToBackend({ force: true });
      } catch (err) {
        console.error("Gagal memuat barang berdasarkan lokasi:", err);
        if (this.handleExpiredToken(err)) {
          return;
        }

        const statusCode = err.response?.status ?? null;
        const payload = err.response?.data ?? null;

        if (this.isClosedLocationResponse(payload, statusCode)) {
          this.redirectToLokasiBecauseClosedByAdmin();
        }
      }
    },

    getExistingTeamData() {
      if (this.teamData) {
        return this.teamData;
      }

      const currentKodeLokasi = localStorage.getItem("current_kode_lokasi");

      if (!currentKodeLokasi) return null;

      const locationData = localStorage.getItem(`lokasi_${currentKodeLokasi}`);

      if (!locationData) return null;

      try {
        const parsedData = JSON.parse(locationData);
        return parsedData.team || null;
      } catch (error) {
        console.error("Gagal membaca team dari localStorage:", error);
        return null;
      }
    },

    // =======================
    // SAVE ITEMS KE LOCAL STORAGE
    // =======================
    async saveItemsToLocalStorage() {
      if (!this.currentKodeLokasi) {
        console.warn("Tidak ada kode lokasi yang aktif");
        return;
      }

      this.items = this.reindexItemsSec(this.items);
      await this.saveLocationData({
        team: this.teamData,
        items: this.items,
        backend_items: Array.isArray(this.backendItems)
          ? this.backendItems.map((item, index) =>
              this.mapBackendLocationItem(item, index),
            )
          : [],
      });

      this.scheduleDraftProgressSync();
    },

    saveEditedItemToLocalStorage(editedItem) {
      if (!this.currentKodeLokasi) {
        console.warn("Tidak ada kode lokasi yang aktif");
        return;
      }

      const storageKey = `lokasi_${this.currentKodeLokasi}`;
      const locationData = localStorage.getItem(storageKey);

      if (!locationData) return;

      const parsedData = JSON.parse(locationData);
      if (!Array.isArray(parsedData.edited_items)) {
        parsedData.edited_items = [];
      }

      parsedData.edited_items.push(editedItem);
      parsedData.team = this.buildStoredTeamData(parsedData.team, parsedData);
      localStorage.setItem(storageKey, JSON.stringify(parsedData));
      this.scheduleDraftProgressSync();
    },

    markItemAsPendingAddition(item, overrides = {}) {
      const timestamp = new Date().toISOString();

      this.saveEditedItemToLocalStorage({
        item_detail_id: item?.item_detail_id || "",
        barang_id: item?.barang_id || "",
        kode_plu: item?.kode_plu || "",
        kode_barcode: item?.kode_barcode || "",
        nama_barang: item?.nama_barang || "",
        uom: item?.uom || "",
        tipe_barang: item?.tipe_barang || "",
        is_decimal: item?.is_decimal ?? 0,
        qty_sebelumnya: overrides.qty_sebelumnya ?? null,
        qty_baru: overrides.qty_baru ?? item?.qty ?? null,
        note_sebelumnya: overrides.note_sebelumnya ?? "",
        note_baru: overrides.note_baru ?? item?.note ?? "",
        createdAt: item?.createdAt || timestamp,
        updatedAt: overrides.updatedAt ?? item?.updatedAt ?? timestamp,
        is_new_item: true,
      });
    },

    // =======================
    // CLOSE NOT FOUND POPUP
    // =======================
    closeNotFoundPopup() {
      this.showNotFoundPopup = false;
      this.notFoundCode = "";
      this.notFoundMessage = "";
      this.notFoundItemDetail = {
        nama_barang: "",
        kode_plu: "",
        kode_barcode: "",
        uom: "",
      };
    },

    mapNotFoundItemDetail(payload = {}) {
      return {
        nama_barang: payload?.nama_barang || "",
        kode_plu: payload?.kode_plu || "",
        kode_barcode: payload?.kode_barcode || "",
        uom: payload?.uom || "",
      };
    },

    normalizeItemCode(code) {
      const cleanedCode = String(code || "").trim();

      if (/^\d+$/.test(cleanedCode) && cleanedCode.length < 6) {
        return cleanedCode.padStart(6, "0");
      }

      return cleanedCode;
    },

    parseStoredQuantity(value, isDecimal = false) {
      if (typeof value === "number") {
        return Number.isFinite(value) ? value : 0;
      }

      const rawValue = String(value ?? "").trim();

      if (!rawValue) {
        return 0;
      }

      let normalizedValue = rawValue;

      if (isDecimal) {
        const lastCommaIndex = rawValue.lastIndexOf(",");
        const lastDotIndex = rawValue.lastIndexOf(".");
        const decimalSeparatorIndex = Math.max(lastCommaIndex, lastDotIndex);

        if (decimalSeparatorIndex !== -1) {
          const integerPart = rawValue
            .slice(0, decimalSeparatorIndex)
            .replace(/[.,]/g, "");
          const fractionalPart = rawValue
            .slice(decimalSeparatorIndex + 1)
            .replace(/[.,]/g, "");

          normalizedValue = `${integerPart}.${fractionalPart}`;
        } else {
          normalizedValue = rawValue.replace(/[^\d-]/g, "");
        }
      } else {
        normalizedValue = rawValue.replace(/[.,]/g, "");
      }

      const parsedValue = Number(normalizedValue);
      return Number.isFinite(parsedValue) ? parsedValue : 0;
    },

    formatStoredQuantity(value, isDecimal) {
      const parsedValue = this.parseStoredQuantity(value, isDecimal);

      if (isDecimal) {
        return parsedValue.toFixed(3).replace(".", ",");
      }

      return Math.trunc(parsedValue);
    },

    parseBarcodeCommand(input) {
      const rawInput = String(input || "").trim();
      const matchedCommand = rawInput.match(/^(\d+(?:[.,]\d+)?)\s*\*\s*(.+)$/);

      if (!matchedCommand) {
        return {
          qty: null,
          code: this.normalizeItemCode(rawInput),
          rawInput,
          isBulkCommand: false,
        };
      }

      const qty = Number(matchedCommand[1].replace(",", "."));
      const code = this.normalizeItemCode(matchedCommand[2]);

      return {
        qty: Number.isFinite(qty) ? qty : null,
        code,
        rawInput,
        isBulkCommand: true,
      };
    },

    findExistingItemIndex(itemData = {}, searchCode = "") {
      const normalizedSearchCode = this.normalizeItemCode(searchCode);
      const normalizedPlu = this.normalizeItemCode(itemData.kode_plu || "");
      const normalizedBarcode = String(itemData.kode_barcode || "").trim();

      return this.items.findIndex((item) => {
        const itemPlu = this.normalizeItemCode(item.kode_plu || "");
        const itemBarcode = String(item.kode_barcode || "").trim();

        return (
          (normalizedBarcode && itemBarcode === normalizedBarcode) ||
          (normalizedPlu && itemPlu === normalizedPlu) ||
          (normalizedSearchCode &&
            (itemPlu === normalizedSearchCode ||
              itemBarcode === normalizedSearchCode))
        );
      });
    },

    buildItemPayload(itemData, quantity, note = "", timestamps = {}) {
      return {
        item_detail_id: itemData.item_detail_id || "",
        barang_id: itemData.barang_id || "",
        kode_plu: itemData.kode_plu || "",
        kode_barcode: itemData.kode_barcode || "",
        nama_barang: itemData.nama_barang || "",
        uom: itemData.uom || "",
        tipe_barang: itemData.tipe_barang || "",
        is_decimal: itemData.is_decimal || 0,
        sec:
          Number(itemData.sec) > 0
            ? Number(itemData.sec)
            : this.items.length + 1,
        qty: this.formatStoredQuantity(quantity, itemData.is_decimal),
        note,
        createdAt: timestamps.createdAt || new Date().toISOString(),
        ...(timestamps.updatedAt ? { updatedAt: timestamps.updatedAt } : {}),
      };
    },

    async tambahItemTanpaPopup(itemData, quantity) {
      if (!(quantity > 0)) {
        this.errorMessage = "Qty harus lebih dari 0";
        setTimeout(() => {
          this.errorMessage = "";
        }, 2000);
        return;
      }

      const existingIndex = this.items.findIndex(
        (item) =>
          item.kode_barcode === itemData.kode_barcode ||
          item.kode_plu === itemData.kode_plu,
      );

      if (existingIndex !== -1) {
        const existingItem = this.items[existingIndex];
        const currentQty = this.parseStoredQuantity(
          existingItem.qty,
          existingItem.is_decimal,
        );
        const nextQty = currentQty + Number(quantity);
        const updatedAt = new Date().toISOString();

        this.saveEditedItemToLocalStorage({
          item_detail_id: existingItem.item_detail_id || "",
          barang_id: existingItem.barang_id,
          kode_plu: existingItem.kode_plu,
          kode_barcode: existingItem.kode_barcode,
          nama_barang: existingItem.nama_barang,
          uom: existingItem.uom,
          tipe_barang: existingItem.tipe_barang,
          is_decimal: existingItem.is_decimal,
          qty_sebelumnya: existingItem.qty,
          qty_baru: this.formatStoredQuantity(nextQty, existingItem.is_decimal),
          note_sebelumnya: existingItem.note || "",
          note_baru: existingItem.note || "",
          createdAt: existingItem.createdAt || null,
          updatedAt,
        });

        this.items[existingIndex] = {
          ...existingItem,
          qty: this.formatStoredQuantity(nextQty, existingItem.is_decimal),
          updatedAt,
        };
        this.successMessage = "Qty berhasil ditambahkan tanpa membuka popup";
      } else {
        const newItem = this.buildItemPayload(itemData, quantity);
        this.items.unshift(newItem);
        this.items = this.reindexItemsSec(this.items);
        this.markItemAsPendingAddition(newItem);
        this.successMessage = "Item berhasil ditambahkan tanpa membuka popup";
      }

      await this.saveItemsToLocalStorage();
      this.barcodeInput = "";

      setTimeout(() => {
        this.successMessage = "";
      }, 2000);
    },

    closeSearchResultPopup() {
      this.showSearchResultPopup = false;
      this.searchResultItems = [];
      this.pendingSearchKeyword = "";
      this.pendingSearchCommand = null;
    },

    async selectSearchResultItem(itemData) {
      const pendingCommand = this.pendingSearchCommand || {
        isBulkCommand: false,
        qty: 0,
      };

      this.closeSearchResultPopup();

      const existingIndex = this.findExistingItemIndex(
        itemData,
        itemData.kode_plu || itemData.kode_barcode || "",
      );

      this.selectedItem = {
        item_detail_id:
          itemData.item_detail_id || itemData.ISITEAMITEMDETAIL_ID || "",
        barang_id: itemData.barang_id || "",
        kode_plu: itemData.kode_plu || "",
        kode_barcode: itemData.kode_barcode || "",
        nama_barang: itemData.nama_barang || "",
        uom: itemData.uom || "",
        tipe_barang: itemData.tipe_barang || "",
        is_decimal: itemData.is_decimal || 0,
      };

      if (pendingCommand.isBulkCommand) {
        await this.tambahItemTanpaPopup(itemData, pendingCommand.qty);
        return;
      }

      if (existingIndex !== -1) {
        const existingItem = this.items[existingIndex];
        this.selectedItem = {
          item_detail_id:
            existingItem.item_detail_id || this.selectedItem.item_detail_id,
          barang_id: existingItem.barang_id || this.selectedItem.barang_id,
          kode_plu: existingItem.kode_plu || this.selectedItem.kode_plu,
          kode_barcode:
            existingItem.kode_barcode || this.selectedItem.kode_barcode,
          nama_barang:
            existingItem.nama_barang || this.selectedItem.nama_barang,
          uom: existingItem.uom || this.selectedItem.uom,
          tipe_barang:
            existingItem.tipe_barang || this.selectedItem.tipe_barang,
          is_decimal:
            existingItem.is_decimal ?? this.selectedItem.is_decimal,
        };
        this.itemQuantity = this.parseStoredQuantity(
          existingItem.qty,
          existingItem.is_decimal,
        );
        this.itemNote = existingItem.note || "";
        this.editIndex = existingIndex;
      } else {
        this.itemQuantity = 1;
        this.itemNote = "";
        this.editIndex = null;
      }

      this.showItemDetail = true;
      this.barcodeInput = "";
    },

    // =======================
    // CARI BARANG VIA API
    // =======================
    async cariBarang() {
      const barcodeCommand = this.parseBarcodeCommand(this.barcodeInput);
      const searchCode = barcodeCommand.code;

      if (!searchCode) {
        this.errorMessage = "Masukkan barcode/PLU";
        return;
      }

      if (
        barcodeCommand.isBulkCommand &&
        (!(barcodeCommand.qty > 0) || !Number.isFinite(barcodeCommand.qty))
      ) {
        this.errorMessage = "Format qty*kode tidak valid";
        setTimeout(() => {
          this.errorMessage = "";
        }, 2000);
        return;
      }

      this.isLoading = true;
      this.errorMessage = "";

      try {
        const response = await axios.post(
          `${teamBackendUrl}/api/item/code`,
          {
            kode: searchCode,
            kode_lokasi: this.currentKodeLokasi || "",
          },
          {
            headers: {
              Authorization: "Bearer " + (localStorage.getItem("token") || ""),
            },
          },
        );

        console.log("Response API:", response.data);

        if (this.handleExpiredTokenResponse(response.data)) {
          return;
        }

        if (response.data && response.data.success) {
          if (Array.isArray(response.data?.data?.items)) {
            this.searchResultItems = response.data.data.items;
            this.pendingSearchKeyword = response.data.data.keyword || searchCode;
            this.pendingSearchCommand = barcodeCommand;
            this.showSearchResultPopup = true;
            this.barcodeInput = "";
            return;
          }

          // Mengambil data dari response API
          const data = response.data.data;
          const existingIndex = this.findExistingItemIndex(data, searchCode);

          this.selectedItem = {
            item_detail_id:
              data.item_detail_id || data.ISITEAMITEMDETAIL_ID || "",
            barang_id: data.barang_id || "",
            kode_plu: data.kode_plu || "",
            kode_barcode: data.kode_barcode || "",
            nama_barang: data.nama_barang || "",
            uom: data.uom || "",
            tipe_barang: data.tipe_barang || "",
            is_decimal: data.is_decimal || 0,
          };

          if (barcodeCommand.isBulkCommand) {
            await this.tambahItemTanpaPopup(data, barcodeCommand.qty);
          } else {
            if (existingIndex !== -1) {
              const existingItem = this.items[existingIndex];

              this.selectedItem = {
                item_detail_id:
                  existingItem.item_detail_id ||
                  this.selectedItem.item_detail_id,
                barang_id:
                  existingItem.barang_id || this.selectedItem.barang_id,
                kode_plu: existingItem.kode_plu || this.selectedItem.kode_plu,
                kode_barcode:
                  existingItem.kode_barcode || this.selectedItem.kode_barcode,
                nama_barang:
                  existingItem.nama_barang || this.selectedItem.nama_barang,
                uom: existingItem.uom || this.selectedItem.uom,
                tipe_barang:
                  existingItem.tipe_barang || this.selectedItem.tipe_barang,
                is_decimal:
                  existingItem.is_decimal ?? this.selectedItem.is_decimal,
              };
              this.itemQuantity = this.parseStoredQuantity(
                existingItem.qty,
                existingItem.is_decimal,
              );
              this.itemNote = existingItem.note || "";
              this.editIndex = existingIndex;
            } else {
              this.itemQuantity = 1;
              this.itemNote = "";
              this.editIndex = null;
            }

            this.showItemDetail = true;
            this.barcodeInput = "";
          }
        } else {
          // Tampilkan popup not found dengan animasi
          this.notFoundCode = searchCode;
          this.notFoundMessage =
            response.data?.message ||
            `Kode barang "${searchCode}" tidak ditemukan di sistem.`;
          this.notFoundItemDetail = this.mapNotFoundItemDetail(
            response.data?.data,
          );
          this.showNotFoundPopup = true;
          this.barcodeInput = "";
        }
      } catch (err) {
        console.error(err);
        if (this.handleExpiredToken(err)) {
          return;
        }
        // Tampilkan popup not found dengan animasi
        this.notFoundCode = searchCode;
        this.notFoundMessage =
          err.response?.data?.message ||
          `Kode barang "${searchCode}" tidak ditemukan di sistem.`;
        this.notFoundItemDetail = this.mapNotFoundItemDetail(
          err.response?.data?.data,
        );
        this.showNotFoundPopup = true;
        this.barcodeInput = "";
      } finally {
        this.isLoading = false;
      }
    },

    // =======================
    // TAMBAH KE DAFTAR
    // =======================
    async tambahKeDaftar() {
      if (this.itemQuantity <= 0) {
        this.errorMessage = "Qty harus lebih dari 0";
        setTimeout(() => {
          this.errorMessage = "";
        }, 2000);
        return;
      }

      const newItem = {
        item_detail_id: this.selectedItem.item_detail_id || "",
        barang_id: this.selectedItem.barang_id,
        kode_plu: this.selectedItem.kode_plu,
        kode_barcode: this.selectedItem.kode_barcode,
        nama_barang: this.selectedItem.nama_barang,
        uom: this.selectedItem.uom,
        tipe_barang: this.selectedItem.tipe_barang,
        is_decimal: this.selectedItem.is_decimal,
        sec:
          this.editIndex !== null
            ? this.items[this.editIndex]?.sec || this.editIndex + 1
            : this.items.length + 1,
        qty: this.formatStoredQuantity(
          this.itemQuantity,
          this.selectedItem.is_decimal,
        ),
        note: this.itemNote || "",
        createdAt: new Date().toISOString(),
      };

      if (this.editIndex !== null) {
        // Edit existing item
        const existingItem = this.items[this.editIndex];
        const updatedAt = new Date().toISOString();

        newItem.createdAt = existingItem?.createdAt || newItem.createdAt;
        newItem.updatedAt = updatedAt;
        this.items[this.editIndex] = newItem;
        this.saveEditedItemToLocalStorage({
          item_detail_id:
            existingItem?.item_detail_id || newItem.item_detail_id || "",
          barang_id: existingItem?.barang_id || newItem.barang_id,
          kode_plu: existingItem?.kode_plu || newItem.kode_plu,
          kode_barcode: existingItem?.kode_barcode || newItem.kode_barcode,
          nama_barang: existingItem?.nama_barang || newItem.nama_barang,
          uom: existingItem?.uom || newItem.uom,
          tipe_barang: existingItem?.tipe_barang || newItem.tipe_barang,
          is_decimal: existingItem?.is_decimal ?? newItem.is_decimal,
          qty_sebelumnya: existingItem?.qty ?? null,
          qty_baru: newItem.qty,
          note_sebelumnya: existingItem?.note || "",
          note_baru: newItem.note,
          createdAt: existingItem?.createdAt || null,
          updatedAt,
        });
        this.successMessage = "Item berhasil diupdate";
      } else {
        // Check if item already exists
        const existingIndex = this.items.findIndex(
          (item) => item.kode_barcode === this.selectedItem.kode_barcode,
        );

        if (existingIndex !== -1) {
          // Add qty to existing item
          const currentQty = this.parseStoredQuantity(
            this.items[existingIndex].qty,
            this.items[existingIndex].is_decimal,
          );
          const nextQty = currentQty + Number(this.itemQuantity);
          const updatedAt = new Date().toISOString();
          const existingItem = this.items[existingIndex];

          this.items[existingIndex].qty = this.formatStoredQuantity(
            nextQty,
            this.items[existingIndex].is_decimal,
          );
          if (this.itemNote) {
            this.items[existingIndex].note = this.itemNote;
          }
          this.items[existingIndex].updatedAt = updatedAt;
          this.saveEditedItemToLocalStorage({
            item_detail_id: existingItem?.item_detail_id || "",
            barang_id: existingItem?.barang_id || "",
            kode_plu: existingItem?.kode_plu || "",
            kode_barcode: existingItem?.kode_barcode || "",
            nama_barang: existingItem?.nama_barang || "",
            uom: existingItem?.uom || "",
            tipe_barang: existingItem?.tipe_barang || "",
            is_decimal: existingItem?.is_decimal ?? 0,
            qty_sebelumnya: existingItem?.qty ?? null,
            qty_baru: this.items[existingIndex].qty,
            note_sebelumnya: existingItem?.note || "",
            note_baru: this.items[existingIndex].note || "",
            createdAt: existingItem?.createdAt || null,
            updatedAt,
            is_new_item: !existingItem?.item_detail_id,
          });
          this.successMessage =
            "Qty berhasil ditambahkan ke item yang sudah ada";
        } else {
          // Add new item
          this.items.unshift(newItem);
          this.items = this.reindexItemsSec(this.items);
          this.markItemAsPendingAddition(newItem);
          this.successMessage = "Item berhasil ditambahkan";
        }
      }

      // Simpan items ke localStorage
      await this.saveItemsToLocalStorage();

      setTimeout(() => {
        this.successMessage = "";
      }, 2000);

      this.closeItemDetail();
    },

    async openItemLogPopup(item) {
      const kodeBarang = this.normalizeItemCode(
        item?.kode_plu || item?.kode_barcode || "",
      );

      if (!this.currentKodeLokasi || !kodeBarang) {
        this.errorMessage = "Data barang tidak lengkap";
        setTimeout(() => {
          this.errorMessage = "";
        }, 2000);
        return;
      }

      this.itemLogItem = {
        kode_plu: item?.kode_plu || "",
        kode_barcode: item?.kode_barcode || "",
        nama_barang: item?.nama_barang || "",
        is_decimal: Number(item?.is_decimal ?? 0) || 0,
      };
      this.itemLogs = [];
      this.itemLogError = "";
      this.itemLogLoading = true;
      this.showItemLogPopup = true;

      try {
        const response = await axios.post(
          `${teamBackendUrl}/api/log/get/itemLog`,
          {
            kode_lokasi: this.currentKodeLokasi,
            kode_barang: kodeBarang,
          },
          {
            headers: {
              Authorization: "Bearer " + (localStorage.getItem("token") || ""),
            },
          },
        );

        if (this.handleExpiredTokenResponse(response.data)) {
          return;
        }

        if (response.data?.success === false) {
          throw new Error(response.data?.message || "Gagal memuat riwayat");
        }

        const responsePayload = response.data?.data || {};
        const barangData = responsePayload?.barang || {};
        const logs = Array.isArray(barangData?.perubahan)
          ? barangData.perubahan
          : Array.isArray(responsePayload?.perubahan)
            ? responsePayload.perubahan
            : Array.isArray(response.data?.logs)
              ? response.data.logs
              : Array.isArray(response.data?.items)
                ? response.data.items
                : Array.isArray(response.data?.data)
                  ? response.data.data
                  : [];

        this.itemLogItem = {
          kode_plu:
            item?.kode_plu ||
            barangData?.KODE_BARANG ||
            barangData?.kode_barang ||
            "",
          kode_barcode:
            item?.kode_barcode ||
            barangData?.BARCODE ||
            barangData?.barcode ||
            "",
          nama_barang:
            item?.nama_barang ||
            barangData?.NAMA_BARANG ||
            barangData?.nama_barang ||
            "",
          is_decimal:
            Number(
              barangData?.IS_DECIMAL ??
                barangData?.is_decimal ??
                item?.is_decimal ??
                0,
            ) || 0,
        };

        this.itemLogs = logs;
      } catch (err) {
        console.error("Gagal memuat item log:", err);
        if (this.handleExpiredToken(err)) {
          return;
        }
        this.itemLogError =
          err.response?.data?.message || err.message || "Gagal memuat riwayat";
      } finally {
        this.itemLogLoading = false;
      }
    },

    closeItemLogPopup() {
      this.showItemLogPopup = false;
      this.itemLogLoading = false;
      this.itemLogError = "";
      this.itemLogs = [];
      this.itemLogItem = {
        kode_plu: "",
        kode_barcode: "",
        nama_barang: "",
        is_decimal: 0,
      };
    },

    // =======================
    // EDIT ITEM
    // =======================
    editItem(index) {
      const item = this.items[index];
      this.selectedItem = {
        item_detail_id: item.item_detail_id || "",
        barang_id: item.barang_id,
        kode_plu: item.kode_plu,
        kode_barcode: item.kode_barcode,
        nama_barang: item.nama_barang,
        uom: item.uom,
        tipe_barang: item.tipe_barang,
        is_decimal: item.is_decimal,
      };
      this.itemQuantity = this.parseStoredQuantity(item.qty, item.is_decimal);
      this.itemNote = item.note || "";
      this.editIndex = index;
      this.showItemDetail = true;
    },

    async hapusItem(index) {
      const item = this.items[index];

      if (!item) return;

      if (!confirm(`Hapus barang ${item.nama_barang}?`)) {
        return;
      }

      const deletedAt = new Date().toISOString();

      this.saveEditedItemToLocalStorage({
        item_detail_id: item.item_detail_id || "",
        barang_id: item.barang_id,
        kode_plu: item.kode_plu,
        kode_barcode: item.kode_barcode,
        nama_barang: item.nama_barang,
        uom: item.uom,
        tipe_barang: item.tipe_barang,
        is_decimal: item.is_decimal,
        qty_sebelumnya: item.qty,
        qty_baru: null,
        note_sebelumnya: item.note || "",
        note_baru: null,
        createdAt: item.createdAt || null,
        updatedAt: item.updatedAt || null,
        terhapus: true,
        deletedAt,
      });

      this.items.splice(index, 1);
      this.items = this.reindexItemsSec(this.items);
      await this.saveItemsToLocalStorage();

      this.successMessage = "Item berhasil dihapus";
      setTimeout(() => {
        this.successMessage = "";
      }, 2000);
    },

    // =======================
    // CLOSE ITEM DETAIL
    // =======================
    closeItemDetail() {
      this.showItemDetail = false;
      this.selectedItem = {
        item_detail_id: "",
        barang_id: "",
        kode_plu: "",
        kode_barcode: "",
        nama_barang: "",
        uom: "",
        tipe_barang: "",
        is_decimal: 0,
      };
      this.itemQuantity = 1;
      this.itemNote = "";
      this.editIndex = null;
    },

    // =======================
    // SCANNER ITEM
    // =======================
    async openItemScanner() {
      this.showItemScanner = true;
      await this.$nextTick();
      await this.getCameras();
      await this.startItemScanner();
    },

    closeItemScanner() {
      this.showItemScanner = false;
      if (this.scanner) {
        this.scanner
          .stop()
          .then(() => {
            this.scanner.clear();
            this.scanner = null;
          })
          .catch(() => {
            this.scanner = null;
          });
      }
    },

    async startItemScanner() {
      if (this.scanner) {
        await this.scanner.stop();
        await this.scanner.clear();
      }

      this.scanner = new Html5Qrcode("reader-item");

      try {
        await this.scanner.start(
          { deviceId: this.cameraId },
          { fps: 10, qrbox: { width: 250, height: 250 } },
          async (decodedText) => {
            // Cari barang via API
            this.barcodeInput = decodedText;
            await this.cariBarang();
            this.closeItemScanner();
          },
        );
      } catch (err) {
        console.error(err);
      }
    },

    // =======================
    // SCANNER POPUP
    // =======================
    async openScannerPopup() {
      this.showScanner = true;
      await this.$nextTick();
      await this.getCameras();
      await this.startScanner();
    },

    closeScannerPopup() {
      this.showScanner = false;
      if (this.scanner) {
        this.scanner
          .stop()
          .then(() => {
            this.scanner.clear();
            this.scanner = null;
          })
          .catch(() => {
            this.scanner = null;
          });
      }
    },

    // =======================
    // CAMERA
    // =======================
    async getCameras() {
      try {
        const devices = await Html5Qrcode.getCameras();
        if (devices && devices.length) {
          this.cameras = devices;
          const backCamera = devices.find(
            (device) =>
              device.label.toLowerCase().includes("back") ||
              device.label.toLowerCase().includes("rear") ||
              device.label.toLowerCase().includes("environment"),
          );
          this.cameraId = backCamera ? backCamera.id : devices[0].id;
        }
      } catch (err) {
        console.error(err);
      }
    },

    async startScanner() {
      if (this.scanner) {
        await this.scanner.stop();
        await this.scanner.clear();
      }

      this.scanner = new Html5Qrcode("reader");

      try {
        await this.scanner.start(
          { deviceId: this.cameraId },
          { fps: 10, qrbox: { width: 250, height: 250 } },
          (decodedText) => {
            this.kodeLokasi = decodedText;
            this.successMessage = "Berhasil scan: " + decodedText;
            this.closeScannerPopup();
          },
        );
        this.checkFlash();
      } catch (err) {
        console.error(err);
        alert("Tidak bisa membuka kamera");
      }
    },

    // =======================
    // SWITCH CAMERA
    // =======================
    async toggleCamera() {
      if (this.isSwitchingCamera) return;
      this.isSwitchingCamera = true;
      try {
        const currentIndex = this.cameras.findIndex(
          (c) => c.id === this.cameraId,
        );
        const nextIndex = (currentIndex + 1) % this.cameras.length;
        this.cameraId = this.cameras[nextIndex].id;
        await this.closeScannerPopup();
        await this.$nextTick();
        await this.openScannerPopup();
      } catch (err) {
        console.error("Gagal ganti kamera:", err);
      }
      this.isSwitchingCamera = false;
    },

    // =======================
    // FLASH
    // =======================
    async checkFlash() {
      try {
        const track = this.scanner.getRunningTrack();
        if (!track) return;
        const capabilities = track.getCapabilities();
        this.flashAvailable = !!capabilities.torch;
      } catch (e) {
        this.flashAvailable = false;
      }
    },

    async toggleFlash() {
      try {
        const track = this.scanner.getRunningTrack();
        if (!track) return;
        const capabilities = track.getCapabilities();
        if (!capabilities.torch) {
          alert("Flash tidak didukung");
          return;
        }
        this.flashOn = !this.flashOn;
        await track.applyConstraints({
          advanced: [{ torch: this.flashOn }],
        });
      } catch (err) {
        console.error(err);
      }
    },

    // =======================
    // SUBMIT SCAN LOKASI
    // =======================
    async submitScan() {
      if (!this.ensureNoPendingLocationChanges("berpindah lokasi")) {
        return;
      }

      if (!this.kodeLokasi) {
        this.errorMessage = "Kode lokasi belum diisi";
        return;
      }

      this.isLoading = true;
      this.errorMessage = "";
      this.successMessage = "";

      try {
        const targetKodeLokasi = this.kodeLokasi;
        const currentKodeLokasi =
          this.currentKodeLokasi ||
          localStorage.getItem("current_kode_lokasi") ||
          "";

        if (currentKodeLokasi && currentKodeLokasi === targetKodeLokasi) {
          this.errorMessage = `Lokasi ${targetKodeLokasi} sedang aktif`;
          return;
        }

        if (
          currentKodeLokasi &&
          currentKodeLokasi !== targetKodeLokasi &&
          this.hasPendingLocationChanges(currentKodeLokasi)
        ) {
          this.showPendingLocationChangesMessage("berpindah lokasi");
          return;
        }

        if (currentKodeLokasi && currentKodeLokasi !== targetKodeLokasi) {
          const loginStatus = await this.checkLocationLoginStatus(
            currentKodeLokasi,
          );

          if (loginStatus.aborted) {
            return;
          }

          if (loginStatus.status === null || loginStatus.isOpen) {
            const logoutSuccess = await this.logoutLocationByCode(
              currentKodeLokasi,
              {
                redirectToLogin: false,
                preserveToken: true,
                preserveModal: true,
                preserveKodeLokasi: targetKodeLokasi,
              },
            );

            if (!logoutSuccess) {
              return;
            }
          }
        }

        const response = await axios.post(
          `${teamBackendUrl}/api/auth/login/location`,
          { kode_lokasi: targetKodeLokasi },
          {
            headers: {
              Authorization: "Bearer " + (localStorage.getItem("token") || ""),
            },
          },
        );

        if (this.handleExpiredTokenResponse(response.data)) {
          return;
        }

        if (response.data.success) {
          const storageKey = `lokasi_${targetKodeLokasi}`;
          const activeTeam = this.getExistingTeamData() || response.data.team;

          // Cek apakah sudah ada data sebelumnya
          const existingData = localStorage.getItem(storageKey);
          let existingItems = [];
          let existingEditedItems = [];
          let existingTeam = {};
          if (existingData) {
            const parsedData = JSON.parse(existingData);
            existingItems = parsedData.items || [];
            existingEditedItems = parsedData.edited_items || [];
            existingTeam =
              parsedData.team &&
              typeof parsedData.team === "object" &&
              !Array.isArray(parsedData.team)
                ? parsedData.team
                : {};
          }

          const locationData = {
            kode_lokasi: targetKodeLokasi,
            team: this.buildStoredTeamData(
              response.data.team || activeTeam || {},
              {
                team: existingTeam,
                loginTimestamp:
                  existingTeam.loginTimestamp || new Date().toISOString(),
              },
            ),
            items: existingItems, // Mempertahankan items yang sudah ada
            backend_items: [],
            edited_items: existingEditedItems,
          };

          localStorage.setItem(storageKey, JSON.stringify(locationData));
          localStorage.setItem("current_kode_lokasi", targetKodeLokasi);
          this.currentKodeLokasi = targetKodeLokasi;

          this.successMessage = `Berhasil masuk dengan lokasi ${targetKodeLokasi}!`;
          this.loadDataFromLocalStorage();
          this.loadItemsFromLocalStorage();
          await this.fetchItemsByLocationTeam(targetKodeLokasi);

          setTimeout(() => {
            this.showModal = false;
          }, 1500);
        } else {
          this.errorMessage = response.data.message || "Gagal";
        }
      } catch (err) {
        console.error(err);
        if (this.handleExpiredToken(err)) {
          return;
        }
        const activeTeam = err.response?.data?.active_team;
        this.errorMessage =
          (activeTeam && this.buildActiveTeamMessage(activeTeam)) ||
          err.response?.data?.message ||
          "Error";
      } finally {
        this.isLoading = false;
      }
    },

    clearLocationSessionState() {
      this.resetLocationState();
    },

    async logoutLocationByCode(
      kodeLokasi,
      {
        redirectToLogin = true,
        preserveToken = false,
        preserveModal = false,
        preserveKodeLokasi = "",
      } = {},
    ) {
      if (!kodeLokasi) {
        this.resetLocationState({
          preserveToken,
          preserveModal,
          preserveKodeLokasi,
        });

        if (redirectToLogin) {
          this.$router.push("/");
        }

        return true;
      }

      const response = await axios.post(
        `${teamBackendUrl}/api/auth/logout/location`,
        { kode_lokasi: kodeLokasi },
        {
          headers: {
            Authorization: "Bearer " + (localStorage.getItem("token") || ""),
          },
        },
      );

      if (this.handleExpiredTokenResponse(response.data)) {
        return false;
      }

      if (!response.data?.success) {
        throw new Error(response.data?.message || "Logout lokasi gagal");
      }

      this.resetLocationState({
        preserveToken,
        preserveModal,
        preserveKodeLokasi,
      });

      if (redirectToLogin) {
        this.$router.push("/");
      }

      return true;
    },

    // =======================
    // LOGOUT LOKASI
    // =======================
    async logoutLokasi() {
      if (!this.ensureNoPendingLocationChanges("logout")) {
        return;
      }

      if (!confirm("Apakah Anda yakin ingin logout dari lokasi ini?")) {
        return;
      }

      const kodeLokasi =
        this.currentKodeLokasi ||
        localStorage.getItem("current_kode_lokasi") ||
        "";

      if (!kodeLokasi) {
        this.clearLocationSessionState();
        this.$router.push("/");
        return;
      }

      this.isLoading = true;
      this.errorMessage = "";
      this.successMessage = "";

      try {
        const loginStatus = await this.checkLocationLoginStatus(kodeLokasi);

        if (loginStatus.aborted) {
          return;
        }

        if (loginStatus.status !== null && !loginStatus.isOpen) {
          this.clearLocationSessionState();
          this.$router.push("/");
          return;
        }

        await this.logoutLocationByCode(kodeLokasi);
      } catch (err) {
        console.error(err);
        if (this.handleExpiredToken(err)) {
          return;
        }
        this.errorMessage =
          err.response?.data?.message || err.message || "Logout gagal";
      } finally {
        this.isLoading = false;
      }
    },

    // =======================
    // REFRESH DATA
    // =======================
    async refreshData() {
      this.loadDataFromLocalStorage();
      this.loadItemsFromLocalStorage();
      await this.fetchItemsByLocationTeam();
      this.successMessage = "Data berhasil direfresh";
      setTimeout(() => {
        this.successMessage = "";
      }, 2000);
    },

    async exportStocktakingPdf() {
      if (!this.currentKodeLokasi) {
        this.errorMessage = "Kode lokasi tidak ditemukan";
        setTimeout(() => {
          this.errorMessage = "";
        }, 2000);
        return;
      }

      this.isExportingPdf = true;
      this.errorMessage = "";
      this.successMessage = "";

      try {
        const reportYear = new Date().getFullYear();
        const response = await axios.post(
          `${teamBackendUrl}/api/report/stocktaking/pdf`,
          {
            kode_lokasi: this.currentKodeLokasi,
            year: reportYear,
            deleted_items: this.buildDeletedItemsReportPayload(),
          },
          {
            headers: {
              Authorization: "Bearer " + (localStorage.getItem("token") || ""),
            },
            responseType: "blob",
          },
        );

        const contentType = response.headers["content-type"] || "";
        if (contentType.includes("application/json")) {
          const text = await response.data.text();
          let parsedError = null;

          try {
            parsedError = JSON.parse(text);
          } catch (_error) {
            parsedError = null;
          }

          if (this.handleExpiredTokenResponse(parsedError)) {
            return;
          }

          throw new Error(
            parsedError?.message || "Gagal mengekspor laporan PDF",
          );
        }

        const blob = new Blob([response.data], { type: "application/pdf" });
        const downloadUrl = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        const filename = `stocktaking-${this.currentKodeLokasi}-${reportYear}.pdf`;

        link.href = downloadUrl;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(downloadUrl);

        this.successMessage = "PDF berhasil diekspor";
        setTimeout(() => {
          this.successMessage = "";
        }, 2000);
      } catch (err) {
        console.error("Gagal export PDF:", err);
        if (this.handleExpiredToken(err)) {
          return;
        }

        this.errorMessage =
          err.response?.data?.message || err.message || "Gagal export PDF";
      } finally {
        this.isExportingPdf = false;
      }
    },

    async publishReportToAdmin() {
      if (!this.currentKodeLokasi) {
        return;
      }

      const response = await axios.post(
        `${teamBackendUrl}/api/report/stocktaking/publish`,
        {
          kode_lokasi: this.currentKodeLokasi,
          deleted_items: this.buildDeletedItemsReportPayload(),
        },
        {
          headers: {
            Authorization: "Bearer " + (localStorage.getItem("token") || ""),
          },
        },
      );

      if (this.handleExpiredTokenResponse(response.data)) {
        return;
      }

      if (response.data?.success === false) {
        throw new Error(
          response.data?.message || "Gagal sinkron report admin",
        );
      }
    },

    // =======================
    // GO TO LIST SO
    // =======================
    async goToListSO() {
      if (!this.currentKodeLokasi) {
        this.errorMessage = "Kode lokasi tidak ditemukan";
        return;
      }

      this.isLoading = true;
      this.errorMessage = "";
      this.successMessage = "";

      try {
        await this.syncItemsToBackend();
        await this.syncLogsToBackend();
        await this.syncDeletedItemsToBackend();
        await this.publishReportToAdmin();

        await this.saveLocationData({ edited_items: [] });
        await this.fetchItemsByLocationTeam();
        this.successMessage =
          "Data LIST SO berhasil disinkronkan dan report admin diperbarui";
      } catch (err) {
        console.error("Gagal sinkronisasi LIST SO:", err);
        if (this.handleExpiredToken(err)) {
          return;
        }
        this.errorMessage =
          err.response?.data?.message ||
          err.message ||
          "Gagal sinkronisasi LIST SO";
        return;
      } finally {
        this.isLoading = false;
      }

      setTimeout(() => {
        this.successMessage = "";
      }, 2000);

      this.$router.push("/listso");
    },
  },
  beforeRouteLeave(to, from, next) {
    if (this.forceAllowRouteLeave) {
      this.forceAllowRouteLeave = false;
      next();
      return;
    }

    if (!this.hasPendingLocationChanges()) {
      next();
      return;
    }

    if (to.path === from.path) {
      next();
      return;
    }

    this.showPendingLocationChangesMessage("meninggalkan halaman");
    next(false);
  },
};
</script>

<style scoped>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes shake {
  0%,
  100% {
    transform: translateX(0);
  }
  10%,
  30%,
  50%,
  70%,
  90% {
    transform: translateX(-5px);
  }
  20%,
  40%,
  60%,
  80% {
    transform: translateX(5px);
  }
}

@keyframes pulse {
  0%,
  100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.8;
  }
}

.animate-fadeInUp {
  animation: fadeInUp 0.3s ease-out;
}

.animate-shake {
  animation: shake 0.5s ease-in-out;
}

.animate-pulse {
  animation: pulse 0.6s ease-in-out;
}
</style>
