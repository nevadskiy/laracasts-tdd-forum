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
        title: this.thread.title,
        body: this.thread.body,
        form: {},
        editing: false,
      };
    },

    created() {
        this.resetForm();
    },

    methods: {
      toggleLock() {
        axios[this.locked ? 'delete' : 'post'](`/threads/${this.thread.slug}/lock`);

        this.locked = !this.locked;
      },

      update() {
        let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}/`;

        axios.put(uri, this.form)
          .then(() => {
            this.editing = false;
            this.title = this.form.title;
            this.body = this.form.body;

            flash('Thread updated');
          });
      },

      resetForm() {
        this.form = {
          title: this.thread.title,
          body: this.thread.body,
        };
        this.editing = false;
      }
    }
  };
</script>