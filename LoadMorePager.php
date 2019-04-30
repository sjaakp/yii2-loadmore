<?php
/**
 * MIT licence
 * Version 1.0
 * Sjaak Priester, Amsterdam 29-04-2019.
 *
 * LoadMorePager - Load More button for Yii 2.0 GridView or ListView
 */

namespace sjaakp\loadmore;

use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class Collapse
 * @package sjaakp\loadmore
 */
class LoadMorePager extends Widget
{
    /**
     * @var Pagination - the pagination object that this pager is associated with.
     * Will be set by GridView or ListView.
     */
    public $pagination;

    /**
     * @var array - HTML options for the Load More link.
     */
    public $options = [];

    /**
     * @var string - text of the Load More Link.
     */
    public $label = 'Load more';

    /**
     * @var string - HTML selector for the indicator element.
     * This gets the class 'show' while the list is waiting for new items.
     */
    public $indicator;

    /**
     * @inheritDoc
     * @throws  InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if ($this->pagination === null) {
            throw new InvalidConfigException('The "pagination" property must be set.');
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    public function run()
    {
        $pageCount = $this->pagination->pageCount;
        
        if ($pageCount > 1) {
            $view = $this->view;
            LoadMoreAsset::register($view);

            $id = $this->options['id'];

            $options = [
                'pageCount' => $pageCount,
                'pageParam' => $this->pagination->pageParam
            ];
            if ($this->indicator) $options['indicator'] = $this->indicator;
            $jOpts = Json::encode($options);

            $view->registerJs("$('#$id').loadmore($jOpts);");

            return Html::a($this->label, '#', $this->options);
        }

        return '';
    }
}
