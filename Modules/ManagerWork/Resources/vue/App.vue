<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
import Dashboard from './components/Dashboard.vue'
import { mapGetters } from "vuex"
import { mapActions } from "vuex";
import { db } from './utils/db'
import axios from "axios"


export default {
    name: 'app', 
    components: {
        Dashboard,
    },
    methods: {
        ...mapActions({
            getListWorkAction: "getListWorkAction",
        }),
    },
    created() {
        this.getListWorkAction(this.filters)
    },
    data() {
        return {
            msg: 'Welcome to Your Vue.js App',
            filters: []
        }
    },
    // firebase: {
    //     projects: db.ref('projetcs'),
    // },
    mounted() {
        
    }
}
</script>

<style lang="scss">
    #app{
        width: 100%;
    }
</style>
