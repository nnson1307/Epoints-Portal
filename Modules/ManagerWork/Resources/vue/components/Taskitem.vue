<template>
  <div>
    <li class="task-item">
      <div class="over-time" v-if="checkOverTime(this.item)" v-html="getOverTime(this.item.date_end)"></div>
      <div class="task-item-header">
        <div class="task-priority" :style="getStatusNameColor(this.item.manage_color_code)">
          {{ this.item.manage_status_name }}
        </div>
        <div class="process-percent" @click="showProcess(item.manage_work_id)" v-html="createCircleChart(this.item.progress)"></div>
      </div>
      <div class="task-item-body">
        <p class="task-title" @click="openTaskDetail(item)">{{this.item.manage_work_title}} </p>
        <p class="task-title non-underline"><img width="20" :src="this.item.manage_type_work_icon" :alt="this.item.manage_type_work_name"/> {{this.item.manage_type_work_name}} | {{ this.item.manage_project_name }}</p>
        <!-- <textarea type="text" class="form-control task-title" :value="task.title" rows="2"></textarea> -->
      </div>
      <div class="task-item-footer">
        <div class="assigned-users" :manage_work_id="item.manage_work_id">
          <div class="user-avatar" v-html="getAvatar(this.item.processor_avatar, this.item.processor_full_name, 0)"></div>
          <div class="user-avatar" v-for="(support, index) in item.work_support_list_avatar" :key="index" v-html="getAvatarSupport(support, item.work_support_list_avatar.length, index)">
          </div>
        </div>
        <div class="comments-attachments">
          <div class="comments" :manager-work-id="item.manage_work_id">
            <i class="far fa-comment-alt"></i> {{ item.count_comment.length }}
          </div>
          <div class="attachment">
            <i class="fa fa-calendar"></i> {{ this.item.date_end | formatDate }}
          </div>
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
import "vue-select/dist/vue-select.css";
import TaskDetailPopup from "./popups/TaskDetailPopup";
import Helper from "@/utils/helper";
import { showProcessApi } from "@/utils/api";

export default {
  name: "Taskitem",
  props: ["item", "list", "board"],
  components: {
    "v-select": vSelect,
    TaskDetailPopup
  },
  data() {
    return {
      showTaskPriorityDropdown: false,
      showTaskPriority: true,
      isOverTime: false,
      translate: {}
    };
  },
  watch: {},
  methods: {
    assignUser(user){
      this.item.assignedUsers.push(user)
      console.log(this.item);

    },

    getTranslate(){
        let translateCache = localStorage.getItem('tranlate');
        this.translate = JSON.parse(translateCache);
    },

    async showProcess(manageWorkId) {
      const response = await showProcessApi({manage_work_id: manageWorkId});

      if(response.data.error == 0){
        const popup = response.data.data;
        Bus.$emit("open-process-popup", popup);
      }
    },

    getAvatarSupport(item, total, index) {
      const limit = 3;
      if(index > limit){
        return;
      }

      if(index == limit){
        return `<a href="javascript:void(0)" class="avatars_overview__item">+${total}</a>`;
      }
      else{
        let avatar = item.staff_avatar;
        if(avatar){
          return `<img src="${avatar}" alt=""/>`;
        }

        let supportName = item.full_name;

        if(supportName){
          const charactName = supportName.split("")[0].toUpperCase();
          return `<img src="https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name=${charactName}" alt=""/>`;
        }

      }
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

    checkOverTime(item) {
      let date = new Date();
      let now = moment(String(date)).format('YYYY-MM-DD hh:mm:ss');

      if(now > item.date_end && item.manage_status_id != 6){
        return true;
      }
      else{
        return false;
      }
    },

    getOverTime(date_end) {
      const overTime = Helper.timeSince(date_end, 'default', this.translate);
      const textOverTime = this.translate['Quá hạn'];
      const textOverTimeAgo = this.translate['Trước'];
      return `<div class="mb-0 title_overdue overtime"><i class="far fa-clock"></i> ${textOverTime} ${overTime} ${textOverTimeAgo}</div>`;
    },

    createCircleChart(percent, color = '#ff9f00', size = 35, stroke = 4) {
        return `<svg class="mkc_circle-chart" viewbox="0 0 36 36" width="${size}" height="${size}" xmlns="http://www.w3.org/2000/svg">
            <path class="mkc_circle-bg" stroke="#eeeeee" stroke-width="${stroke * 0.5}" fill="none" d="M18 2.0845
                  a 15.9155 15.9155 0 0 1 0 31.831
                  a 15.9155 15.9155 0 0 1 0 -31.831"/>
            <path class="mkc_circle" stroke="${color}" stroke-width="${stroke}" stroke-dasharray="${percent},100" stroke-linecap="round" fill="none"
                d="M18 2.0845
                  a 15.9155 15.9155 0 0 1 0 31.831
                  a 15.9155 15.9155 0 0 1 0 -31.831" />
            <text class="mkc_info" x="50%" y="50%" alignment-baseline="central" text-anchor="middle" font-size="8">${percent}%</text>
        </svg>`;
    }, 

    getStatusNameColor (color) {
      return {
        background: color
      }
    },

    changePriority() {
      this.showTaskPriorityDropdown = !this.showTaskPriorityDropdown;
      this.showTaskPriority = !this.showTaskPriority;
      this.$nextTick(() => {
        const input = this.$refs.vueDropdown.$el.querySelector("input");
        input.focus();
      });
    },
    setNewPriority(e) {
      this.showTaskPriorityDropdown = !this.showTaskPriorityDropdown;
      this.showTaskPriority = !this.showTaskPriority;
    },
    openTaskDetail(item) {
      const path = `/manager-work/detail/${item.manage_work_id}`;
      window.open(path, '_blank').focus();
    },
  },
  created() {
    this.getTranslate();
  },
  computed: {}
};
</script>

<style scoped lang="scss" >
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
}
</style>
