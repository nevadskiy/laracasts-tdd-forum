<template>
    <div
            v-show="isShow"
            class="alert alert-flash"
            :class="'alert-' + type"
            role="alert"
            v-text="body"
    >
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
        body: this.message,
        type: 'success',
        isShow: false,
      };
    },

    created() {
      if (this.message) {
        this.show();
      }

      window.events.$on('flash', data => this.show(data));
    },

    methods: {
      show(data) {
        if (data) {
          this.body = data.message;
          this.type = data.type;
        }

        this.isShow = true;

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
