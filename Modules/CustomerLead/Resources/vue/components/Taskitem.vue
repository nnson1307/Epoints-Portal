<template>
  <div>
    <li class="task-item">
      <div class="task-item-header">
      </div>
      <div class="task-item-body">
        <p class="task-title" v-html="getTitle(item)"></p>
        <p class="task-title">{{item.phone}} </p>
        <p class="task-title" v-if="item.sale_name">{{item.sale_name}} </p>
        <p class="task-title" v-if="item.last_care">{{item.last_care}} <span class="diff-day" v-if="item.diff_day">({{ item.diff_day }})</span></p>
        <p class="task-title tag-list" v-if="item.tags.length">
          <span v-for="(tag, i) in item.tags" :key="i">{{ tag }}</span>
        </p>
        <p class="task-title" v-if="item.note">{{item.note}} </p>
      </div>
      <div class="task-item-footer">
        <div class="assigned-users">
          <div class="icon-lead"><img :src="IconTodoList" alt=""/><span>{{ item.related_work }}</span></div>
          <div class="icon-lead"><img :src="IconTimeRemain" alt=""/><span>{{ item.appointment }}</span></div>
          <tooltip />
        </div>
        <div class="comments-attachments">
          <div class="comments" @click="showModalCallAction()"><i class="la la-phone"></i></div>
          <div class="comments act-customer-detail" :customer-lead-id="item.customer_lead_id"><i class="la la-eye"></i></div>
          <div class="comments" v-popover:tooltip="translate('Xóa')" @click="deleteCustomerLeadAction()"><i class="la la-trash"></i></div>
          <div class="comments act-customer-edit" :customer-lead-id="item.customer_lead_id"><i class="la la-edit"></i></div>
          <div class="comments act-customer-care" :customer-lead-id="item.customer_lead_id"><i class="la la-gratipay"></i></div>
          <tooltip />
        </div>
      </div>
    </li>
  </div>
</template>

<script>
import moment from 'moment';
import store from "./../store/index";
import { mapActions, mapGetters } from "vuex";
import vSelect from "vue-select";
import { Bus } from "./../utils/bus";
import Helper from "@CustomerLead/utils/helper";
import IconTimeRemain from "@CustomerLead/assets/images/icon_timeremain.svg";
import IconTodoList from "@CustomerLead/assets/images/icon-todolist.svg";
import "vue-select/dist/vue-select.css";
import { 
  showModalCallApi, 
  deleteCustomerLeadApi
} from "@CustomerLead/utils/api";

export default {
  name: "Taskitem",
  props: ["item", "list", "board"],
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      showTaskPriorityDropdown: false,
      showTaskPriority: true,
      isOverTime: false,
      IconTimeRemain,
      IconTodoList
    };
  },
  watch: {},
  methods: {
    ...mapActions({
        getListCustomerLeadAction: "getListCustomerLeadAction",
    }),

    translate(key){
        return Helper.translate(key);
    },

    getTitle(item){
      let tag = item.customer_type == 'personal' ? this.translate('Cá nhân') : this.translate('Doanh nghiệp');

      return `${tag} - <b>${item.full_name}</b>`;
    },

    getAvatar(avatar, name){
      if(avatar){
          return `<img src="${avatar}" alt=""/>`;
        }

        let charactName = 'N';
        if(name){
          charactName = name.split("")[0].toUpperCase();
        }

        return `<img src="https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name=${charactName}" alt=""/>`;
    },

    async showModalCallAction() {
      const response = await showModalCallApi({customer_lead_id: this.item.customer_lead_id});

      if(response.data.html){
        const popup = response.data.html;
        Bus.$emit("open-popup", popup);
      }
    },

    async deleteCustomerLeadAction() {
      swal({ 
          text: this.translate('Thông báo'),
          text: this.translate('Bạn có muốn xóa không?'),
          type: "warning",
          showCancelButton: true,
          confirmButtonText: this.translate('Xác nhận'),
          cancelButtonText: this.translate('Hủy'),
          closeOnConfirm: false,
          closeOnCancel: false 
        }).then(async(confirmed) => {
          if (confirmed && confirmed.value) {
            const response = await deleteCustomerLeadApi({customer_lead_id: this.item.customer_lead_id});

            if(response && !response.data.error){
              Vue.swal('', response.data.message, 'success');
              this.getListCustomerLeadAction(this.currentFilter);
            }
            else{
              Vue.swal('', response.data.message, 'error');
            }
          }
      });
    },


  },
  created() {
  },
  computed: {
    ...mapGetters({
        currentFilter: "currentFilter",
        isLoading: "isLoading"
    }),
  }
};
</script>

<style scoped lang="scss" >
.task-item-body{
  text-align: left;
}
.assigned-users {
  .user-avatar {
    margin-right: -10px;
    cursor: pointer;
  }
}
.process-percent{
  cursor: pointer;
}
.assigned-users .add-icon {
  margin-left: 20px;
  cursor: pointer;
}
.custom-v-select {
  font-size: 14px;
}
.assignee-selection .dropdown-item {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  padding: 0.4rem .8rem;
  .user-avatar {
    margin-right: 15px;
  }
  .user-name {
    font-size: 14px;
    font-weight: 400;
    color: rgb(45, 45, 82);
  }
  .task-priority{
    background: #0067AC !important;
  }

  .diff-day{
    color: #aeaeae;
  }

  
}
</style>
