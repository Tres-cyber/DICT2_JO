<script setup lang="ts">
import { ref } from "vue";
import { format, isSameMonth, isSameDay } from "date-fns";
import VueDatePicker from "@vuepic/vue-datepicker";

const props = withDefaults(
  defineProps<{
    date?: string | number | Date;
    startDate?: string | number | Date;
    endDate?: string | number | Date;
    range?: boolean;
  }>(),
  {
    date: () => new Date(),
    startDate: () => new Date(),
    range: false,
  },
);

const date = ref(
  props.range
    ? [
        new Date(props.startDate),
        props.endDate ? new Date(props.endDate) : undefined,
      ]
    : new Date(props.date),
);

function formatDateRange([start, end]: [Date, Date]) {
  end = end ?? start;
  if (isSameDay(start, end)) {
    return format(start, "MMMM dd, yyyy");
  }

  if (isSameMonth(start, end) && start.getFullYear() === end.getFullYear()) {
    return `${format(start, "MMMM dd")}-${format(end, "dd, yyyy")}`;
  }

  return `${format(start, "MMM dd, yyyy")} - ${format(end, "MMM dd, yyyy")}`;
}
</script>
<template>
  <VueDatePicker
    v-bind="$attrs"
    v-model="date"
    :range="props.range"
    :clearable="false"
    :format="
      props.range ? formatDateRange : (d: Date) => format(d, 'MMMM dd, yyyy')
    "
    :enable-time-picker="false"
  />
</template>
<style>
.dp__theme_light {
  --dp-primary-color: theme(colors.blue.950);
  --dp-range-between-dates-background-color: theme(colors.blue.100);
  --dp-menu-border-color: theme(colors.neutral.400);
  --dp-text-color: inherit;
}

.dp__input_wrap {
  display: flex;
}

.dp__clear_icon {
  padding: 0;
}

@media print {
  .dp__clear_icon {
    display: none;
  }

  .dp__menu {
    display: none;
  }
}
</style>
