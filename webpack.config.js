const path = require('path');

module.exports = {
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "Modules/ManagerWork/Resources/vue"),
            "@CustomerLead": path.resolve(__dirname, "Modules/CustomerLead/Resources/vue"),
            "@CustomerDeal": path.resolve(__dirname, "Modules/CustomerLead/Resources/vue-deal"),
        }
    }
}