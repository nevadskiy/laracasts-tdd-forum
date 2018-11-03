let user = window.app.user;

let authorization = {
  updateReply(reply) {
    return reply.user_id === user.id;
  }
};

module.exports = authorization;
