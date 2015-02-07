$(function() {
	
	$.fn.tooltip = $.fn.tooltip.noConflict();
	$(document.body).tooltip({
		selector: "[data-toggle=tooltip]",
		placement: 'bottom'
	});
	
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

Array.prototype.union = function(a) 
{
    var result = this.slice(0);
    a.forEach(function(i) { 
		if (result.indexOf(i) == -1) {
			result.push(i); 
		}
	});
    return result;
};

$.fn.notifier = function(url, interval) {
	var time = (new Date()).valueOf() / 1000;
	var context = $(this);
	var tasks = [];
	var base = 0;
	interval = interval || 60000;
	setInterval(function() {
		$(this).ajaxRequest($.param.querystring(url, {'time': time, 'r': Math.random()}));
	}, interval);
	$.ajaxBindings.on('notification.new', function(e, data) {
		if (typeof window.notificationCallback == 'function') {
			data = window.notificationCallback(data, context);
		}
		if (typeof data == 'object') {
			context.find('audio').get(0).play();
			time = (new Date()).valueOf() / 1000;
			var badge = context.find('.badge');
			if (badge.length > 0) {
				tasks = tasks.union(data.task);
			} else {
				tasks = data.task;
				base = data.total - tasks.length;
				badge = $('<span class="badge"></span>');
				badge.appendTo(context.find('a'));
			}
			if (base + tasks.length > 0) {
				badge.removeClass('hidden').text(base + tasks.length);
			} else {
				badge.addClass('hidden');
			}
		}
	});
}
