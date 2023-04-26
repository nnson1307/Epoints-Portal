<?php

return [
    'notification' => [
        'cost' => 'Please enter the notification cost',
        'title_required' => 'Please enter the notification title',
        'short_title_required' => 'Please enter a short title display',
        'feature_required' => 'Please enter feature information',
        'max_255' => 'Content cannot exceed 255 characters',
        'action_name_required' => 'Please enter action name',
        'action_name_max' => 'Action name must be less than 20 characters',
        'param_action_name_required' => 'Please select a destination',
        'param_action_name_max' => 'Param for action no more than 300 characters',
        'specific_time_required' => 'Please select a time',
        'specific_time_valid' => 'Input time is only in second',
        'specific_time_numeric' => 'Input time should not exceed 4 characters',
        'specific_time_old' =>
            'Send time must be greater than current time and must be 5 minutes greater than current time',
        'non_specific_time_required' => 'Please enter the corresponding time',
        'content_required' => 'Please enter notification details',
        'end_point_detail_checked' => 'Please select a destination',
        'group_checked' => 'You have not selected the store group. Please try strange',
        'group_id_required' => 'Please select the group to send',
        'greater_5_minute' => 'Send time must be 5 minutes greater than current time',

        'create_success' => 'Successfully created notification',
        'create_fail' => 'An error occurred!',
        'push_api_fail' => 'Notification not sent!',

        'edit_success' => 'Successfully updated notification',
        'over_time' => 'Time to send notifications must be greater than the current time! Please update the time.',
        'sent' => 'Notification has been sent, this operation is not allowed.',

        'TITLE_POPUP' => 'Do you want to delete the notification?',
        'TEXT_POPUP' => 'You will not be able to undo!',
        'HTML_POPUP' => 'When the static content is deleted, the notification cannot be restored.'.
            '<br>Are you sure you want to delete this notification?',
        'YES_BUTTON' => 'Agree to delete!',
        'CANCEL_BUTTON' => 'Do not delete',
        'update-success'=> 'Update successfully !',
        'january' => 'January Year',
        'febuary' => 'Febuary Year',
        'march' => 'March Year',
        'april' => 'April Year',
        'may' => 'May Year',
        'june' => 'June Year',
        'july' => 'July Year',
        'august' => 'August Year',
        'september' => 'September Year',
        'october' => 'October Year',
        'november' => 'November Year',
        'december' => 'December Year',
        'confirm' => 'Confirm',
        'exit' => 'Exit',
        'are-you-sure' => 'Are you sure?',
        'undo_able' => 'You will not be able to Undo!',
        'yes_confirm' => 'Yes, confirm to delete!',
        'error' => 'Error!',
        'exceeded' => 'Exceeded 20 items <br> Please remove some items ',
        'input_content' => 'Enter content',
        'product' => ' product',
        'many_image' => 'Too many images uploaded',
        'incorrect_file' => 'Incorrect file format',
        'delete' => 'Delete',
        'correct_size' => 'Your selected image is not in correct size',
        'maximum' => 'Maximum 08 images are allowed',
        'minimum' => 'Minimum 01 images are allowed',
        'delete_account' => 'Are you sure you want to delete your account?',
        'delete_item_check' => 'Once the item has been Deleted, you will not be able to recover it back',
        'delete_item' => 'Are you sure you want to Delete this item?',
        'yes' => 'Yes!',
        'no_delete' => 'No',
        'check_item' => 'Item has been checked successfully',
        'want_delete' => 'Are you sure you want to Delete?',
        'recovered_back' => 'Your data will not be recovered back!',
        'confirm_delete' => 'Confirm to Delete',
        'deleted_successfully' => 'Deleted successfully',
        'delete_success' => 'Your data has been Deleted',
        'not_correct_size' => 'Your selected image is not in correct size. Please select another one',
        'add_fail' => 'Input failed!',
        'add_success' => 'Input success!',
        'edit_successfully' => 'Update successfully!',
        'edit_fail' => 'Update failed!',
        'change_status' => 'Status change successfully!',
        'delete_your_account' => 'Do you want to Delete your account?',
        'account_delete_confirm' => 'Once your account has been Deleted, you will not be able to recover it back.',
        'sure_delete_account' => 'Are you sure you want to delete your account?',
        'password_required' => 'Please enter a password',
        'min_max_password' => 'Password must be between 8-20 characters',
        'min_max_password_text_number' => 'Please enter a password between 8 and 20 characters including letters and numbers',
    ],
    'faq_group' => [
        'faq_group_title_required' => 'Please enter content group name',
        'faq_group_title_max' => 'Content group name no more than 250 characters',
        'faq_group_title_unique' => 'Content group name already exists',
        'faq_group_position_required' => 'Please enter location',
        'faq_group_position_number' => 'Please enter only whole numbers',
        'faq_group_position_unique' => 'This location has already been assigned',
        'faq_group_position_min' => 'Minimum position is 1',
        'faq_group_position_max' => 'The largest position is 1,000,000',
        'IS_DELETED' => 'Data has been deleted',
        'ERROR' => 'An error occurred',
        'TITLE_POPUP' => 'Do you want to delete the content group?',
        'TEXT_POPUP' => 'You will not be able to undo!',
        'HTML_POPUP' => 'When a content group is deleted, it cannot be restored.'.
            '<br>Are you sure you want to delete this content group?',
        'YES_BUTTON' => 'Agree to delete!',
        'CANCEL_BUTTON' => 'Do not delete',
    ],
    'faq' => [
        'faq_title_required' => 'Please enter a title',
        'faq_title_unique' => 'The title of this page already exists',
        'faq_title_max' => 'Title cannot exceed 250 characters',
        'faq_title_required_vi' => 'Please enter a title (VI)',
        'faq_title_unique_vi' => 'The title (VI) of this page already exists',
        'faq_title_max_vi' => 'Title (VI) cannot exceed 250 characters',
        'faq_title_required_en' => 'Please enter a title (EN)',
        'faq_title_unique_en' => 'The title (EN) of this page already exists',
        'faq_title_max_en' => 'Title (EN) cannot exceed 250 characters',
        'faq_group_required' => 'Please select content group',
        'faq_type_only' => 'This page  already has content',
        'faq_position_number' => 'Please enter only whole numbers',
        'faq_position_unique' => 'This location has already been assigned',
        'faq_position_required' => 'Please enter location',
        'faq_group_position_min' => 'Minimum position is 1',
        'faq_group_position_max' => 'The largest position is 1,000,000',
        'IS_DELETED' => 'Data has been deleted',
        'ERROR' => 'An error occurred',
        'TITLE_POPUP' => 'Do you want to delete the question details?',
        'TEXT_POPUP' => 'You will not be able to undo!',
        'HTML_POPUP' => 'Once the question details are deleted, the details of the question cannot be restored.'.
            '<br>Are you sure you want to delete this question details?',
        'YES_BUTTON' => 'Agree to delete!',
        'CANCEL_BUTTON' => 'Do not delete',
    ],
    'content' =>[
        'Import_content' =>'Import content ...',
        'Delete' =>'Delete successful',
        'confirm' =>'Confirm',
        'exit' =>'Exit',
        'month_1' =>'Month 1 year',
        'month_2' =>'Month 2 year',
        'month_3' =>'Month 3 year',
        'month_4' =>'Month 4 year',
        'month_5' =>'Month 5 year',
        'month_6' =>'Month 6 year',
        'month_7' =>'Month 7 year',
        'month_8' =>'Month 8 year',
        'month_9' =>'Month 9 year',
        'month_10' =>'Month 10 year',
        'month_11' =>'Month 11 year',
        'month_12' =>'Month 12 year',
        'T2' =>'MON',
        'T3' =>'TUE',
        'T4' =>'WED',
        'T5' =>'THU',
        'T6' =>'FRI',
        'T7' =>'SAT',
        'CN' =>'SUN',
        'ERR' =>"Error",
        'ERR_max_img' =>"You have loaded too many images",
        'ERR_file' =>"The file is not valid",
        'ERR_max_size' =>"The image selected is not the right size. Please click Delete to add another photo.",
        'ERR_max_size_2m' =>'Image size exceeds 2MB',
        'ERR_changer' =>'Password change failed!',
        'ERR_changer_fail' =>'Change password fail!',
        'STT_change_sus' =>'Password change successful!',
        'CUSTOM_RANGE' =>'Custom range',
        'TO_DAY' =>'To day',
        'YESTERDAY' =>'Yesterday',
        '7_DAY_AGO' =>'Last 7 days',
        '30_DAY_AGO' =>'Last 30 days',
        'THIS_MONTH' =>'This month',
        'LAST_MONTH' =>'Last month',

    ]
];