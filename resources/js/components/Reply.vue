<script>
  export default {
    props: {
      attributes: {
        type: Object,
        required: true,
      }
    },

    data() {
      return {
        editing: false,
        body: this.attributes.body,
      }
    },

    methods: {
      update() {
        axios.put('/replies/' + this.attributes.id, {
          body: this.body,
        });

        this.editing = false;

        flash('Updated!');
      },

      destroy() {
        axios.delete('/replies/' + this.attributes.id);

        $(this.$el).fadeOut(300);

        flash('Your reply has been deleted');
      }
    }
  }
</script>