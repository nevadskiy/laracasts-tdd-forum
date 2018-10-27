<script>
  import Replies from './../components/Replies.vue';
  import Subscribe from './../components/Subscribe.vue';

  export default {
    components: {
      'replies': Replies,
      'subscribe': Subscribe,
    },

    props: {
      initRepliesCount: {
        type: Number,
        default: 0,
      }
    },

    data() {
      return {
        repliesCount: this.initRepliesCount,
      };
    }
  };
</script>