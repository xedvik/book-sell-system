<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pagination Items Per Page
    |--------------------------------------------------------------------------
    |
    | This value determines the default number of items per page when using
    | pagination in the application. Models can override this value by setting
    | the per_page property or by explicitly passing a different value to the
    | paginate() method.
    |
    */
    'per_page' => 15,

    /*
    |--------------------------------------------------------------------------
    | Pagination Template
    |--------------------------------------------------------------------------
    |
    | This setting determines which pagination template will be used when
    | rendering pagination links in the application.
    | Available options: 'bootstrap-4', 'bootstrap-5', 'tailwind'
    |
    */
    'template' => 'bootstrap-5',
];
