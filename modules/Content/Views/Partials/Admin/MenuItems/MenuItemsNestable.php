<?php

use Modules\Content\Models\Taxonomy;
use Modules\Content\Support\Facades\ContentLabel;

// We will sort the items collection with the same algorithm as in the real widget.
$items->sort(function ($a, $b)
{
    if ($a->menu_order === $b->menu_order) {
        return strcmp($a->title, $b->title);
    }

    return ($a->menu_order < $b->menu_order) ? -1 : 1;
});

?>

<ol class="dd-list">
    <?php foreach ($items as $item) { ?>
    <?php $label = ContentLabel::get($type = $item->menu_item_object, 'name', __d('content', 'Unknown [{0}]', $type)); ?>
    <?php if (empty($title = $item->title) && ! is_null($instance = $item->instance())) { ?>
    <?php $title = ($instance instanceof Taxonomy) ? $instance->name : $instance->title; ?>
    <?php } ?>
    <li class="dd-item dd3-item" data-id="<?= $itemId = $item->id; ?>">
        <div class="dd-handle dd3-handle"> </div>
        <div class="dd3-content">
            <div class="pull-left" style="margin-top: 5px;"><?= $label; ?> : <?= $title; ?></div>
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#modal-delete-dialog" data-id="<?= $itemId; ?>" title="<?= __d('content', 'Delete this Menu Item'); ?>" role="button"><i class="fa fa-remove"></i></a>
                <a class="btn btn-sm btn-success" href="#" data-toggle="modal" data-target="#modal-edit-dialog" data-id="<?= $itemId; ?>" data-name="<?= $title; ?>" title="<?= __d('content', 'Edit this Menu Item'); ?>" role="button"><i class="fa fa-pencil"></i></a>
            </div>
        </div>

        <?php $children = $item->children()->get(); ?>

        <?php if (! $children->isEmpty()) { ?>
        <?= View::make('Modules/Content::Partials/Admin/MenuItems/MenuItemsNestable', array('menu' => $menu, 'items' => $children))->render(); ?>
        <?php } ?>
    </li>
    <?php } ?>
</ol>
