<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-60 bg-white shadow-lg fixed inset-y-0">
      <div class="p-6 font-bold text-xl border-b">
        Admin
      </div>

      <nav class="mt-4 space-y-1">
        <RouterLink
          to="/admin"
          class="block px-6 py-3 hover:bg-gray-100"
        >
          Dashboard
        </RouterLink>

        <RouterLink
          to="/admin/facilities"
          class="block px-6 py-3 hover:bg-gray-100"
        >
          Facilities
        </RouterLink>
      </nav>
    </aside>

    <!-- Main content -->
    <div class="flex-1 ml-60">
      <!-- Header -->
      <header class="bg-white shadow px-6 py-4">
        <h1 class="text-xl font-semibold">관리자 시스템</h1>
      </header>

      <main class="p-6">
        <slot></slot>
      </main>
    </div>
  </div>
</template>
