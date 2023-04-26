<?php
return [
    'index' => [
        'NOTIFICATION' => 'Thông báo',
        'NOTIFICATION_LIST' => 'Danh sách thông báo',
        'from' => 'Từ ',
        'come' => 'đến ',
        'not-processed' => 'Chưa xử lý',
        'on-delivery' => 'Đang giao hàng',
        'deliver-claimant' => 'Hoàn tất (giao nguyên đơn)',
        'partial-delivery' => 'Hoàn tất (giao một phần)',
        'cancel' => 'Hủy bỏ',
        'sell-status' => 'Trạng thái bán',
        'search' => [
            'TITLE' => 'Tiêu đề',
            'IS_SEND' => [
                'DEFAULT' => 'Trạng thái gửi thông báo',
                'SENT' => 'Đã gửi',
                'WAIT' => 'Chờ gửi',
                'DONT_SEND' => 'Chưa gửi'
            ],
            'IS_ACTIVED' => [
                'DEFAULT' => 'Hoạt động',
                'ACTIVE' => 'Đang hoạt động',
                'NON_ACTIVE' => 'Không hoạt động'
            ],
            'TIME' => 'Thời gian gửi',
            'BTN_SEARCH' => 'Tìm kiếm',
            'BTN_REMOVE' => 'Xóa'
        ],
        'table' => [
            'header' => [
                'TITLE' => 'Tiêu đề',
                'NOTIFICATIONS_IS_SENT' => 'Số thông báo gửi',
                'RATE_READ_NOTIFICATION' => 'Tỉ lệ đọc thông báo',
                'SEND_TIME' => 'Thời gian gửi',
                'ACTIVE' => 'Hoạt động',
                'ACTION' => 'Hành động',
                'IS_SEND' => 'Trạng thái gửi thông báo'
            ],
            'BTN_EDIT' => 'Sửa thông báo',
            'BTN_DELETE' => 'Xóa thông báo',
            'BTN_ADD' => 'Tạo thông báo',
            'BTN_DETAIL' => 'Chi tiết thông báo'
        ]
    ],
    'create' => [
        'SEND_NOTIFICATION' => 'Gửi thông báo',
        'CREATE_NOTIFICATION' => 'Tạo thông báo mới',
        'BTN_STORE_RELOAD' => 'Lưu và tạo mới',
        'BTN_STORE_EXIT' => 'Lưu và thoát',
        'BTN_CANCEL' => 'Hủy',
        'ADD_BRAND' => 'Thêm thương hiệu',
        'form' => [
            'header' => [
                'INFO_RECEIVER' => 'Thông tin người nhận',
                'CONTENT' => 'Nội dung thông báo',
                'ACTION' => 'Tùy chọn hành động',
                'SCHEDULE' => 'Lịch gửi thông báo'
            ],
            'placeholder' => [
                'TITLE' => 'Hãy nhập tiêu đề thông báo...',
                'SHORT_TITLE' => 'Tiêu đề ngắn hiển thị trên trang danh sách thông báo...',
                'ACTION_NAME' => 'Hãy nhập tên hành động...',
                'END_POINT_DETAIL' => '+ Chọn đích đến chi tiết',
                'SPECIFIC_TIME' => 'Chọn thời gian',
                'NON_SPECIFIC_TIME' => 'Nhập thời gian'
            ],
            'RECEIVER' => 'Người nhận',
            'BACKGROUND' => 'Background',
            'TITLE' => 'Tiêu đề thông báo',
            'SHORT_TITLE' => 'Tiêu đề hiển thị ngắn',
            'FEATURE' => 'Thông tin nổi bật của thông báo',
            'CONTENT' => 'Chi tiết thông báo',
            'ACTION_NAME' => 'Tên hành động',
            'CONTENT_GROUP' => 'Nhóm nội dung',
            'END_POINT' => 'Đích đến',
            'END_POINT_DETAIL' => 'Đích đến chi tiết',
            'SCHEDULE' => 'Thời gian gửi thông báo',
            'SEND_ALL_USER' => 'Gửi cho tất cả Mystore app user',
            'SEND_GROUP' => 'Gửi cho một tập khách hàng tùy chọn',
            'BTN_ADD_SEGMENT' => 'Chọn nhóm khách hàng',
            'SEND_NOW' => 'Gửi ngay lập tức',
            'SEND_SCHEDULE' => 'Gửi thông báo vào thời gian tùy chọn',
            'SPECIFIC_TIME' => 'Giờ chính xác',
            'NON_SPECIFIC_TIME' => 'Giờ tương đối',
            'HOUR' => 'Giờ',
            'MINUTE' => 'Phút',
            'DAY' => __('Ngày'),
            'ACTION_GROUP' => [
                'ACTION' => 'Hành động',
                'NON_ACTION' => 'Không hành động'
            ]
        ],
        'detail_form' => [
            'brand' => [
                'title' => 'Chọn thương hiệu đích',
                'header' => [
                    'LOGO' => 'Logo',
                    'BRAND_NAME' => 'Tên thương hiệu',
                    'BRAND_CODE' => 'Mã thương hiệu',
                    'COMPANY_NAME' => 'Tên công ty',
                    'LINK' => __('Link'),
                    'STATUS' => __('Trạng thái'),
                    'IS_PUBLISHED' => 'Hiện thị trên app'
                ],
                'placeholder' => [
                    'BRAND_NAME' => 'Tên thương hiệu',
                    'BRAND_CODE' => 'Mã thương hiệu',
                    'COMPANY_NAME' => 'Tên công ty',
                    'STATUS' => __('Trạng thái'),
                    'IS_PUBLISHED' => 'Hiện thị trên app'
                ],
                'BTN_SEARCH' => 'Tìm kiếm',
                'IS_ACTIVATED' => [
                    'YES' => 'Cho phép tương tác',
                    'NO' => 'Không được phép tương tác'
                ],
                'IS_PUBLISHED' => [
                    'YES' => 'Có',
                    'NO' => 'Không'
                ]
            ],
            'order' => [
                'title' => 'Chọn đơn hàng',
                'index' => [
                    'LIST_ORDER'=>'Danh sách đơn hàng',
                    'ACTION'=>'Hành động',
                    'EDIT'=>'Sửa sản phẩm',
                    'REMOVE'=>'Xóa sản phẩm',
                    'CHANGE_STATUS'=>'Thay đổi trạng thái thành công',
                    'ORDER_CODE' => 'Mã đơn hàng',
                    'CUSTOMER_NAME' => 'Tên khách hàng',
                    'PHONE' => 'Số điện thoại',
                    'STORE_NAME' => 'Tên cửa hàng DMS',
                    'BRAND_COMPANY' => 'Mã khách hàng/ công ty',
                    'PRODUCT_NAME' => 'Tên sản phẩm',
                    'SKU' => 'Mã SKU',
                    'PROVINCE' => __('Thành phố'),
                    'CHOOSE_PROVINCE' => 'Chọn tỉnh thành',
                    'DISTRICT' => 'Quận huyện',
                    'CHOOSE_DISTRICT' => 'Chọn quận huyện',
                    'ADDRESS' => 'Địa chỉ DMS',
                    'TIME_ORDER' => 'Thời gian đặt',
                    'PLACEHOLDER_TIME' => 'Ngày bắt đầu - Ngày kết thúc',
                    'TIME_SHIP' => 'Thời gian giao',
                    'SEARCH' => 'Tìm kiếm',
                    'RESET' => 'Xóa',
                    'TOTAL_MONEY' => 'Tổng tiền',
                    'STATUS' => 'Trạng thái đơn hàng',
                    'BTN_ADD' => 'Thêm đơn hàng',
                    'BTN_CLOSE' => 'Hủy',
                    'BTN_SEARCH' => 'Tìm kiếm'
                ]
            ],
            'market' => [
                'title' => 'Chọn chương trình khuyến mãi',
                'index' => [
                    'CAMPAIGN_DESCRIPTION'=>'Tên chương trình',
                    'CAMPAIGN_TYPE'=>'Loại chương trình',
                    'IS_DISPLAY'=> [
                        'ON' => 'Có',
                        'OFF' => 'Không',
                        'TITLE' => 'Hiển thị trên app'
                    ],
                    'BTN_SEARCH' => 'Tìm kiếm',
                    'BTN_ADD' => 'Thêm chương trình',
                    'BTN_CLOSE' => 'Hủy',
                    'TITLE_CAMPAIGN' => 'Danh sách chương trình Trade Marketing',
                    'CAMPAIGN_NAME' => 'Tên chương trình',
                    'OUTLET' => 'Nhà bán hàng',
                    'PRODUCT_NAME' => 'Tên sản phẩm',
                    'SELLER_SKU' => 'Seller SKU',
                    'PRODUCT_CODE' => 'Mã sản phẩm tham chiếu',
                    'STATUS' => __('Trạng thái'),
                    'TIME_MARKETING' => 'Thời gian khuyến mãi',
                    'TIME_REGISTER' => 'Thời gian đăng ký',
                    'PROMOTION' => 'On-Invoice',
                    'DISPLAY' => 'Display',
                    'LOYALTY' => 'Accummulative',
                    'SURVEY' => 'Survey',
                    'STOCK_COUNT' => 'Stock Count',
                    'RUNNING' => 'Đang chạy',
                    'END' => 'Kết thúc',
                    'TABLE_BANNER' => 'Banner',
                    'TABLE_CAMPAIGN_NAME' => 'Tên chương trình',
                    'TABLE_CAMPAIGN_TYPE' => 'Loại CTKM',
                    'TABLE_FEATURE' => 'Hiển thị nổi bật',
                    'TABLE_PRODUCT' => 'Sản phẩm',
                    'TABLE_OUTLET' => 'Nhà bán hàng',
                    'TABLE_TIME_REGISTER' => 'Thời gian đăng ký',
                    'TABLE_TIME_MARKETING' => 'Thời gian khuyến mãi',
                    'TABLE_RUNNING' => 'Đang chạy',
                    'TABLE_HIEU_LUC' => 'Hiệu lực',
                    'TABLE_DISPLAY' => 'Hiển thị',
                    'TABLE_EDIT' => 'Chỉnh sửa',
                ]
            ],
            'product' => [
                'title' => 'Danh sách sản phẩm',
                'index' => [
                    'CAMPAIGN_DESCRIPTION'=>'Tên chương trình',
                    'BTN_ADD' => 'Thêm sản phẩm'
                ]
            ],
            'faq' => [
                'title' => 'Chọn hỗ trợ',
                'header' => [
                    'TITLE' => 'Nội dung hỗ trợ',
                    'GROUP_TITLE' => 'Nhóm nội dung hỗ trợ',
                    'GROUP_POSITION' => 'Vị trí hiển thị',
                    'STATUS' => 'Trạng thái hiển thị'
                ],
                'placeholder' => [
                    'TITLE' => 'Tiêu đề'
                ],
                'BTN_ADD' => 'Thêm hỗ trợ',
                'BTN_CLOSE' => 'Hủy',
                'FAQ' => 'Hỏi đáp',
                'IS_ACTIVATED' => [
                    'YES' => 'Kích hoạt',
                    'NO' => 'Chưa kích hoạt'
                ],
                'POLICY' => 'Chính sách bảo mật',
                'TERMS' => 'Điều khoản sử dụng',
                'GROUP' => 'Nhóm nội dung hỗ trợ'
            ]
        ],
        'group' => [
            'title' => 'Chọn nhóm khách hàng',
            'header' => [
                'NAME' => 'Tên nhóm khách hàng',
                'TYPE' => 'Loại nhóm',
                'TIME' => 'Thời gian tạo'
            ],
            'placeholder' => [
                'NAME' => 'Tên nhóm khách hàng',
                'TYPE' => 'Loại nhóm',
                'TIME' => 'Thời gian tạo'
            ],
            'type' => [
                'USER_DEFINE' => 'Nhóm được định nghĩa',
                'AUTO' => 'Nhóm tự động'
            ],
            'BTN_ADD' => 'Thêm nhóm khách hàng',
            'BTN_CLOSE' => 'Hủy',
            'BTN_SEARCH' => 'Tìm kiếm'
        ]
    ],
    'edit'=>[
        'EDIT_NOTIFICATION' => 'Chỉnh sửa thông báo'
    ],
    'detail'=>[
        'DETAIL_NOTIFICATION' => 'Chi tiết thông báo',
        'BACK' => 'Quay về danh sách'
    ]
];
