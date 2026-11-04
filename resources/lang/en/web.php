<?php

return [
    // General Application Translations
    'app' => [
        'system_name' => 'Umrah Offices & Bus Booking Management System',
    ],

    // Sidebar
    'sidebar' => [
        'home' => 'Home',
        'user_management' => 'User Management',
        'users' => 'Users',
        'offices' => 'Offices',
        'buses' => 'Buses',
        'bookings' => 'Bookings',
        'reports' => 'Reports',
        'settings' => 'Settings',
        'languages' => 'Languages'
    ],

    // Authentication
    'auth' => [
        'login' => 'Login',
        'logout' => 'Logout',
        'email' => 'Email',
        'password' => 'Password',
        'remember_me' => 'Remember Me',
        'forgot_password' => 'Forgot Password?',
        'reset_password' => 'Reset Password'
    ],

    // Notifications
    'notifications' => [
        'title' => 'Notifications',
        'unread' => 'unread',
        'view_all' => 'View All Notifications',
        'no_notifications' => 'No notifications',
        'messages' => [
            'new_device' => 'New device registered',
            'booking_confirmed' => 'Booking confirmed',
            'booking_cancelled' => 'Booking cancelled',
            'new_message' => 'New message'
        ]
    ],

    // Dashboard Translations
    'dashboard' => [
        'title' => 'Cat Shop Booking System',
        'welcome' => 'Welcome to Cat Shop Dashboard',
        'stats' => [
            'verification_requests' => 'Verification Requests',
            'new_users' => 'New Users',
            'active_users' => 'Active Users',
            'total_users' => 'Total Users',
            'awaiting_review' => 'Awaiting Review',
            'this_week' => 'This Week',
            'activity_rate' => 'Activity Rate :value%',
            'increase_percentage' => 'Increase by :value%',
            'last_30_days' => 'Last 30 Days'
        ],
        'recent_activities' => [
            'title' => 'Recent Activities',
            'new_booking' => 'New booking added',
            'booking_cancelled' => 'Booking cancelled',
            'user_modified' => 'User data modified'
        ]
    ],

    // Status
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'pending' => 'Pending',
        'new' => 'New'
    ],

    // Users
    'users' => [
        'management' => 'User Management',
        'management_description' => 'Manage and organize user accounts in the system',
        'search_placeholder' => 'Search for a user...',
        'add_new' => 'Add New User',
        'roles' => [
            'admin' => 'System Admin',
            'office' => 'Office',
            'operator' => 'Operator',
            'user' => 'User'
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'pending' => 'Pending'
        ],
        'verification' => [
            'verified' => 'Verified',
            'pending' => 'Pending',
            'rejected' => 'Rejected'
        ]
    ],

    // Table
    'table' => [
        'user' => 'User',
        'email' => 'Email',
        'phone' => 'Phone',
        'role' => 'Role',
        'verification_status' => 'Verification Status',
        'status' => 'Status',
        'actions' => 'Actions',
        'avatar' => 'Avatar'
    ],

    // Actions
    'actions' => [
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete'
    ],

    // Modals
    'modals' => [
        'add_user' => 'Add New User',
        'edit_user' => 'Edit User',
        'close' => 'Close',
        'confirm' => 'Confirm',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'save_changes' => 'Save Changes',
        'delete' => 'Delete',
        'loading' => 'Loading...'
    ],

    // Messages
    'messages' => [
        'confirm_status_change' => 'Are you sure you want to change the user status?',
        'confirm_delete' => 'Are you sure you want to delete this user?',
        'success' => 'Operation completed successfully',
        'error' => 'An error occurred during the operation',
        'confirm_action' => 'Are you sure you want to perform this action?'
    ],

    // Forms
    'forms' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'password' => 'Password',
        'password_hint' => 'Leave empty if you don\'t want to change the password',
        'role' => 'Role',
        'avatar' => 'Avatar',
        'verification_status' => 'Verification Status',
        'status' => 'Status',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'save_changes' => 'Save Changes',
        'choose_file' => 'Choose File',
        'no_file_chosen' => 'No file chosen',
        'current_avatar' => 'Current Avatar',
        'no_avatar' => 'No Avatar',
        'new_avatar_preview' => 'New Avatar Preview'
    ],

    // Verification
    'verification' => [
        'pending' => 'Pending',
        'verified' => 'Verified',
        'rejected' => 'Rejected'
    ],

    // Chat
    'chat' => [
        'title' => 'Messages',
        'unread' => 'new messages',
        'view_all' => 'View All Messages',
        'messages' => [
            'new_message' => 'New Message',
            'sample_message' => 'Hello, can I inquire about bus booking?'
        ]
    ],
    
    // Time
    'time' => [
        'minutes_ago' => 'minutes ago',
        'hours_ago' => 'hours ago',
        'days_ago' => 'days ago',
        'now' => 'now'
    ],

    // Devices
    'devices' => [
        'device_number' => 'Device #:number'
    ],

    // Verification Status
    'verification_status' => [
        'verified' => 'Verified',
        'pending' => 'Pending',
        'rejected' => 'Rejected'
    ],

    // Web Roles
    'web_roles' => [
        'admin' => 'System Admin',
        'office' => 'Office',
        'operator' => 'Operator',
        'user' => 'User'
    ],

    // Roles
    'roles' => [
        'admin' => 'System Admin',
        'office' => 'Office',
        'operator' => 'Operator',
        'user' => 'User'
    ],

    // Filters
    'filters' => [
        'status' => 'Status',
        'role' => 'Role',
        'verification' => 'Verification Status',
        'date' => 'Date',
        'date_options' => [
            'today' => 'Today',
            'week' => 'Last Week',
            'month' => 'Last Month'
        ]
    ],

    // Languages Management
    'languages' => [
        'management' => 'Languages Management',
        'management_description' => 'Manage and organize available languages in the system',
        'add_new' => 'Add New Language',
        'search_placeholder' => 'Search languages...',
        'total_languages' => 'Total Languages',
        'active_languages' => 'Active Languages',
        'default_language' => 'Default Language',
        'total_translations' => 'Total Translations',
        'multilingual' => 'Multilingual',
        'available' => 'Available for Use',
        'across_languages' => 'Across All Languages',
        
        'table' => [
            'flag' => 'Flag',
            'code' => 'Language Code',
            'name' => 'Language Name',
            'native_name' => 'Native Name',
            'translations' => 'Translations',
            'status' => 'Status',
            'actions' => 'Actions'
        ],

        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'default' => 'Default'
        ],

        'forms' => [
            'code' => [
                'label' => 'Language Code',
                'placeholder' => 'Example: en'
            ],
            'name' => [
                'label' => 'Language Name',
                'placeholder' => 'Example: English'
            ],
            'native_name' => [
                'label' => 'Native Name',
                'placeholder' => 'Example: English'
            ],
            'flag' => [
                'label' => 'Language Flag',
                'current' => 'Current Flag',
                'no_flag' => 'No Flag'
            ],
            'status' => [
                'label' => 'Status',
                'active' => 'Active',
                'inactive' => 'Inactive'
            ],
            'is_default' => [
                'label' => 'Default Language',
                'yes' => 'Yes',
                'no' => 'No'
            ]
        ],

        'modals' => [
            'add' => [
                'title' => 'Add New Language'
            ],
            'edit' => [
                'title' => 'Edit Language'
            ]
        ],

        'messages' => [
            'confirm_status' => 'Are you sure you want to change the language status?',
            'confirm_delete' => 'Are you sure you want to delete this language?',
            'loading' => 'Loading...',
            'success' => [
                'add' => 'Language added successfully',
                'update' => 'Language updated successfully',
                'delete' => 'Language deleted successfully',
                'status' => 'Language status updated successfully'
            ],
            'error' => [
                'add' => 'Error occurred while adding the language',
                'update' => 'Error occurred while updating the language',
                'delete' => 'Error occurred while deleting the language',
                'status' => 'Error occurred while updating language status',
                'fetch' => 'Error occurred while fetching language data'
            ]
        ]
    ]
]; 