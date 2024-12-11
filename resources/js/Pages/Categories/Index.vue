<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white text-gray-900 shadow rounded-lg overflow-hidden dark:bg-gray-800 dark:text-gray-100">
                <div v-if="isFormVisible" class="p-6">
                    <h2 class="text-lg font-bold mb-4 dark:text-gray-200">
                        {{ form.id ? 'Editar Categoria' : 'Adicionar Categoria' }}
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
                            <label for="name" class="block text-sm font-medium mb-1 dark:text-gray-200">Nome da Categoria</label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Nome da Categoria"
                                class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                                required
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
                    <div  v-if="$page.props.auth.user.role !== 'common_user'" class="flex justify-between items-center p-4">
                        <h2 class="text-xl font-bold dark:text-gray-200">Lista de Categorias</h2>
                        <button
                            @click="showForm"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700"
                        >
                            Adicionar Categoria
                        </button>
                    </div>
                    <table class="w-full text-left table-auto border-collapse border border-gray-200 dark:border-gray-600">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="border px-4 py-2 dark:border-gray-600">Nome</th>
                            <th v-if="$page.props.auth.user.role !== 'common_user'" class="border px-4 py-2 text-center dark:border-gray-600">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="category in categories.data"
                            :key="category.id"
                            class="hover:bg-gray-50 transition dark:hover:bg-gray-600"
                        >
                            <td class="border px-4 py-2 dark:border-gray-600">{{ category.name }}</td>
                            <td  v-if="$page.props.auth.user.role !== 'common_user'" class="border px-4 py-2 text-center dark:border-gray-600">
                                <div class="flex justify-center space-x-2">
                                    <button
                                        @click="editCategory(category)"
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition dark:bg-yellow-600 dark:hover:bg-yellow-700"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="deleteCategory(category.id)"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition dark:bg-red-600 dark:hover:bg-red-700"
                                    >
                                        Deletar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="categories.length === 0">
                            <td colspan="2" class="text-center py-4 text-gray-600 dark:text-gray-400">
                                Nenhuma categoria disponível
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination
                        :pagination="$page.props.categories"
                        base-url="/categories"
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

export default {
    components: {Pagination},
    props: {
        categories: Object,
    },
    layout: AuthenticatedLayout,
    setup(props) {
        const isFormVisible = ref(false);
        const form = useForm({
            id: null,
            name: "",
        });

        const showForm = () => {
            form.reset();
            isFormVisible.value = true;
        };

        const hideForm = () => {
            isFormVisible.value = false;
        };

        const editCategory = (category) => {
            form.id = category.id;
            form.name = category.name;
            isFormVisible.value = true;
        };

        const deleteCategory = (id) => {
            if (confirm("Você tem certeza que deseja deletar?")) {
                form.delete(`/categories/${id}`), {
                    onSuccess: () => {
                        cancelForm();
                    },
                }
            }
        };

        const submit = () => {
            if (form.id) {
                form.put(`/categories/${form.id}`, {
                    onSuccess: cancelForm,
                });
            } else {
                form.post("/categories", {
                    onSuccess: cancelForm,
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
            editCategory,
            deleteCategory,
            submit,
            cancelForm,
            categories: props.categories,
        };
    },
};
</script>
