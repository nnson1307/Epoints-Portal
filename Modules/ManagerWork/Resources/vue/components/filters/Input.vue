<template>
    <div class="col-lg-3">
        <div class="form-group">
            <input type="text" class="form-control" name=""
                    placeholder="{{ $config['placeholder'] }}" value="">
        </div>
    </div>
</template>

<script>
import store from "./../store/index";
import { mapActions, mapGetters } from "vuex";

export default {
    name: "Input",
    props: ["options"],
    data() {
        return {
            inputName: "",
            inputPlaceholder: "",
        };
    },
    created() {
        
    },
    computed: {
        
    },
    methods: {

    }
};
</script>

<style lang="scss" scoped>
</style>
