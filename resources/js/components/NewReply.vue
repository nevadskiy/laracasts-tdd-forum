<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
            <textarea
                    v-model="body"
                    class="form-control"
                    name="body"
                    id="body"
                    cols="30"
                    rows="2"
                    placeholder="Your reply..."
                    required
            ></textarea>
            </div>
            <button @click="publish" type="submit" class="btn btn-primary mb-2">Post</button>
        </div>
        <p v-else>Please <a href="/login">sign in</a> to participate in this discussion.</p>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        body: '',
        endpoint: location.pathname + '/replies',
      }
    },

    methods: {
      publish() {
        axios.post(this.endpoint, {body: this.body})
          .then(({data}) => {
            this.body = '';
            flash('Your reply has been posted.');
            this.$emit('created', data);
          })
          .catch(error => {
            flash(error.response.data, 'danger');
          })
      },
    }
  };
</script>