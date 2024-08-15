import { AlpineComponent } from "alpinejs";
import { Tooltip } from "bootstrap";

interface TooltipData {
  tooltip: Tooltip | null;
}

export default (): AlpineComponent<TooltipData> => ({
  tooltip: null,

  init() {
    this.tooltip = new Tooltip(this.$el);
  },
});
