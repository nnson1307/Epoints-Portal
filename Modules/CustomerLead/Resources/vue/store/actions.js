// const INITIAL_DATA_URL = "https://raw.githubusercontent.com/techlab23/data-repository/master/boards.json"
import axios from "axios"
import { getSearchOptionApi, getListCustomerLeadApi } from "@CustomerLead/utils/api";

export default {

  async getSearchOptionAction({commit}){
    const response = await getSearchOptionApi();

    if(response.data){
      commit("SET_SEARCH_OPTION", response.data.searchConfig)
    }
  },

  async getListCustomerLeadAction({ commit }, payload) {
    commit("SET_LOADING_STATE", true)
    
    const response = await getListCustomerLeadApi(payload);

    if(response.data){
      commit("SET_INITIAL_DATA", response.data.customerLead)
      commit("SET_LOADING_STATE", false)
    }
  },

  async setCurrentFilterAction({ commit }, payload) {
    commit("SET_CURRENT_FILTER", payload)
  },

  async setLoading({ commit }, payload) {
    commit("SET_LOADING_STATE", payload)
  },

  async saveTaskBoard({ commit }, payload) {
    console.log('payload saveTaskBoard', payload);
    commit("SAVE_TASKBOARD", payload)
  },
  async archiveTaskBoard({ commit }, payload) {
    commit("ARCHIVE_TASKBOARD", payload)
  },
  async restoreTaskBoard({ commit }, payload) {
    commit("RESTORE_TASKBOARD", payload)
  },
  async setActiveTaskBoard({ commit }, payload) {
    commit("SET_ACTIVE_TASKBOARD", payload)
  },

  async saveTaskList({ commit }, payload) {
    console.log('payload saveTaskList', payload);
    commit("SAVE_TASKLIST", payload)
  },
  async archiveTaskList({ commit }, payload) {
    commit("ARCHIVE_TASKLIST", payload)
  },
  async restoreTaskList({ commit }, payload) {
    commit("RESTORE_TASKLIST", payload)
  },

  async reorderTaskLists({ commit }, payload) {
    commit("REORDER_TASKLISTS", payload)
  },
  async reorderTaskListItems({ commit }, payload) {
    commit("REORDER_TASKLIST_ITEMS", payload)
  },
}
