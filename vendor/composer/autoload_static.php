<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3512e20a96a716fe82449bbb19965cdd
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TBC\\IMK\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TBC\\IMK\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3512e20a96a716fe82449bbb19965cdd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3512e20a96a716fe82449bbb19965cdd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}