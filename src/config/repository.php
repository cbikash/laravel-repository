<?php

/*
|--------------------------------------------------------------------------
| Vxsoft Laravel Repository Config
|--------------------------------------------------------------------------
*/
return [
   /*
   |--------------------------------------------------------------------------
   | Generator Config
   |--------------------------------------------------------------------------
   */
    'generator'  => [
        'basePath'      => app()->path(),
        'rootNamespace' => 'App\\',
        'paths'         => [
            'models'       => 'Models',
            'repositories' => 'Http/Repositories',
            'interfaces'   => 'Interfaces',
            'controllers'  => 'Http/Controllers',
        ]
    ]
];