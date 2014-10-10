$.fn.dropdownMenuSelect = function(options) {
	this.each(function() {
		var button = $('#' + options.button);
		var dropdown = $('#' + options.dropdown);
		var hidden = $('#' + options.hidden);
		var thiz = $(this);
		var prev_selection = false;
		var done = false;
		dropdown.find('a[role=menuitem]').click(function() {
			var li = $(this).closest('li');
			if (options.multiple) {
				if (li.is('.null')) {
					dropdown.children('.selected').removeClass('selected');
				} else {
					li.toggleClass('selected');
				}
				return false;
			} else {
				dropdown.children('.selected').removeClass('selected');
				if (!li.is('.null')) {
					li.addClass('selected');
				}
				done = true;
			}
		});
		dropdown.find('button').click(function() {
			done = true;
		});
		dropdown.parent().on('show.bs.dropdown', function() {
			prev_selection = dropdown.children('.selected');
			done = false;
		});
		dropdown.parent().on('hidden.bs.dropdown', function() {
			if (done) {
				var selection = [];
				var labels = [];
				dropdown.find('.selected a').each(function() {
					selection.push($(this).data('value'));
				});
				if (!options.multiple) {
					selection = selection.length > 0 ? selection[0] : '';
					if (false !== options.labels) {
						labels = (selection in options.labels) ? options.labels[selection] : options.emptyLabel;
					} else {
						labels = (selection in options.options) ? options.options[selection] : options.emptyLabel;
					}
				} else {
					for (var i = 0; i < selection.length; i++) {
						if (false !== options.labels) {
							if (selection[i] in options.labels) {
								labels.push(options.labels[selection[i]]);
							}
						} else {
							if (selection[i] in options.options) {
								labels.push(options.options[selection[i]]);
							}
						}
					}
				}
				if (options.type == 2) {
					if (options.multiple) {
						thiz.val(selection.join(options.multipleValueSeparator));
					} else { 
						thiz.val(selection);
					}
				} else {
					if (options.type == 1) {
						if (options.multiple) {
							var name = hidden.data('name');
							hidden.children().remove();
							for (var i = 0; i < selection.length; i++) {
								hidden.append($('<input type="hidden" />').attr('name', name).val(selection[i]));
							}
						} else {
							hidden.val(selection);
						}
					}
					if (options.multiple) {
						if (labels.length == 0) {
							thiz.html(options.emptyLabel);
						} else {
							thiz.html(labels.join(options.multipleLabelSeparator));
						}
					} else {
						if (labels == '') {
							thiz.html(options.emptyLabel);
						} else {
							thiz.html(labels);
						}
					}
				}
				thiz.trigger('selectitem', [selection, labels]);
			} else {
				dropdown.children('.selected').removeClass('selected');
				prev_selection.addClass('selected');
			}
		});
	});
}
