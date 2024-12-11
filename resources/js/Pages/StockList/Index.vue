<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

            <div class="bg-gray-800 shadow rounded-lg">
                <h2 class="text-lg font-bold p-4 text-white">
                    Saldo de Estoque Atual
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-white text-left border-collapse border border-gray-700">
                        <thead class="bg-gray-700 uppercase text-sm">
                        <tr>
                            <th class="border px-4 py-2 dark:border-gray-600">Produto</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Estoque Atual</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in stockBalance.data" :key="item.id" class="hover:bg-gray-600">
                            <td class="border px-4 py-2 dark:border-gray-600">{{ item.name }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ item.stock }}</td>
                        </tr>
                        <tr v-if="stockBalance.length === 0">
                            <td colspan="7" class="text-center py-4 text-gray-600 dark:text-gray-400">
                                Nenhum estoque disponível
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination
                        :pagination="stockBalance"
                        page-param="stockBalance"
                        base-url="stock"
                    />
                </div>
            </div>

            <div class="bg-gray-800 shadow rounded-lg">
                <h2 class="text-lg font-bold p-4 text-white">Saldo de Estoque por Categoria</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-white text-left border-collapse border border-gray-700">
                        <thead class="bg-gray-700 uppercase text-sm">
                        <tr>
                            <th class="border px-4 py-2 dark:border-gray-600">Categoria</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Estoque Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in stockByCategory.data" :key="item.category" class="hover:bg-gray-600">
                            <td class="border px-4 py-2 dark:border-gray-600">{{ item.category }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ item.total_stock }}</td>
                        </tr>
                        <tr v-if="stockByCategory.length === 0">
                            <td colspan="7" class="text-center py-4 text-gray-600 dark:text-gray-400">
                                Nenhum estoque disponível
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination
                        :pagination="stockByCategory"
                        page-param="stockByCategory"
                        base-url="stock"
                    />
                </div>
            </div>

            <div class="bg-gray-800 shadow rounded-lg">
                <h2 class="text-lg font-bold p-4 text-white">Movimentações de Estoque</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-white text-left border-collapse border border-gray-700">
                        <thead class="bg-gray-700 uppercase text-sm">
                        <tr>
                            <th class="border px-4 py-2 dark:border-gray-600">Produto</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Tipo</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Quantidade</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Usuário</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="movement in stockMovements.data" :key="movement.id" class="hover:bg-gray-600">
                            <td class="border px-4 py-2 dark:border-gray-600">{{ movement.product.name }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ translateType(movement.type) }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ movement.quantity }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ movement.user.name }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ formatDate(movement.created_at) }}</td>
                        </tr>
                        <tr v-if="stockMovements.length === 0">
                            <td colspan="7" class="text-center py-4 text-gray-600 dark:text-gray-400">
                                Nenhum estoque disponível
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination
                        :pagination="stockMovements"
                        page-param="stockMovements"
                        base-url="stock"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";

export default {
    components: {Pagination},
    props: {
        stockBalance: Object,
        stockByCategory: Object,
        stockBySupplier: Object,
        stockMovements: Object,
        userRole: String,
    },
    layout: AuthenticatedLayout,
    methods: {
        formatDate(date) {
            return moment(date).format("DD/MM/YYYY HH:mm");
        },
        translateType(type) {
            return {
                entry: 'Entrada',
                exit: 'Saída',
                adjustment: 'Ajuste',
            }[type] || 'Desconhecido';
        }
    },
};
</script>
