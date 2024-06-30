<script setup lang="ts">
import { useTextareaAutosize } from "@vueuse/core";
import { useSlots } from "vue";

const slots = useSlots();

const { textarea, input } = useTextareaAutosize({
  input: slots.default ? slots.default()[0].children?.toString().trim() : "",
});
</script>
<template>
  <textarea ref="textarea" v-bind="$attrs" v-model="input"></textarea>
</template>
