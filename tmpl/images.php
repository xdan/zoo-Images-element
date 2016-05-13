<div class="sm-product-page-image">
    <div class="sm-product-page-image-preview">
        <? foreach($images as $k=>$image) { ?>
        <a class="<?=$k ? 'sm-hidden' : ''?>" data-lightbox="group:mygroup1;showNavArrows:1;cyclic:1"  href="<?=$image?>">
            <img src="<?php echo jhtml::_('xdresizer.thumb', $image, 938, 520, 1)?>" alt="<?=$titles[$k]?>">
        </a>
        <? } ?>
    </div>
    <div class="sm-product-page-image-thumbs">
        <div class="sm-product-page-image-thumbs-prev"></div>
        <div class="sm-product-page-image-thumbs-slider">
            <? foreach($images as $k=>$image) { ?>
            <a data-index="<?=$k?>" href="<?=$image?>" class="sm-product-page-image-thumbs-slider-item">
                <img src="<?php echo jhtml::_('xdresizer.thumb', $image, 160, 100, 1)?>" alt="<?=$titles[$k]?>">
            </a>
            <? } ?>
        </div>
        <div class="sm-product-page-image-thumbs-next"></div>
    </div>
</div>