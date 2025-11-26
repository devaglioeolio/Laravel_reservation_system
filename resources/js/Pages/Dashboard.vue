<template>
  <div class="p-8">
    <h1 style="font-size:30px; font-weight:bold">Dashboard</h1>

    <p style="margin-top:20px; font-size:18px;">
      {{ message }}
    </p>

    <button @click="count++" 
            style="margin-top:20px; padding:10px 20px; background:#4f46e5; color:white; border-radius:6px;">
      Click Count: {{ count }}
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  message: String
})

const count = ref(0)
</script>
