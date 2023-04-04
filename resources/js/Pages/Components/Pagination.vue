<template>
    <div class="bg-indigo-800">
        <p class="inline text-white ml-4">Total {{count}}</p>
        <p class="float-right">
          <span class=" text-white">Page</span>
          <template v-for="(link, n) in links" :key="n">
            <Component
              :is="link.url ? 'Link' : 'span' " 
              v-if="link.active || getLabel(link.label) == '<'"  
              :href="link.url"
              v-html="getLabel(link.label)"
              class="px-1 text-white"
              :class="link.active ? 'text-white' : ''"
              
            />
          </template>
            <span class="text-white">of
            {{totalPages}}
            </span>
            <Component
              :is="links[links.length - 1].url ? 'Link' : 'span' " 
              v-if="getLabel(links[links.length - 1].label) == '>'"  
              :href="links[links.length - 1].url"
              v-html="getLabel(links[links.length - 1].label)"
              class="text-white mr-4 ml-2"
              
            />

        </p>
    </div>
</template>


<script>
export default {
    props: {
        links: Array,
        count: Number,
        totalPages: Number

    },
    methods: {
      getLabel(label) {
          if(label === "&laquo; Previous") {
              return "<";
          }
          if(label === "Next &raquo;") {
              return ">";
          }

          return label;
      }
    },
}
</script>