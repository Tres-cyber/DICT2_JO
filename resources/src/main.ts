import "@vuepic/vue-datepicker/dist/main.css";
import "./style.scss";

import { createApp } from "vue";
import AutocompleteSelect from "./components/AutocompleteSelect.vue";
import DatePicker from "./components/DatePicker.vue";
import DynamicTextarea from "./components/DynamicTextarea.vue";
import MultipleSelect from "./components/MultipleSelect.vue";
import PrintButton from "./components/PrintButton.vue";
import TopBar from "./components/TopBar.vue";

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faArrowDown,
  faBars,
  faEye,
  faFloppyDisk,
  faPlus,
  faPrint,
  faTrash,
} from "@fortawesome/free-solid-svg-icons";

library.add(faPrint, faBars, faEye, faTrash, faPlus, faArrowDown, faFloppyDisk);

const app = createApp({});
app.component("AutocompleteSelect", AutocompleteSelect);
app.component("DatePicker", DatePicker);
app.component("DynamicTextarea", DynamicTextarea);
app.component("FontAwesomeIcon", FontAwesomeIcon);
app.component("MultipleSelect", MultipleSelect);
app.component("PrintButton", PrintButton);
app.component("TopBar", TopBar);
app.mount("#app");
