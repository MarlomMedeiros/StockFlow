<template>
    <div class="flex justify-end my-5 mr-5">
        <input
            type="search"
            v-model="filters.search"
            placeholder="Pesquisar produtos..."
            @input="applyFilters"
            class="w-full max-w-md px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-800 dark:text-gray-200 dark:focus:ring-blue-600"
        />
    </div>
</template>

<script>
export default {
    props: {
        filters: {
            type: Object,
            default: () => ({}),
        },
        baseUrl: {
            type: String,
            required: true,
        },
    },
    methods: {
        applyFilters() {
            this.$inertia.get(this.baseUrl, { ...this.filters }, {
                preserveState: true,
                replace: true,
            });
        },
    },
};
</script>
