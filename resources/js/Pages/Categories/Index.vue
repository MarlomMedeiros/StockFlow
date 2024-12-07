<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="bg-white text-gray-900 shadow rounded-lg overflow-hidden dark:bg-gray-800 dark:text-gray-100"
            >
                <div class="p-6">
                    <span v-if="$page.props.errors.name" class="text-red-500 dark:text-red-400 mb-4 block">
                        {{ $page.props.errors.name }}
                    </span>
                    <form
                        @submit.prevent="submit"
                        class="flex flex-col sm:flex-row sm:gap-4 gap-2 items-center"
                    >
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Entre com o nome da categoria"
                            class="flex-grow border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition w-full sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700"
                        >
                            {{ form.id ? 'Atualizar' : 'Adicionar' }}
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table
                        class="w-full text-left table-auto border-collapse border border-gray-200 dark:border-gray-600"
                    >
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="border px-4 py-2 dark:border-gray-600">Categoria</th>
                            <th class="border px-4 py-2 text-center dark:border-gray-600">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="category in categories"
                            :key="category.id"
                            class="hover:bg-gray-50 transition dark:hover:bg-gray-600"
                        >
                            <td class="border px-4 py-2 dark:border-gray-600">
                                {{ category.name }}
                            </td>
                            <td class="border px-4 py-2 text-center dark:border-gray-600">
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    props: {
        categories: Array,
    },
    layout: AuthenticatedLayout,
    setup() {
        const form = useForm({
            name: "",
            id: null,
        });

        const submit = () => {
            if (form.id) {
                form.put(`/categories/${form.id}`, {
                    onSuccess: () => form.reset(),
                });
            } else {
                form.post("/categories", {
                    onSuccess: () => form.reset(),
                });
            }
        };

        const editCategory = (category) => {
            form.name = category.name;
            form.id = category.id;
        };

        const deleteCategory = (id) => {
            if (confirm("Você tem certeza que deseja deletar?")) {
                form.delete(`/categories/${id}`);
            }
        };

        return {form, submit, editCategory, deleteCategory};
    },
};
</script>
