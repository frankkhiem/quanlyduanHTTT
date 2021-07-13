<template>
  
</template>
<script>
import {mapActions, mapGetters} from 'vuex'

export default {
    computed: {
    ...mapGetters({
      user: 'info_user'
    })
  },

  created: function() {
    this.getListNotis();
   
    const intervalId = setInterval(() => {
      if ( this.user.length !== 0 ) {
        Echo.private('notification_realtime_for_user_' + this.user[0].id)
          .listen('NewNotification', (data) => {
            this.getNotiRealTime(data.notification);
            this.showToast(data.notification)
          }); 
          
        clearInterval(intervalId);
      }
    }, 100);
  },

  methods: {
    ...mapActions({
      getListNotis: 'fetchNotifications',
      getNotiRealTime: 'insertNewNotification'
    }),

    showToast: function(notification) {
      let option = {
        transition: "Vue-Toastification__bounce",
        maxToasts: 16,
        newestOnTop: true,
        position: "bottom-center",
        timeout: 4000,
        closeOnClick: true,
        pauseOnFocusLoss: true,
        pauseOnHover: true,
        draggable: true,
        draggablePercent: 0.6,
        showCloseButtonOnHover: false,
        hideProgressBar: false,
        closeButton: "button",
        icon: true,
        rtl: false
      };

      this.$toast(notification.content, option);
    }
  }
}
</script>