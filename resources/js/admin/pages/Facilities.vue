<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Facilities</h1>

    <!-- 신규 등록 버튼 -->
    <button
        @click="openCreateModal"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
    >
        + 시설 추가
    </button>

    <!-- 로딩 표시 -->
    <div v-if="loading" class="text-gray-500">Loading...</div>

    <!-- 목록 -->
    <!-- 시설 테이블 -->
    <div v-else class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
            <thead class="bg-gray-100 border-b">
            <tr>
                <th class="text-left px-4 py-3 font-semibold text-gray-700">시설명</th>
                <th class="text-right px-4 py-3 font-semibold text-gray-700">관리</th>
            </tr>
            </thead>

            <tbody>
            <tr
                v-for="facility in facilities"
                :key="facility.id"
                class="border-b hover:bg-gray-50"
            >
                <td class="px-4 py-3">
                {{ facility.name }}
                </td>

                <td class="px-4 py-3 text-right space-x-2">
                <button
                    @click="openEditModal(facility)"
                    class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600"
                >
                    수정
                </button>

                <button
                    @click="openDeleteModal(facility)"
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
                @click="loadFacilities(1)"
                :disabled="currentPage === 1"
                class="px-3 py-2 rounded bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-400"
            >
                처음
            </button>

            <button
                @click="loadFacilities(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-2 rounded bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-400"
            >
                이전
            </button>

            <div class="px-4 py-2 text-gray-700">
                {{ currentPage }} / {{ totalPages }}
            </div>

            <button
                @click="loadFacilities(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-2 rounded bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-400"
            >
                다음
            </button>

            <button
                @click="loadFacilities(totalPages)"
                :disabled="currentPage === totalPages"
                class="px-3 py-2 rounded bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-400"
            >
                마지막
            </button>
        </div>
    </div>

  </div>

  <!-- 모달 -->
    <div
    v-if="showCreateModal"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg w-96 p-6 shadow-xl">
            <h2 class="text-xl font-bold mb-4">시설 추가</h2>

            <input
            v-model="newFacility"
            type="text"
            class="w-full border p-2 rounded mb-4"
            placeholder="시설 이름 입력"
            />

            <div class="flex justify-end gap-2">
                <button @click="closeCreateModal" class="px-4 py-2 rounded bg-gray-300">
                    취소
                </button>

                <button
                    @click="createFacility"
                    class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
                >
                    저장
                </button>
            </div>
        </div>
    </div>

    <!-- 수정 모달 -->
    <div
        v-if="showEditModal"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg w-96 p-6 shadow-xl">
            <h2 class="text-xl font-bold mb-4">시설 수정</h2>

            <input
            v-model="editFacilityName"
            type="text"
            class="w-full border p-2 rounded mb-4"
            placeholder="시설 이름 입력"
            />

            <div class="flex justify-end gap-2">
                <button @click="closeEditModal" class="px-4 py-2 rounded bg-gray-300">
                    취소
                </button>

                <button
                    @click="updateFacility"
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
            <h2 class="text-xl font-bold mb-4 text-red-600">시설 삭제</h2>

            <p class="mb-6">
            정말로 "<strong>{{ deleteFacilityName }}</strong>" 시설을 삭제하시겠습니까?
            </p>

            <div class="flex justify-end gap-2">
                <button
                    @click="closeDeleteModal"
                    class="px-4 py-2 rounded bg-gray-300"
                >
                    취소
                </button>

                <button
                    @click="deleteFacility"
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
    import axios from 'axios'

    const facilities = ref([])
    const loading = ref(true)
    const newFacility = ref('')   // 입력값 저장
    const currentPage = ref(1)
    const totalPages = ref(1)
    const perPage = 10
    // 모달 열기/닫기 상태
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const showDeleteModal = ref(false)

    const editFacilityId = ref(null)
    const editFacilityName = ref('')

    const deleteFacilityId = ref(null)
    const deleteFacilityName = ref('')

    // 모달 열기
    function openCreateModal() {
        newFacility.value = ''
        showCreateModal.value = true
    }

    // 모달 닫기
    function closeCreateModal() {
        showCreateModal.value = false
    }

    function openEditModal(facility) {
        editFacilityId.value = facility.id
        editFacilityName.value = facility.name
        showEditModal.value = true
    }

    function closeEditModal() {
        showEditModal.value = false
    }

    function openDeleteModal(facility) {
        deleteFacilityId.value = facility.id
        deleteFacilityName.value = facility.name
        showDeleteModal.value = true
    }

    function closeDeleteModal() {
        showDeleteModal.value = false
    }

    onMounted(async () => {
        await loadFacilities()
        loading.value = false
    })

    // 목록 불러오기get (함수로 분리)
    async function loadFacilities(page = 1) {
        try{
            const res = await axios.get('/api/admin/facilities', {
                params: {
                    page: page,
                    per_page: perPage
                }
            })
            facilities.value = res.data.data
            currentPage.value = res.data.current_page
            totalPages.value = res.data.last_page
        } catch (e) {
            console.error('시설 목록 불러오기 실패:', e)
        }
    }

    async function createFacility() {
        if (!newFacility.value.trim()) return

        try {
            await axios.post('/api/admin/facilities', {
            name: newFacility.value,
            })

            closeCreateModal()
            await loadFacilities()   // 목록 새로고침
        } catch (e) {
            console.error('시설 추가 실패:', e)
        }
    }

    async function updateFacility() {
        if (!editFacilityName.value.trim()) return

        try {
            await axios.put(`/api/admin/facilities/${editFacilityId.value}`, {
            name: editFacilityName.value,
            })

            closeEditModal()
            await loadFacilities()
        } catch (e) {
            console.error('시설 수정 실패:', e)
        }
    }

    async function deleteFacility() {
        try {
            await axios.delete(`/api/admin/facilities/${deleteFacilityId.value}`)

            closeDeleteModal()
            await loadFacilities()
        } catch (e) {
            console.error('시설 삭제 실패:', e)
        }
    }
</script>
