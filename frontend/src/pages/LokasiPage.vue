<template>
  <div
    class="min-h-screen bg-gray-200 text-black flex items-center justify-center p-4"
  >
    <div class="text-center w-full max-w-md mx-auto">
      <!-- Logo -->
      <div class="mb-4">
        <img
          src="../assets/logoassalam.png"
          alt="Logo Assalaam"
          class="mx-auto w-40"
        />
        <p class="mt-2 font-bold tracking-widest text-black">STOCK TAKING</p>
      </div>

      <!-- Card -->
      <div class="bg-white text-black p-6 rounded-xl shadow-xl w-full border">
        <div class="flex gap-2">
          <!-- Input kode -->
          <input
            v-model="kodePenghitung"
            type="text"
            placeholder="Kode Lokasi"
            inputmode="numeric"
            maxlength="8"
            @input="formatKodeLokasi"
            class="flex-1 border rounded-md px-3 py-2 bg-white text-black focus:outline-none focus:ring-2 focus:ring-orange-400"
          />
          <!-- Button Scan -->
          <button
            @click="openScannerPopup"
            class="bg-orange-500 text-white px-4 rounded-md hover:bg-orange-600"
          >
            ⬚
          </button>
        </div>

        <!-- Button Masuk -->
        <button
          @click="submitKode"
          class="mt-4 w-full bg-orange-500 text-white py-2 rounded-md font-semibold hover:bg-orange-600"
          :disabled="isLoading"
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
            <button @click="closeScannerPopup" class="text-xl">✕</button>
          </div>
          <div id="reader" class="w-full"></div>
          <div class="flex gap-2 mt-4">
            <button
              @click="toggleCamera"
              class="flex-1 bg-blue-500 text-white py-2 rounded-md"
            >
              🔄 Balik Kamera
            </button>
            <button
              @click="toggleFlash"
              :disabled="!flashAvailable"
              class="flex-1 bg-yellow-500 text-white py-2 rounded-md disabled:opacity-50"
            >
              {{ flashOn ? "⚡ Flash Off" : "💡 Flash On" }}
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
</template>

<script>
import { Html5Qrcode } from "html5-qrcode";
import axios from "axios";

const teamBackendUrl =
  import.meta.env.VITE_BACKEND_2 || import.meta.env.VITE_BACKEND;

export default {
  name: "LoginPage",
  data() {
    return {
      kodePenghitung: "",
      isLoading: false,
      errorMessage: "",
      successMessage: "",
      scanner: null,
      showScanner: false,
      cameras: [],
      cameraId: "",
      flashOn: false,
      flashAvailable: false,
      isSwitchingCamera: false,
    };
  },

  methods: {
    buildActiveTeamMessage(activeTeam) {
      if (!activeTeam || typeof activeTeam !== "object") {
        return "Lokasi tersebut sedang digunakan oleh team yang lain";
      }

      const teamLabel = activeTeam.no_team || "Team lain";
      const penghitung1 = activeTeam.penghitung_1 || "-";
      const penghitung2 = activeTeam.penghitung_2 || "-";

      return `Lokasi tersebut sedang digunakan oleh team ${teamLabel} (${penghitung1} & ${penghitung2})`;
    },

    getExistingTeamData() {
      const currentKodeLokasi = localStorage.getItem("current_kode_lokasi");

      if (!currentKodeLokasi) return null;

      const currentLocationData = localStorage.getItem(
        `lokasi_${currentKodeLokasi}`,
      );

      if (!currentLocationData) return null;

      try {
        const parsedData = JSON.parse(currentLocationData);
        return parsedData.team || null;
      } catch (error) {
        console.error("Gagal membaca team dari localStorage:", error);
        return null;
      }
    },

    formatKodeLokasi(event) {
      const rawValue = event.target.value.replace(/\D/g, "").slice(0, 6);
      const parts = [];

      if (rawValue.length > 0) parts.push(rawValue.slice(0, 1));
      if (rawValue.length > 1) parts.push(rawValue.slice(1, 3));
      if (rawValue.length > 3) parts.push(rawValue.slice(3, 6));

      this.kodePenghitung = parts.join(".");
    },

    // ===== QR Scanner =====
    async openScannerPopup() {
      this.showScanner = true;
      await this.$nextTick();
      await this.getCameras();
      await this.startScanner();
    },

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

    closeScannerPopup() {
      this.showScanner = false;
      if (this.scanner) {
        this.scanner.stop().then(() => {
          this.scanner.clear();
          this.scanner = null;
        });
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
            this.kodePenghitung = decodedText;
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

    async toggleCamera() {
      if (this.isSwitchingCamera) return;
      this.isSwitchingCamera = true;
      try {
        const currentIndex = this.cameras.findIndex(
          (c) => c.id === this.cameraId,
        );
        const nextIndex = (currentIndex + 1) % this.cameras.length;
        this.cameraId = this.cameras[nextIndex].id;
        this.flashOn = false;
        await this.scanner.stop();
        await this.scanner.clear();
        this.scanner = null;
        await this.$nextTick();
        await this.startScanner();
      } catch (err) {
        console.error("Gagal ganti kamera:", err);
      }
      this.isSwitchingCamera = false;
    },

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
        await track.applyConstraints({ advanced: [{ torch: this.flashOn }] });
      } catch (err) {
        console.error(err);
      }
    },

    // ===== Submit kode lokasi ke backend =====
    async submitKode() {
      if (!this.kodePenghitung) {
        this.errorMessage = "Kode lokasi belum diisi";
        return;
      }

      this.isLoading = true;
      this.errorMessage = "";
      this.successMessage = "";

      try {
        const response = await axios.post(
          `${teamBackendUrl}/api/auth/login/location`,
          { kode_lokasi: this.kodePenghitung },
          {
            headers: {
              Authorization: "Bearer " + (localStorage.getItem("token") || ""),
            },
          },
        );

        console.log("Backend response:", response.data);

        if (response.data.success) {
          const storageKey = `lokasi_${this.kodePenghitung}`;
          const activeTeam = this.getExistingTeamData() || response.data.team;

          // Siapkan data yang akan disimpan
          const locationData = {
            kode_lokasi: this.kodePenghitung,
            team: activeTeam,
            loginTimestamp: new Date().toISOString(),
          };

          // Simpan ke localStorage dengan key berdasarkan kode lokasi
          localStorage.setItem(storageKey, JSON.stringify(locationData));

          // Optional: Simpan juga kode lokasi yang sedang aktif
          localStorage.setItem("current_kode_lokasi", this.kodePenghitung);

          this.successMessage = `Berhasil masuk dengan lokasi ${this.kodePenghitung}! ✅`;

          // Redirect ke admin
          this.$router.push("/listso");
        } else {
          this.errorMessage = response.data.message || "Login gagal";
        }
      } catch (err) {
        console.error(err);
        const activeTeam = err.response?.data?.active_team;
        this.errorMessage = activeTeam
          ? this.buildActiveTeamMessage(activeTeam)
          : err.response?.data?.message || "Login gagal";
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
#reader {
  width: 100%;
  min-height: 300px;
  background: white;
}
#reader video {
  width: 100%;
  height: auto;
}
</style>
