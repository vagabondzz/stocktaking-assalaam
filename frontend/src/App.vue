<template>
  <router-view />
</template>

<script>
import { isAuthenticated } from "./utils/auth";

export default {
  name: "App",
  data() {
    return {
      authCheckInterval: null,
    };
  },
  mounted() {
    this.authCheckInterval = window.setInterval(() => {
      const currentRoute = this.$router.currentRoute.value;

      if (currentRoute.meta?.requiresAuth && !isAuthenticated()) {
        this.$router.push("/");
      }
    }, 30000);
  },
  beforeUnmount() {
    if (this.authCheckInterval) {
      clearInterval(this.authCheckInterval);
      this.authCheckInterval = null;
    }
  },
};
</script>

<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}
</style>
