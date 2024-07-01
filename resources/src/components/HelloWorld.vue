<script setup lang="ts">
import { ref } from 'vue'

defineProps<{ msg: string }>()

const count = ref(0)
function inc() {
  count.value += 1;
}
</script>

<template>
  <p>{{ count }}</p>
  <button @click="inc">inc</button>
</template>
