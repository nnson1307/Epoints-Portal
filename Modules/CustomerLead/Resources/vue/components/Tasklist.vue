<template>
    <div>
        <div v-if="collapsedTaskItem">
            <div class="collapse-task" @click.prevent="collapseItemTask" :style="{borderColor: getBackgroundColor(list)}">
                <div class="collapse-task-header" >
                    <a href="#" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </a>
                    <p class="board-name">{{ list.journey_name }} ({{ list.count }})</p>
                </div>
            </div>
        </div>
        <div v-show="!collapsedTaskItem" class="task-board" :style="{borderColor: getBackgroundColor(list)}">
            <div class="board-header" @click.prevent="collapseItemTask">
                <p class="board-name">{{ list.journey_name }} ({{ list.count }})</p>
                <div class="d-flex flex-wrap float-end">
                    <div class="toggle-task mr-2">
                        <a href="#" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="board-content">
                <ul class="task-list">
                    <virtual-list :items="items" :list="list" :board="board">
                    </virtual-list>
                    <taskItemTemplate v-if="showTemplate" :list="list" />
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from "vuedraggable";
import Taskitem from "./Taskitem";
import { mapGetters } from "vuex";
import taskItemTemplate from "./TaskItemTemplate";
import { Bus } from "./../utils/bus";
import { mapActions } from "vuex";
import VirtualList from './virtual-scroll-list/VirtualScroll.vue'

export default {
    components: {
        Taskitem,
        draggable,
        taskItemTemplate,
        'virtual-list': VirtualList
    },
    props: ["list","board"],
    data() {
        return {
            drag: false,
            showTemplate: false,
            isEditing: false,
            collapsedTaskItem: false,
        };
    },
    created() {
        Bus.$on("remove-template", this.removeTemplate);
    },
    computed: {

        ...mapGetters({
            isLoading: "isLoading"
        }),

        defaultItem() {
            console.log("defaultItem");
            return {
                id: "",
                text: ""
            };
        },

        items: {
            get() {
                return this.list.items;
                // console.log("get items");
                // return this.list.items;
            },
        },
        shouldAllowTaskItemsReorder() {
            console.log("shouldAllowTaskItemsReorder");
            return this.isDesktop || this.isTablet;
        }
    },
    methods: {
        ...mapActions({
            reorderTaskListItems: "reorderTaskListItems",
            saveTaskListItem: "saveTaskListItem",
            deleteTaskList: "deleteTaskList",
        }),

        getBackgroundColor(list){
            if(list && list.background_color){
                return list.background_color;
            }
            
            return '#0067AC';
        },

        removeTemplate(data) {
            console.log("removeTemplate");
            // console.log("remove template ", data);
            this.showTemplate = false;
        },

        collapseItemTask() {
            this.collapsedTaskItem = !this.collapsedTaskItem;
        } 
    }
};
</script>

<style lang="scss">
.sortable-chosen.ghost .task-item {
    background: repeating-linear-gradient(145deg,
            transparent,
            transparent 5px,
            #e8eaf1 5px,
            #e8eaf1 10px);
    border: 2px solid#e2e2e2;
}

.flip-list-move {
    transition: transform 0.2s;
}

.no-move {
    transition: transform 0s;
}

.list-group {
    min-height: 400px;
}

.list-group-item {
    cursor: move;
}

.list-group-item i {
    cursor: pointer;
}

.task-board {
    .board-header {
        .options {
            padding: 5px 5px;
        }

        .dropdown-menu {
            min-width: 10rem;
        }
    }
}

.ps {
    max-height: calc(100vh - 350px);
}

.collapse-task {
    height: calc(100vh - 350px);
    margin: 15px;
    padding: 15px;
    background: #f6f8fc;
    position: relative;
    border-left: 4px solid red; 
}

.collapse-task::before {
    content: '';
    width: 4px;
    height: calc(100vh - 350px);
    // background: linear-gradient(89deg, #c875e8 0, #dd6883 100%);
    position: absolute;
    border-radius: 5px;
    top: 0;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
    left: 0;
}
.collapse-task-header {
    font-size: 18px;
    font-weight: 600;
    overflow: hidden;
    padding: 8px 0 0 13px;
    /* position: relative; */
    text-overflow: ellipsis;
    white-space: nowrap;
    -ms-writing-mode: tb-lr;
    writing-mode: vertical-rl;
    display: contents;
}
</style>

