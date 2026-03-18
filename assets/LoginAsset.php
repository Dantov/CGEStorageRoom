<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function __construct( array $config = [] )
    {
        parent::__construct( $config );

        $this->css = [
            'css/bootstrap.min.css',
            'css/style.css?v=' . time(),
            'fontawesome-free-7.1.0/css/all.min.css',
        ];

        $this->js = [
            //'js/jquery-3.4.1.min.js',
            'js/bootstrap.bundle.min.js',
            'fontawesome-free-7.1.0/js/all.min.js',
        ];
    }
}
