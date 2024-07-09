import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "bootstrap/dist/css/bootstrap.min.css";
import "./style.css";

import { library, dom } from "@fortawesome/fontawesome-svg-core";
import {
  faArrowDown,
  faBars,
  faEye,
  faFloppyDisk,
  faPen,
  faPlus,
  faPrint,
  faTrash,
} from "@fortawesome/free-solid-svg-icons";

library.add(
  faPrint,
  faBars,
  faEye,
  faTrash,
  faPlus,
  faArrowDown,
  faFloppyDisk,
  faPen,
);
dom.watch();
