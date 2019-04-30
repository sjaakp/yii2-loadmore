<?php
/**
 * MIT licence
 * Version 1.0
 * Sjaak Priester, Amsterdam 29-04-2019.
 *
 * LoadMorePager - Load More button for Yii 2.0 GridView or ListView
 */

namespace sjaakp\loadmore;

use yii\web\AssetBundle;

/**
 * Class LoadMoreAsset
 * @package sjaakp\loadmore
 */
class LoadMoreAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';

    public $js = [
        'loadmore.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
