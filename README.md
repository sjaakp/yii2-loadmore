yii2-loadmore
=============

#### Load More button for Yii2 ####

[![Latest Stable Version](https://poser.pugx.org/sjaakp/yii2-loadmore/v/stable)](https://packagist.org/packages/sjaakp/yii2-loadmore)
[![Total Downloads](https://poser.pugx.org/sjaakp/yii2-loadmore/downloads)](https://packagist.org/packages/sjaakp/yii2-loadmore)
[![License](https://poser.pugx.org/sjaakp/yii2-loadmore/license)](https://packagist.org/packages/sjaakp/yii2-loadmore)

**LoadMorePager**, the main part of the **yii2-loadmore** package,
 is a widget that can be used as a pager for a 
 [GridView](https://www.yiiframework.com/doc/api/2.0/yii-grid-gridview "Yii2") or a 
 [ListView](https://www.yiiframework.com/doc/api/2.0/yii-widgets-listview "Yii2")
 of the [Yii 2.0](https://www.yiiframework.com/ "Yii") PHP Framework. In stead of the 
 usual [LinkPager](https://www.yiiframework.com/doc/api/2.0/yii-widgets-linkpager "Yii"),
 a 'Load More' button is rendered. Clicking it, adds a bunch of new items to the 
 list. With every click, the list grows until there are no more items to show.  

A demonstration of **LoadMorePager** is [here](http://www.sjaakpriester.nl/software/loadmore).

## Installation ##

Install **yii2-loadmore** in the usual way with [Composer](https://getcomposer.org/). 
Add the following to the `require` section of your `composer.json` file:

`"sjaakp/yii2-loadmore": "*"` 

or run:

`composer require sjaakp/yii2-loadmore` 

You can manually install **yii2-loadmore** by [downloading the source in ZIP-format](https://github.com/sjaakp/yii2-loadmore/archive/master.zip).

## Using LoadMorePager ##

Use **LoadMorePager** in a **GridView** just by setting the latter's 
 [`pager`](https://www.yiiframework.com/doc/api/2.0/yii-widgets-baselistview#$pager-detail "Yii2") property
 to a configuration array with the former's class, like:

	<?php
	    use yii\grid\GridView;
	    use sjaakp\loadmore\LoadMorePager;
	?>
    ...
    <?= GridView::widget([
        'dataProvider' => ...
        'pager' => [
            'class' => LoadMorePager::class
        ],
        // ...other GridView options, like 'columns'... 
    ])  ?>
	...
        
That's all that's needed for basic functionality. The Load More button will appear as a
 standard link. In a **ListView**, set the `pager` property likewise.
 
## Options ##
 
**LoadMorePager**'s options can be set like so:

	<?php
	    use yii\grid\GridView;
	    use sjaakp\loadmore\LoadMorePager;
	?>
    ...
    <?= GridView::widget([
        'dataProvider' => ...
        'pager' => [
            'class' => LoadMorePager::class,
            'label' => 'Show more data'
        ], 
        // ...other GridView options, like 'columns'... 
    ])  ?>
	...

**LoadMorePager** has four options:

#### label ####

`string` The text of the Load More button. Default: `'Load more'`.

#### id ####

`string` The HTML ID of the Load More button. If not set (default) it will be auto-generated.

#### options ####

`array` The HTML options of the Load More button. Set this to something like
 `[ 'class' => 'btn btn-secondary' ]` to give the button the looks of a real button (assuming that
 you use Bootstrap). Default: `[]` (empty array).
 
#### indicator ####

`string` Optional. The CSS selector for the indicator element(s). While the list is waiting
  for new items, the indicator element(s) get the extra CSS class `'show'`. Great for showing
  a 'spinner' after the Load More button is clicked. Default: `null`.
 
## Refinement 1: summary ##

In its basic setup, **LoadMorePager** will not update the **GridView**'s or **ListView**'s
 summary, if present. To correct that, wrap the `{end}` token in the list's 
 [`summary` setting](https://www.yiiframework.com/doc/api/2.0/yii-widgets-baselistview#$summary-detail "Yii2") 
 with a `<span>` having the class `'summary-end'`. For example:

	<?php
	    use yii\grid\GridView;
	    use sjaakp\loadmore\LoadMorePager;
	?>
    ...
    <?= GridView::widget([
        'dataProvider' => ...,
        'pager' => [
            'class' => LoadMorePager::class,
            'label' => 'Show more data'
        ], 
        'summary' => 'Showing {begin}-<span class="summary-end">{end}</span> of {totalCount} items',
        // ...other GridView options, like 'columns'... 
    ])  ?>
	...
 
## Refinement 2: efficiency

Clicking the Load More button sends an Ajax call to the server, which sends a complete page back to the browser.
  Only a small part is actuallly used. This works, but it could be made quite a bit more efficient by taking
  the following steps.
  
#### Put the list in a separate subview

In stead of the usual view file:

    <?php
        /* loadmore.php */
	    use yii\grid\GridView;
	    use sjaakp\loadmore\LoadMorePager;
	?>
	// other stuff on the page
    ...
    <?= GridView::widget(...) ?>
    ...
    // more other stuff
    ...
    
Create *two* view files, one main view which renders a subview:    

    <?php
        /* loadmore.php */
	?>
	// other stuff on the page
    ...
    <?= $this->render('_loadmore.php', [
        'dataProvider' => $dataProvider
    ]) ?>
    ...
    // more other stuff
    ...
     
The subview:

    <?php
        /* _loadmore.php */
	    use yii\grid\GridView;
	    use sjaakp\loadmore\LoadMorePager;
	?>
	// no other stuff!
    <?= GridView::widget(...) ?>
    
#### Modify the action function in the controller ####

Change the usual:

    public function actionLoadmore()    {
        $dataProvider = ...;

        return $this->render('loadmore', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
into:

    public function actionLoadmore()    {
        $dataProvider = ...;

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_loadmore', [
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('loadmore', [
            'dataProvider' => $dataProvider,
        ]);
    }

This makes the server only render the subview if an Ajax call is made by the
 Load More button.
 
**Important:** if you use this technique, be sure to set an explicit `id` to the **GridView**
or the **ListView**, as well as to the **LoadMorePager**, like so:

	<?php
	    use yii\grid\GridView;
	    use sjaakp\loadmore\LoadMorePager;
	?>
    ...
    <?= GridView::widget([
        'dataProvider' => ...,
        'id' => 'myGrid',
        'pager' => [
            'class' => LoadMorePager::class,
            'id' => 'myPager',
            // ...other LoadMorePager options, like 'label'...
        ], 
        // ...other GridView options, like 'columns'... 
    ])  ?>
	...
  
 
### How do I change the number of returned items?

Do this by modifying the [`pagination` value](https://www.yiiframework.com/doc/api/2.0/yii-data-basedataprovider#$pagination-detail "Yii2") 
 of the list's **dataProvider**, like:
 
     $dataProvider = new ActiveDataProvider([
         'query' => ... ,
         'pagination' => [
             'pageSize' => 12
         ]
     ]);
