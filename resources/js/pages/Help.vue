<template>
  <motion.div :initial="'hidden'" :while-in-view="'visible'" class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <motion.div :variants="variants" :transition="transition" class="w-full p-4 bg-white rounded-xl shadow">
      <h1 class="text-xl sm:text-2xl md:text-3xl px-2 sm:px-4 font-semibold flex items-center gap-3 sm:gap-4">
        <FaRegCircleQuestion class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-indigo-400" />
        Помощь
      </h1>
      <div class="text-base sm:text-lg md:text-xl lg:text-2xl px-2 sm:px-4 py-4 max-w-5xl flex items-start gap-3 flex-wrap">
        <span>Если у вас возникли вопросы или требуется поддержка, пожалуйста, воспользуйтесь следующей ссылкой:</span>
        <a
          href="https://t.me/skorleep"
          class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 underline"
          target="_blank"
          rel="noopener noreferrer"
        >
          Telegram
          <AnOutlinedLink class="w-5 h-5 md:w-6 md:h-6" />
        </a>
      </div>
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