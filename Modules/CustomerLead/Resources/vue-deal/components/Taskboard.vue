<template>
  <div style="height:100%">
    <!-- <Navbar buttonType="taskboard"  /> -->
    <div class="main-container scrollable-div">
      <Loading :loading="isLoading"/>
      <div class="board-wrapper">
        <div class="board-details"></div>
        <draggable
          v-model="lists"
          draggable=".task-item"
          class="row flex-nowrap"
          v-bind="getDragOptions"
        >
          <TaskList
            v-for="(listItem, index) in lists"
            :key="index"
            :board="lists"
            :list="listItem"
          ></TaskList>
        </draggable>
      </div>
    </div>
    <ShowPopup />
  </div>
</template>

<script>
import Taskitem from "./Taskitem.vue";
import Navbar from "./Navbar";
import TaskList from "./Tasklist.vue";
import Loading from "./Loading.vue";
import store from "./../store/index";
import draggable from "vuedraggable";
import { mapActions, mapGetters } from "vuex";
import ShowPopup from "./popups/ShowPopup";

export default {
  name: "Taskboard",
  props: ["board"],
  components: {
    Taskitem,
    TaskList,
    draggable,
    Navbar,
    Loading,
    ShowPopup
  },
  data() {
    return {
      projectName: "",
      projectDescription: "",
      currentBoard:''
    };
  },
  created() {
    // console.log("this.getBoard ", this.getBoard);
  },
  computed: {
    ...mapGetters({
      boards: "listCustomerLead",
      isLoading: "isLoading"
    }),
    getDragOptions() {
      return {
        animation: "200",
        ghostClass: "ghost",
        handle: ".board-header",
        group: "kanban-board-lists"
      };
    },
    shouldAllowListOrder() {
      return this.isDesktop || this.isTablet;
    },
    getBoard() {
      return this.boards;
    },
    lists: {
      get() {
        return this.boards;
      },
      async set(value) {
        await this.reorderTaskLists({
          boardId: this.param,
          lists: value
        });
      }
    }
  },
  methods: {
    // ...mapActions(["addTaskToBoard", "reorderTaskLists"]),
    ...mapActions({
      reorderTaskLists: "reorderTaskLists",
      setActiveTaskBoard: "setActiveTaskBoard",
      saveTaskBoard:"saveTaskBoard"
    }),
    createNewTask(key) {
      let newTask = {
        title: "",
        priority: "Low",
        comments: [],
        attachmets: [],
        assignedUsers: []
      };
      this.addTaskToBoard({ key, newTask });
    }
  }
};
</script>

<style lang="scss" scoped>
.board-details {
  .project-name {
    display: flex;
    // justify-content: center;
    align-items: center;

    &:hover {
      .name-edit-icon {
        display: block;
      }
    }
  }
  .name-edit-icon {
    display: none;
    width: 50px;
    text-align: center;
    cursor: pointer;
  }
  .project-name-input, .project-desc-input{
    padding: 0;
    font-size: 24px;
    color: #525f7f;
    border: 1px solid transparent;
    background: transparent;
    width: 50%;
    padding-left: 10px;
    &:hover{
      border: 1px solid #cad1d7;
    }
    &:focus{
      border: 1px solid #98a8fb ;
    }
  }
  .project-desc-input{
    font-size: 15px;
    height: 30px;
  }
}
</style>
