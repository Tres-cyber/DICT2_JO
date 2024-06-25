<template>
  <p>Count: {{ count }}</p>
  <button @click="inc" class="bg-red-500">+</button>
</template>
<script setup lang="ts">
import { ref } from "vue";

const count = ref(0);

function inc() {
  count.value += 1;
}
</script>
