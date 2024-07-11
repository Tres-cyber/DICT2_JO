import { AlpineComponent } from "alpinejs";

import $ from "jquery";
import "bootstrap-daterangepicker";
import "bootstrap-daterangepicker/daterangepicker.css";

interface DateRangeData {
  startDate: Date;
  endDate: Date;
}

export default (): AlpineComponent<DateRangeData> => ({
  startDate: new Date(),
  endDate: new Date(),

  init() {
    const _this = this;

    ($(this.$el.querySelector("input")) as any).daterangepicker({
      startDate: _this.startDate,
      endDate: _this.endDate,
      minDate: new Date(),
    });
  },
});
