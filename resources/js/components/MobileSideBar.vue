<template>
    <div class="h-full w-full flex sm:hidden bg-indigo-400 border-r border-gray-200 flex-col p-2 max-sm:p-0 overflow-y-auto text-center">
        <div class="max-sm:mb-2 max-sm:pt-0">
            <h1 class="text-5xl font-light text-white capitalize">MM</h1>
        </div>
        <div>
        <div class="container">
            <motion.nav
                :initial="false"
                :animate="isOpen ? 'open' : 'closed'"
                :custom="dimensions.height"
                ref="containerRef"
                class="nav"
            >
                <motion.div class="background" :variants="sidebarVariants" />
                
                <!-- Navigation -->
                <motion.ul class="list" :variants="navVariants">
                    <motion.li
                        v-for="i in 5"
                        :key="i-1"
                        class="list-item"
                        :variants="itemVariants"
                        :whilePress="{ scale: 0.95 }"
                        :whileHover="{ scale: 1.1 }"
                    >
                    Hi
                    </motion.li>
                </motion.ul>
                
                <!-- Menu Toggle -->
                <motion.button
                type="button"
                class="toggle-container"
                @click="toggle"
                :initial="'closed'"
                :animate="isOpen ? 'open' : 'closed'"
                >
                    <motion.svg
                        width="23"
                        height="23"
                        viewBox="0 0 23 23"
                        stroke="white"
                        >
                        <motion.path
                            fill="transparent"
                            stroke-width="3"
                            stroke-linecap="round"
                            :variants="{ closed: { d: 'M 2 2.5 L 20 2.5' }, open: { d: 'M 3 16.5 L 17 2.5' } }"
                            :transition="{ duration: 0.2, ease: 'easeInOut' }"
                        />
                        <motion.path
                            fill="transparent"
                            stroke-width="3"
                            stroke-linecap="round"
                            d="M 2 9.423 L 20 9.423"
                            :variants="{ closed: { opacity: 1 }, open: { opacity: 0 } }"
                            :transition="{ duration: 0.1 }"
                        />
                        <motion.path
                            fill="transparent"
                            stroke-width="3"
                            stroke-linecap="round"
                            :variants="{ closed: { d: 'M 2 16.346 L 20 16.346' }, open: { d: 'M 3 2.5 L 17 16.346' } }"
                            :transition="{ duration: 0.2, ease: 'easeInOut' }"
                        />
                    </motion.svg>
                </motion.button>
            </motion.nav>
        </div>
    </div>

        
    </div>
</template>


<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { motion,useDomRef, type MotionProps } from 'motion-v'

const isOpen = ref(false)
const containerRef = useDomRef()
const dimensions = ref({ width: 0, height: 0 })

onMounted(() => {
  if (containerRef.value) {
    dimensions.value.width = containerRef.value.offsetWidth
    dimensions.value.height = containerRef.value.offsetHeight
  }
})

const navVariants: MotionProps['variants'] = {
  open: {
    transition: { staggerChildren: 0.07, delayChildren: 0.2 }
  },
  closed: {
    transition: { staggerChildren: 0.05, staggerDirection: -1 }
  }
}

const itemVariants = {
  open: {
    y: 0,
    opacity: 1,
    transition: {
      y: { stiffness: 1000, velocity: -100 }
    }
  },
  closed: {
    y: 50,
    opacity: 0,
    transition: {
      y: { stiffness: 1000 }
    }
  }
}

const toggle = () => { 
    isOpen.value = !isOpen.value 
}

const sidebarVariants: MotionProps['variants'] = {
  open: (height: any = 1000) => ({
    clipPath: `circle(${height * 2 + 200}px at 40px 40px)`,
    transition: {
      type: "spring",
      stiffness: 20,
      restDelta: 2
    }
  }),
  closed: {
    clipPath: "circle(30px at 40px 40px)",
    transition: {
      delay: 0.2,
      type: "spring",
      stiffness: 400,
      damping: 40
    }
  }
}
</script>

<style scoped>
.container {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: stretch;
  flex: 1;
  width: 100%;
  max-width: 100%;
  background-color: var(--accent);
  border-radius: 20px;
  overflow: hidden;
}

.nav {
  width: 100%;
}

.background {
  background-color: #f5f5f5;
  position: absolute;
  top: 50px;
  left: 50%;
  width: 100%;
}

.toggle-container {
  outline: none;
  border: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  cursor: pointer;
  position: absolute;
  top: 50px;
  left: 50%;
  transform: translateX(-50%);
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: var(--color-indigo-400);

  display: flex;
  align-items: center;
  justify-content: center;
}

.list {
  list-style: none;
  padding: 25px;
  margin: 0;
  position: absolute;
  top: 80px;
  width: 230px;
}

.list-item {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding: 0;
  margin: 0;
  list-style: none;
  margin-bottom: 20px;
  cursor: pointer;
}

.icon-placeholder {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  flex: 40px 0;
  margin-right: 20px;
}

.text-placeholder {
  border-radius: 5px;
  width: 200px;
  height: 20px;
  flex: 1;
}
</style>
