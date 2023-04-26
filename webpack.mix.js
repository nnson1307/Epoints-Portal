const mix = require('laravel-mix');
const config = require('./webpack.config');

mix.webpackConfig(config);

mix.js('Modules/ManagerWork/Resources/app.js', 'public/vue/kanban-managerwork/js')
    .postCss('Modules/ManagerWork/Resources/assets/app.css', 'public/vue/kanban-managerwork/css');

mix.js('Modules/CustomerLead/Resources/app.js', 'public/vue/kanban-customerlead/js')
    .postCss('Modules/CustomerLead/Resources/assets/app.css', 'public/vue/kanban-customerlead/css');

mix.js('Modules/CustomerLead/Resources/app-deal.js', 'public/vue/kanban-customerdeal/js')
    .postCss('Modules/CustomerLead/Resources/assets/app-deal.css', 'public/vue/kanban-customerdeal/css');