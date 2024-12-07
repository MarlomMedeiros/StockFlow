<template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="bg-white shadow rounded-lg overflow-hidden dark:bg-gray-800 dark:text-gray-100"
            >
                <div class="p-6" v-if="$page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'manager'">
                    <div v-if="$page.props.errors" class="text-red-500 mb-4">
                        <ul class="list-disc pl-5">
                            <li v-for="(errorMessages, field) in $page.props.errors" :key="field">
                                <span v-for="(message, index) in errorMessages" :key="index">{{ message }}</span>
                            </li>
                        </ul>
                    </div>
                    <form @submit.prevent="submit" class="flex flex-col sm:flex-row sm:gap-4 gap-2 items-center">
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Nome completo"
                            class="flex-grow border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <input
                            v-model="form.phone"
                            type="text"
                            placeholder="Celular para contato"
                            class="flex-grow border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="E-mail"
                            class="flex-grow border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Senha padrão"
                            class="flex-grow border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        />
                        <select
                            v-model="form.role"
                            class="flex-grow border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-blue-600"
                        >
                            <option value="" disabled>Selecione o cargo</option>
                            <option value="admin">Administrador</option>
                            <option value="manager">Gerente</option>
                            <option value="common_user">Usuário comum</option>
                        </select>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition w-full sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700"
                        >
                            {{ form.id ? 'Atualizar' : 'Adicionar' }}
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
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
                            v-for="user in users"
                            :key="user.id"
                            class="hover:bg-gray-50 transition dark:hover:bg-gray-600"
                        >
                            <td class="border px-4 py-2 dark:border-gray-600">{{ user.name }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ user.phone }}</td>
                            <td class="border px-4 py-2 dark:border-gray-600">{{ user.email }}</td>
                            <td class="border px-4 py-2 capitalize dark:border-gray-600">{{ user.role }}</td>
                            <td class="border px-4 py-2 text-center dark:border-gray-600" v-if="user.role !== 'admin' || user.id === $page.props.auth.user.id">
                                <div class="flex justify-center space-x-2">
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
        users: Array,
    },
    layout: AuthenticatedLayout,
    setup(props) {
        const form = useForm({
            name: "",
            phone: "",
            email: "",
            password: "",
            role: "",
            id: null,
        });

        const submit = () => {
            if (form.id) {
                form.put(`/users/${form.id}`, {
                    onSuccess: () => form.reset(),
                });
            } else {
                form.post("/users", {
                    onSuccess: () => form.reset(),
                });
            }
        };

        const editUser = (user) => {
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

        return { form, submit, editUser, deleteUser };
    },
};
</script>
