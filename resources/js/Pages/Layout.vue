<template>
  <div class="p-6 max-w-3xl mx-auto">
    <header class="mb-6">
      <h1 class="text-3xl font-bold">Reservation System</h1>

      <nav class="mt-3 space-x-4">
        <a href="/products">상품 목록</a>
        <a href="/reservations">예약 목록</a>
      </nav>
    </header>

    <main>
      <slot></slot>
    </main>
  </div>
</template>

<script>
export default {}
</script>
