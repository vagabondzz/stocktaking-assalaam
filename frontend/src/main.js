import { createApp } from "vue";
import "./style.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import App from "./App.vue";
import router from "./router";
import axios from "axios";
import { clearAuthSession, hasExpiredTokenMessage } from "./utils/auth";

axios.interceptors.response.use(
  (response) => {
    if (
      response?.data?.success === false &&
      hasExpiredTokenMessage(response?.data?.message)
    ) {
      clearAuthSession();
      if (router.currentRoute.value.path !== "/") {
        router.push("/");
      }
    }

    return response;
  },
  (error) => {
    const statusCode = error?.response?.status;
    const responseMessage =
      error?.response?.data?.message || error?.message || "";

    if (statusCode === 401 || hasExpiredTokenMessage(responseMessage)) {
      clearAuthSession();
      if (router.currentRoute.value.path !== "/") {
        router.push("/");
      }
    }

    return Promise.reject(error);
  },
);

const app = createApp(App);

app.use(router);
app.mount("#app");
