<?php

return [
    'company' => [
        'index' => 'company.index',
        'create' => 'company.create',
        'store' => 'company.store',
        'show' => 'company.show',
        'edit' => 'company.edit',
        'update' => 'company.update',
        'destroy' => 'company.destroy',
    ],
    'auth' => [
        'index' => 'login',
        'store' => 'login.attempt',
        'destroy' => 'logout',
    ],
    'home' => 'home',
]; 