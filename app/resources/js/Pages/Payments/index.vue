<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
    'payments' : Array
})

onMounted(() => {
    props.payments.forEach(payment => {
        paymentList.value.push({
            summary_ym: payment.summary_ym,
            income: payment.income,
            expense: payment.expense,
            total: payment.total
        })
    })
})

const paymentList = ref([])

const currentDate = new Date()
onMounted(() => {
    createDate.year = currentDate.getFullYear()
    createDate.month = currentDate.getMonth()
})
const createDate = ref({
    'year' : currentDate.getFullYear(),
    'month' : currentDate.getMonth()
})
const format = args => {
    return `${args.getFullYear()}年${((args.getMonth() + 1)).toString().padStart(2, "0")}月`
}

const getCreateDate = () => {
    if (createDate.value === null) {
        return ""
    }
    else {
        return `${createDate.value.year}${((createDate.value.month + 1)).toString().padStart(2, "0")}`
    }
}
</script>

<template>
    <Head title="収支一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                収支一覧
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-900 body-font">
                            <div class="container px-5 py-24 mx-auto" style="min-height:500px;">
                                <div class="flex pl-4 mb-4 ml-auto max-w-md ">
                                    <v-row justify="center">
                                        <v-card width="500" height="80" class="mt-16 pa-5">
                                            <Datepicker
                                                v-model="createDate"
                                                :format="format"
                                                monthPicker
                                                locale="jp"
                                                auto-apply
                                            />
                                        </v-card>
                                    </v-row>
                                    <div class="ml-4" v-if="getCreateDate() !== ''">
                                        <Link class="flex mx-auto text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded" :href="route('payments.showSummary', { summary_ym: getCreateDate() })">新規作成</Link>
                                    </div>
                                    <div class="ml-4" v-else>
                                        <button class="flex mx-auto text-white bg-slate-400 border-0 py-2 px-6 focus:outline-none cursor-default rounded">新規作成</button>
                                    </div>
                                </div>
                                <div class="w-full mx-auto overflow-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">集計年月</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">収入額</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">支出額</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">収支合計</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">内訳</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="payment in paymentList" :key="payment.summary_ym">
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ payment.summary_ym }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ Number(payment.income).toLocaleString() }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ Number(payment.expense).toLocaleString() }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-lg text-gray-900 text-end">{{ Number(payment.total).toLocaleString() }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 text-center">
                                                <Link class="mx-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" :href="route('payments.showSummary', { summary_ym: payment.summary_ym })">内訳</Link>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
