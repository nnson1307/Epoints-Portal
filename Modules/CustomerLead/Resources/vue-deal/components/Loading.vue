<template>
    <div v-show="loading" class="loading-wrap">
        <div class="loader"></div>
    </div>
</template>

<script>

export default {
    name: "Loading",
    components: {},
    props: ["loading"],
    data() {
        return {
            isLoading: false
        };
    },
};
</script>

<style lang="scss" scoped>
    .loading-wrap{
        position: absolute;
        left: 50%;
        margin-top: 100px;
        z-index: 99;
        width: 100px;
        height: 50px;
        border-radius: 10px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loader {
        border: 6px solid #c3c3c3;
        border-radius: 50%;
        border-top: 6px solid #0067AC;
        width: 35px;
        height: 35px;
        -webkit-animation: spin .8s linear infinite; /* Safari */
        animation: spin .8s linear infinite;
    }
    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
