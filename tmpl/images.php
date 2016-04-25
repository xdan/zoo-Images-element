<div class="sm-product-page-image">
    <div class="sm-product-page-image-preview">
        <img src="<?php echo jhtml::_('xdresizer.thumb', $images[0], 938, 520, 1)?>" alt="">
    </div>
    <div class="sm-product-page-image-thumbs">
        <div class="sm-product-page-image-thumbs-prev"></div>
        <div class="sm-product-page-image-thumbs-slider">
            <? foreach($images as $k=>$image) { ?>
            <div class="sm-product-page-image-thumbs-slider-item">
                <img src="<?php echo jhtml::_('xdresizer.thumb',$image, 160, 100, 1)?>" alt="">
            </div>
            <? } ?>
        </div>
        <div class="sm-product-page-image-thumbs-next"></div>
    </div>
</div>