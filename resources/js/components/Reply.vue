<template>
    <div :id="'reply-' + id" class="card mb-3" :class="isBest ? 'card-best' : ''">
        <div class="card-header text-white">
            <div class="d-flex align-items-center justify-content-between">
                <span>
                    <a :href="'/profiles/' + reply.owner.name">{{ reply.owner.name }}</a>
                    <span class="">said {{ ago }}</span>
                </span>

                <favorite v-if="signedIn" :reply="reply"></favorite>
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

        <div v-if="authorize('owns', reply) || authorize('owns', reply.thread)" class="card-footer d-flex">
            <template v-if="authorize('owns', reply)">
                <button class="btn btn-sm btn-primary mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-sm btn-danger mr-2" @click="destroy">Delete</button>
            </template>
            <button v-if="authorize('owns', reply.thread)" v-show="!isBest" class="btn btn-sm btn-default ml-auto"
                    @click="markBest">Best reply
            </button>
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
      reply: {
        type: Object,
        required: true,
      }
    },

    data() {
      return {
        editing: false,
        body: this.reply.body,
        id: this.reply.id,
        isBest: this.reply.isBest,
      };
    },

    computed: {
      ago() {
        return moment.utc(this.reply.created_at).fromNow();
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

    created() {
      window.events.$on('best-reply-selected', (id) => {
        this.isBest = (id === this.id);
      })
    },

    methods: {
      update() {
        axios.put('/replies/' + this.reply.id, {
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
        axios.delete('/replies/' + this.reply.id);

        flash('Your reply has been deleted');

        this.$emit('deleted', this.reply.id);
      },

      markBest() {
        axios.post('/replies/' + this.reply.id + '/best');

        window.events.$emit('best-reply-selected', this.reply.id);
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