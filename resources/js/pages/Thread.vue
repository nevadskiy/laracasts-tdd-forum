<script>
  import Replies from './../components/Replies.vue';
  import Subscribe from './../components/Subscribe.vue';

  export default {
    components: {
      'replies': Replies,
      'subscribe': Subscribe,
    },

    props: {
      thread: {
        type: Object,
        required: true,
      }
    },

    data() {
      return {
        repliesCount: this.thread.replies_count,
        locked: this.thread.locked,
      };
    },

    methods: {
      toggleLock() {
        axios[this.locked ? 'delete' : 'post'](`/threads/${this.thread.slug}/lock`);

        this.locked = ! this.locked;
      },
    }
  };
</script>