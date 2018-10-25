<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li v-show="prevUrl" class="page-item">
            <a @click="page--" class="page-link" href="javascript:void(0);" aria-label="Previous" rel="prev">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>
        <li v-show="nextUrl" class="page-item">
            <a @click.prevent="page++" class="page-link" href="javascript:void(0);" aria-label="Next" rel="next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</template>

<script>
  export default {
    props: {
      dataSet: {
        type: Object,
        default: null
      }
    },

    data() {
      return {
        page: 1,
        prevUrl: false,
        nextUrl: false,
      }
    },

    computed: {
      shouldPaginate() {
        return !! this.prevUrl || !! this.nextUrl;
      }
    },

    watch: {
      dataSet() {
        this.page = this.dataSet.current_page;
        this.prevUrl = this.dataSet.prev_page_url;
        this.nextUrl = this.dataSet.next_page_url;
      },

      page() {
        this.broadcast().updateUrl();
      }
    },

    methods: {
      broadcast() {
        return this.$emit('changed', this.page);
      },

      updateUrl() {
        history.pushState(null, null, '?page=' + this.page);
      }
    },
  }
</script>
