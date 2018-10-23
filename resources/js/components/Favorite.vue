<template>
    <button type="submit" @click="toggle" :class="buttonClasses">
        <span class="fab fa-gratipay"></span>
        <span>{{ favoritesCount }}</span>
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
        favoritesCount: this.reply.favoritesCount,
        isFavorited: this.reply.isFavorited,
      };
    },

    computed: {
      buttonClasses() {
        return ['btn', 'btn-sm', this.isFavorited ? ['btn-primary', 'text-white'] : ['btn-default', 'text-primary']];
      },

      endpoint() {
        return `/replies/${this.reply.id}/favorites`;
      }
    },

    methods: {
      toggle() {
        return this.isFavorited ? this.remove() : this.add();
      },

      add() {
        axios.post(this.endpoint);

        this.isFavorited = true;
        this.favoritesCount++;
      },

      remove() {
        axios.delete(this.endpoint);

        this.isFavorited = false;
        this.favoritesCount--;
      }
    }
  };
</script>
