<template>
    <motion.div :initial="'hidden'" :while-in-view="'visible'" class="container w-full h-full py-10 flex bg-[#F9FAFE] gap-8 justify-center overflow-hidden">

        <!-- Управление e-mail, левая панель -->
        <motion.div :variants="variants" :transition="transition" class="w-1/2 h-full flex-1 bg-transparent ">

            <!-- Управление e-mailом -->
            <div class="w-full h-52 p-6 flex-1 bg-white rounded-xl">
                <input v-model="emailStore.email" class="w-full h-16 p-4 bg-[#F9FAFE] rounded-xl outline-none text-2xl text-gray-500" readonly/>
                <div class="flex flex-row gap-6 py-8 justify-between">
                    <button @click="handleCopy" type="button" class="text-white cursor-pointer bg-indigo-400 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-300
                        font-medium rounded-lg text-2xl px-5 py-2.5 me-2 mb-2 dark:hover:bg-indigo-600 focus:outline-none dark:focus:ring-indigo-800">
                        Копировать
                    </button>
                    <button @click="emailStore.recreateEmail" type="button" class="flex items-center gap-2 cursor-pointer font-medium text-2xl px-5 py-2.5 me-2 mb-2 bg-transparent">
                        <CgSpinner
                        v-if="emailStore.loading"
                        class="w-12 h-12 text-indigo-400 animate-spin"
                        />
                        <CgSpinner
                        v-else
                        class="w-12 h-12 text-indigo-400"
                        />
                    Обновить</button>
                </div>
            </div>

            <!-- Лист сообщений -->
            <div class="w-full h-140 flex-1">
                <div class="flex flex-row justify-between mt-4 p-1">
                    <p class="font-medium text-3xl">Входящие</p>
                    <button @clock="isAscending = !isAscending" class="cursor-pointer"><CaArrowsHorizontal class="w-10 h-10 hover:text-gray-600"/></button>
                </div>

                <!-- Проверка на имеющиеся сообщения -->
                <div v-if="emailStore.messages.length === 0" class="h-full flex text-xl text-gray-500 justify-center items-center">
                У вас нет сообщений
                </div>

                <!-- Рендер листа сообщений, если они есть -->
                <ul>
                    <li
                    v-for="msg in sortedMessages"
                    :key="msg.subject + msg.received_at"
                    @click="openMessage(msg)"
                        class="w-full h-32 flex gap-2 items-start bg-transparent hover:bg-white border-b border-gray-200 p-4 rounded-lg cursor-pointer"
                    >
                        <HeFilledUiUserProfile class="w-20 h-20 text-indigo-400"/>
                        <div>
                            <div class="text-xl font-medium">{{ msg.subject }}</div>
                            <div class="text-lg text-gray-400">От: {{ msg.from }}</div>
                        </div>
                        <div class="ms-auto -mx-1.5 text-lg text-gray-400">{{ new Date(msg.received_at).toLocaleString(undefined, {hour12: false}) }}</div>
                    </li>
                </ul>
            </div>
        </motion.div>

        <!-- Раскрытое сообщние, правая панель -->
        <motion.div :variants="variants" :transition="transition" class="w-1/2 h-full flex-1 bg-white rounded-xl p-8 box-border">
            <div v-if="selectedMessage" class="flex flex-col items-start gap-4 h-full overflow-y-auto overflow-x-hidden">

                <!-- Заголовок -->
                <div class="flex border-b border-gray-200 flex-row w-full p-1">
                    <div class="flex gap-6 flex-col">
                        <h2 class="text-3xl font-medium">{{ selectedMessage.subject }}</h2>
                        <div class="text-xl text-gray-500">От: {{ selectedMessage.from }}</div>
                    </div>
                    <div class="ms-auto text-lg text-gray-500">{{ new Date(selectedMessage.received_at).toLocaleString(undefined, {hour12: false}) }}</div>
                </div>

                <!-- Текст сообщения -->
                <div class="border-b border-gray-200 w-full p-1 h-3/4">
                    <div class="text-lg">{{ selectedMessage.body_text }}</div>
                </div>

                <!-- Рендер вложений, если они есть -->
                <div class="w-full p-1">
                    <h4 class="text-lg font-medium">Вложения:</h4>
                    <ul class="space-y-1 flex flex-col gap-3">
                        <li
                        v-for="file in selectedMessage.attachments"
                        :key="file.url!"
                        class="w-full flex items-center text-sm bg-[#F9FAFE] rounded-b-lg p-1"
                        >   
                            <FlDocumentText class="w-8 h-8 text-indigo-400"/>
                            <a v-if="file.url" :href="file.url" :download="file.filename ?? 'attachment'" target="_blank" class="text-indigo-400 underline mx-3 text-lg">
                                {{ file.filename ?? 'Unknown file' }}
                            </a>
                            <span v-if="file.size !== null" class="text-gray-400 text-lg ms-auto"> {{ (file.size / 1024).toFixed(1) }} KB</span>
                        </li>
                        <div v-if="selectedMessage && selectedMessage.attachments?.length === 0">
                            В сообщение нет вложений
                        </div>
                    </ul>
                </div>
            </div>

            <!-- Проверка на выбранное сообщение -->
            <div v-if="!selectedMessage" class="h-full flex text-2xl text-gray-500 justify-center items-center"> 
                Выберите сообщение
            </div>
        </motion.div>
   </motion.div>
</template>

<script setup lang="ts">
import {useEmailStore} from '../stores/emailStore'
import {computed, onMounted, ref} from 'vue'
import { CgSpinner } from '@kalimahapps/vue-icons';
import { CaArrowsHorizontal } from '@kalimahapps/vue-icons';
import { HeFilledUiUserProfile } from '@kalimahapps/vue-icons';
import { FlDocumentText } from '@kalimahapps/vue-icons';
import { motion, cubicBezier } from 'motion-v'
import { isAccessor } from 'typescript';

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

const emailStore = useEmailStore()
const selectedMessage = ref<EmailMessage | null>(null)
const useMock = import.meta.env.VITE_USE_MOCK === 'false'
const isAscending = ref(false)

onMounted(() => {
    if (useMock) {
        emailStore.loadMockMessages()
    } else {
        const savedEmail = localStorage.getItem('tempEmail')
        if (savedEmail) {
            emailStore.email = savedEmail
            emailStore.fetchMessages()
        } else {
            emailStore.recreateEmail()
        }
    }
})

const sortedMessages = computed(() => {
    return[...emailStore.messages].sort((a, b) => {
        const timeA = new Date(a.received_at).getTime();
        const timeB = new Date(b.received_at).getTime();
        return isAscending.value ? timeA - timeB : timeB - timeA;
    })
})

function handleCopy() {
    navigator.clipboard.writeText(emailStore.email)
}

function openMessage(msg: EmailMessage) {
    selectedMessage.value = msg;
}
</script>