var helper = {
    showLoading: function(){
        mApp.block("#div-loading", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: "Loading..."
        });
    },
    hideLoading: function(){
        mApp.unblock("#div-loading");
    }
}