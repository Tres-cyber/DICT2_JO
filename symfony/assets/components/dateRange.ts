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

export default (
  time = false,
  notEarlier = true,
): AlpineComponent<DateRangeData> => ({
  startDate: new Date(),
  endDate: new Date(),

  init() {
    const _this = this;

    const options = {
      startDate: _this.startDate,
      endDate: _this.endDate,
      locale: {
        format: "MMM DD, YY",
      },
    } as any;

    if (notEarlier) {
      options.minDate = new Date();
    }

    if (time) {
      options.timePicker = true;
      options.locale.format = "MMM DD, YY hh:mm a";
    }

    (
      $(this.$el.querySelector("input") as HTMLInputElement) as any
    ).daterangepicker(options, (start: Moment, end: Moment) => {
      _this.startDate = start.toDate();
      _this.endDate = end.toDate();
    });
  },

  format(d) {
    if (time) {
      return moment(d).utc().toISOString(false);
    }
    return moment(d).utc().format("YYYY-MM-DD");
  },
});
