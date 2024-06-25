import { createApp } from 'vue'
import CounterButton from './CounterButton.vue'

const app = createApp({});
app.component('CounterButton', CounterButton);
app.mount('#app')
