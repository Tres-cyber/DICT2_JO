import { Controller } from "@hotwired/stimulus";
import { Tooltip } from "bootstrap";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  private tooltip!: Tooltip;

  connect(): void {
    this.tooltip = new Tooltip(this.element);
  }

  disconnect(): void {
    this.tooltip.dispose();
  }
}
