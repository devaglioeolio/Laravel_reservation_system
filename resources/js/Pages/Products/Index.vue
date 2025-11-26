<script setup>
import Layout from '../Layout.vue';

const props = defineProps({
  products: Array,
});

</script>

<template>
  <Layout>
    <h1 class="text-2xl font-bold mb-4">상품 목록</h1>

    <table class="border border-gray-300 w-full">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 border">ID</th>
          <th class="p-2 border">상품명</th>
          <th class="p-2 border">가격</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td class="p-2 border">{{ product.id }}</td>
          <td class="p-2 border">{{ product.name }}</td>
          <td class="p-2 border">{{ product.price.toLocaleString() }}원</td>
        </tr>
      </tbody>
    </table>
  </Layout>
</template>