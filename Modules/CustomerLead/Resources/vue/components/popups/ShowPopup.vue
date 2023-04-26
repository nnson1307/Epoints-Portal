<template>
  <div id="call-popup" v-html="item"></div>
</template>

<script>
import { Bus } from "../../utils/bus";

export default {
  name: "ShowPopup",
  data() {
    return {
      isLoading: true,
      item: null
    };
  },
  created() {
    Bus.$on("open-popup", this.showPopup);
    Bus.$on("closePopup", this.closePopup);
  },
  methods: {
    showPopup(item) {
      this.item = item;
      this.isLoading = false;
      setTimeout(() => {
        $("#call-popup .modal").modal("show");
      }, 500);
    },

    closePopup() {
      console.log('close');
      this.item = null;
      this.isLoading = true;
      $("#call-popup .modal").modal("hide");
    }
  }
};
</script>

<style scoped lang="scss">
.modal.fade .modal-dialog.modal-dialog-zoom {
  -webkit-transform: translate(0, 0) scale(0.5);
  transform: translate(0, 0) scale(0.5);
}
.modal.show .modal-dialog.modal-dialog-zoom {
  -webkit-transform: translate(0, 0) scale(1);
  transform: translate(0, 0) scale(1);
}
</style>
