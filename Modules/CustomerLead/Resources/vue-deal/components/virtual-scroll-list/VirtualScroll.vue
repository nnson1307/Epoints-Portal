
<template>
    <div class="root" ref="root" :style="rootStyle">
        <div class="viewport" ref="viewport" :style="viewportStyle">
            <draggable 
                v-model="visibleItems" 
                v-bind="dragOptions" 
                class="list-group spacer" 
                ref="spacer" 
                :style="spacerStyle"
                @change="onChanged($event)"
            >
                <Taskitem v-for="item in visibleItems" :item="item" :key="item.id" />
            </draggable>
        </div>
    </div>
</template>

<script>

var passiveSupportMixin = {
    methods: {

        doesBrowserSupportPassiveScroll() {
            let passiveSupported = false;

            try {
                const options = {
                    get passive() {

                        passiveSupported = true;
                        return false;
                    }
                };

                window.addEventListener("test", null, options);
                // window.removeEventListener("test", null, options);
            } catch (err) {
                passiveSupported = false;
            }
            return passiveSupported;
        }
    }
};

import Taskitem from "../Taskitem";
import draggable from "vuedraggable";
import { mapActions, mapGetters } from "vuex";
import { updateJourneyApi } from "@CustomerDeal/utils/api";

export default {
    mixins: [passiveSupportMixin],
    components: {
        Taskitem,
        draggable,
    },
    props: ["items", "board", "list"],
    data() {
        return {
            rootHeight: 400,
            rowHeight: this.itemCount,
            scrollTop: 0,
            nodePadding: 20,
            drag: false,
            flag: false,
            filters: []
        };
    },
    computed: {
        ...mapGetters({
            listCustomerLead: "listCustomerLead",
            currentFilter: "currentFilter",
            isLoading: "isLoading"
        }),

        dragOptions() {
            return {
                animation: "200",
                ghostClass: "ghost",
                group: "kanban-board-list-items"
                // disabled: this.isEditing || !this.shouldAllowTaskItemsReorder
            };
        },



        viewportHeight() {
            return this.itemCount * this.rowHeight;
        },

        startIndex() {
            let startNode = Math.floor(this.scrollTop / this.rowHeight) - this.nodePadding;
            startNode = Math.max(0, startNode);
            return startNode || 0;
        },

        visibleNodeCount() {
            let count = Math.ceil(this.rootHeight / this.rowHeight) + (2 * this.nodePadding);
            count = Math.min(this.itemCount - this.startIndex, count);
            return count || 0;
        },

        visibleItems: {
            get() {
                return this.items.slice(
                    this.startIndex,
                    this.startIndex + this.visibleNodeCount
                );
            },
            set(reorderedListItems) {
                const payload = {
                    listId: this.list.journey_id,
                    items: reorderedListItems
                };
                this.reorderTaskListItems(payload);
            }
        },
        shouldAllowTaskItemsReorder() {
            console.log("shouldAllowTaskItemsReorder");
            return this.isDesktop || this.isTablet;
        },

        // visibleItems() {
        //     return this.items.slice(
        //         this.startIndex,
        //         this.startIndex + this.visibleNodeCount
        //     );
        // },
        itemCount() {
            return this.items.length;
        },

        offsetY() {
            return this.startIndex * this.rowHeight;
        },

        spacerStyle() {
            return {
                transform: "translateY(" + this.offsetY + "px)"
            };
        },
        viewportStyle() {
            return {
                overflow: "hidden",
                // height: (this.viewportHeight - 140) + "px",
                position: "relative"
            };
        },
        rootStyle() {
            return {
                "min-height": "160px !important",
                "max-height": "100vh",
                overflow: "auto",
            };
        }
    },
    methods: {
        ...mapActions({
            reorderTaskListItems: "reorderTaskListItems",
            getListCustomerDealAction: "getListCustomerDealAction",
        }),

        handleScroll(event) {
            this.scrollTop = this.$refs.root.scrollTop;
        },

        async onChanged($event) {
            if($event.added){
                const dealId = $event.added?.element?.deal_id;
                const journeyOld = $event.added?.element?.journey_code;

                const updateStatus = await updateJourneyApi({
                    deal_id: dealId, 
                    journey_old: journeyOld,
                    journey_new: this.list.journey_code
                });

                // Reload list works
                this.getListCustomerDealAction(this.currentFilter);

                if(updateStatus.data.error){
                    Vue.swal('', updateStatus.data.message, 'error');
                }
                else{
                    Vue.swal('', updateStatus.data.message, 'success');
                }
            }
        },

        calculateInitialRowHeight() {
            const children = this.$refs.spacer.$children;
            // console.log(children[0].$el);
            let largestHeight = 320; //độ dài mặc định khi list không có item
            if (typeof children[0] !== "undefined") {
                for (let i = 0; i < children.length; i++) {
                    if (children[i].$el.offsetHeight > largestHeight) {
                        largestHeight = children[i].$el.offsetHeight;
                    }
                }
            }
            return largestHeight;
        },

        calculateRowHeight(){
            const largestHeight = this.calculateInitialRowHeight();
            this.rowHeight = (typeof largestHeight !== "undefined" && largestHeight !== null && largestHeight !== 0)
                    ? largestHeight
                    : this.rowHeight;
        }
    },
    mounted() {
        this.$refs.root.addEventListener(
            "scroll",
            this.handleScroll,
            this.doesBrowserSupportPassiveScroll() ? { passive: true } : false
        );

        this.calculateRowHeight();
    },
    destroyed() {
        // this.$refs.root.removeEventListener("scroll", this.handleScroll);
    }
};

</script>
