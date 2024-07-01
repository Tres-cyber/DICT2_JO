import { createApp } from "vue";
import DatePicker from "./components/DatePicker.vue";
import DynamicTextarea from "./components/DynamicTextarea.vue";
import HelloWorld from "./components/HelloWorld.vue";
import PrintButton from "./components/PrintButton.vue";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faPrint } from "@fortawesome/free-solid-svg-icons";

import "@vuepic/vue-datepicker/dist/main.css";
import "./style.css";

library.add(faPrint);

const app = createApp({});
app.component("DatePicker", DatePicker);
app.component("DynamicTextarea", DynamicTextarea);
app.component("FontAwesomeIcon", FontAwesomeIcon);
app.component("HelloWorld", HelloWorld);
app.component("PrintButton", PrintButton);
app.mount("#app");
