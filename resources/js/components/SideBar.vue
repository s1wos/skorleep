<template>
    <div class="h-full w-full bg-indigo-400 border-r border-gray-200 flex flex-col p-2 overflow-y-auto text-center">
      <div class="mb-8 pt-8">
        <h1 class="text-4xl font-light text-white capitalize">MM</h1>
      </div>

      <nav class="flex flex-col gap-2 flex-1">
        <router-link to='/'></router-link>
      </nav>

      <div class="pt-6">
        <button
          class="w-full flex items-center justify-start gap-3 text-red-600 "
        >
         Выйти
        </button>
      </div>
    </div>
</template>

<script setup lang="ts">

</script>