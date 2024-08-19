import { Controller } from "@hotwired/stimulus";
import { Toast } from "bootstrap";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  private toast!: Toast;

  connect() {
    this.toast = new Toast(this.element);
    this.toast.show();
  }

  disconnect(): void {
    this.toast.dispose();
  }
}
