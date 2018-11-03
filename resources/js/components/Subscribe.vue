<template>
    <button v-if="signedIn" type="submit" @click="toggle" :class="buttonClasses">Subscribe</button>
</template>

<script>
  export default {
    props: {
      status: {
        type: Boolean,
        required: true,
      },
    },

    data() {
      return {
        active: this.status,
        endpoint: location.pathname + '/subscriptions'
      };
    },

    computed: {
      buttonClasses() {
        return ['btn', this.active ? ['btn-primary', 'text-white'] : ['btn-default', 'text-primary']];
      },
    },

    methods: {
      toggle() {
        return this.active ? this.unsubscribe() : this.subscribe();
      },

      subscribe() {
        axios.post(this.endpoint);

        this.active = true;

        flash('Subscribed');
      },

      unsubscribe() {
        axios.delete(this.endpoint);

        this.active = false;

        flash('Unsubscribed');
      },
    },
  };
</script>
