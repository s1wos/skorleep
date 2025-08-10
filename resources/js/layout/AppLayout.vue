<template>
    <div class="flex h-screen max-sm:flex-col bg-[#F9FAFE]">
      <aside class="h-screen w-[160px] max-sm:w-screen max-sm:h-[75px] shrink-0">
        <SideBar />
        <MobileSideBar/>
      </aside>

      <main class="flex-1 overflow-x-hidden overflow-y-auto w-screen justify-items-center">
        <router-view />
      </main>
    </div>
</template>

<script setup lang="ts">
import MobileSideBar from '@/components/MobileSideBar.vue';
import SideBar from '@/components/SideBar.vue';
</script>