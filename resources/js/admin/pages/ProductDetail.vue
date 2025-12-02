<template>
  <div>
    <div class="flex items-center gap-4 mb-6">
      <button
          @click="goBack"
          class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
      >
          ← 뒤로
      </button>
      <h1 class="text-2xl font-bold">상품 상세</h1>
    </div>

    <!-- 로딩 표시 -->
    <div v-if="loading" class="text-gray-500">Loading...</div>

    <!-- 상세 정보 -->
    <div v-else-if="product" class="bg-white rounded-lg shadow p-6">
      <!-- 상품코드 -->
      <div class="mb-6">
        <label class="block mb-2 text-sm font-semibold text-gray-700">상품코드</label>
        <div class="text-gray-900 p-2 bg-gray-50 rounded">{{ product.id }}</div>
      </div>

      <!-- 시설 -->
      <div class="mb-6">
        <label class="block mb-2 text-sm font-semibold text-gray-700">시설</label>
        <div class="text-gray-900 p-2 bg-gray-50 rounded">{{ product.facility?.name || '-' }}</div>
      </div>

      <!-- 상품명 -->
      <div class="mb-6">
        <label class="block mb-2 text-sm font-semibold text-gray-700">상품명</label>
        <div class="text-gray-900 p-2 bg-gray-50 rounded">{{ product.product_name }}</div>
      </div>

      <!-- 상품 타입 -->
      <div class="mb-6">
        <label class="block mb-2 text-sm font-semibold text-gray-700">상품 타입</label>
        <div class="text-gray-900 p-2 bg-gray-50 rounded">{{ getTypeLabel(product.type) }}</div>
      </div>

      <!-- 설명 -->
      <div class="mb-6">
        <label class="block mb-2 text-sm font-semibold text-gray-700">설명</label>
        <div class="text-gray-900 p-2 bg-gray-50 rounded whitespace-pre-wrap">
          {{ product.description || '(없음)' }}
        </div>
      </div>

      <!-- 활성 여부 -->
      <div class="mb-6">
        <label class="block mb-2 text-sm font-semibold text-gray-700">상태</label>
        <div class="text-gray-900 p-2 bg-gray-50 rounded">
          <span v-if="product.status" class="bg-green-200 text-green-800 px-3 py-1 rounded text-sm font-semibold">
            활성화 (판매중)
          </span>
          <span v-else class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm font-semibold">
            비활성화 (판매중지)
          </span>
        </div>
      </div>

      <!-- 생성일시 -->
      <div class="mb-6">
        <label class="block mb-2 text-sm font-semibold text-gray-700">생성일시</label>
        <div class="text-gray-900 p-2 bg-gray-50 rounded">{{ formatDate(product.created_at) }}</div>
      </div>

      <!-- 수정일시 -->
      <div class="mb-6">
        <label class="block mb-2 text-sm font-semibold text-gray-700">수정일시</label>
        <div class="text-gray-900 p-2 bg-gray-50 rounded">{{ formatDate(product.updated_at) }}</div>
      </div>

      <!-- 버튼 -->
      <div class="flex justify-end gap-2 mt-8 pt-6 border-t">
        <button
            @click="goBack"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
        >
            닫기
        </button>
      </div>
    </div>

    <!-- 상품을 찾을 수 없음 -->
    <div v-else class="text-red-600 text-center p-6">
      상품을 찾을 수 없습니다.
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import axios from 'axios'

  const route = useRoute()
  const router = useRouter()

  const product = ref(null)
  const loading = ref(true)

  const typeLabels = {
    'room': '객실 상품',
    'ticket': '티켓형 상품',
    'pass': '시즌권/패스'
  }

  function getTypeLabel(type) {
    return typeLabels[type] || type
  }

  function formatDate(dateString) {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return date.toLocaleString('ko-KR', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    })
  }

  function goBack() {
    router.back()
  }

  onMounted(async () => {
    try {
      const productId = route.params.id
      const res = await axios.get(`/api/admin/products/${productId}`)
      product.value = res.data.data || res.data
      console.log('Product data:', product.value)
    } catch (e) {
      console.error('상품 상세 정보 불러오기 실패:', e)
    } finally {
      loading.value = false
    }
  })
</script>
