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
      }
    }
  }
</script>