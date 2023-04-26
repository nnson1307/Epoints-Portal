<?php

Route::group(['middleware' => ['web', 'auth', 'account'], 'prefix' => 'report', 'namespace' => 'Modules\Report\Http\Controllers'], function () {
//Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'report', 'namespace' => 'Modules\Report\Http\Controllers'], function () {
    Route::group(['prefix' => 'product'], function () {
        Route::get('/', 'ReportProductController@index')->name('report.product');
        Route::post('load-chart', 'ReportProductController@loadChartAction')->name('report.product.load-chart');
        Route::post('product-export-total', 'ReportProductController@exportExcelTotalAction')->name('report.product.export-total');
    });
    Route::group(['prefix' => 'product-category'], function () {
        Route::get('/', 'ReportProductCategoryController@index')->name('report.product-category');
        Route::post('load-chart', 'ReportProductCategoryController@loadChartAction')->name('report.product-category.load-chart');
    });
    // Report the purchase rate by time (all order except order cancel)
    Route::group(['prefix' => 'purchase-by-hour'], function () {
        Route::get('/', 'ReportPurchaseByHourController@index')->name('report.purchase-by-hour');
        Route::post('load-chart', 'ReportPurchaseByHourController@loadChartAction')->name('report.purchase-by-hour.load-chart');
    });

    // Report customer by view or purchase
    Route::group(['prefix' => 'customer-by-view-purchase'], function () {
        Route::get('/', 'ReportCustomerByViewPurchaseController@index')->name('report.customer-by-view-purchase');
        Route::post('load-chart', 'ReportCustomerByViewPurchaseController@loadChartAction')->name('report.customer-by-view-purchase.load-chart');
        Route::post('export', 'ReportCustomerByViewPurchaseController@exportExcel')->name('report.customer-by-view-purchase.export');
    });

    // Report based on postcode
    Route::group(['prefix' => 'base-on-postcode'], function () {
        Route::get('/', 'ReportBaseOnPostcodeController@index')->name('report.base-on-postcode');
        Route::post('load-chart', 'ReportBaseOnPostcodeController@loadChartAction')->name('report.base-on-postcode.load-chart');
    });

    // Report revenue: báo cáo doanh thu
    Route::group(['prefix' => 'report-revenue'], function () {
        // by branch: theo chi nhánh
        Route::get('report-revenue-by-branch', 'ReportRevenueByBranchController@indexAction')
            ->name('admin.report-revenue.branch');
        Route::post('report-revenue-by-branch-filter', 'ReportRevenueByBranchController@filterAction')
            ->name('admin.report-revenue.branch.filter');
        Route::post('report-revenue-by-branch-list-detail-branch', 'ReportRevenueByBranchController@listDetailAction')
            ->name('admin.report-revenue.branch.list-detail-branch');
        Route::post('report-revenue-by-branch-export-total', 'ReportRevenueByBranchController@exportExcelTotalAction')
            ->name('admin.report-revenue.branch.export-total');
        Route::post('report-revenue-by-branch-export-detail', 'ReportRevenueByBranchController@exportExcelDetail')
            ->name('admin.report-revenue.branch.export-detail');
        // by customer: theo khách hàng
        Route::get('report-revenue-by-customer', 'ReportRevenueByCustomerController@indexAction')
            ->name('admin.report-revenue.customer');
        Route::post('report-revenue-by-customer-filter', 'ReportRevenueByCustomerController@filterAction')
            ->name('admin.report-revenue.customer.filter');
        Route::post('report-revenue-by-customer-list-detail', 'ReportRevenueByCustomerController@listDetailAction')
            ->name('admin.report-revenue.customer.list-detail');
        Route::post('report-revenue-by-customer-export-total', 'ReportRevenueByCustomerController@exportExcelTotalAction')
            ->name('admin.report-revenue.customer.export-total');
        Route::post('report-revenue-by-customer-export-detail', 'ReportRevenueByCustomerController@exportExcelDetail')
            ->name('admin.report-revenue.customer.export-detail');
        // by staff: theo nhân viên
        Route::get('report-revenue-by-staff', 'ReportRevenueByStaffController@indexAction')
            ->name('admin.report-revenue.staff');
        Route::post('report-revenue-by-staff-filter', 'ReportRevenueByStaffController@filterAction')
            ->name('admin.report-revenue.staff.filter');
        Route::post('report-revenue-by-staff-list-detail', 'ReportRevenueByStaffController@listDetailAction')
            ->name('admin.report-revenue.staff.list-detail');
        Route::post('report-revenue-by-staff-export-total', 'ReportRevenueByStaffController@exportExcelTotalAction')
            ->name('admin.report-revenue.staff.export-total');
        Route::post('report-revenue-by-staff-export-detail', 'ReportRevenueByStaffController@exportExcelDetail')
            ->name('admin.report-revenue.staff.export-detail');
        // by product: theo sản phẩm
        Route::get('report-revenue-by-product', 'ReportRevenueByProductController@indexAction')
            ->name('admin.report-revenue.product');
        Route::post('report-revenue-by-product-filter', 'ReportRevenueByProductController@filterAction')
            ->name('admin.report-revenue.product.filter');
        Route::post('report-revenue-by-product-list-detail', 'ReportRevenueByProductController@listDetailAction')
            ->name('admin.report-revenue.product.list-detail');
        Route::post('report-revenue-by-product-export-total', 'ReportRevenueByProductController@exportExcelTotalAction')
            ->name('admin.report-revenue.product.export-total');
        Route::post('report-revenue-by-product-export-detail', 'ReportRevenueByProductController@exportExcelDetail')
            ->name('admin.report-revenue.product.export-detail');
        // by service: theo dịch vụ
        Route::get('report-revenue-by-service', 'ReportRevenueByServiceController@indexAction')
            ->name('admin.report-revenue.service');
        Route::post('report-revenue-by-service-filter', 'ReportRevenueByServiceController@filterAction')
            ->name('admin.report-revenue.service.filter');
        Route::post('report-revenue-by-service-list-detail', 'ReportRevenueByServiceController@listDetailAction')
            ->name('admin.report-revenue.service.list-detail');
        Route::post('report-revenue-by-service-export-total', 'ReportRevenueByServiceController@exportExcelTotalAction')
            ->name('admin.report-revenue.service.export-total');
        Route::post('report-revenue-by-service-export-detail', 'ReportRevenueByServiceController@exportExcelDetail')
            ->name('admin.report-revenue.service.export-detail');
        // by service: theo nhóm dịch vụ
        Route::get('report-revenue-by-service-group', 'ReportRevenueByServiceController@indexServiceGroupAction')
            ->name('admin.report-revenue.service-group');
        Route::post('report-revenue-by-service-group-filter', 'ReportRevenueByServiceController@filterGroupAction')
            ->name('admin.report-revenue.service-group.filter');
        Route::post('report-revenue-by-service-group-list-detail', 'ReportRevenueByServiceController@listDetailGroupAction')
            ->name('admin.report-revenue.service-group.list-detail');
        Route::post('report-revenue-by-service-group-export-total', 'ReportRevenueByServiceController@exportExcelTotalGroup')
            ->name('admin.report-revenue.service-group.export-total');
        Route::post('report-revenue-by-service-group-export-detail', 'ReportRevenueByServiceController@exportExcelGroupDetail')
            ->name('admin.report-revenue.service-group.export-detail');
        // by service card: theo thẻ dịch vụ
        Route::get('report-revenue-by-service-card', 'ReportRevenueByServiceCardController@indexAction')
            ->name('admin.report-revenue.service-card');
        Route::post('report-revenue-by-service-card-filter', 'ReportRevenueByServiceCardController@filterAction')
            ->name('admin.report-revenue.service-card.filter');
        Route::post('report-revenue-by-service-card-list-detail', 'ReportRevenueByServiceCardController@listDetailAction')
            ->name('admin.report-revenue.service-card.list-detail');
        Route::post('report-revenue-by-service-card-export-total', 'ReportRevenueByServiceCardController@exportExcelTotalAction')
            ->name('admin.report-revenue.service-card.export-total');
        Route::post('report-revenue-by-service-card-export-detail', 'ReportRevenueByServiceCardController@exportExcelDetail')
            ->name('admin.report-revenue.service-card.export-detail');

        //Báo cáo doanh thu nv phục vụ
        Route::group(['prefix' => 'service-staff'], function () {
            Route::get('/', 'ReportServiceStaffController@index')->name('admin.report-service-staff');
            Route::post('load-chart', 'ReportServiceStaffController@loadChartAction')
                ->name('admin.report-service-staff.load-chart');
            Route::post('report-service-staff-list-detail', 'ReportServiceStaffController@listDetailAction')
                ->name('admin.report-service-staff.list-detail');
            Route::post('report-service-staff-export-total', 'ReportServiceStaffController@exportExcelTotalAction')
                ->name('admin.report-service-staff.export-total');
            Route::post('report-service-staff-export-detail', 'ReportServiceStaffController@exportExcelDetail')
                ->name('admin.report-service-staff.export-detail');
        });
        // by surcharge service: theo dịch vụ phụ thu
        Route::get('report-revenue-by-surcharge-service', 'ReportRevenueBySurchargeServiceController@indexAction')
            ->name('admin.report-revenue.surcharge-service');
        Route::post('report-revenue-by-surcharge-service-filter', 'ReportRevenueBySurchargeServiceController@filterAction')
            ->name('admin.report-revenue.surcharge-service.filter');
        Route::post('report-revenue-by-surcharge-service-list-detail', 'ReportRevenueByServiceController@listDetailAction')
            ->name('admin.report-revenue.surcharge-service.list-detail');
        Route::post('report-revenue-by-surcharge-service-export-total', 'ReportRevenueByServiceController@exportExcelTotalAction')
            ->name('admin.report-revenue.surcharge-service.export-total');
        Route::post('report-revenue-by-surcharge-service-export-detail', 'ReportRevenueByServiceController@exportExcelDetail')
            ->name('admin.report-revenue.surcharge-service.export-detail');

    });
    // Report debt: báo cáo công nợ
    Route::group(['prefix' => 'report-debt-by-branch'], function () {
        Route::get('/', 'ReportDebtByBranchController@indexAction')->name('admin.report-debt-branch');
        Route::post('load-chart', 'ReportDebtByBranchController@filterAction')
            ->name('admin.report-debt-branch.load-chart');
        Route::post('list-detail', 'ReportDebtByBranchController@listDetailAction')
            ->name('admin.report-debt-branch.list-detail');
        Route::post('export-total', 'ReportDebtByBranchController@exportExcelTotalAction')
            ->name('admin.report-debt-branch.export-total');
        Route::post('export-detail', 'ReportDebtByBranchController@exportExcelDetail')
            ->name('admin.report-debt-branch.export-detail');
    });
    // Report commission: báo cáo hoa hồng nhân viên
    Route::group(['prefix' => 'report-staff-commission'], function () {
        Route::get('/', 'ReportStaffCommissionController@indexAction')
            ->name('admin.report-staff-commission');
        Route::post('load-chart', 'ReportStaffCommissionController@filterAction')
            ->name('admin.report-staff-commission.load-chart');
        Route::post('list-detail', 'ReportStaffCommissionController@listDetailAction')
            ->name('admin.report-staff-commission.list-detail');
        Route::post('export-detail', 'ReportStaffCommissionController@exportExcelDetail')
            ->name('admin.report-staff-commission.export-detail');
        Route::post('export-total', 'ReportStaffCommissionController@exportExcelTotal')
            ->name('admin.report-staff-commission.export-total');
    });
    // Statistic
    Route::group(['prefix' => 'statistical'], function () {
        // branch
        Route::get('statistical-branch', 'StatisticBranchController@indexAction')->name('admin.report-growth.branch');
        Route::post('statistical-branch-filter', 'StatisticBranchController@filterAction')
            ->name('admin.report-growth.branch.filter');
        Route::post('report-growth-branch-list-detail', 'StatisticBranchController@listDetailAction')
            ->name('admin.report-growth.branch.list-detail');
        Route::post('report-growth-branch-export-detail', 'StatisticBranchController@exportExcelDetailAction')
            ->name('admin.report-growth.branch.export-detail');
        Route::post('report-growth-branch-export-total', 'StatisticBranchController@exportExcelTotalAction')
            ->name('admin.report-growth.branch.export-total');
        // service card
        Route::get('statistical-service-card', 'StatisticServiceCardController@indexAction')
            ->name('admin.report-growth.service-card');
        Route::post('statistical-service-card-filter', 'StatisticServiceCardController@filterAction')
            ->name('admin.report-growth.service-card.filter');
        Route::post('report-growth-service-card-list-detail', 'StatisticServiceCardController@listDetailAction')
            ->name('admin.report-growth.service-card.list-detail');
        Route::post('report-growth-service-card-export-detail', 'StatisticServiceCardController@exportExcelDetailAction')
            ->name('admin.report-growth.service-card.export-detail');
        Route::post('report-growth-service-card-export-total', 'StatisticServiceCardController@exportExcelTotalAction')
            ->name('admin.report-growth.service-card.export-total');
        // appointment: lấy từ module admin qua nên không đổi name route
        Route::get('statistical-customer-appointment', 'StatisticCustomerAppointmentController@indexAction')
            ->name('admin.report-customer-appointment');
        Route::post('statistical-customer-appointment-filter', 'StatisticCustomerAppointmentController@filterAction')
            ->name('admin.report-customer-appointment.load-index');
        Route::post('statistical-customer-appointment-list-detail', 'StatisticCustomerAppointmentController@listDetailAction')
            ->name('admin.report-customer-appointment.list-detail');
        Route::post('statistical-customer-appointment-export-detail', 'StatisticCustomerAppointmentController@exportExcelDetailAction')
            ->name('admin.report-customer-appointment.export-detail');
        Route::post('statistical-customer-appointment-export-total', 'StatisticCustomerAppointmentController@exportExcelTotalAction')
            ->name('admin.report-customer-appointment.export-total');
        // order
        Route::get('statistical-order', 'StatisticOrderController@indexAction')->name('admin.statistical.order');
        Route::post('statistical-order-filter', 'StatisticOrderController@filterAction')
            ->name('admin.statistical.order.filter');
        Route::post('statistical-order-list-detail', 'StatisticOrderController@listDetailAction')
            ->name('admin.statistical.order.list-detail');
        Route::post('statistical-order-export-detail', 'StatisticOrderController@exportExcelDetailAction')
            ->name('admin.statistical.order.export-detail');
        Route::post('statistical-order-export-total', 'StatisticOrderController@exportExcelTotalAction')
            ->name('admin.statistical.order.export-total');
        // service
        Route::get('statistical-service', 'StatisticServiceController@indexAction')
            ->name('admin.report-growth.service');
        Route::post('statistical-service-filter', 'StatisticServiceController@filterAction')
            ->name('admin.report-growth.service.filter');
        Route::post('statistical-service-list-detail', 'StatisticServiceController@listDetailAction')
            ->name('admin.report-growth.service.list-detail');
        Route::post('statistical-service-export-detail', 'StatisticServiceController@exportExcelDetailAction')
            ->name('admin.report-growth.service.export-detail');
        Route::post('statistical-service-export-total', 'StatisticServiceController@exportExcelTotalAction')
            ->name('admin.report-growth.service.export-total');
        // customer
        Route::get('report-growth-by-customer', 'StatisticCustomerController@indexAction')
            ->name('admin.report-growth.customer');
        Route::post('report-growth-by-customer-filter', 'StatisticCustomerController@filterAction')
            ->name('admin.report-growth.customer.filter');
        Route::post('report-growth-by-customer-list-detail', 'StatisticCustomerController@listDetailAction')
            ->name('admin.report-growth.customer.list-detail');
        Route::post('report-growth-by-customer-export-detail', 'StatisticCustomerController@exportExcelDetailAction')
            ->name('admin.report-growth.customer.export-detail');
        Route::post('report-growth-by-customer-export-total', 'StatisticCustomerController@exportExcelTotalAction')
            ->name('admin.report-growth.customer.export-total');
    });

    // Báo cáo ngày đăng kiểm xe (product attribute, type = date)
    Route::group(['prefix' => 'vehicle-registration-date'], function () {
        Route::get('/', 'ReportVehicleRegistrationController@index')->name('report.vehicle-registration-date');
        Route::post('filter', 'ReportVehicleRegistrationController@filterAction')
            ->name('report.vehicle-registration-date.filter');
    });
    // Báo cáo hoa hồng cho deal
    Route::group(['prefix' => 'deal-commission'], function () {
        Route::get('/', 'ReportDealCommissionController@indexAction')->name('report.deal-commission');
        Route::post('filter', 'ReportDealCommissionController@filterAction')
            ->name('report.deal-commission.filter');
        Route::post('deal-commission-list-detail', 'ReportDealCommissionController@listDetailAction')
            ->name('report.deal-commission.list-detail');
        Route::post('deal-commission-export-detail', 'ReportDealCommissionController@exportExcelDetail')
            ->name('report.deal-commission.export-detail');
        Route::post('deal-commission-export-total', 'ReportDealCommissionController@exportExcelTotal')
            ->name('report.deal-commission.export-total');
    });

    Route::group(['prefix' => 'product-inventory'], function () {
        Route::get('/', 'ProductInventoryController@index')->name('report.product-inventory');
        Route::post('paginate-detail', 'ProductInventoryController@paginateDetailAction')
            ->name('report.product-inventory.paginate');
        Route::post('export-detail', 'ProductInventoryController@exportDetailAction')
            ->name('report.product-inventory.export-detail');
        //Lấy ds sản phẩm load mare
        Route::post('option-child', 'ProductInventoryController@getListChildAction')
            ->name('report.product-inventory.list-child');
    });
    Route::group(['prefix' => 'campaign-report'], function () {
        Route::get('/', 'CampaignOverviewReportController@indexAction')
            ->name('report.campaign-report');
        Route::post('/filter', 'CampaignOverviewReportController@filterAction')
            ->name('report.campaign-report.filter');
        Route::post('/filter-ii', 'CampaignOverviewReportController@filterIIAction')
            ->name('report.campaign-report.filter-ii');
    });
    Route::group(['prefix' => 'performance-report'], function () {
        Route::get('/', 'PerformanceReportController@indexAction')
            ->name('report.performance-report');
        Route::post('/filter', 'PerformanceReportController@filterAction')
            ->name('report.performance-report.filter');
        Route::post('/filter-staff', 'PerformanceReportController@filterStaff')
            ->name('report.performance-report.filter-staff');
    });


//    Report chatbot
    Route::group(['prefix' => 'over-view'], function () {
        Route::get('/', 'DashboardController@indexAction')->name('dashboard');
        Route::post('load-chart', 'DashboardController@chartAction')->name('dashboard.load-chart');
        Route::post('chart-month', 'DashboardController@chartMonthAction')->name('dashboard.chart-month');
        Route::post('export-user', 'DashboardController@exportUserAction')->name('dashboard.export-user');
        Route::post('export-total-message', 'DashboardController@exportTotalMessageAction')->name('dashboard.export-total-message');
    });

    Route::group(['prefix' => 'message-completion'], function () {
        Route::get('/', 'MessageCompletionController@indexAction')->name('message-completion');
        Route::post('load-chart', 'MessageCompletionController@chartAction')->name('message-completion.load-chart');
        Route::post('export-completion', 'MessageCompletionController@exportMessageCompletion')
            ->name('message-completion.export');
    });

    Route::group(['prefix' => 'message-attribute'], function () {
        Route::get('/', 'MessageAttributeController@indexAction')->name('message-attribute');
        Route::post('load-chart', 'MessageAttributeController@chartAction')->name('message-attribute.load-chart');
        Route::post('export-attr-not-response',
            'MessageAttributeController@exportMessageAttributeNotResponseAction')
            ->name('export-attr-not-response');
    });

    Route::group(['prefix' => 'word-cloud'], function () {
        Route::get('/', 'WordCloudController@indexAction')->name('word-cloud');
        Route::post('load-chart', 'WordCloudController@chartAction')->name('word-cloud.load-chart');
        Route::post('export-word-cloud', 'WordCloudController@exportWordCloud')->name('export-word-cloud');
    });

    Route::group(['prefix' => 'user-management'], function () {
        Route::get('/', 'UserManagementController@indexAction')->name('user-management');
        Route::post('load-chart', 'UserManagementController@chartAction')->name('user-management.load-chart');
        Route::post('chart-sku', 'UserManagementController@chartSkuAction')->name('user-management.chart-sku');
        Route::post('chart-attr', 'UserManagementController@chartAttributeAction')->name('user-management.chart-attr');
        Route::post('export-user-time', 'UserManagementController@exportUserTimeAction')->name('user-management.export-user-time');
        Route::post('export-user-brand', 'UserManagementController@exportUserByBrand')->name('user-management.export-user-brand');
        Route::post('export-user-sku-brand', 'UserManagementController@exportUserSkuByBrand')->name('user-management.export-user-sku-brand');
        Route::post('export-user-attr-brand', 'UserManagementController@exportUserAttributeBrand')->name('user-management.export-user-attr-brand');
//        Route::group(['prefix' => 'other-overview'], function () {
//            Route::get('/', 'OtherOverviewController@indexAction')->name('other-overview');
//            Route::post('load-chart', 'OtherOverviewController@chartAction')->name('other-overview.load-chart');
//        });
    });

    Route::group(['prefix' => 'user-management-status'], function () {
        Route::get('/', 'UserManagementStatusController@indexAction')->name('user-management-status');
        Route::post('load-chart', 'UserManagementStatusController@chartAction')->name('user-management-status.load-chart');
    });

    Route::group(['prefix' => 'message-completion'], function () {
        Route::get('/', 'MessageCompletionController@indexAction')->name('message-completion');
        Route::post('load-chart', 'MessageCompletionController@chartAction')->name('message-completion.load-chart');
        Route::post('export-completion', 'MessageCompletionController@exportMessageCompletion')
            ->name('message-completion.export');
    });

    Route::group(['prefix' => 'message-attribute'], function () {
        Route::get('/', 'MessageAttributeController@indexAction')->name('message-attribute');
        Route::post('load-chart', 'MessageAttributeController@chartAction')->name('message-attribute.load-chart');
        Route::post('export-attr-not-response',
            'MessageAttributeController@exportMessageAttributeNotResponseAction')
            ->name('export-attr-not-response');
    });

    Route::group(['prefix' => 'message-attribute-other'], function () {
        Route::get('/', 'MessageAttributeOtherController@indexAction')->name('message-attribute-other');
        Route::post('load-chart', 'MessageAttributeOtherController@chartAction')->name('message-attribute-other.load-chart');
        Route::post('export-attr-other', 'MessageAttributeOtherController@exportMessageAttributeOther')
            ->name('message-attribute-other.export');
    });

});
