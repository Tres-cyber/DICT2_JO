import { AlpineComponent } from "alpinejs";

import $ from "jquery";
import "bootstrap-daterangepicker";
import "bootstrap-daterangepicker/daterangepicker.css";
import moment, { Moment } from "moment";

interface DateRangeData {
  startDate: Date;
  endDate: Date;
  format(d: Date): string;
}

export default (): AlpineComponent<DateRangeData> => ({
  startDate: new Date(),
  endDate: new Date(),

  init() {
    const _this = this;

    (
      $(this.$el.querySelector("input") as HTMLInputElement) as any
    ).daterangepicker(
      {
        startDate: _this.startDate,
        endDate: _this.endDate,
        minDate: new Date(),
      },
      (start: Moment, end: Moment) => {
        _this.startDate = start.toDate();
        _this.endDate = end.toDate();
      },
    );
  },

  format(d) {
    return moment(d).utc().format("YYYY-MM-DD");
  },
});
