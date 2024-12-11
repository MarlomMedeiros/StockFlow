<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white text-gray-900 shadow rounded-lg overflow-hidden dark:bg-gray-800 dark:text-gray-100">
                <div v-if="isFormVisible" class="p-6">
                    <h2 class="text-lg font-bold mb-4 dark:text-gray-200">
                        {{ form.id ? 'Editar Movimentação' : 'Registrar Movimentação' }}
                    </h2>

                    <div v-if="$page.props.errors" class="text-red-500 dark:text-red-400 mb-4">
                        <ul class="list-disc pl-5">
                            <li v-for="(errorMessages, field) in $page.props.errors" :key="field">
                                <span v-for="(message, index) in errorMessages" :key="index">{{ message }}</span>
                            </li>
                        </ul>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-4">
                        <select
                            v-model="form.product_id"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                            required
                        >
                            <option value="" disabled>Selecione um Produto</option>
                            <option v-for="product in products" :key="product.id" :value="product.id" id="selectProduct">
                                {{ product.name }}
                            </option>
                        </select>
                        <select
                            v-model="form.type"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                            required
                        >
                            <option value="" disabled>Selecione o Tipo</option>
                            <option value="entry">Entrada</option>
                            <option value="exit">Saída</option>
                            <option value="adjustment">Ajuste</option>
                        </select>
                        <input
                            v-model="form.quantity"
                            type="number"
                            placeholder="Quantidade"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                            required
                        />
                        <input
                            v-model="form.reason"
                            type="text"
                            placeholder="Motivo (opcional)"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <div class="flex justify-between" v-if="$page.props.auth.user.role !== 'common_user'">
                            <button
                                type="submit"
                                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700"
                            >
                                {{ form.id ? 'Atualizar' : 'Registrar' }}
                            </button>
                            <button
                                type="button"
                                @click="cancelForm"
                                class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition dark:bg-gray-600 dark:hover:bg-gray-700"
                            >
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <div v-else class="overflow-x-auto">
                    <div class="flex justify-between items-center p-4">
                        <h2 class="text-xl font-bold dark:text-gray-200">Movimentações de Estoque</h2>
                        <div class="flex gap-2">
                            <select v-model="selectedProduct"
                                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600">
                                <option value="">Todos os Produtos</option>
                                <option v-for="p in products" :key="p.id" :value="p.id">
                                    {{ p.name }}
                                </option>
                            </select>
                            <button @click="filterByProduct"
                                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition dark:bg-gray-600 dark:hover:bg-gray-700">
                                Filtrar
                            </button>
                            <button v-if="$page.props.auth.user.role !== 'common_user'"
                                @click="showForm"
                                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700"
                            >
                                Registrar Movimentação
                            </button>
                        </div>
                    </div>
                    <table
                        class="w-full text-left table-auto border-collapse border border-gray-200 dark:border-gray-600">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="border px-4 py-2 dark:border-gray-600">Produto</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Tipo</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Quantidade</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Motivo</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="(movement, index) in movements.data"
                            :key="movement.id || index"
                            class="hover:bg-gray-50 transition dark:hover:bg-gray-600"
                        >
                            <td class="border px-4 py-2 dark:border-gray-600">{{ movement.product || 'N/A' }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ translateType(movement.type) || 'N/A' }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ movement.quantity || 0 }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ movement.reason || 'N/A' }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ formatDate(movement.created_at) || 'N/A' }}</td>
                        </tr>
                        <tr v-if="movements.data.length === 0">
                            <td colspan="5" class="text-center py-4 text-gray-600 dark:text-gray-400">
                                Nenhuma movimentação registrada
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination
                        :pagination="movements"
                        base-url="/stocks"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {ref} from "vue";
import {useForm, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import moment from 'moment';
import 'moment/locale/pt-br';
import Pagination from "@/Components/Pagination.vue";

export default {
    components: {Pagination},
    props: {
        movements: Object,
        products: Array,
        selectedProduct: String,
    },
    methods: {
        formatDate(date) {
            return moment(date).locale('pt-br').format('DD/MM/YYYY HH:mm:ss');
        },
        translateType(type) {
            return {
                entry: 'Entrada',
                exit: 'Saída',
                adjustment: 'Ajuste',
            }[type] || 'Desconhecido';
        },
    },
    layout: AuthenticatedLayout,
    setup(props) {
        const isFormVisible = ref(false);
        const form = useForm({
            id: null,
            product_id: "",
            type: "",
            quantity: "",
            reason: "",
        });

        const selectedProduct = ref('');

        const showForm = () => {
            form.reset();
            isFormVisible.value = true;
        };

        const hideForm = () => {
            isFormVisible.value = false;
        };

        const submit = () => {
            form.post("/stocks", {
                onSuccess: () => hideForm(),
            });
        };

        const cancelForm = () => {
            hideForm();
        };

        const filterByProduct = () => {
            router.get('/stocks', { product_id: selectedProduct.value });
        };



        return {
            form,
            isFormVisible,
            showForm,
            hideForm,
            submit,
            cancelForm,
            movements: props.movements,
            products: props.products,
            selectedProduct,
            filterByProduct
        };
    },
};
</script>
