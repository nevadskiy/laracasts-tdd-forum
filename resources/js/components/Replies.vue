<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>
    </div>
</template>
<script>
  import Reply from './Reply.vue';

  export default {
    components: {
      'reply': Reply,
    },

    props: {
      data: {
        type: Array,
        default: () => [],
      },
    },

    data() {
      return {
        items: this.data,
      };
    },

    methods: {
      remove(index) {
        this.items.splice(index, 1);

        this.$emit('removed');
      }
    }
  };
</script>