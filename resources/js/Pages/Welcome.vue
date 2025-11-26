<template>
  <div style="padding: 40px; font-size: 24px;">
    <h1>{{ message }}</h1>
    <p>Laravel + Vue + Inertia complete! </p>
  </div>
</template>

<script>
export default {
  props: {
    message: String,
  }
}
</script>
