<?php
return [
    'index' => [
        'NOTIFICATION' => 'Notification',
        'NOTIFICATION_LIST' => 'Notification list',
        'from' => 'From ',
        'come' => 'come ',
        'not-processed' => 'Not processed',
        'on-delivery' => 'On delivery',
        'deliver-claimant' => 'Completed (deliver claimant)',
        'partial-delivery' => 'Completed (partial delivery)',
        'cancel' => 'Cancel',
        'sell-status' => 'Sell status',
        'search' => [
            'TITLE' => 'Title',
            'IS_SEND' => [
                'DEFAULT' => 'Notification status',
                'SENT' => 'Sent',
                'WAIT' => 'Send pending',
                'DONT_SEND' => 'Send failed'
            ],
            'IS_ACTIVED' => [
                'DEFAULT' => 'Activity',
                'ACTIVE' => 'Active',
                'NON_ACTIVE' => 'Inactive'
            ],
            'TIME' => 'Sent Timestamp',
            'BTN_SEARCH' => 'Search',
            'BTN_REMOVE' => 'Delete'
        ],
        'table' => [
            'header' => [
                'TITLE' => 'Title',
                'NOTIFICATIONS_IS_SENT' => 'Sent notifications',
                'RATE_READ_NOTIFICATION' => 'Notification ratio',
                'SEND_TIME' => 'Timestamp',
                'ACTIVE' => 'Activity',
                'ACTION' => 'Action',
                'IS_SEND' => 'Notification status'
            ],
            'BTN_EDIT' => 'Edit notification',
            'BTN_DELETE' => 'Delete notification',
            'BTN_ADD' => 'Create notification',
            'BTN_DETAIL' => 'Notification details'
        ]
    ],
    'create' => [
        'SEND_NOTIFICATION' => 'Send notification',
        'CREATE_NOTIFICATION' => 'Create new notification',
        'BTN_STORE_RELOAD' => 'Save and create new',
        'BTN_STORE_EXIT' => 'Save and exit',
        'BTN_CANCEL' => 'Cancel',
        'ADD_BRAND' => 'Add a brand',
        'form' => [
            'header' => [
                'INFO_RECEIVER' => 'Receiver\'s information',
                'CONTENT' => 'Notification content',
                'ACTION' => 'Action options',
                'SCHEDULE' => 'Schedule to send notifications'
            ],
            'placeholder' => [
                'TITLE' => 'Please enter the message title ...',
                'SHORT_TITLE' => 'Short titles show up on the notification page ...',
                'ACTION_NAME' => 'Please enter action name ...',
                'END_POINT_DETAIL' => '+ Select detail destination',
                'SPECIFIC_TIME' => 'Select time',
                'NON_SPECIFIC_TIME' => 'Time input'
            ],
            'RECEIVER' => 'Receiver',
            'BACKGROUND' => 'Background',
            'TITLE' => 'Notification title',
            'SHORT_TITLE' => 'Title summary',
            'FEATURE' => 'Key notification detail',
            'CONTENT' => 'Notification details',
            'ACTION_NAME' => 'Action name',
            'CONTENT_GROUP' => 'Content group',
            'END_POINT' => 'Destination',
            'END_POINT_DETAIL' => 'Destination details',
            'SCHEDULE' => 'Sent notifications timestamp',
            'SEND_ALL_USER' => 'Send to all Mystore users',
            'SEND_GROUP' => 'Sent to selected client group',
            'BTN_ADD_SEGMENT' => 'Select a store group',
            'SEND_NOW' => 'Send immediately',
            'SEND_SCHEDULE' => 'Send notification at optional time',
            'SPECIFIC_TIME' => 'Exact time',
            'NON_SPECIFIC_TIME' => 'Relative time',
            'HOUR' => 'Hours',
            'MINUTE' => 'Minute',
            'DAY' => 'Day',
            'ACTION_GROUP' => [
                'ACTION' => 'Action',
                'NON_ACTION' => 'No action'
            ]
        ],
        'detail_form' => [
            'brand' => [
                'title' => 'Select destination brand',
                'header' => [
                    'LOGO' => 'Logo',
                    'BRAND_NAME' => 'Brand name',
                    'BRAND_CODE' => 'Brand code',
                    'COMPANY_NAME' => 'Company name',
                    'LINK' => __('Link'),
                    'STATUS' => 'Status',
                    'IS_PUBLISHED' => 'Shown on app'
                ],
                'placeholder' => [
                    'BRAND_NAME' => 'Brand name',
                    'BRAND_CODE' => 'Brand code',
                    'COMPANY_NAME' => 'Company name',
                    'STATUS' => 'Status',
                    'IS_PUBLISHED' => 'Show on app'
                ],
                'BTN_SEARCH' => 'Search',
                'IS_ACTIVATED' => [
                    'YES' => 'Enable interaction',
                    'NO' => 'Disable interaction'
                ],
                'IS_PUBLISHED' => [
                    'YES' => 'Yes',
                    'NO' => 'No'
                ]
            ],
            'order' => [
                'title' => 'Choose an order',
                'index' => [
                    'LIST_ORDER'=>'List of orders',
                    'ACTION'=>'Action',
                    'EDIT'=>'Edit product',
                    'REMOVE'=>'Delete product',
                    'CHANGE_STATUS'=>'Status change was successful',
                    'ORDER_CODE' => 'Order number',
                    'CUSTOMER_NAME' => 'Customer name',
                    'PHONE' => 'Phone number',
                    'STORE_NAME' => 'DMS store name',
                    'BRAND_COMPANY' => 'Customer / company code',
                    'PRODUCT_NAME' => 'Product\'s name',
                    'SKU' => 'SKU code',
                    'PROVINCE' => 'City',
                    'CHOOSE_PROVINCE' => 'Select a province',
                    'DISTRICT' => 'District',
                    'CHOOSE_DISTRICT' => 'Select a district',
                    'ADDRESS' => 'DMS address',
                    'TIME_ORDER' => 'Booking time',
                    'PLACEHOLDER_TIME' => 'Start date - End date',
                    'TIME_SHIP' => 'Delivery time',
                    'SEARCH' => 'Search',
                    'RESET' => 'Delete',
                    'TOTAL_MONEY' => 'Total',
                    'STATUS' => 'Order status',
                    'BTN_ADD' => 'Add order',
                    'BTN_CLOSE' => 'Cancel',
                    'BTN_SEARCH' => 'Search'
                ]
            ],
            'market' => [
                'title' => 'Select promotion',
                'index' => [
                    'CAMPAIGN_DESCRIPTION'=>'Promotion name',
                    'CAMPAIGN_TYPE'=>'Type of promotion',
                    'IS_DISPLAY'=> [
                        'ON' => 'Yes',
                        'OFF' => 'No',
                        'TITLE' => 'Shown on app'
                    ],
                    'BTN_SEARCH' => 'Search',
                    'BTN_ADD' => 'More promotion',
                    'BTN_CLOSE' => 'Cancel',
                    'TITLE_CAMPAIGN' => 'List of Trade Marketin promotions',
                    'CAMPAIGN_NAME' => 'Promotion name',
                    'OUTLET' => 'Shop name',
                    'PRODUCT_NAME' => 'Product\'s name',
                    'SELLER_SKU' => 'Seller SKU',
                    'PRODUCT_CODE' => 'Displayed prduct code',
                    'STATUS' => 'Status',
                    'TIME_MARKETING' => 'Promotion duration',
                    'TIME_REGISTER' => 'Register duration',
                    'PROMOTION' => 'On-Invoice',
                    'DISPLAY' => 'Display',
                    'LOYALTY' => 'Accummulative',
                    'SURVEY' => 'Survey',
                    'STOCK_COUNT' => 'Stock Count',
                    'RUNNING' => 'Running',
                    'END' => 'Done',
                    'TABLE_BANNER' => 'Banner',
                    'TABLE_CAMPAIGN_NAME' => 'Promotion name',
                    'TABLE_CAMPAIGN_TYPE' => 'Promotion type',
                    'TABLE_FEATURE' => 'Display features',
                    'TABLE_PRODUCT' => 'Product',
                    'TABLE_OUTLET' => 'Shop name',
                    'TABLE_TIME_REGISTER' => 'Register duration',
                    'TABLE_TIME_MARKETING' => 'Promotion duration',
                    'TABLE_RUNNING' => 'Running',
                    'TABLE_HIEU_LUC' => 'Activated',
                    'TABLE_DISPLAY' => 'Display',
                    'TABLE_EDIT' => 'Edit',
                ]
            ],
            'product' => [
                'title' => 'Product list',
                'index' => [
                    'CAMPAIGN_DESCRIPTION'=>'Promotion name',
                    'BTN_ADD' => 'Add product'
                ]
            ],
            'faq' => [
                'title' => 'Choose support',
                'header' => [
                    'TITLE' => 'Supporting content',
                    'GROUP_TITLE' => 'Supporting content group',
                    'GROUP_POSITION' => 'Display location',
                    'STATUS' => 'Display status'
                ],
                'placeholder' => [
                    'TITLE' => 'Title'
                ],
                'BTN_ADD' => 'Add support',
                'BTN_CLOSE' => 'Cancel',
                'FAQ' => 'Q&A',
                'IS_ACTIVATED' => [
                    'YES' => 'Activate',
                    'NO' => 'Not activated'
                ],
                'POLICY' => 'Privacy Policy',
                'TERMS' => 'Terms of use',
                'GROUP' => 'Supporting content group'
            ]
        ],
        'group' => [
            'title' => 'Select product group',
            'header' => [
                'NAME' => 'Store group name',
                'TYPE' => 'Group type',
                'TIME' => 'Created time-field'
            ],
            'placeholder' => [
                'NAME' => 'Store group name',
                'TYPE' => 'Group type',
                'TIME' => 'Created time-field'
            ],
            'type' => [
                'USER_DEFINE' => 'Group definition',
                'AUTO' => 'Automatic group'
            ],
            'BTN_ADD' => 'Add store group',
            'BTN_CLOSE' => 'Cancel',
            'BTN_SEARCH' => 'Search'
        ]
    ],
    'edit'=>[
        'EDIT_NOTIFICATION' => 'Edit notification'
    ],
    'detail'=>[
        'DETAIL_NOTIFICATION' => 'Notification details',
        'BACK' => 'Back to list'
    ]
];
