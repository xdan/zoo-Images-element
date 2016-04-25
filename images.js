/*global jQuery,SqueezeBox*/
(function ($) {
	"use strict";
	$(function () {
		var root = $('.images_selector_by_xdsoft').data('root');
		function trigger() {
			var images = [], titles = [];
			$('.images_selector_by_xdsoft input.image_selector_field').each(function () {
				var box = $(this).closest('div.images_item'), preview = box.find('.image-preview');
				if (this.value) {
					if (!preview.find('img').length) {
						preview.append('<img>');
					}
					preview.find('img').attr('src', root + this.value);
				} else {
					preview.find('img').remove();
				}
				images.push(this.value);
			});
			$('.images_selector_by_xdsoft input.image_title_field').each(function () {
				titles.push(this.value);
			});
			$('.images_collector_field').val(images.join('|'));
			$('.image_titles_collector_field').val(titles.join('|'));
			return false;
		}
		$('.images_selector_by_xdsoft').on('click', '.copy', function () {
			var that = this, box = $(that).closest('div.images_item'), clone = box.clone();
			clone.find('input').attr('id', clone.find('input').attr('id').replace(/([0-9]+)$/, $('.images_selector_by_xdsoft input.image_selector_field').length));
			clone.find('a.modal').attr('href', clone.find('a.modal').attr('href').replace(/fieldid=xdsoft_image([0-9]+)&/, 'fieldid=' + clone.find('input').attr('id') + '&'));
			clone.find('a[onclick]').attr('onclick', clone.find('a[onclick]').attr('onclick').replace(/xdsoft_image([0-9]+)/, clone.find('input').attr('id')));
			box.after(clone);
			SqueezeBox.assign(clone.find('a.modal').get(), {
				parse: 'rel'
			});
			trigger();
			return false;
		});
		$('.images_selector_by_xdsoft').on('click', '.delete', function () {
			var that = this, box = $(that).closest('div.images_item');
			box.remove();
			trigger();
			return false;
		});
		$('.images_selector_by_xdsoft').on('change', 'input.image_selector_field,input.image_title_field', trigger);
	});
}(jQuery));