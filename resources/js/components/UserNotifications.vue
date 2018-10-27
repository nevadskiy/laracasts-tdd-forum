<template>
    <div v-if="notifications.length" class="dropdown">
        <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
            <span class="fas fa-bell"></span>
        </button>
        <div class="dropdown-menu">
            <div v-for="notification in notifications" :key="notification.id" class="dropdown-item">
                <a
                        @click="markAsRead(notification)"
                        :href="notification.data.link"
                        v-text="notification.data.message"
                ></a>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        notifications: [],
      };
    },

    created() {
        this.fetch();
    },

    methods: {
      fetch() {
        axios.get(`/profiles/${window.app.user.name}/notifications`)
          .then(({data}) => this.notifications = data);
      },

      markAsRead(notification) {
        axios.delete(`/profiles/${window.app.user.name}/notifications/${notification.id}`)
      }
    },
  };
</script>
