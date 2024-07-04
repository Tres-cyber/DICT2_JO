<template>
  <slot :clientName="client_name" :setClientName="setClientName"></slot>
</template>
<script setup lang="ts">
import { ref } from "vue";

const props = withDefaults(defineProps<{ clientNameDefault?: string }>(), {
  clientNameDefault: "",
});

const client_name = ref(props.clientNameDefault);

function setClientName(a: string) {
  client_name.value = a;
}

const slot = defineSlots<{
  default(props: { clientName: string; setClientName(v: string): void }): any;
}>();
</script>
