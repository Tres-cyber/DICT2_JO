import Alpine from "alpinejs";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "bootstrap/dist/css/bootstrap.min.css";
import "./tailwind-base.scss";
import "./style.css";

import { library, dom } from "@fortawesome/fontawesome-svg-core";
import { faRotateRight } from "@fortawesome/free-solid-svg-icons/faRotateRight";
import { faPen } from "@fortawesome/free-solid-svg-icons/faPen";
import { faFloppyDisk } from "@fortawesome/free-solid-svg-icons/faFloppyDisk";
import { faArrowDown } from "@fortawesome/free-solid-svg-icons/faArrowDown";
import { faPlus } from "@fortawesome/free-solid-svg-icons/faPlus";
import { faTrash } from "@fortawesome/free-solid-svg-icons/faTrash";
import { faEye } from "@fortawesome/free-solid-svg-icons/faEye";
import { faBars } from "@fortawesome/free-solid-svg-icons/faBars";
import { faBroom } from "@fortawesome/free-solid-svg-icons/faBroom";
import { faPrint } from "@fortawesome/free-solid-svg-icons/faPrint";
import { faRightFromBracket } from "@fortawesome/free-solid-svg-icons";
import poll from "./components/poll";
import autocomplete from "./components/autocomplete";
import dateRange from "./components/dateRange";
import multipleSelect from "./components/multipleSelect";
import { faXmark } from "@fortawesome/free-solid-svg-icons/faXmark";

library.add(
  faPrint,
  faBars,
  faEye,
  faTrash,
  faPlus,
  faArrowDown,
  faFloppyDisk,
  faPen,
  faRotateRight,
  faRightFromBracket,
  faXmark,
  faBroom,
);
dom.watch();

(window as any).Alpine = Alpine;

Alpine.data("autocomplete", autocomplete);
Alpine.data("dateRange", dateRange);
Alpine.data("multipleSelect", multipleSelect);
Alpine.data("poll", poll);
Alpine.data("autocomplete", autocomplete);
Alpine.data("dateRange", dateRange);

Alpine.start();
