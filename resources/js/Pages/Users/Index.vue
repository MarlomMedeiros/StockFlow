<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="bg-white shadow rounded-lg overflow-hidden dark:bg-gray-800 dark:text-gray-100"
            >
                <div v-if="isFormVisible" class="p-6">
                    <div v-if="$page.props.errors" class="text-red-500 mb-4">
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
                            placeholder="Nome completo"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <input
                            v-model="form.phone"
                            type="text"
                            placeholder="Celular para contato"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="E-mail"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Senha padrão"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <select
                            v-model="form.role"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        >
                            <option value="" disabled>Selecione o cargo</option>
                            <option v-if="$page.props.auth.user.role === 'admin'" value="admin">Administrador</option>
                            <option value="manager">Gerente</option>
                            <option value="common_user">Usuário comum</option>
                        </select>
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
                    <div class="flex justify-between items-center p-4" v-if="$page.props.auth.user.role !== 'common_user'">
                        <h2 class="text-xl font-bold dark:text-gray-200">Lista de Usuários</h2>
                        <button
                            @click="showForm"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700"
                        >
                            Adicionar Usuário
                        </button>
                    </div>
                    <table class="w-full text-left table-auto border-collapse border border-gray-200 dark:border-gray-600">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="border px-4 py-2 dark:border-gray-600">Nome completo</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Celular</th>
                            <th class="border px-4 py-2 dark:border-gray-600">E-mail</th>
                            <th class="border px-4 py-2 dark:border-gray-600">Cargo</th>
                            <th class="border px-4 py-2 text-center dark:border-gray-600">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="user in users.data"
                            :key="user.id"
                            class="hover:bg-gray-50 transition dark:hover:bg-gray-600"
                        >
                            <td class="border px-4 py-2 dark:border-gray-600">{{ user.name }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ user.phone }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ user.email }}</td>
                            <td class="border px-4 py-2 capitalize dark:border-gray-600">{{ user.role }}</td>
                            <td class="border px-4 py-2 text-center dark:border-gray-600">
                                <div class="flex justify-center space-x-2" v-if="user.role !== 'admin' || user.id === $page.props.auth.user.id">
                                    <button
                                        @click="editUser(user)"
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition dark:bg-yellow-600 dark:hover:bg-yellow-700"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="deleteUser(user.id)"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition dark:bg-red-600 dark:hover:bg-red-700"
                                    >
                                        Deletar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td colspan="5" class="text-center text-gray-600 py-4 dark:text-gray-400">Nenhum usuário encontrado.</td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination
                        :pagination="$page.props.users"
                        base-url="/users"
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
        users: Object,
    },
    layout: AuthenticatedLayout,
    setup(props) {
        const isFormVisible = ref(false);

        const form = useForm({
            name: "",
            phone: "",
            email: "",
            password: "",
            role: "",
            id: null,
        });

        const showForm = () => {
            isFormVisible.value = true;
            form.reset();
        };

        const cancelForm = () => {
            isFormVisible.value = false;
        };

        const submit = () => {
            if (form.id) {
                form.put(`/users/${form.id}`, {
                    onSuccess: () => {
                        form.reset();
                        isFormVisible.value = false;
                    },
                });
            } else {
                form.post("/users", {
                    onSuccess: () => {
                        form.reset();
                        isFormVisible.value = false;
                    },
                });
            }
        };

        const editUser = (user) => {
            isFormVisible.value = true;
            form.name = user.name;
            form.phone = user.phone;
            form.email = user.email;
            form.role = user.role;
            form.password = "";
            form.id = user.id;
        };

        const deleteUser = (id) => {
            if (confirm("Você tem certeza que deseja excluir este usuário?")) {
                form.delete(`/users/${id}`);
            }
        };

        return { form, submit, editUser, deleteUser, showForm, cancelForm, isFormVisible };
    },
};
</script>
