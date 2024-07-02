<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { metaphone } from "metaphone";
import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
} from "@headlessui/vue";

defineOptions({
  inheritAttrs: false,
});

const props = withDefaults(
  defineProps<{
    options?: string | string[];
    seperator?: string;
    containerClass?: string;
    inputClass?: string;
    choicesClass?: string;
    optionClass?: string;
  }>(),
  {
    options: "",
    seperator: ",",
    containerClass: "",
    inputClass: "",
    choicesClass: "",
    optionClass: "",
  },
);

const emit = defineEmits<{
  select: [value: string | null];
}>();

const options = computed(() =>
  typeof props.options == "string"
    ? props.options.trim() === ""
      ? []
      : props.options.split(props.seperator)
    : props.options,
);

const phonemesOptions = computed(() => options.value.map(metaphone));

const selected = defineModel<string | null>({ default: null });
const query = ref("");

const filteredOptions = computed(() =>
  query.value === ""
    ? options.value
    : options.value.filter(
        (person, i) =>
          person.toLowerCase().includes(query.value.toLowerCase()) ||
          phonemesOptions.value[i].includes(metaphone(query.value)),
      ),
);

watch(selected, () => {
  emit("select", selected.value);
});

function deselect() {
  selected.value = null;
}

defineExpose({
  deselect,
});
</script>

<template>
  <Combobox v-model="selected" nullable as="div" :class="props.containerClass">
    <ComboboxInput
      @change="query = $event.target.value"
      v-bind="$attrs"
      :class="props.inputClass"
    />
    <ComboboxOptions :class="props.choicesClass">
      <ComboboxOption
        v-for="option in filteredOptions"
        :key="option"
        :value="option"
        :class="props.optionClass"
      >
        {{ option }}
      </ComboboxOption>
    </ComboboxOptions>
  </Combobox>
</template>
