import { ConfirmEvent } from "../events/confirm-event";
import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller<HTMLFormElement> {
  static values = {
    title: String,
    message: String,
    action: String,
  };

  declare titleValue: string;
  declare messageValue: string;
  declare actionValue: string;
  declare readonly hasTitleValue: boolean;
  declare readonly hasMessageValue: boolean;
  declare readonly hasActionValue: boolean;

  confirm(event: Event): void {
    event.preventDefault();

    const confirmEvent = new ConfirmEvent({
      title: this.hasTitleValue ? this.titleValue : undefined,
      message: this.hasMessageValue ? this.messageValue : undefined,
      action: this.hasActionValue ? this.actionValue : undefined,
      onConfirm: () => {
        this.element.submit();
      },
    });
    this.element.dispatchEvent(confirmEvent);
  }
}
