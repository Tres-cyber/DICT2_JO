import { ConfirmEvent } from "../events/confirm-event";
import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["title", "message", "action"];

  private event: ConfirmEvent | null = null;
  private modal!: Modal;
  private confirmEventListener!: (event: Event) => void;

  declare readonly titleTarget: Element;
  declare readonly messageTarget: Element;
  declare readonly actionTarget: Element;

  connect(): void {
    this.modal = new Modal(this.element);

    this.confirmEventListener = (event: Event) => {
      if (!(event instanceof ConfirmEvent)) return;

      this.event = event;
      this.titleTarget.innerHTML = event.detail.title ?? "Confirm";
      this.messageTarget.innerHTML =
        event.detail.message ?? "Are you sure you want to continue?";
      this.actionTarget.innerHTML = event.detail.action ?? "Confirm";

      this.modal.show();
    };

    window.addEventListener("confirm", this.confirmEventListener);
  }

  disconnect(): void {
    window.removeEventListener("confirm", this.confirmEventListener);
    this.modal.dispose();
  }

  confirm() {
    if (this.event !== null && this.event.detail.onConfirm !== undefined) {
      this.event.detail.onConfirm();
    }
    this.modal.hide();
  }
}
