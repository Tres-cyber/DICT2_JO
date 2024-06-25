import { createApp } from "vue";
import CounterButton from "./CounterButton.vue";

import "./index.css";

const app = createApp({});
app.component("CounterButton", CounterButton);
app.mount("#app");
