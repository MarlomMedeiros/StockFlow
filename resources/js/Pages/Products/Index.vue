<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="bg-white text-gray-900 shadow rounded-lg overflow-hidden dark:bg-gray-800 dark:text-gray-100"
            >
                <div v-if="isFormVisible" class="p-6">
                    <h2 class="text-lg font-bold mb-4 dark:text-gray-200">
                        {{ form.id ? 'Editar Produto' : 'Adicionar Produto' }}
                    </h2>

                    <div v-if="$page.props.success" class="text-green-500 dark:text-green-400 mb-4">
                        {{ $page.props.success }}
                    </div>

                    <div v-if="$page.props.errors" class="text-red-500 dark:text-red-400 mb-4">
                        <ul class="list-disc pl-5">
                            <li v-for="(errorMessages, field) in $page.props.errors" :key="field">
                                <span v-for="(message, index) in errorMessages" :key="index">{{ message }}</span>
                            </li>
                        </ul>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-1 dark:text-gray-200">Nome do Produto</label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Nome do Produto"
                                class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                                required
                            />
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium mb-1 dark:text-gray-200">Código do Produto</label>
                            <input
                                id="code"
                                v-model="form.code"
                                type="text"
                                placeholder="Código do Produto"
                                class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                                required
                            />
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium mb-1 dark:text-gray-200">Descrição do Produto</label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Descrição do Produto"
                                class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                            ></textarea>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium mb-1 dark:text-gray-200">Categoria</label>
                            <select
                                id="category"
                                v-model="form.category_id"
                                class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                                required
                            >
                                <option value="" disabled>Selecione uma Categoria</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="supplier" class="block text-sm font-medium mb-1 dark:text-gray-200">Fornecedor</label>
                            <select
                                id="supplier"
                                v-model="form.supplier_id"
                                class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                            >
                                <option value="" disabled>Selecione um Fornecedor</option>
                                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                                    {{ supplier.name }}
                                </option>
                            </select>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label for="cost_price" class="block text-sm font-medium mb-1 dark:text-gray-200">Preço de Custo</label>
                                <input
                                    id="cost_price"
                                    v-model="form.cost_price"
                                    type="number"
                                    step="0.01"
                                    placeholder="Preço de Custo"
                                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                                    required
                                />
                            </div>
                            <div class="w-full">
                                <label for="sale_price" class="block text-sm font-medium mb-1 dark:text-gray-200">Preço de Venda</label>
                                <input
                                    id="sale_price"
                                    v-model="form.sale_price"
                                    type="number"
                                    step="0.01"
                                    placeholder="Preço de Venda"
                                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                                    required
                                />
                            </div>
                        </div>


                        <div class="w-full">
                            <label for="minimum_stock" class="block text-sm font-medium mb-1 dark:text-gray-200">Estoque Mínimo</label>
                            <input
                                id="minimum_stock"
                                v-model="form.minimum_stock"
                                type="number"
                                placeholder="Estoque Mínimo"
                                class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                                required
                            />
                        </div>


                        <div>
                            <label for="expiration_date" class="block text-sm font-medium mb-1 dark:text-gray-200">Data de Validade</label>
                            <input
                                id="expiration_date"
                                v-model="form.expiration_date"
                                type="date"
                                class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                            />
                        </div>

                        <div class="flex justify-between">
                            <button
                                type="submit"
                                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700"
                            >
                                {{ form.id ? 'Atualizar' : 'Adicionar' }}
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
                    <Search :filters="filters" :base-url="route('products.index')"/>
                    <div class="flex  justify-between items-center p-4" v-if="$page.props.auth.user.role !== 'common_user'">
                        <h2 class="text-xl font-bold dark:text-gray-200">Lista de Produtos</h2>
                        <button
                            @click="showForm"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700"
                        >
                            Adicionar Produto
                        </button>
                    </div>
                    <table
                        class="w-full text-left table-auto border-collapse border border-gray-200 dark:border-gray-600"
                    >
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="border px-4 py-2 dark:border-gray-600">Nome</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Código</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Categoria</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Fornecedor</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Preço Venda</th>
                            <th v-if="$page.props.auth.user.role !== 'common_user'" class="border px-4 py-2 text-center dark:border-gray-600">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="product in products.data"
                            :key="product.id"
                            class="hover:bg-gray-50 transition dark:hover:bg-gray-600"
                        >
                            <td class="border px-4 py-2 dark:border-gray-600">{{ product.name }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ product.code }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ product.category?.name }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ product.supplier?.name || 'N/A' }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">R$ {{ parseFloat(product.sale_price).toFixed(2) }}</td>
                            <td v-if="$page.props.auth.user.role !== 'common_user'" class="border px-4 py-2 text-center dark:border-gray-600">
                                <div class="flex justify-center space-x-2">
                                    <button
                                        @click="editProduct(product)"
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition dark:bg-yellow-600 dark:hover:bg-yellow-700"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="deleteProduct(product.id)"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition dark:bg-red-600 dark:hover:bg-red-700"
                                    >
                                        Deletar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="products.length === 0">
                            <td colspan="7" class="text-center py-4 text-gray-600 dark:text-gray-400">
                                Nenhum produto disponível
                            </td>
                        </tr>
                        </tbody>

                    </table>
                    <Pagination
                        :pagination="products"
                        base-url="/products"
                        :filters="filters"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import Search from '@/Components/Search.vue';


export default {
    components: {Pagination, Search},
    props: {
        products: Object,
        categories: Array,
        suppliers: Array,
        filters: Object,
    },
    layout: AuthenticatedLayout,
    setup(props) {
        const isFormVisible = ref(false);
        const form = useForm({
            id: null,
            name: "",
            code: "",
            description: "",
            category_id: null,
            supplier_id: null,
            cost_price: 0,
            sale_price: 0,
            minimum_stock: 1,
            expiration_date: null,
        });


        const showForm = () => {
            form.reset();
            isFormVisible.value = true;
        };

        const hideForm = () => {
            isFormVisible.value = false;
        };

        const editProduct = (product) => {
            form.id = product.id;
            form.name = product.name;
            form.code = product.code;
            form.description = product.description;
            form.category_id = product.category_id;
            form.supplier_id = product.supplier_id;
            form.cost_price = product.cost_price;
            form.sale_price = product.sale_price;
            form.minimum_stock = product.minimum_stock;
            form.expiration_date = product.expiration_date
                ? product.expiration_date.split("T")[0]
                : null;

            isFormVisible.value = true;
        };

        const deleteProduct = (id) => {
            if (confirm("Tem certeza que deseja excluir este produto?")) {
                form.delete(`/products/${id}`);
            }
        };

        const submit = () => {
            if (form.id) {
                form.put(`/products/${form.id}`, {
                    onSuccess: hideForm,
                });
            } else {
                form.post("/products", {
                    onSuccess: hideForm,
                });
            }
        };

        const cancelForm = () => {
            hideForm();
            form.reset();
        };

        return {
            form,
            isFormVisible,
            showForm,
            hideForm,
            editProduct,
            deleteProduct,
            submit,
            cancelForm,
            categories: props.categories,
            suppliers: props.suppliers,
        };
    },
};
</script>
