<template>
    <div :id="'reply-' + id" class="card mb-3">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <span>
                    <a :href="'/profiles/' + data.owner.name">{{ data.owner.name }}</a>
                    <span class="">said {{ ago }}</span>
                </span>

                <favorite v-if="signedIn" :reply="data"></favorite>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button @click="update" class="btn btn-sm btn-primary">Update</button>
                <button @click="editing = false" class="btn btn-sm btn-link">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        <div v-if="canUpdate" class="card-footer d-flex">
            <button class="btn btn-sm btn-primary mr-2" @click="editing = true">Edit</button>
            <button class="btn btn-sm btn-danger mr-2" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
  import Favorite from './Favorite.vue';
  import moment from 'moment';

  export default {
    components: {
      'favorite': Favorite,
    },

    props: {
      data: {
        type: Object,
        required: true,
      }
    },

    data() {
      return {
        editing: false,
        body: this.data.body,
        id: this.data.id,
      };
    },

    computed: {
      signedIn() {
        return window.app.signedIn;
      },

      canUpdate() {
        return this.authorize(user => this.data.user_id === user.id);
      },

      ago() {
        return moment.utc(this.data.created_at).fromNow();
      }
    },

    methods: {
      update() {
        axios.put('/replies/' + this.data.id, {
          body: this.body,
        })
          .then(() => {
            this.editing = false;
            flash('Updated!');
          })
          .catch(error => {
            flash(error.response.data.errors.body[0], 'danger');
          });

      },

      destroy() {
        axios.delete('/replies/' + this.data.id);

        flash('Your reply has been deleted');

        this.$emit('deleted', this.data.id);
      }
    }
  };
</script>
