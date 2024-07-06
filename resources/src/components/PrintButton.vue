<script setup lang="ts">
function showPrint() {
  window.print();
}

defineProps<{
  text?: string;
}>();
</script>
<template>
  <button v-bind="$attrs" @click="showPrint">
    <slot>
      <font-awesome-icon icon="fa-solid fa-print" class="mr-1" />
      <span>{{ text ?? "Print PDF" }}</span>
    </slot>
  </button>
</template>
