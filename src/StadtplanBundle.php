<?php
namespace matuzo\wmstadtplan;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class StadtplanBundle extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@matuzo/wmstadtplan/assets';

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            ['stadtplan.js', 'type' => 'module'],
        ];

        $this->css = [
            'stadtplan.css'
        ];

        parent::init();
    }
}