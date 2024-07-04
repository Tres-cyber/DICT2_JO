<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { metaphone } from "metaphone";
import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
  TransitionRoot,
} from "@headlessui/vue";

defineOptions({
  inheritAttrs: false,
});

const props = withDefaults(
  defineProps<{
    default?: string;
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

const selected = defineModel<string | null>({
  default: null,
});
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

const value = computed({
  get() {
    return selected.value ?? props.default ?? null;
  },
  set(newval) {
    selected.value = newval;
  },
});

function deselect() {
  selected.value = null;
}

defineExpose({
  deselect,
});
</script>

<template>
  <Combobox v-model="value" nullable as="div" :class="props.containerClass">
    <ComboboxInput
      @change="query = $event.target.value"
      v-bind="$attrs"
      :class="props.inputClass"
    />
    <TransitionRoot
      as="template"
      leave="transition duration-100"
      leaveFrom="opacity-100"
      leaveTo="opacity-0"
      enter="transition duration-150"
      enterFrom="opacity-0 -translate-y-full"
      enterTo="opacity-100 translate-y-0"
      @after-leave="query = ''"
    >
      <ComboboxOptions :class="props.choicesClass">
        <em v-if="filteredOptions.length == 0" class="text-neutral-600"
          >Nothing found.</em
        >
        <ComboboxOption
          v-for="option in filteredOptions"
          :key="option"
          :value="option"
          :class="props.optionClass"
        >
          {{ option }}
        </ComboboxOption>
      </ComboboxOptions>
    </TransitionRoot>
  </Combobox>
</template>
