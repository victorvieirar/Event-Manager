<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite96af9a0455e6a920a3bd70d01324c9c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PagSeguro\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PagSeguro\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagseguro/pagseguro-php-sdk/source',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite96af9a0455e6a920a3bd70d01324c9c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite96af9a0455e6a920a3bd70d01324c9c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
