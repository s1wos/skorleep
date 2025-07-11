<template>
    <div class="flex h-screen bg-[#F9FAFE]">
      <aside class="h-screen w-[160px] shrink-0">
        <SideBar />
      </aside>

      <main class="flex-1 overflow-x-hidden overflow-y-auto w-screen justify-items-center">
        <router-view />
      </main>
    </div>
</template>

<script setup lang="ts">
    import SideBar from '@/components/SideBar.vue';
</script>