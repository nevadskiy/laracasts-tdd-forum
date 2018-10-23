<template>
    <button type="submit" @click="toggle" :class="buttonClasses">
        <span class="fab fa-gratipay"></span>
        <span>{{ count }}</span>
    </button>
</template>

<script>
  export default {
    props: {
      reply: {
        type: Object,
        required: true,
      }
    },

    data() {
      return {
        count: this.reply.favoritesCount,
        active: this.reply.isFavorited,
      };
    },

    computed: {
      buttonClasses() {
        return ['btn', 'btn-sm', this.active ? ['btn-primary', 'text-white'] : ['btn-default', 'text-primary']];
      },

      endpoint() {
        return `/replies/${this.reply.id}/favorites`;
      }
    },

    methods: {
      toggle() {
        return this.active ? this.remove() : this.add();
      },

      add() {
        axios.post(this.endpoint);

        this.active = true;
        this.count++;
      },

      remove() {
        axios.delete(this.endpoint);

        this.active = false;
        this.count--;
      }
    }
  };
</script>
