<template>
  <p>{{ count }}</p>
  <button @click="inc">+</button>
</template>
<script setup lang="js">
import {ref} from "vue"

const count = ref(0)
function  inc() {
count.value += 1
}
</script>
