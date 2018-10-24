<script>
  import Replies from './../components/Replies.vue';

  export default {
    props: {
      initRepliesCount: {
        type: Number,
        default: 0,
      }
    },

    components: {
      'replies': Replies
    },

    data() {
      return {
        repliesCount: this.initRepliesCount,
      };
    }
  };
</script>