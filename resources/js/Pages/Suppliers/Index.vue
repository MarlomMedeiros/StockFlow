<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white text-gray-900 shadow rounded-lg overflow-hidden dark:bg-gray-800 dark:text-gray-100">
                <div v-if="isFormVisible" class="p-6">
                    <h2 class="text-lg font-bold mb-4 dark:text-gray-200">
                        {{ form.id ? 'Editar Fornecedor' : 'Adicionar Fornecedor' }}
                    </h2>

                    <div v-if="$page.props.errors" class="text-red-500 dark:text-red-400 mb-4">
                        <ul class="list-disc pl-5">
                            <li v-for="(errorMessages, field) in $page.props.errors" :key="field">
                                <span v-for="(message, index) in errorMessages" :key="index">{{ message }}</span>
                            </li>
                        </ul>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-4">
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Nome do Fornecedor"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                            required
                        />
                        <input
                            v-model="form.cnpj"
                            type="text"
                            placeholder="CNPJ do Fornecedor"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                            required
                        />
                        <input
                            v-model="form.contact"
                            type="text"
                            placeholder="Contato do Fornecedor"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                            required
                        />
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
                    <div class="flex justify-between items-center p-4">
                        <h2 class="text-xl font-bold dark:text-gray-200">Lista de Fornecedores</h2>
                        <button
                            @click="showForm"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700"
                        >
                            Adicionar Fornecedor
                        </button>
                    </div>
                    <table
                        class="w-full text-left table-auto border-collapse border border-gray-200 dark:border-gray-600"
                    >
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="border px-4 py-2 dark:border-gray-600">Nome</th>
                            <th class="border px-4 py-2 dark:border-gray-600">CNPJ</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Contato</th>
                            <th class="border px-4 py-2 text-center dark:border-gray-600">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="supplier in suppliers"
                            :key="supplier.id"
                            class="hover:bg-gray-50 transition dark:hover:bg-gray-600"
                        >
                            <td class="border px-4 py-2 dark:border-gray-600">{{ supplier.name }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ supplier.cnpj }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ supplier.contact }}</td>
                            <td class="border px-4 py-2 text-center dark:border-gray-600">
                                <div class="flex justify-center space-x-2">
                                    <button
                                        @click="editSupplier(supplier)"
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition dark:bg-yellow-600 dark:hover:bg-yellow-700"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="deleteSupplier(supplier.id)"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition dark:bg-red-600 dark:hover:bg-red-700"
                                    >
                                        Deletar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="suppliers.length === 0">
                            <td colspan="4" class="text-center py-4 text-gray-600 dark:text-gray-400">
                                Nenhum fornecedor disponível
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    props: {
        suppliers: Array,
    },
    layout: AuthenticatedLayout,
    setup() {
        const isFormVisible = ref(false);
        const form = useForm({
            id: null,
            name: "",
            cnpj: "",
            contact: "",
        });

        const showForm = () => {
            form.reset();
            isFormVisible.value = true;
        };

        const hideForm = () => {
            isFormVisible.value = false;
        };

        const editSupplier = (supplier) => {
            form.id = supplier.id;
            form.name = supplier.name;
            form.cnpj = supplier.cnpj;
            form.contact = supplier.contact;
            isFormVisible.value = true;
        };

        const deleteSupplier = (id) => {
            if (confirm("Tem certeza que deseja excluir este fornecedor?")) {
                form.delete(`/suppliers/${id}`);
            }
        };

        const submit = () => {
            if (form.id) {
                form.put(`/suppliers/${form.id}`, {
                    onSuccess: () => {
                        hideForm();
                    },
                });
            } else {
                form.post("/suppliers", {
                    onSuccess: () => {
                        hideForm();
                    },
                });
            }
        };

        const cancelForm = () => {
            hideForm();
        };

        return { form, isFormVisible, showForm, hideForm, editSupplier, deleteSupplier, submit, cancelForm };
    },
};
</script>


