<template>
    <div class="py-12 bg-gray-900">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                <div class="bg-gray-800 shadow rounded-lg overflow-hidden">
                    <h2 class="text-lg font-bold p-4 text-white">
                        Produtos com Estoque Baixo
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse border border-gray-700 text-white">
                            <thead class="bg-gray-700 text-white uppercase text-sm">
                            <tr>
                                <th class="border px-4 py-2 dark:border-gray-600">Nome</th>
                                <th class="border px-4 py-2 dark:border-gray-600">Estoque</th>
                                <th class="border px-4 py-2 dark:border-gray-600">Mínimo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="product in lowStockProducts.data"
                                :key="product.id"
                                class="hover:bg-gray-600 transition"
                            >
                                <td class="border px-4 py-2 dark:border-gray-600">{{ product.name }}</td>
                                <td class="border px-4 py-2 dark:border-gray-600">{{ product.stock }}</td>
                                <td class="border px-4 py-2 dark:border-gray-600">{{ product.minimum_stock }}</td>
                            </tr>
                            <tr v-if="lowStockProducts.length === 0">
                                <td colspan="3" class="text-center py-4 text-gray-400">
                                    Nenhum produto com estoque baixo
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <Pagination
                            :pagination="lowStockProducts"
                            page-param="lowStockProducts"
                            base-url="/home"
                        />
                    </div>
                </div>

                <div class="bg-gray-800 shadow rounded-lg overflow-hidden">
                    <h2 class="text-lg font-bold p-4 text-white">
                        Produtos Próximos do Vencimento
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse border border-gray-700 text-white">
                            <thead class="bg-gray-700 text-white uppercase text-sm">
                            <tr>
                                <th class="border px-4 py-2 dark:border-gray-600">Nome</th>
                                <th class="border px-4 py-2 dark:border-gray-600">Validade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="product in nearExpirationProducts.data"
                                :key="product.id"
                                class="hover:bg-gray-600 transition"
                            >
                                <td class="border px-4 py-2 dark:border-gray-600">{{ product.name }}</td>
                                <td class="border px-4 py-2 dark:border-gray-600">{{ formatDate(product.expiration_date) }}</td>
                            </tr>
                            <tr v-if="nearExpirationProducts.length === 0">
                                <td colspan="2" class="text-center py-4 text-gray-400">
                                    Nenhum produto próximo ao vencimento
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <Pagination
                            :pagination="nearExpirationProducts"
                            page-param="nearExpirationProducts"
                            base-url="/home"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import moment from "moment";
import "moment/locale/pt-br";
import Pagination from "@/Components/Pagination.vue";

export default {
    components: {Pagination},
    props: {
        lowStockProducts: Object,
        nearExpirationProducts: Object,
    },
    methods: {
        formatDate(date) {
            return moment(date).locale("pt-br").format("DD/MM/YYYY HH:mm:ss");
        },
    },
    layout: AuthenticatedLayout,
};
</script>
