<script setup lang="ts">
import { computed, ref } from "vue";
import AutocompleteSelect from "./AutocompleteSelect.vue";

defineOptions({
  inheritAttrs: false,
});

const selectRef = ref<InstanceType<typeof AutocompleteSelect> | null>(null);

const props = withDefaults(
  defineProps<{
    name?: string;
    readonly?: boolean;
    permanent?: string | string[];
    options?: string | string[];
    seperator?: string;
    containerClass?: string;
    selectClass?: string;
    valueClass?: string;
    deleteClass?: string;
    inputClass?: string;
    choicesClass?: string;
    optionClass?: string;
  }>(),
  {
    permanent: () => [],
    readonly: false,
    default: "",
    options: "",
    seperator: ",",
    containerClass: "",
    selectClass: "",
    valueClass: "",
    deleteClass: "",
    inputClass: "",
    choicesClass: "",
    optionClass: "",
  },
);

const permanent = computed(() =>
  typeof props.permanent == "string"
    ? props.permanent.trim() === ""
      ? []
      : props.permanent.split(props.seperator)
    : props.permanent,
);

const options = computed(() =>
  typeof props.options == "string"
    ? props.options.trim() === ""
      ? []
      : props.options.split(props.seperator)
    : props.options,
);

function removeSelected(value: string) {
  selected.value = selected.value.filter((x) => x !== value);
}

const selected = defineModel<string[]>("selected", { default: [] });

function add(value: string | null) {
  if (value === null) return;
  if (selectRef.value === null) return;
  if (selected.value.includes(value)) return;
  const temp = [...selected.value, value];
  const inPermanent = temp.filter((v) => permanent.value.includes(v));
  const notInPermanent = temp.filter((v) => !permanent.value.includes(v));

  selected.value = [...inPermanent, ...notInPermanent];
  selectRef.value.deselect();
}
</script>
<template>
  <div :class="props.containerClass">
    <span v-for="(value, i) in selected" :key="value" :class="props.valueClass">
      {{ value
      }}{{
        i === selected.length - 1
          ? ""
          : i === selected.length - 2
            ? ", and "
            : ", "
      }}
      <button
        v-if="!permanent.includes(value) && !props.readonly"
        :class="props.deleteClass"
        @click="removeSelected(value)"
      >
        X
      </button>
      <input :name="props.name" type="hidden" :value="value" />
    </span>
    <div class="relative" v-if="!props.readonly">
      <AutocompleteSelect
        ref="selectRef"
        v-bind="{ ...$attrs, ...props, name: undefined }"
        :options="options.filter((v) => !selected.includes(v))"
        :container-class="selectClass"
        @select="add"
      />
    </div>
  </div>
</template>
