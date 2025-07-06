<template>
    <div class="flex h-screen bg-[#F9FAFE]">
      <aside class="h-screen w-[100px] shrink-0">
        <SideBar />
      </aside>

      <main class="flex-1 px-8 overflow-x-hidden overflow-y-auto">
        <router-view />
      </main>
    </div>
</template>

<script setup lang="ts">
    import SideBar from '@/components/SideBar.vue';
</script>