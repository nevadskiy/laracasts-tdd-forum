<template>
    <div>
        <form v-if="canUpdate" method="POST" enctype="multipart/form-data" class="p-3">
            <image-upload @loaded="onLoad" name="avatar"></image-upload>
        </form>

        <img :src="avatar" width="200" height="200">
    </div>
</template>

<script>
  import ImageUpload from './ImageUpload.vue';
  export default {
    components: {
      'image-upload': ImageUpload,
    },

    props: {
      user: {
        type: Object,
        required: true,
      }
    },

    data() {
      return {
        avatar: this.user.avatar_path,
      };
    },

    computed: {
      canUpdate() {
        return this.authorize(user => user.id === this.user.id);
      }
    },

    methods: {
      onLoad(payload) {
        this.avatar = payload.src;

        this.persist(payload.file);
      },

      persist(avatar) {
        let data = new FormData();

        data.append('avatar', avatar);

        axios.post(`/api/users/${this.user.name}/avatar`, data)
          .then(() => flash('Avatar uploaded'));
      }
    }
  };
</script>\