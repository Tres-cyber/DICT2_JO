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
    this.interval = window.setInterval(
      () => this.fetchUpdate(),
      this.intervalValue,
    );
  }

  disconnect(): void {
    this.abortController.abort();
    window.clearInterval(this.interval);
  }

  private async fetchUpdate(): Promise<void> {
    try {
      const response = await fetch(this.targetValue || window.location.href, {
        headers: { Accept: "text/vnd.turbo-stream.html" },
        signal: this.abortController.signal,
      });

      if (response.redirected) {
        window.location.href = response.url;
        return;
      }

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const contentType = response.headers.get("Content-Type");
      if (contentType && contentType.includes("text/vnd.turbo-stream.html")) {
        const html = await response.text();
        Turbo.renderStreamMessage(html);
      } else {
        console.warn("Received non-Turbo Stream response. Ignoring.");
      }
    } catch (error) {
      console.log(error);
    }
  }
}
