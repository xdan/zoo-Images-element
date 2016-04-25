(function ($) {    
    $(function(){
        $('.sm-product-page-image').each(function () {
            var $box = $(this),
                $previews = $box.find('.sm-product-page-image-preview a'),
                $items = $box.find('.sm-product-page-image-thumbs-slider-item');
            $items.on('click', function () {
                var index = parseInt($(this).data('index'), 10);
                $previews.addClass('sm-hidden');
                $previews.eq(index).removeClass('sm-hidden');
                return false;
            });
            $(this).find('.sm-product-page-image-thumbs-prev,.sm-product-page-image-thumbs-next').on('click', function () {
                if (this.className.indexOf('next') !== -1) {
                    $items.eq(0).insertAfter($items.eq(-1));
                } else {
                    $items.eq(-1).insertBefore($items.eq(0));
                }
                $items = $box.find('.sm-product-page-image-thumbs-slider-item');
            });
        });
    });
} (jQuery));