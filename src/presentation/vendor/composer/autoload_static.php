<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd2b05978f9814896d6a967cfe3dd39c8
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Ouzo\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ouzo\\' => 
        array (
            0 => __DIR__ . '/..' . '/letsdrink/ouzo-goodies',
        ),
    );

    public static $prefixesPsr0 = array (
        'W' => 
        array (
            'WSDL\\' => 
            array (
                0 => __DIR__ . '/..' . '/piotrooo/wsdl-creator/src',
            ),
        ),
        'M' => 
        array (
            'Mocks\\' => 
            array (
                0 => __DIR__ . '/..' . '/piotrooo/wsdl-creator/tests',
            ),
        ),
        'F' => 
        array (
            'Factory\\' => 
            array (
                0 => __DIR__ . '/..' . '/piotrooo/wsdl-creator/tests',
            ),
        ),
        'C' => 
        array (
            'Clients\\' => 
            array (
                0 => __DIR__ . '/..' . '/piotrooo/wsdl-creator/examples',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd2b05978f9814896d6a967cfe3dd39c8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd2b05978f9814896d6a967cfe3dd39c8::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitd2b05978f9814896d6a967cfe3dd39c8::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}