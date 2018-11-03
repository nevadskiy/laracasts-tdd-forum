let user = window.app.user;

let authorization = {
  owns(item, prop = 'user_id') {
    return item[prop] === user.id;
  },

  admin() {
    return ['admin'].includes(user.name);
  }
};

module.exports = authorization;
