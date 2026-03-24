<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'css/bootstrap.min.css',
        'css/iziModal.min.css',
        'css/iziToast.min.css',
        'css/style.css',
        'css/style4.css',
        'css/widgets.css',
        'fontawesome-free-7.1.0/css/all.min.css',

        //'css/site.css',
        //'css/bar.css',
        //'css/pignose.calender.css',
        //'css/simplyCountdown.css',
    ];
    public $js = [
        //'js/modernizr.js',   //loading-gif Js
        //'js/example.js',     //Bar-chart
        //'js/script.js',     //profile-widget-dropdown
        //'js/SimpleChart.js', //Graph
        //'js/amcharts.js', //Graph
        //'js/circle_bar.js', //Graph
        //'js/moment.min.js',  //Calender
        //'js/pignose.calender.js',  //Calender
        //'js/simplyCountdown.js',     //Count-down
        //'js/percentage-circles.js',     //pie-chart
        //'js/rumcaJS.js',     //pie-chart
        //'js/popper.min.js',
        //'js/skycons.js',
        //'js/jquery-3.4.1.min.js',

        'fontawesome-free-7.1.0/js/all.min.js',
        'js/iziToast.min.js',     
        'js/iziModal.min.js',     
        'js/bootstrap.bundle.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap5\BootstrapAsset'
    ];

    public function __construct()
    {
        $this->js[] = 'js/AlertResponse.js?v='. time();
        $this->js[] = 'js/main/Search.js?v='. time();
        $this->js[] = 'js/layout.js?v='. time();
        $this->js[] = 'js/MyBox.js?v='. time();
        
        $this->css[] = 'css/styleDB.css?v='. time();
        parent::__construct();
    }
}
