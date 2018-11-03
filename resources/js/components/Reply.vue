<template>
    <div :id="'reply-' + id" class="card mb-3" :class="isBest ? 'card-best' : ''">
        <div class="card-header text-white">
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
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea id="body" class="form-control" v-model="body" required></textarea>
                    </div>

                    <button class="btn btn-sm btn-primary">Update</button>
                    <button @click="editing = false" type="button" class="btn btn-sm btn-link">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer d-flex">
            <template v-if="canUpdate">
                <button class="btn btn-sm btn-primary mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-sm btn-danger mr-2" @click="destroy">Delete</button>
            </template>
            <button v-show="!isBest" class="btn btn-sm btn-default ml-auto" @click="markBest">Best reply</button>
        </div>
    </div>
</template>

<script>
  import Favorite from './Favorite.vue';
  import moment from 'moment';
  import 'jquery.caret';
  import 'at.js';

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
        isBest: false,
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

    mounted() {
      $('#body').atwho({
        at: "@",
        delay: 500,
        callbacks: {
          remoteFilter(query, callback) {
            $.getJSON("/api/users", {name: query}, (usernames) => {
              callback(usernames);
            })
          }
        },
      })
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
            flash(error.response.data, 'danger');
          });

      },

      destroy() {
        axios.delete('/replies/' + this.data.id);

        flash('Your reply has been deleted');

        this.$emit('deleted', this.data.id);
      },

      markBest() {
        axios.post('/replies/' + this.data.id + '/best');
        this.isBest = true;
      }
    }
  };
</script>

<style>
    .card-best {
        background-color: #5aa97a7d;
        color: white;
    }
</style>