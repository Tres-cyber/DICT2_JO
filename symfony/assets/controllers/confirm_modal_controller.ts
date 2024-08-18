import { ConfirmEvent } from "../events/confirm-event";
import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["title", "message", "action"];

  private event: ConfirmEvent | null = null;
  private modal!: Modal;

  declare readonly titleTarget: Element;
  declare readonly messageTarget: Element;
  declare readonly actionTarget: Element;

  connect(): void {
    this.modal = new Modal(this.element);

    window.addEventListener("confirm", (event: Event) => {
      if (!(event instanceof ConfirmEvent)) return;

      this.event = event;

      this.titleTarget.innerHTML = event.detail.title ?? "Confirm";
      this.messageTarget.innerHTML =
        event.detail.message ?? "Are you sure you want to continue?";
      this.actionTarget.innerHTML = event.detail.action ?? "Confirm";

      this.modal.show();
    });
  }

  confirm() {
    if (this.event !== null && this.event.detail.onConfirm !== undefined) {
      this.event.detail.onConfirm();
    }
    this.modal.hide();
  }
}
