<template>
    <div class="h-full w-full bg-indigo-400 border-r border-gray-200 flex flex-col p-2 max-sm:p-0 overflow-y-auto text-center">
      <div class="mb-8 pt-10 max-sm:mb-2 max-sm:pt-0">
        <h1 class="text-5xl font-light text-white capitalize">MM</h1>
      </div>

      <nav class="flex flex-col gap-20 flex-1 items-center py-10 relative max-sm:hidden">
        <ul class="flex flex-col gap-20 items-center relative w-full pr-2">
          <router-link
            v-for="tab in tabs"
            :key="tab.label"
            :to="tab.path"
            class="w-full"
          >
            <AnimatePresence mode="wait">
              <motion.li
                tag="li"
                class="relative flex justify-center items-center w-full rounded-r-lg cursor-pointer"
                :initial="{x: 10, opacity: 0}"
                :animate="{
                  scale: selectedTab.label === tab.label ? 1.1 : 1,
                  backgroundColor: selectedTab.label === tab.label ? '#ffffff' : 'transparent',
                  x: 0, opacity: 1
                }"
                :exit="{x: -10, opacity:0}"
                :transition="{
                  scale: { duration: 0.1, ease: 'easeOut' },
                  backgroundColor: { duration: 0.2, ease: 'easeInOut' }
                }"
              >
                <component
                  :is="tab.icon"
                  :class="[
                    'w-9 h-16 text-xl transition-colors duration-200',
                    selectedTab.label === tab.label ? 'text-indigo-400' : 'text-white'
                  ]"
                />
                
              </motion.li>
            </AnimatePresence>
          </router-link>
        </ul>
      </nav>


    </div>
</template>

<script setup lang="ts">
import { FaRegUser } from '@kalimahapps/vue-icons';
import { FaRegClone } from '@kalimahapps/vue-icons';
import { FaRegCircleQuestion } from '@kalimahapps/vue-icons';
import { ref, watch } from 'vue';
import { motion } from 'motion-v'
import { useRoute } from 'vue-router';

const tabs = [
  {icon: FaRegUser, label: 'Profile', path: '/'},
  {icon: FaRegClone, label: 'About', path: '/about'},
  {icon: FaRegCircleQuestion, label: 'Help', path: '/help'}
]

const selectedTab = ref(tabs[0])

const route = useRoute()

watch(
  () => route.path,
  (newPath: any) => {
    const match = tabs.find(tab => tab.path === newPath)
    if (match) selectedTab.value = match
  },
  { immediate: true }
)
</script>