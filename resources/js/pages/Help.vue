<template>
    <motion.div :initial="'hidden'" :while-in-view="'visible'" class="container mx-auto py-10">
        <motion.div :variants="variants" :transition="transition" class="w-full p-4 bg-white rounded-xl">
            <h1 class="text-3xl px-5 font-semibold flex items-center gap-4"><FaRegCircleQuestion class="w-8 h-8 text-indigo-400"/>Помощь</h1>
            <div class="text-2xl px-5 py-4 max-w-7xl flex items-center gap-4">Если у вас возникли вопросы или требуется поддержка, пожалуйста, воспользуйтесь следующей ссылкой:<a href="https://t.me/skorleep"><AnOutlinedLink class="w-8 h-8 text-blue-600"/></a></div>
        </motion.div>
   </motion.div>
</template>

<script setup lang="ts">
import { motion, cubicBezier} from 'motion-v'
import { FaRegCircleQuestion } from '@kalimahapps/vue-icons';
import { AnOutlinedLink } from '@kalimahapps/vue-icons';

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