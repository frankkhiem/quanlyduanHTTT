const state = {
  notifications: [],
  numberNewNotifications: 0
}

const getters = {
  notifications: (state) => {
    return state.notifications;
  },

  numberNewNotis: (state) => {
    return state.numberNewNotifications;
  }
}

const mutations = {
  getNotifications: (state, data) => {
    state.notifications = data.listNotifications;
    state.numberNewNotifications = data.numberNewNotis;
  },

  insertNotification: (state, notification) => {
    state.notifications.unshift(notification);
    state.numberNewNotifications++;
  },

  watchedNewNotifications: (state) => {
    state.numberNewNotifications = 0;
  }
}

const actions = {
  fetchNotifications: async ({commit}) => {
    let response1 = await axios.get('/api/list-notifications');
    let listNotifications = response1.data.data;
    let response2 = await axios.get('/api/number-of-new-notifications');
    let numberNewNotis = response2.data;
    commit('getNotifications', { listNotifications, numberNewNotis });
  },

  insertNewNotification: ({commit}, newNotification) => {
    commit('insertNotification', newNotification);
  },

  clickMenuNotifications: async ({commit, state}) => {
    if( state.numberNewNotifications === 0 ) return;
    commit('watchedNewNotifications');
    await axios.get('/api/watched-new-notifications');
  }
}

export default {
  state,
  getters,
  mutations,
  actions
}