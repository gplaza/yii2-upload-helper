<?php

namespace gplaza\uploadutil\assets;

use yii\web\AssetBundle;

class cadViewerAsset extends AssetBundle
{
   public $sourcePath = '@vendor/gplaza/yii2-upload-helper/src/assets';
    
    public $js = [
        'js/cad-viewer.js',
    ];
    public $depends = [
        'kartik\file\FileInputAsset',
    ];
}