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
        body: '',
        isShow: false,
        type: 'success',
      };
    },

    created() {
      if (this.message) {
        this.show(this.message);
      }

      window.events.$on('flash', data => this.show(data));
    },

    methods: {
      show(data) {
        this.isShow = true;
        this.body = data.message;
        this.type = data.type;

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
