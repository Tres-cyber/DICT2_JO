import "@vuepic/vue-datepicker/dist/main.css";
import "./style.css";

import { createApp } from "vue";
import AutocompleteSelect from "./components/AutocompleteSelect.vue";
import DatePicker from "./components/DatePicker.vue";
import DynamicTextarea from "./components/DynamicTextarea.vue";
import MultipleSelect from "./components/MultipleSelect.vue";
import PrintButton from "./components/PrintButton.vue";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faPrint } from "@fortawesome/free-solid-svg-icons";

library.add(faPrint);

const app = createApp({});
app.component("AutocompleteSelect", AutocompleteSelect);
app.component("DatePicker", DatePicker);
app.component("DynamicTextarea", DynamicTextarea);
app.component("FontAwesomeIcon", FontAwesomeIcon);
app.component("MultipleSelect", MultipleSelect);
app.component("PrintButton", PrintButton);
app.mount("#app");
