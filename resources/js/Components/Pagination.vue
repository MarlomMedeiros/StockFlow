<template>
    <div class="flex items-center space-x-2">
    <span v-for="(link, i) in links" :key="i">
      <inertia-link
          v-if="link.url"
          :href="link.url"
          class="px-2 py-1 border rounded"
          :class="{'font-bold bg-gray-200': link.active}"
      >
        <span v-if="isPreviousLink(link)">&laquo; Anterior</span>
        <span v-else-if="isNextLink(link)">Próximo &raquo;</span>
        <span v-else v-html="link.label"></span>
      </inertia-link>

      <span
          v-else
          class="px-2 py-1 border rounded text-gray-500 bg-gray-100"
      >
        <span v-if="isPreviousLink(link)">&laquo; Anterior</span>
        <span v-else-if="isNextLink(link)">Próximo &raquo;</span>
        <span v-else v-html="link.label"></span>
      </span>
    </span>
    </div>
</template>

<script>
export default {
    props: {
        links: {
            type: Array,
            required: true,
        },
    },
    methods: {
        isPreviousLink(link) {
            return link.label.includes('Previous') || link.label.includes('&laquo;');
        },
        isNextLink(link) {
            return link.label.includes('Next') || link.label.includes('&raquo;');
        },
    },
};
</script>
