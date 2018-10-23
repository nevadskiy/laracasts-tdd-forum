<template>
    <div v-show="isShow" class="alert alert-success alert-flash" role="alert">
        <h4 class="alert-heading">Success!</h4>
        <p class="mb-0">{{ body }}</p>
    </div>
</template>

<script>
  export default {
    props: {
      message: {
        type: String,
        required: false,
        default: '',
      },
    },

    data() {
      return {
        body: '',
        isShow: false,
      };
    },

    created() {
      if (this.message) {
        this.show(this.message);
      }

      window.events.$on('flash', message => this.show(message));
    },

    methods: {
      show(message) {
        this.isShow = true;
        this.body = message;

        this.hide();
      },

      hide() {
        setTimeout(() => {
          this.isShow = false;
        }, 3000);
      }
    }
  };
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
