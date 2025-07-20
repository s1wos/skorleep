<template>
    <motion.div :initial="'hidden'" :while-in-view="'visible'" class="container mx-auto py-10">
        <motion.div :variants="variants" :transition="transition" class="w-full p-4 bg-white rounded-xl">
            <h1 class="text-3xl px-5 font-semibold flex items-center gap-4"><FaRegClone class="w-8 h-8 text-indigo-400"/>О нас</h1>
            <div class="text-2xl px-5 py-4 max-w-6xl">Наш сервис позволяет вам моментально получить временный e-mail для одноразового использования. Это удобно для регистрации на сайтах, получения писем и защиты от спама. Все просто, быстро и без лишних данных.</div>
        </motion.div>
   </motion.div>
</template>

<script setup lang="ts">
import { motion, cubicBezier} from 'motion-v'
import { FaRegClone } from '@kalimahapps/vue-icons';

const transition = { duration: 1, ease: cubicBezier(0.25, 0.1, 0.25, 1) }

const variants = {
  hidden: {
    filter: 'blur(10px)',
    transform: 'translateY(15%)',
    opacity: 0
  },
  visible: {
    filter: 'blur(0)',
    transform: 'translateY(0)',
    opacity: 1
  }
}
</script>