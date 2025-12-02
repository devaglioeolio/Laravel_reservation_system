<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Products</h1>

    <!-- 신규 등록 버튼 -->
    <button
        @click="openCreateModal"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
    >
        + 상품 추가
    </button>

    <!-- 로딩 표시 -->
    <div v-if="loading" class="text-gray-500">Loading...</div>

    <!-- 목록 -->
    <!-- 상품 테이블 -->
    <div v-else class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
            <thead class="bg-gray-100 border-b">
            <tr>
                <th class="text-left px-4 py-3 font-semibold text-gray-700">상품코드</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-700">상품명</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-700">시설</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-700">상품타입</th>
                <th class="text-right px-4 py-3 font-semibold text-gray-700">관리</th>
            </tr>
            </thead>

            <tbody>
            <tr
                v-for="product in products"
                :key="product.id"
                class="border-b hover:bg-gray-50"
            >

                <td class="px-4 py-3">
                {{ product.id }}
                </td>

                <td class="px-4 py-3">
                {{ product.product_name }}
                </td>

                <td class="px-4 py-3">
                {{ product.facility.name }}
                </td>


                <td class="px-4 py-3">
                {{ getTypeLabel(product.type) }}
                </td>
                
                <td class="px-4 py-3 text-right space-x-2">
                <button
                    @click="goToDetail(product.id)"
                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600"
                >
                    상세
                </button>

                <button
                    @click="openDeleteModal(product)"
                    class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                >
                    삭제
                </button>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- 페이지네이션 -->
        <div class="mt-4 flex items-center justify-center gap-2">
            <button
                @click="loadProducts(1)"
                :disabled="currentPage === 1"
                class="px-3 py-2 rounded bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-400"
            >
                처음
            </button>

            <button
                @click="loadProducts(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-2 rounded bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-400"
            >
                이전
            </button>

            <div class="px-4 py-2 text-gray-700">
                {{ currentPage }} / {{ totalPages }}
            </div>

            <button
                @click="loadProducts(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-2 rounded bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-400"
            >
                다음
            </button>

            <button
                @click="loadProducts(totalPages)"
                :disabled="currentPage === totalPages"
                class="px-3 py-2 rounded bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-400"
            >
                마지막
            </button>
        </div>
    </div>

  </div>

    <!-- 상품 추가 모달 -->
    <div
    v-if="showCreateModal"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg w-[450px] p-6 shadow-xl">
            <h2 class="text-xl font-bold mb-4">상품 추가</h2>

            <!-- 시설 선택 -->
            <label class="block mb-2 text-sm font-semibold">시설 선택</label>
            <select
            v-model="productForm.facility_id"
            class="w-full border p-2 rounded mb-4"
            >
            <option disabled value="">시설을 선택하세요</option>
            <option v-for="f in facilities" :key="f.id" :value="f.id">
                {{ f.name }}
            </option>
            </select>

            <!-- 상품명 -->
            <label class="block mb-2 text-sm font-semibold">상품명</label>
            <input
            v-model="productForm.product_name"
            type="text"
            class="w-full border p-2 rounded mb-4"
            placeholder="상품명 입력"
            />

            <!-- 상품 타입 -->
            <label class="block mb-2 text-sm font-semibold">상품 타입</label>
            <select
            v-model="productForm.type"
            class="w-full border p-2 rounded mb-4"
            >
            <option value="room">객실 상품</option>
            <option value="ticket">티켓형 상품</option>
            <option value="pass">시즌권/패스</option>
            </select>

            <!-- 설명 -->
            <label class="block mb-2 text-sm font-semibold">설명</label>
            <textarea
            v-model="productForm.description"
            class="w-full border p-2 rounded mb-4"
            placeholder="상품 설명 (선택)"
            rows="3"
            ></textarea>

            <!-- 활성 여부 -->
            <label class="flex items-center gap-2 mb-4">
            <input type="checkbox" v-model="productForm.status" />
            <span>활성화 (판매중)</span>
            </label>

            <!-- 버튼 -->
            <div class="flex justify-end gap-2 mt-4">
                <button @click="closeCreateModal" class="px-4 py-2 rounded bg-gray-300">
                    취소
                </button>

                <button
                    @click="createProduct"
                    class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
                >
                    저장
            </button>
            </div>
        </div>
    </div>

    <!-- 삭제 확인 모달 -->
    <div
    v-if="showDeleteModal"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg w-96 p-6 shadow-xl">
            <h2 class="text-xl font-bold mb-4 text-red-600">상품 삭제</h2>

            <p class="mb-6">
            정말로 "<strong>{{ deleteProductName }}</strong>" 상품을 삭제하시겠습니까?
            </p>

            <div class="flex justify-end gap-2">
                <button
                    @click="closeDeleteModal"
                    class="px-4 py-2 rounded bg-gray-300"
                >
                    취소
                </button>

                <button
                    @click="deleteProduct"
                    class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700"
                >
                    삭제
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import { useRouter } from 'vue-router'
    import axios from 'axios'

    const router = useRouter()
    const products = ref([])
    const loading = ref(true)
    const currentPage = ref(1)
    const totalPages = ref(1)
    const perPage = 10

    // 모달 열기/닫기 상태
    const showCreateModal = ref(false)
    const showDeleteModal = ref(false)

    const deleteProductId = ref(null)
    const deleteProductName = ref('')

    const facilities = ref([])

    const typeLabels = {
        'room': '객실 상품',
        'ticket': '티켓형 상품',
        'pass': '시즌권/패스'
    }

    function getTypeLabel(type) {
        return typeLabels[type] || type
    }

    const productForm = ref({
        facility_id: '',
        product_name: '',
        type: 'room',
        description: '',
        status: true,
    })

    // 모달 열기
    function openCreateModal() {
        showCreateModal.value = true
        resetForm()
    }

    // 모달 닫기
    function closeCreateModal() {
        showCreateModal.value = false
    }

    function resetForm() {
        productForm.value = {
            facility_id: '',
            product_name: '',
            type: 'room',
            description: '',
            status: true,
        }
    }

    onMounted(() => {
        loadFacilities()
    })

    function goToDetail(productId) {
        router.push(`/products/${productId}`)
    }

    function openDeleteModal(product) {
        deleteProductId.value = product.id
        deleteProductName.value = product.product_name
        showDeleteModal.value = true
    }

    function closeDeleteModal() {
        showDeleteModal.value = false
    }

    onMounted(async () => {
        await loadProducts()
        loading.value = false
    })

    // 목록 불러오기get (함수로 분리)
    async function loadProducts(page = 1) {
        try{
            const res = await axios.get('/api/admin/products', {
                params: {
                    page: page,
                    per_page: perPage
                }
            })
            products.value = res.data.data
            currentPage.value = res.data.current_page
            totalPages.value = res.data.last_page
        } catch (e) {
            console.error('상품 목록 불러오기 실패:', e)
        }
    }

    async function loadFacilities() {
        const res = await axios.get('/api/admin/facilities')
        facilities.value = res.data // resource 기준
    }


    async function createProduct() {
        try {
                await axios.post('/api/admin/products', {
                    facility_id: productForm.value.facility_id,
                    product_name: productForm.value.product_name,
                    type: productForm.value.type,
                    description: productForm.value.description,
                    status: productForm.value.status,
                })

                closeCreateModal()
                await loadProducts() // 목록 새로고침
        } catch (e) {
            console.error('상품 추가 실패:', e)
        }
    }

    async function deleteProduct() {
        try {
            await axios.delete(`/api/admin/products/${deleteProductId.value}`)

            closeDeleteModal()
            await loadProducts()
        } catch (e) {
            console.error('상품 삭제 실패:', e)
        }
    }
</script>
