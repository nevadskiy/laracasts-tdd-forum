/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

let authorizations = require('./authorization');

Vue.prototype.authorize = function (...params) {
  if (! window.app.signedIn) return false;

  if (typeof params[0] === 'string') {
    return authorizations[params[0]](params[1]);
  }

  return params[0](window.app.user);
};

Vue.prototype.signedIn = window.app.signedIn;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.flash = (message, type = 'success') => {
  window.events.$emit('flash', {message, type});
};

window.events = new Vue();

Vue.component('flash', require('./components/Flash.vue'));
Vue.component('paginator', require('./components/Paginator.vue'));

Vue.component('avatar-form', require('./components/AvatarForm.vue'));
Vue.component('user-notifications', require('./components/UserNotifications.vue'));
Vue.component('thread-view', require('./pages/Thread.vue'));

const app = new Vue({
  el: '#app'
});
