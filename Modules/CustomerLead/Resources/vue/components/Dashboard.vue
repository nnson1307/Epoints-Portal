<template>
  <div class="">
    <Search :options="options"/>
    <Taskboard/>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { Bus } from "./../utils/bus";
import Search from "./Search.vue";
import Taskboard from "./Taskboard.vue";

export default {
  components: {
    Search,
    Taskboard
  },
  data(){
    return{
      documents: [],
      options: [],
    }
  },

  mounted(){

  },
  computed: {
    ...mapGetters({
      unarchivedBoards: "unarchivedBoards",
      archivedBoards: "archivedBoards"
    })
  },
  methods: {
    ...mapActions({
      setActiveTaskBoard: "setActiveTaskBoard",
      archiveTaskBoard: "archiveTaskBoard",
      restoreTaskBoard: "restoreTaskBoard"
    }),
    totalItems(lists) {


      let count = 0;
      lists.forEach(element => {
        console.log(element);
        if(element.items)
          count += element.items.length;
      });
      return count;
    },
    handleArchiveTaskBoard(board) {
      this.archiveTaskBoard({ boardId: board.id });
    },
    handleRestoreTaskBoard(board) {
      this.restoreTaskBoard({ boardId: board.id });
    }
  },
  async created() {
    await this.setActiveTaskBoard({
      board: null
    });

  }
};
</script>

<style lang="scss" scoped>
</style>


