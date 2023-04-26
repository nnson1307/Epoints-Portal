<?php
return [
    'report' => [
        'title_popup_confirm' => 'Are you sure you want to export the data?',
        'yes' => 'Yes',
        'no' => 'No',
        'display' => 'Display',
        'of_the' => 'of',
        'please_wait' => 'please wait...',
        'no_data' => 'No data',
        'code' => 'Vote number',
        'type' => 'Document type',
        'description' => 'Document description',
        'created_at' => 'Create time',
        'status' => 'Status',
        'reason_for_error' => 'Error reason',
        'processing' => 'Processing',
        'complete' => 'Processed',
        'failed' => 'Failed',
    ],
    'accumulate' => [
        'name_accumulate_required' => 'Module name cannot be empty',
        'name_accumulate_max' => 'Adjustable name can only be 50 characters',
        'name_accumulate_unique' => 'Adjustment name already in use',
        'des_required' => 'Adjustment cannot be empty',
        'des_max' => 'The maximum content can only be adjusted to 200 characters',
        'point_digits' => 'The adjustment point is an integer and greater than 0',
        'time_accumulate' => 'Select adjustment time',
        'time_adjust' => 'Please select an adjustment time',
        'survey_required' => 'Please select survey',
        'status_required' => 'Please select a status',
        'adjustment_type' => 'Please select an adjustment type',
        'point_rank' => 'Please enter rating',
        'point_consumed' => 'Please enter spendable points',
        'validity_period_type_required' => 'Please select the validity period',
        'program_required' => 'Please select a membership program',
        'is_active_required' => 'Please select a status',
        'apply_type_required' => 'Please selecte a point',
        'create_success' => 'New created successfully',
        'create_fail' => 'Create failed',
        'update_success' => 'Update successful',
        'update_fail' => 'Update failed',
        'delete_fail' => 'Delete failed',
        'arrOutlet' => 'Please select a member group',

        'approve_success' => 'Adjustment request approval was successful',
        'cancel_success' => 'Cancel the adjustment request successfully',

        'notification' => 'Cancellation confirmation',
        'html_popup_page' => 'Once the adjustment is canceled, the program will not be able to return. Are you sure you want to cancel?',
        'yes' => 'cancellation confirmation',
        'no' => 'no cancellation',

        'update_fail_adjusted' => 'Current status Adjusted so cumulative points adjustment cannot be saved',

        'notification_back' => 'Cancellation confirmation',
        'html_popup_page_back' => 'No changes will be saved when undo editing is confirmed',
        'yes_back' => 'Confirm Cancellation',
        'no_back' => 'No cancellation',
    ],
    'setting_program' => [
        'name_program' => 'Program name cannot be empty',
        'name_program_max' => 'The maximum program name can only be 100 characters',
        'name_point' => 'Point name cannot be empty',
        'name_pint_max' => 'The maximum score name is only 50 characters',
        'periodically_required' => 'Enter the number of days to apply the review period',
        'periodically_type' => 'Number of days must be numeric and greater than 0',

        'update_success' => 'Update successful',
        'update_fail' => 'Update failed',
        'periodically_error' => 'Recurring error',
        'message_error' => 'The summary must contain the default variable [New Membership Class]',
        'detail_content_error' => 'Detailed content must contain default variable [New Membership Class]'
    ],
    'membership' => [
        'create' => [
            'NOT_ACHIEVE_REQUIRED' => 'Please enter the accumulated points not reaching the rank',
            'NOT_ACHIEVE_NUMBER' => 'The cumulative score must be an integer',
            'NOT_ACHIEVE_MIN' => 'Accumulated points must be positive',
            'MEMBERSHIP_NAME_REQUIRED' => 'Please enter membership class name',
            'MEMBERSHIP_NAME_MAX' => 'Member class name exceeds 50 characters',
            'BENEFIT_TITLE_REQUIRED' => 'Please enter a title',
            'BENEFIT_TITLE_MAX' => 'Title cannot exceed 50 characters',
            'BENEFIT_RANK_REQUIRED' => 'Please enter the benefit content of the class',
            'BENEFIT_RANK_MAX' => 'Up to 245 characters',
            'POINT_ACHIEVE_REQUIRED' => 'Please enter your points to qualify',
            'POINT_ACHIEVE_NUMBER' => 'The passing score must be an integer',
            'POINT_ACHIEVE_MIN' => 'Range retention point must be greater than or equal to 0',
            'NOT_CHECK' => 'Please select change',
            'RESET_POINT_REQUIRED' => 'Please enter a reset point',
            'RESET_POINT_NUMBER' => 'Reset point must be an integer',
            'RESET_POINT_MIN' => 'Reset point must be greater than or equal to 0',
            'DEDUCTION_POINT_REQUIRED' => 'Please enter a minus',
            'DEDUCTION_POINT_NUMBER' => 'Minus point must be an integer',
            'DEDUCTION_POINT_MIN' => 'Minus point must be greater than or equal to 0',
            'ADD_ERROR' => 'Add failed',
            'MEMBERSHIP' => 'Member',
            'POINT' => 'point',
            'MEMBERSHIP_POINT_NUMBER' => 'Score scale must be an integer',
            'CANCEL' => 'Cancel',
            'NOTE_CANCEL' => 'Do you want to cancel?',
            'BACK' => 'Back',
            'NOTE_BACK' => 'Do you want to return to the previous page?',
            'YES' => 'Agree',
            'NO' => 'Disagree'
        ]
    ],
    'reward_program' => [
        'create' => [
            'reward_program_name_required' => 'Please enter a program name!',
            'reward_program_name_max' => 'Program name must not exceed 255 characters!',
            'date_start_required' => 'Please select a start date!',
            'date_end_required' => 'Please select an end date!',
            'point_required' => 'Please enter the accumulated points needed to redeem!',
            'point_min' => 'The accumulated points needed to redeem must be greater than 0!',
            'point_digits' => 'Score must be a positive integer!',
            'point_number' => 'Points must be in the correct number format!',
            'type_reward_required' => 'Please select an offer type!',
            'bonus_product_required' => 'Please select a reward product!',
            'product_uom_required' => 'Please select uom!',
            'amount_each_exchange_required' => 'Please enter the amount for each redemption!',
            'amount_each_exchange_min' => 'Amount must be greater than 0!',
            'amount_each_exchange_digits' => 'Amount must be a positive integer!',
            'amount_each_exchange_number' => 'Amount must be in the correct number format!',
            'program_content_required' => 'Please enter program content!',

            'member_program_required' => 'Please select a program!',
            'check_date' => 'End date must be after start date',
            'amount_required' => 'Please enter number of times',
            'amount_min' => 'Number of times must be greater than 0',
            'amount_integer' => 'Number of times must be a positive integer!',
            'rank_min' => 'Must choose at least one rank!',

            'fileName_mimes' => 'This file is not an image file',
            'fileSize_max' => 'File size should not exceed 10MB',

            'choose_program_member' => 'Select membership program',
            'point_accumulation1_error' => 'Enter the number of points needed to redeem',
            'point_accumulation1_error_min' => 'Score must be numeric and greater than 0',
            'play_turn_number_required' => 'Please enter the number of turns per store!',
            'play_turn_number_min_max' => 'The number of turns per store must be from 1 to 999,999,999',
            'reward_program_name_unique' => 'The program name already exists',
        ],
        'edit' => [
            'success' => "Focus program update successful",
            'fail' => 'An error occurred!'
        ],
        'notification_back' => 'Cancellation confirmation',
        'html_popup_page_back' => 'When destroying the information will not be stored and will be deleted forever. Are you sure you want to cancel?',
        'yes_back' => 'Confirm',
        'no_back' => 'No',
    ],
    'brand_loyalty' => [
        'create_success' => 'Add store to :name successfully',
        'create_fail' => 'Add store to :name failed',
        'program_required' => 'Please select a membership program',
        'outlet_unique' => 'Select list whose store already exists in the membership program',
        'notification' => 'Notification',
        'html_popup_page' => 'Stores will be unchecked when switching pages',
        'yes' => 'Confirm',
        'no' => 'Stay on page',
        'html_popup_filter' => 'Stores will be deselected when re-filtering the list',
        'export_outlet' => 'Are you sure you want to export the data',
        'export_yes' => 'Yes',
        'export_no' => 'No'
    ],

    'accumulate_point' => [
        'name_required' => 'Please enter program name',
        'name_max' => 'Program name cannot exceed 255 characters',
        'accumulate_point' => 'Enter cumulative number of points',
        'accumulate_min' => 'Accumulated points must be greater than or equal to 0',
        'diemphanhang' => 'Please enter rating',
        'diemphanhang_min' => 'Grade score must be greater than or equal to 0',
        'diemcothetieu' => 'Enter spendable points',
        'diemcothetieu_min' => 'Scorable points must be greater than or equal to 0',
        'create_success' => 'New created successfully',
        'create_fail' => 'Create failed',
        'update_success' => 'Update successful',
        'update_fail' => 'Update failed',
        'time_start_required' => 'Please enter a start time',
        'time_end_required' => 'Please enter an end time',
        'photo_tracking' => 'Please select a photo program',
        'time_limit' => 'End time must be greater than start time',
        'program_photo_tracking' => 'The validity period of this loyalty program has coincided with the program :name. Please check again',
        'digits_validate' => 'Only positive integers can be entered',
        'survey_id_required' => 'Please select a survey program',
        'survey_id_unique' => 'The loyalty program for this survey has been merged with another program. Please check again.',
        'accumulate_point_min' => 'Grading score must be greater than 0.',
        'available_point_min' => 'The number of points that can be spent must be greater than 0.',
    ],
    'loyalty_order_master' => [
        'add_success' => 'Add success!',
        'add_fail' => 'Add failure!',
        'edit_success' => 'Edit successful!',
        'edit_fail' => 'Edit failed!',
        'add_error' => 'Add failure!',
        'edit_error' => 'Edit failed!',
        'not_null' => 'cannot be blank.',
        'enter_value' => 'Please enter a value.',
        'max_100' => 'Up to 100 characters.',
        'max_50' => 'Up to 50 characters.',
        'enter_campaign_description' => 'The displayed program name cannot be empty.',
        'campaign_description_max_100' => 'Program name to display up to 100 characters.',
        'enter_campaign_code' => 'Program code cannot be empty.',
        'campaign_code_max_50' => 'Up to 50 characters program code.',
        'campaign_code_already_exist' => 'Program code already exists.',
        'enter_loyalty_program_id' => 'Loyalty program cannot be empty.',
        'enter_order_limit' => 'Order limit per customer cannot be empty.',
        'campaign_scheme_code_50' => 'Scheme code up to 50 characters.',
        'campaign_deal_code_50' => 'IO number up to 50 characters.',
        'enter_effective_date' => 'The start date cannot be empty.',
        'enter_end_date' => 'End date cannot be empty.',
        'end_date_after_or_equal' => 'End date must be greater than or equal to start date.',
        'enter_start_reg_date' => 'Registration start date cannot be empty.',
        'enter_end_reg_date' => 'Registration end date cannot be empty.',
        'end_reg_date_after_or_equal' => 'Subscription end date must be greater than subscription start date.',
        'remove_title_popup' => 'Confirm program deletion',
        'remove_content_popup' => 'Are you sure you want to delete this program? Once deleted, the program cannot be restored.',
        'btn_confirm' => 'Confirm',
        'btn_cancel' => 'Cancel',
        'not_remove_title_popup' => 'Cannot delete program',
        'not_remove_content_popup' => 'You do not have permission to delete the program or your program has been approved so it cannot be deleted. Please check again.',
        'remove_success' => 'Delete successful!',
        'remove_fail' => 'Delete failed!',
        'reject_title_popup' => 'Confirm program rejection',
        'reject_content_popup' => 'Once you confirm the rejection of the program, you will no longer be able to edit the program information. Are you sure you want to decline?',
        'release_title_popup' => 'Confirm browsing',
        'release_content_popup' => 'Once you confirm browsing, you will not be able to edit some information about the program you are browsing. Are you sure to approve?',
        'not_release' => 'To be able to approve the program, please set the information for all displayed tabs.',
        'close_title_popup' => 'Confirm end',
        'close_content_popup' => 'Once the program ends, your program will no longer be valid and cannot be edited. Are you sure you want to continue?',
        'error_mes' => 'Error reporting',
        'cancel_title_popup' => 'Confirm cancel update',
        'cancel_content_popup' => 'When you confirm to cancel the update, your content will not be saved. Are you sure you want to continue?',
        'close' => 'Close',
        'input_money' => 'Input amount required',
        'digits_money' => 'Invalid amount',
        'input_point' => 'Request to enter score',
        'digits_point' => 'Invalid number of points',
        'amount' => 'Amount',
        'money' => 'Total money',
        'enter_product_condition' => 'Please add a product/product group with 1 or more products to the product with the attached condition.',
        'enter_product_sell' => 'Please add a product/product group with 1 or more products to the sale.',
        'error_quantity' => 'Number cannot be empty, must be greater than 0 and less than 999,999,999,999.',
        'error_amount' => 'Total amount cannot be empty, must be greater than 0 and less than 999,999,999,999.',
        'error_quantity_uom' => 'The product you added matches the product already in the list. Please check again!',
        'error_name_group' => 'Product group name cannot be empty and can be up to 255 characters.',
        'error_value' => 'Value cannot be empty, must be greater than 0 and less than 999,999,999,999.',
    ],

    'reward_redeem' => [
        'btn_yes' => 'Agree',
        'btn_no' => 'Cancel',
        'confirm_title' => 'Confirmation of paid delivery',
        'confirm_html' => 'Once the gift is confirmed delivered, the status will not be restored. Are you sure you want to continue?',

        'confirm_success' => 'Confirmation successful',
        'confirm_fail' => 'Confirmation failed',
        'confirm_no_redeem' => 'Please select at least 1 gift request',

        'confirm_title_fail' => 'Cannot confirm delivery',
        'confirm_html_fail' => 'The products you are selecting for confirmation are only in pending delivery status. Please double check before confirming delivery.'
    ],
    'budget' => [
        'province' => 'Province/city',
        'district' => 'District',
        'ward' => 'Ward',
        'enter_code' => 'Code cannot be empty.',
        'code_max_length' => 'Code up to 50 characters.',
        'budget_code_unique' => 'Code already exists.',
        'description_max_length' => 'Description up to 200 characters.',
        'reference_code_max_length' => 'Reference code up to 50 characters.',
        'io_number_max_length' => 'IO number up to 50 characters.',
        'max_50' => 'Up to 50 characters.',
        'max_200' => 'Up to 200 characters.',
        'enter_budget_total' => 'Budget cannot be empty.',
        'budget_total_max' => 'Budget max 1000000000000.',
        'enter_budget_limit' => 'The limit cannot be empty.',
        'budget_limit_max' => 'Maximum limit 1000000000000.',
        'enter_outlet' => 'Must have at least 1 outlet.',
        'enter_limit_outlet' => 'Limit cannot be empty.',
        'title_popup_approve' => 'Confirm budget approval',
        'content_popup_approve' => 'Are you sure you want to approve this budget? After approval, the budget will take effect when associated with trade marketing and accumulation programs.',
        'title_popup_reject' => 'Budget rejection confirmation',
        'content_popup_reject' => 'Are you sure you want to reject this budget? Once the disapproval is complete, the budget cannot be edited',
        'limit_min' => 'The limit number of each object must be greater than the sum of the calculated and added numbers.',
        'budget_total_min' => 'Total budget must be greater than the estimated and cumulative sums of all stores and programs that have been tied to this budget.',
        'budget_limit_min' => 'The general limit of the object must be greater than the estimated and added numbers of any stores.',
        'title_popup_remove' => 'Confirm budget deletion',
        'content_popup_remove' => 'Are you sure you want to remove this budget? Once deleted, the budget cannot be recovered',
        'title_popup_not_remove' => 'Budget cannot be deleted',
        'content_popup_not_remove' => 'Please double check your selected budget. You can only delete budgets in "Draft" or "Rejected"',
    ],
    'choose_program' => 'Choose program',
    'cancel_confirm' => 'Cancel confirm',
];