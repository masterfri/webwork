$(function() {
	
	$.fn.tooltip = $.fn.tooltip.noConflict();
	$(document.body).tooltip({
		selector: "[data-toggle=tooltip]",
		placement: 'bottom'
	});
	
	$.fn.tagval = function() {
		var val = [];
		$(this).each(function() {
			if ('' != this.value) {
				$(this.value.split(',')).each(function() {
					val.push(this);
				});
			}
		})
		return val;
	}
	
	$(document.body).on('click', '[data-toggle=search-form]', function() {
		$('.search-form').toggle();
		return false;
	});
	
	$(document.body).on('submit', '[role=search-form]', function() {
		var target = $(this).attr('data-target');
		var type = $(this).attr('data-target-type');
		if ('listview' == type) {
			$.fn.yiiListView.update(target, {
				url: location.href,
				data: $(this).serialize()
			});
		} else {
			$.fn.yiiGridView.update(target, {
				url: location.href,
				data: $(this).serialize()
			});
		}
		return false;
	});

});
