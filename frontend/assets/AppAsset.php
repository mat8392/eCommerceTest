<?php

namespace frontend\assets;

use yii\web\AssetBundle;
/**
 * Main frontend application asset bundle.
 */


class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $css = [
        'css/site.css',
        'css/bootstrap.min.css',
        'css/bootstrap-responsive.min.css',
        'css/bootstrappage.css',
        'css/flexslider.css',
        'css/main.css',
    ];

    public $js = [
        'js/common.js', 
        'js/jquery.scrolltotop.js',
        'js/superfish.js',
        'js/jquery.flexslider-min.js',               
        'js/bootstrap.min.js',
        'js/jquery-1.7.2.min.js',
        'js/jquery-1.9.1.min.js',     
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
