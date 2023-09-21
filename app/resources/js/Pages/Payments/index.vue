<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'

const props = defineProps({
    'payments' : Array
})

onMounted(() => {
    props.payments.forEach(payment => {
        paymentList.value.push({
            summary_ym: payment.summary_ym,
            income: Number(payment.income),
            expense: Number(payment.expense),
            total: Number(payment.total)
        })
    })
})

const paymentList = ref([])
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
                            <div class="container px-5 py-24 mx-auto">
                                <div class="flex pl-4 mb-4 ml-auto max-w-sm ">
                                    <input type="text" id="name" name="name" class="mx-auto bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規作成</button>
                                </div>
                                <div class="w-full mx-auto overflow-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">集計年月</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">収入額</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">支出額</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">収支合計</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">編集</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="payment in paymentList" :key="payment.summary_ym">
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ payment.summary_ym }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ payment.income.toLocaleString() }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ payment.expense.toLocaleString() }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-lg text-gray-900 text-end">{{ payment.total.toLocaleString() }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 text-center">
                                                <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">編集</button>
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
