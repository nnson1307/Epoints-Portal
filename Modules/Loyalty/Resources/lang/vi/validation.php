<?php
return [
    'report' => [
        'title_popup_confirm' => 'Bạn có chắc muốn xuất dữ liệu?',
        'yes' => 'Có',
        'no' => 'Không',
        'display' => 'Hiển thị',
        'of_the' => 'của',
        'please_wait' => 'vui lòng đợi...',
        'no_data' => 'Không có dữ liệu',
        'code' => 'Số chứng từ',
        'type' => 'Loại chứng từ',
        'description' => 'Mô tả chứng từ',
        'created_at' => 'Thời gian tạo',
        'status' => 'Trạng thái',
        'reason_for_error' => 'Lý do lỗi',
        'processing' => 'Đang xử lý',
        'complete' => 'Đã xử lý',
        'failed' => 'Thất bại',
    ],
    'accumulate' => [
        'name_accumulate_required' => 'Tên điều chỉnh không được để trống',
        'name_accumulate_max' => 'Tên điều chỉnh tối đa chỉ được 50 ký tự',
        'name_accumulate_unique' => 'Tên điều chỉnh đã được sử dụng',
        'des_required' => 'Nội dung điều chỉnh không được để trống',
        'des_max' => 'Nội dung điều chỉnh tối đa chỉ được 200 ký tự',
        'point_digits' => 'Điểm điều chỉnh là số nguyên và lớn hơn 0',
        'time_accumulate' => 'Chọn thời gian điều chỉnh',
        'time_adjust' => 'Hãy chọn thời gian điều chỉnh',

        'status_required' => 'Hãy chọn trạng thái',
        'adjustment_type' => 'Hãy chọn loại điều chỉnh',
        'point_rank' => 'Hãy nhập điểm phân hạng',
        'point_consumed' => 'Hãy nhập điểm có thể tiêu',

        'program_required' => 'Hãy chọn chương trình thành viên',

        'create_success' => 'Tạo mới thành công',
        'create_fail' => 'Tạo mới thất bại',
        'update_success' => 'Cập nhật thành công',
        'update_fail' => 'Cập nhật thất bại',

        'arrOutlet' => 'Hãy chọn nhóm thành viên',

        'approve_success' => 'Duyệt yêu cầu điều chỉnh thành công',
        'cancel_success' => 'Hủy yêu cầu điều chỉnh thành công',

        'notification' => 'Xác nhận hủy',
        'html_popup_page' => 'Khi hủy điều chỉnh, chương trình sẽ không thể quay trở lại được nữa. Bạn có chắc muốn hủy không?',
        'yes' => 'xác nhận hủy',
        'no' => 'không hủy',

        'update_fail_adjusted' => 'Trạng thái hiện tại Đã điều chỉnh nên không thể lưu chỉnh sửa điều chỉnh điểm tích lũy',

        'notification_back' => 'Xác nhận hủy',
        'html_popup_page_back' => 'Sẽ không lưu thay đổi khi xác nhận hủy chỉnh sửa',
        'yes_back' => 'Xác nhận hủy',
        'no_back' => 'Không hủy',
    ],
    'setting_program' => [
        'name_program' => 'Tên chương trình không được để trống',
        'name_program_max' => 'Tên chương trình tối đa chỉ được 100 ký tự',
        'name_point' => 'Tên điểm không được để trống',
        'name_pint_max' => 'Tên điểm tối đa chỉ được 50 ký tự',
        'periodically_required' => 'Nhập số ngày áp dụng chu lỳ xét lại hạng',
        'periodically_type' => 'Số ngày phải là số và lớn hơn 0',

        'update_success' => 'Cập nhật thành công',
        'update_fail' => 'Cập nhật thất bại',
        'periodically_error' => 'Lỗi định kỳ',
        'message_error' => 'Nội dung tóm tắt phải chứa biến mặc định [Hạng thành viên mới]',
        'detail_content_error' => 'Nội dung chi tiết phải chứa biến mặc định [Hạng thành viên mới]'
    ],
    'membership' => [
        'create' => [
            'NOT_ACHIEVE_REQUIRED' => 'Vui lòng nhập điểm tích lũy không đạt hạng',
            'NOT_ACHIEVE_NUMBER' => 'Điểm tích lũy không đạt hạng phải là số nguyên',
            'NOT_ACHIEVE_MIN' => 'Điểm tích lũy không đạt hạng phải là số dương',
            'MEMBERSHIP_NAME_REQUIRED' => 'Vui lòng nhập tên hạng thành viên',
            'MEMBERSHIP_NAME_MAX' => 'Tên hạng thành viên vượt quá 50 ký tự',
            'BENEFIT_TITLE_REQUIRED' => 'Vui lòng nhập tiêu đề',
            'BENEFIT_TITLE_MAX' => 'Tiêu đề không được quá 50 ký tự',
            'BENEFIT_RANK_REQUIRED' => 'Vui lòng nhập nội dung lợi ích của hạng',
            'BENEFIT_RANK_MAX' => 'Tối đa 245 ký tự',
            'POINT_ACHIEVE_REQUIRED' => 'Vui lòng nhập điểm để đạt hạng',
            'POINT_ACHIEVE_NUMBER' => 'Điểm đạt hạng phải là số nguyên',
            'POINT_ACHIEVE_MIN' => 'Điểm duy trì hạng phải lớn hơn hoặc bằng 0',
            'NOT_CHECK' => 'Vui lòng chọn thay đổi',
            'RESET_POINT_REQUIRED' => 'Vui lòng nhập điểm reset',
            'RESET_POINT_NUMBER' => 'Điểm reset phải là số nguyên',
            'RESET_POINT_MIN' => 'Điểm reset phải lớn hơn hoặc bằng 0',
            'DEDUCTION_POINT_REQUIRED' => 'Vui lòng nhập điểm trừ',
            'DEDUCTION_POINT_NUMBER' => 'Điểm trừ phải là số nguyên',
            'DEDUCTION_POINT_MIN' => 'Điểm trừ phải lớn hơn hoặc bằng 0',
            'ADD_ERROR' => 'Thêm thất bại',
            'MEMBERSHIP' => 'Thành viên ',
            'POINT' => ' điểm',
            'MEMBERSHIP_POINT_NUMBER' => 'Tỉ lệ điểm của hạng phải là số nguyên',
            'CANCEL' => 'Hủy',
            'NOTE_CANCEL' => 'Bạn có muốn hủy?',
            'BACK' => 'Quay lại',
            'NOTE_BACK' => 'Bạn có muốn quay về trang trước?',
            'YES' => 'Đồng ý',
            'NO' => 'Không đồng ý',
        ]
    ],
    'reward_program' => [
        'create' => [
            'reward_program_name_required' => 'Vui lòng nhập tên chương trình!',
            'reward_program_name_max' => 'Tên chương trình không quá 255 ký tự!',
            'date_start_required' => 'Vui lòng chọn ngày bắt đầu!',
            'date_end_required' => 'Vui lòng chọn ngày kết thúc!',
            'point_required' => 'Vui lòng nhập điểm tích lũy cần để đổi!',
            'point_min' => 'Điểm tích lũy cần để đổi phải lớn hơn 0!',
            'point_digits' => 'Điểm phải là số nguyên dương!',
            'point_number' => 'Điểm phải đúng định dạng số!',
            'type_reward_required' => 'Vui lòng chọn loại ưu đãi!',
            'bonus_product_required' => 'Vui lòng chọn sản phẩm trả thưởng!',
            'product_uom_required' => 'Vui lòng chọn uom!',
            'amount_each_exchange_required' => 'Vui lòng nhập số lượng mỗi lần đổi điểm!',
            'amount_each_exchange_min' => 'Số lượng mỗi lần đổi điẻm phải lớn hơn 0!',
            'amount_each_exchange_digits' => 'Số lượng phải là số nguyên dương!',
            'amount_each_exchange_number' => 'Số lượng phải đúng định dạng số!',
            'program_content_required' => 'Vui lòng nhập nội dung chương trình!',

            'member_program_required' => 'Vui lòng chọn chương trình!',
            'check_date' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'amount_required' => 'Vui lòng nhập số lần',
            'amount_min' => 'Số lần phải lớn hơn 0',
            'amount_integer' => 'Số lần phải là số nguyên dương!',
            'rank_min' => 'Phải chọn tối thiểu một hạng!',

            'fileName_mimes' => 'File này không phải file hình',
            'fileSize_max' => 'Kích thước file không quá 10MB',

            'choose_program_member' => 'Chọn chương trình thành viên',
            'point_accumulation1_error' => 'Nhập số điểm cần để đổi',
            'point_accumulation1_error_min' => 'Số điểm phải là số và lớn hơn 0',
            'play_turn_number_required' => 'Vui lòng nhập số lượng lượt quay mỗi cửa hàng!',
            'play_turn_number_min_max' => 'Số lượng lượt quay mỗi cửa hàng phải từ 1 đến 999,999,999',
            'reward_program_name_unique' => 'Tên chương trình đã tồn tại',

        ],
        'edit' => [
            'success' => "Cập nhật chương trình tiêu điểm thành công",
            'fail' => 'Có lỗi xảy ra!'
        ],

        'notification_back' => 'Xác nhận hủy',
        'html_popup_page_back' => 'Khi hủy các thông tin sẽ không được lưu trữ và bị xóa mất vĩnh viễn. Bạn có chắc muốn hủy không?',
        'yes_back' => 'Xác nhận',
        'no_back' => 'Không',
    ],
    'brand_loyalty' => [
        'create_success' => 'Thêm cửa hàng vào :name thành công',
        'create_fail' => 'Thêm cửa hàng vào :name thất bại',
        'program_required' => 'Hãy chọn chương trình thành viên',
        'outlet_unique' => 'Danh sách chọn có cửa hàng đã tồn tại trong chương trình thành viên',
        'notification' => 'Thông báo',
        'html_popup_page' => 'Các cửa hàng sẽ được bỏ chọn khi chuyển trang',
        'yes' => 'Xác nhận',
        'no' => 'Ở lại trang',
        'html_popup_filter' => 'Các cửa hàng sẽ được bỏ chọn khi lọc lại danh sách',
        'export_outlet' => 'Bạn có chắc muốn xuất dữ liệu',
        'export_yes' => 'Có',
        'export_no' => 'Không'
    ],
    'accumulate_point' => [
        'name_required' => 'Hãy nhập tên chương trình',
        'name_max' => 'Tên chương trình không được quá 255 ký tự',
        'survey_required' => 'Hãy chọn khảo sát',
        'accumulate_point' => 'Nhập số điểm tích lũy',
        'accumulate_min' => 'Điểm tích lũy phải lớn hơn hoặc bằng 0',
        'diemphanhang' => 'Hãy nhập điểm phân hạng',
        'diemphanhang_min' => 'Điểm phân hạng phải lớn hơn hoặc bằng 0',
        'diemcothetieu' => 'Hãy nhập điểm có thể tiêu',
        'diemcothetieu_min' => 'Điểm có thể tiêu phải lớn hơn hoặc bằng 0',
        'create_success' => 'Tạo mới thành công',
        'create_fail' => 'Tạo mới thất bại',
        'update_success' => 'Cập nhật thành công',
        'update_fail' => 'Cập nhật thất bại',
        'delete_fail' => 'Xoá thất bại',
        'time_start_required' => 'Hãy nhập thời gian bắt đầu',
        'time_end_required' => 'Hãy nhập thời gian kết thúc',
        'photo_tracking' => 'Hãy chọn chương trình chụp ảnh',
        'time_limit' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu',
        'program_photo_tracking' => 'Thời gian hiệu lực chương trình tích điểm này đã bị trùng với chương trình tích điểm :name. Vui lòng kiểm tra lại',
        'digits_validate' => 'Chỉ được nhập số nguyên dương',
        'survey_id_required' => 'Hãy chọn chương trình khảo sát',
        'survey_id_unique' => 'Chương trình tích điểm của khảo sát này đã bị trùng với chương trình tích điểm khác. Vui lòng kiểm tra lại.',
        'accumulate_point_min' => 'Số điểm phân hạng phải lớn hơn 0.',
        'available_point_min' => 'Số điểm có thể tiêu phải lớn hơn 0.',
        'validity_period_type_required' => 'Vui lòng chọn thời gian hiệu lực',
        'is_active_required' => 'Vui lòng chọn trạng thái',
        'apply_type_required' => 'Vui lòng chọn Điểm tích luỹ'
    ],
    'loyalty_order_master' => [
        'add_success' => 'Thêm thành công!',
        'add_fail' => 'Thêm thất bại!',
        'edit_success' => 'Chỉnh sửa thành công!',
        'edit_fail' => 'Chỉnh sửa thất bại!',
        'add_error' => 'Thêm thất bại!',
        'edit_error' => 'Chỉnh sửa thất bại!',
        'not_null' => 'không được để trống.',
        'enter_value' => 'Vui lòng nhập giá trị.',
        'max_100' => 'Tối đa 100 ký tự.',
        'max_50' => 'Tối đa 50 ký tự.',
        'enter_campaign_description' => 'Tên chương trình hiển thị không được để trống.',
        'campaign_description_max_100' => 'Tên chương trình hiển thị tối đa 100 ký tự.',
        'enter_campaign_code' => 'Mã chương trình không được để trống.',
        'campaign_code_max_50' => 'Mã chương trình tối đa 50 ký tự.',
        'campaign_code_already_exist' => 'Mã chương trình đã tồn tại.',
        'enter_loyalty_program_id' => 'Chương trình khách hàng thân thiết không được để trống.',
        'enter_order_limit' => 'Giới hạn đơn hàng áp dụng mỗi khách hàng không được để trống.',
        'campaign_scheme_code_50' => 'Scheme code tối đa 50 ký tự.',
        'campaign_deal_code_50' => 'IO number tối đa 50 ký tự.',
        'enter_effective_date' => 'Ngày bắt đầu không được để trống.',
        'enter_end_date' => 'Ngày kết thúc không được để trống.',
        'end_date_after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
        'enter_start_reg_date' => 'Ngày bắt đầu đăng ký không được để trống.',
        'enter_end_reg_date' => 'Ngày kết thúc đăng ký không được để trống.',
        'end_reg_date_after_or_equal' => 'Ngày kết thúc đăng ký phải lớn hơn ngày bắt đầu đăng ký.',
        'remove_title_popup' => 'Xác nhận xóa chương trình',
        'remove_content_popup' => 'Bạn có chắc chắn muốn xóa chương trình này không? Sau khi xóa, chương trình sẽ không thể phục hồi lại được.',
        'btn_confirm' => 'Xác nhận',
        'btn_cancel' => 'Hủy',
        'not_remove_title_popup' => 'Không thể xóa chương trình',
        'not_remove_content_popup' => 'Bạn không có quyền xóa chương trình hoặc chương trình của bạn đã được duyệt nên không thể xóa. Vui lòng kiểm tra lại.',
        'remove_success' => 'Xóa thành công!',
        'remove_fail' => 'Xóa thất bại!',
        'reject_title_popup' => 'Xác nhận từ chối chương trình',
        'reject_content_popup' => 'Khi xác nhận từ chối chương trình, bạn sẽ không thể chỉnh sửa thông tin chương trình được nữa. Bạn có chắc chắn muốn từ chối không?',
        'release_title_popup' => 'Xác nhận duyệt chương trình',
        'release_content_popup' => 'Khi xác nhận duyệt chương trình, bạn sẽ không thể chỉnh sửa một số thông tin của chương trình bạn đang duyệt. Bạn có chắc chắn duyệt không?',
        'not_release' => 'Để có thể duyệt chương trình, vui lòng cài đặt các thông tin cho tất cả các tab được hiển thị.',
        'close_title_popup' => 'Xác nhận kết thúc',
        'close_content_popup' => 'Khi xác nhận kết thúc chương trình, chương trình của bạn sẽ không còn hiệu lực và không thể chỉnh sửa được nữa. Bạn có chắc chắn muốn tiếp tục không?',
        'error_mes' => 'Báo lỗi',
        'cancel_title_popup' => 'Xác nhận hủy cập nhật',
        'cancel_content_popup' => 'Khi xác nhận hủy cập nhật, nội dung của bạn sẽ không được lưu. Bạn có chắc chắn muốn tiếp tục không?',
        'close' => 'Đóng',
        'input_money' => 'Yêu cầu nhập số tiền',
        'digits_money' => 'Số tiền sai định dạng',
        'input_point' => 'Yêu cầu nhập số điểm',
        'digits_point' => 'Số điểm sai định dạng',
        'amount' => 'Số lượng',
        'money' => 'Tổng tiền',
        'enter_product_condition' => 'Vui lòng thêm sản phẩm/ nhóm sản phẩm có từ 1 sản phẩm trở lên vào sản phẩm điều kiện kèm theo.',
        'enter_product_sell' => 'Vui lòng thêm sản phẩm/ nhóm sản phẩm có từ 1 sản phẩm trở lên vào sản phẩm bán.',
        'error_quantity' => 'Số lượng không được để trống, phải lớn hơn 0 và nhỏ hơn 999,999,999,999.',
        'error_amount' => 'Tổng tiền không được để trống, phải lớn hơn 0 và nhỏ hơn 999,999,999,999.',
        'error_quantity_uom' => 'Sản phẩm của bạn thêm vào trùng với sản phẩm đã có trong danh sách. Vui lòng kiểm tra lại!',
        'error_name_group' => 'Tên nhóm sản phẩm không được để trống và tối đa 255 ký tự.',
        'error_value' => 'Giá trị không được để trống, phải lớn hơn 0 và nhỏ hơn 999,999,999,999.',
    ],

    'reward_redeem' => [
        'btn_yes' => 'Đồng ý',
        'btn_no' => 'Hủy',
        'confirm_title' => 'Xác nhận đã giao hàng trả thưởng',
        'confirm_html' => 'Khi quà tặng được xác nhận đã giao, trạng thái sẽ không thể phục hồi lại được. Bạn có chắc chắn muốn tiếp tục không?',

        'confirm_success' => 'Xác nhận thành công',
        'confirm_fail' => 'Xác nhận thất bại',
        'confirm_no_redeem' => 'Vui lòng chọn ít nhất 1 yêu cầu nhận quà tặng',

        'confirm_title_fail' => 'Không thể xác nhận giao',
        'confirm_html_fail' => 'Các sản phẩm bạn đang chọn để xác nhận chỉ được ở trạng thái chờ giao. Vui lòng kiểm tra lại trước khi xác nhận giao.'
    ],
    'budget' => [
        'province' => 'Tỉnh/ thành phố',
        'district' => 'Quận/ huyện',
        'ward' => 'Phường xã',
        'enter_code' => 'Code không được để trống.',
        'code_max_length' => 'Code tối đa 50 ký tự.',
        'budget_code_unique' => 'Code đã tồn tại.',
        'description_max_length' => 'Mô tả tối đa 200 ký tự.',
        'reference_code_max_length' => 'Reference code tối đa 50 ký tự.',
        'io_number_max_length' => 'IO number tối đa 50 ký tự.',
        'max_50' => 'Tối đa 50 ký tự.',
        'max_200' => 'Tối đa 200 ký tự.',
        'enter_budget_total' => 'Ngân sách không được để trống.',
        'budget_total_max' => 'Ngân sách tối đa 1000000000000.',
        'enter_budget_limit' => 'Số giới hạn không được để trống.',
        'budget_limit_max' => 'Số giới hạn tối đa 1000000000000.',
        'enter_outlet' => 'Phải có ít nhất 1 cửa hàng.',
        'enter_limit_outlet' => 'Giới hạn không được để trống.',
        'title_popup_approve' => 'Xác nhận duyệt ngân sách',
        'content_popup_approve' => 'Bạn có chắc chắn muốn duyệt ngân sách này không? Sau khi duyệt xong, ngân sách sẽ có hiệu lực khi gắn với các chương trình trade marketing và tích lũy',
        'title_popup_reject' => 'Xác nhận từ chối ngân sách',
        'content_popup_reject' => 'Bạn có chắc chắn muốn từ chối ngân sách này không? Sau khi từ chối xong, ngân sách sẽ không thể được chỉnh sửa',
        'limit_min' => 'Số giới hạn của từng đối tượng phải lớn hơn tổng số tạm tính và số đã cộng.',
        'budget_total_min' => 'Ngân sách tổng phải lớn hơn tổng số tạm tính và số đã cộng của tất cả các cửa hàng và các chương trình đã từng gắn với ngân sách này.',
        'budget_limit_min' => 'Giới hạn chung của đối tượng phải lớn hơn số tạm tính và số đã cộng của bất kỳ cửa hàng nào.',
        'title_popup_remove' => 'Xác nhận xóa ngân sách',
        'content_popup_remove' => 'Bạn có chắc chắn muốn xóa ngân sách này không? Sau khi xóa, ngân sách sẽ không thể phục hồi lại được',
        'title_popup_not_remove' => 'Ngân sách không thể xóa',
        'content_popup_not_remove' => 'Bạn vui lòng kiểm tra lại ngân sách đã chọn. Bạn chỉ được xóa các ngân sách ở trạng thái "Bản nháp" hoặc "Từ chối"',
    ],
    'choose_program' => 'Chọn chương trình',
    'cancel_confirm' => 'Xác nhận hủy',
    'template_config_notifi' => [
        'title_required' => 'Vui lòng nhập tiêu đề',
        'title_max_255' => 'Nhập tối đa 255 kí tự',
        'description_required' => 'Vui lòng nhập nội dung tóm tắt',
        'description_detail_required' => 'Vui lòng nhập nội dung chi tiết',
        'update_success' => 'Cập nhật cấu hình mẫu thông báo thành công',
        'update_fail' => 'Cập nhật cấu hình mẫu thông báo thất bại'
    ]
];