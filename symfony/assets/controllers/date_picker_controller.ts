import { Controller } from "@hotwired/stimulus";

import $ from "jquery";
import "bootstrap-daterangepicker";
import "bootstrap-daterangepicker/daterangepicker.css";
import { Moment } from "moment";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["input", "selected", "start", "end"];

  static values = {
    range: Boolean,
    time: Boolean,
    min: String,
  };

  declare readonly inputTarget: HTMLInputElement;
  declare readonly selectedTarget: HTMLInputElement;
  declare readonly startTarget: HTMLInputElement;
  declare readonly endTarget: HTMLInputElement;
  declare readonly hasInputTarget: boolean;
  declare readonly hasSelectedTarget: boolean;
  declare readonly hasStartTarget: boolean;
  declare readonly hasEndTarget: boolean;

  declare rangeValue: boolean;
  declare timeValue: boolean;
  declare minValue: string;
  declare readonly hasRangeValue: boolean;
  declare readonly hasTimeValue: boolean;
  declare readonly hasMinValue: boolean;

  connect(): void {
    if (!this.hasInputTarget) throw "input must be assigned";
    if (!this.rangeValue && !this.hasSelectedTarget)
      throw "selected target must be assign for non range selector";
    if (this.rangeValue && (!this.hasStartTarget || !this.hasEndTarget)) {
      throw "start and end target must be assign for range selector";
    }

    const options = {
      startDate: new Date(),
      endDate: new Date(),
      locale: {
        format: "MMM DD, YY",
      },
    } as any;

    if (this.hasMinValue) {
      options.minDate = new Date(this.minValue);
    }

    if (this.timeValue) {
      options.timePicker = true;
      options.locale.format = "MMM DD, YY hh:mm a";
    }

    if (!this.rangeValue) {
      options.singleDatePicker = true;
    }

    ($(this.inputTarget) as any).daterangepicker(
      options,
      (start: Moment, end: Moment) => {
        if (this.rangeValue) {
          this.startTarget.value = this.format(start);
          this.endTarget.value = this.format(end);
        } else {
          this.selectedTarget.value = this.format(start);
        }
      },
    );
  }

  disconnect(): void {
    $(this.inputTarget).data("daterangepicker").remove();
  }

  format(date: Moment) {
    if (this.timeValue) {
      return date.utc().toISOString(false);
    }
    return date.format("YYYY-MM-DD");
  }
}
