<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps({
    'summary_ym' : String,
    'members' : Array,
    'payments' : Array
})

onMounted(() => {
    props.members.forEach(member => {
        memberList.value.push({
            member_id: member.member_id,
            member_name: member.member_name
        })
    })
    props.payments.forEach(payment => {
        paymentList.value.push({
            member_id: payment.payments,
            category_id: payment.category_id,
            category_name: payment.category_name,
            categorized_payment_id: payment.categorized_payment_id,
            payment_date: payment.payment_date,
            amount: payment.amount,
            payment_label: payment.payment_label
        })
    })
})
const memberList = ref([])
const paymentList = ref([])

const commaSeparateOrBlank = args => {
    if (args !== "") {
        return Number(args).toLocaleString()
    }
    else {
        return "";
    }
}
</script>

<template>
    <Head title="収支登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                収支登録
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-900 body-font">
                            <div class="container px-5 py-4 mx-auto" style="min-height:500px;">
                                <h1 class="text-3xl">{{ props.summary_ym.substring(0, 4) + '年' + props.summary_ym.substring(4, 6) + '月' }}</h1>
                                <div class="flex pl-4 mb-4 ml-auto max-w-sm ">
                                    <Link class="flex mx-auto text-black bg-slate-100 border-2 py-2 px-6 focus:outline-none hover:bg-slate-200 rounded" :href="route('payments.index', {})">一覧へ戻る</Link>
                                </div>
                                <div class="w-full mx-auto overflow-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 rounded-tl rounded-bl">aaa</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 text-center">bbb</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 text-center">ccc</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">AAA</th>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">BBB</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">CCC</td>
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
