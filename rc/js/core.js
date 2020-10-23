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
	
	$(document.body).on('click', '[data-toggle=quick-create-form]', function() {
		$('.quick-create-form').toggle();
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

	var activeConvertPopover = null;

	$(document.body).on('click', '[data-money-value]', function() {
		if (activeConvertPopover === this) {
			$(this).popover('hide');
			activeConvertPopover = null;
		} else {
			var value = $(this).attr('data-money-value');
			var html = $(this).html();
			if (!isNaN(value)) {
				if (activeConvertPopover !== null) {
					$(activeConvertPopover).popover('hide');
				}
				activeConvertPopover = this;
				var moneyRate = getRecentMoneyRate();
				var $this = $(this);
				$this.popover({
					trigger: 'manual',
					html: true,
					content: function() {
						return '<div class="convert-money"><span>' + html + '</span><em>×</em>' + 
							'<input type="text" class="convert-rate" onchange="updateMoneyConversion(this, ' + value + ')"/>' +
							'<em>=</em><span class="convert-result"></span></div>';
					},
				});
				$this.off('shown.bs.popover').on('shown.bs.popover', function() {
					var popver = $this.data('bs.popover');
					if (popver) {
						popver.$tip.find('.convert-rate').val(moneyRate).trigger('change');
					}
				});
				$this.popover('show');
			}
		}
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
		$(this).ajaxRequest($.param.querystring(url, {'time': time, 'rn': Math.random()}));
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

const getRecentMoneyRate = function() {
	var rate = parseFloat(localStorage.getItem('recentMoneyRate'));
	return isNaN(rate) ? 0 : rate;
}

const updateMoneyConversion = function(el, moneyValue) {
	var value = parseFloat(el.value);
	if (!isNaN(value)) {
		localStorage.setItem('recentMoneyRate', el.value);
		let conversion = (moneyValue * value).toFixed(2);
		$(el).parent().children('.convert-result').html(conversion);
	}
}