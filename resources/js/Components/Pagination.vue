<template>
    <div class="overflow-x-auto">
        <div class="flex justify-center gap-2 my-5">
            <button
                v-for="link in pagination.links.filter(l => l.url)"
                :key="link.label"
                :class="[
                    'px-3 py-1 border rounded',
                    link.active
                        ? 'font-bold underline text-black bg-gray-200 dark:text-white dark:bg-gray-800'
                        : 'hover:bg-gray-300 text-gray-800 dark:hover:bg-gray-600 dark:text-gray-300'
                ]"
                @click="handleClick(link)"
            >
                <span v-html="link.label"></span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        pagination: {
            type: Object,
            required: true,
        },
        filters: {
            type: Object,
            default: () => ({}),
        },
        baseUrl: {
            type: String,
            required: true,
        },
        pageParam: {
            type: String,
            default: null,
        },
    },
    methods: {
        changePage(page) {
            const params = { ...this.filters };
            if (this.pageParam) {
                params[this.pageParam] = page;
            } else {
                params.page = page;
            }
            this.$inertia.get(this.baseUrl, params);
        },
        handleClick(link) {
            if (link.url) {
                const urlParams = new URL(link.url).searchParams;
                const page = this.pageParam
                    ? urlParams.get(this.pageParam)
                    : urlParams.get('page');
                this.changePage(page);
            }
        },
        applyFilters() {
            this.$inertia.get(this.baseUrl, {...this.filters}, {
                preserveState: true,
                replace: true,
            });
        },
    },
};
</script>
