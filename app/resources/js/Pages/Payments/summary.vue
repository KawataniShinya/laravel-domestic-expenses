<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
    'members' : Array,
    'categories' : Array,
    'payments' : Array
})

onMounted(() => {
    props.members.forEach(member => {
        memberList.value.push({
            member_id: member.member_id,
            member_name: member.member_name
        })
    })
    props.categories.forEach(category => {
        categoryList.value.push({
            category_id: category.category_id,
            category_name: category.category_name
        })
    })
    props.payments.forEach(payment => {
        paymentList.value.push({
            member_id: payment.member_id,
            category_id: payment.category_id,
            amount: payment.amount
        })
    })
})

const memberList = ref([])
const categoryList = ref([])
const paymentList = ref([])
</script>

<template>
    <Head title="収支詳細" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                収支詳細
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-900 body-font">
                            <div class="container px-5 py-24 mx-auto" style="min-height:500px;">
                                <div class="flex pl-4 mb-4 ml-auto max-w-sm ">
                                    <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">編集</button>
                                </div>
                                <div class="w-full mx-auto overflow-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">index</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">member_id</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">member_name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(member, memberIndex) in memberList" :key="memberList.member_id">
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ memberIndex }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ member.member_id }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ member.member_name }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">index</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">category_id</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">category_name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(category, categoryIndex) in categoryList" :key="categoryList.category_id">
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ categoryIndex }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ category.category_id }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ category.category_name }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">index</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">member_id</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">category_id</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-center">amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(payment, paymentIndex) in paymentList" :key="paymentList.member_id">
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ paymentIndex }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ payment.member_id }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ payment.category_id }}</td>
                                            <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3 text-end">{{ payment.amount }}</td>
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
