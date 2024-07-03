<script setup lang="ts">
function showPrint() {
  window.print();
}
</script>
<template>
  <button v-bind="$attrs" @click="showPrint">
    <slot>
      <font-awesome-icon icon="fa-solid fa-print" class="mr-1" />
      <span>Print PDF</span>
    </slot>
  </button>
</template>
