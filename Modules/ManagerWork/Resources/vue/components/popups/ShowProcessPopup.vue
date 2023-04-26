<template>
  <div class="modal" id="process-popup">
      <div class="modal-dialog modal-dialog-centered modal-lg" v-html="item"></div>
  </div>
</template>

<script>
import { Bus } from "../../utils/bus";

export default {
  name: "ShowProcessPopup",
  data() {
    return {
      isLoading: true,
      item: null
    };
  },
  created() {
    Bus.$on("open-process-popup", this.showPopup);
    Bus.$on("closePopup", this.closePopup);
  },
  methods: {
    showPopup(item) {
      this.item = item;
      this.isLoading = false;
      $("#process-popup").modal("show");
    },

    closePopup() {
      this.item = null;
      this.isLoading = true;
      $("#process-popup").modal("hide");
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
