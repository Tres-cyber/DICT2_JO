import * as bootstrap from "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import "./tailwind-base.scss";
import "./style.css";

import { faArrowDown } from "@fortawesome/free-solid-svg-icons/faArrowDown";
import { faArrowTurnDown } from "@fortawesome/free-solid-svg-icons/faArrowTurnDown";
import { faArrowTurnUp } from "@fortawesome/free-solid-svg-icons/faArrowTurnUp";
import { faBars } from "@fortawesome/free-solid-svg-icons/faBars";
import { faBroom } from "@fortawesome/free-solid-svg-icons/faBroom";
import { faEye } from "@fortawesome/free-solid-svg-icons/faEye";
import { faFloppyDisk } from "@fortawesome/free-solid-svg-icons/faFloppyDisk";
import { faPen } from "@fortawesome/free-solid-svg-icons/faPen";
import { faPlus } from "@fortawesome/free-solid-svg-icons/faPlus";
import { faPrint } from "@fortawesome/free-solid-svg-icons/faPrint";
import { faRightFromBracket } from "@fortawesome/free-solid-svg-icons/faRightFromBracket";
import { faRotateRight } from "@fortawesome/free-solid-svg-icons/faRotateRight";
import { faTrash } from "@fortawesome/free-solid-svg-icons/faTrash";
import { faXmark } from "@fortawesome/free-solid-svg-icons/faXmark";
import { library, dom, config } from "@fortawesome/fontawesome-svg-core";

config.mutateApproach = "sync";
library.add(
  faArrowDown,
  faArrowTurnDown,
  faArrowTurnUp,
  faBars,
  faBroom,
  faEye,
  faFloppyDisk,
  faPen,
  faPlus,
  faPrint,
  faRightFromBracket,
  faRotateRight,
  faTrash,
  faXmark,
);
dom.watch();

(window as any).bootstrap = bootstrap;

import "./bootstrap";
