<?php

use Illuminate\Support\Facades\Route;

Route::group([
      'prefix' => 'sync-data-googlesheet',
      'namespace' => 'Modules\SyncDataGoogleSheet\Http\Controllers'
], function () {
      // lấy hàng cuối cùng insert googleSheet //
      Route::get('/get-lastRow-insert/{id_google_sheet}', 'SyncDataGoogleSheetController@getLastRowInsert');
      // đồng bộ data googleSheet (insert dữ liệu từ googleSheet  và update hàng cuối cùng insert ) //
      Route::post('/sync-data-google-sheet', 'SyncDataGoogleSheetController@syncDataGoogleSheet');
      // Kiểm tra tình trạng thái phân bổ (tự động hay mặc định) //
      Route::get('/get-status-allotment/{id_google_sheet}', 'SyncDataGoogleSheetController@getStatusAllotment');
      // lấy id cấu hình phân bổ //
      Route::get('/get-config-allotment/{id_google_sheet}', 'SyncDataGoogleSheetController@getConfigAllotment');
      // lấy id nguồn khách hàng //
      // Route::get('/get-customer-source/{code_customer_source}', 'SyncDataGoogleSheetController@getCustomerSource');
      // lấy id người tạo cấu hình phân bổ googleSheet //
      Route::get('/get-user-create-pipelines', 'SyncDataGoogleSheetController@getIdUserCreatePipelines');
      // kiểm tra trạng thái cấu hình  //
      Route::get('/get-status-config-allotment/{id_google_sheet}', 'SyncDataGoogleSheetController@getConfigStatusAllotment');
      
});
