import { Controller } from "@hotwired/stimulus";
import { ValueDefinitionMap } from "@hotwired/stimulus/dist/types/core/value_properties";
import * as Turbo from "@hotwired/turbo";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values: ValueDefinitionMap = {
    interval: { type: Number, default: 5000 },
    target: String,
  };

  private interval!: number;
  private abortController!: AbortController;

  declare intervalValue: number;
  declare targetValue: string;

  connect(): void {
    this.abortController = new AbortController();
    this.interval = window.setInterval(() => {
      fetch(this.targetValue || window.location.href, {
        headers: { Accept: "text/vnd.turbo-stream.html" },
        signal: this.abortController.signal,
      })
        .then((response) => response.text())
        .then((html) => Turbo.renderStreamMessage(html));
    }, this.intervalValue);
  }

  disconnect(): void {
    this.abortController.abort();
    window.clearInterval(this.interval);
  }
}
