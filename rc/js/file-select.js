$.fn.fileSelect = function(options) {
	options = $.extend({}, {
		multiple: false,
		container: '.file-select-container',
		itemCssClass: 'file-select-item',
		thumbClass: 'file-select-thumb',
		maxfiles: false,
		accept: false,
		previewImages: false,
		pasteTarget: false,
	}, options || {});
	
	function isImage(type) {
		return -1 != ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/bmp'].indexOf(type.toLowerCase());
	}
	
	function discard(input) {
		var clone = $(input).clone();
		$(input).replaceWith(clone);
		clone.on('change', init);
	}
	
	function countSelected(input) {
		return $(input)
				.closest(options.container)
				.children('.' + options.itemCssClass).length;
	}
	
	function cleanup(input) {
		return $(input)
				.closest(options.container)
				.children('.' + options.itemCssClass)
				.remove();
	}
	
	function createFileList(file) {
		var list = new ClipboardEvent('').clipboardData || new DataTransfer();
		list.items.add(file);
		return list.files;
	}
	
	function insertAttachment(file) {
		var field = $(options.pasteTarget).get(0);
		insertAtCursor(field, '[attachment:' + file.name + ']');
	}
	
	function bindClick(item, file) {
		if (options.pasteTarget) {
			item.css('cursor', 'pointer');
			item.click(function() {
				insertAttachment(file);
			});
		}
	}
	
	function createItem(input, file, fromClipboard) {
		var i = $('<span></span>');
		i.addClass(options.itemCssClass)
			.attr('data-type', file.type);
		if (isImage(file.type) && options.previewImages && window.FileReader) {
			var tmb = $('<img class="thumbnail" />')
				.attr('title', file.name)
				.addClass(options.thumbClass);
			var reader = new FileReader();
			reader.onload = function (e) {
				tmb.attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
			i.append(tmb).addClass('has-preview');
			bindClick(tmb, file);
		} else {
			var text = $('<span />')
				.text(file.name)
				.addClass(options.thumbClass);
			i.append(text);
			bindClick(text, file);
		}
		i.append('<a href="#" class="delete">&times</a>');
		if (fromClipboard) {
			var fileInput = $(input).clone();
			fileInput.get(0).files = createFileList(file);
			fileInput
				.removeAttr('id')
				.appendTo(i);
		} else {
			$(input)
				.detach()
				.removeAttr('id')
				.appendTo(i);
		}
		return i;
	}
	
	function checkFileType(file) {
		if (options.accept !== false) {
			if (options.accept.indexOf(file.type) == -1) {
				return false;
			}
		}
		return true;
	}
	
	function checkFileLimit(widget) {
		if (options.multiple && false !== options.maxfiles) {
			if (countSelected(widget) >= options.maxfiles) {
				return false;
			}
		}
		return true;
	}
	
	function init() {
		if (this.files.length) {
			var file = this.files[0];
			if (!checkFileType(file)) {
				discard(this);
				alert('File type is not allowed: ' + file.type);
				return true;
			}
			if (!checkFileLimit(this)) {
				discard(this);
				alert('Please, select up to ' + options.maxfiles + ' files');
				return true;
			}
			if (!options.multiple) {
				cleanup(this);
			}
			var p = $(this).parent(), c = $(this).clone();
			var i = createItem(this, file, false);
			p.append(c);
			i.insertBefore(p);
			c.on('change', init);
		}
	}
	
	function paste(widget, file) {
		if (!checkFileType(file)) {
			return false;
		}
		if (!checkFileLimit(widget)) {
			return false;
		}
		if (!options.multiple) {
			cleanup(widget);
		}
		var p = $(widget).parent();
		var i = createItem(widget, file, true);
		i.insertBefore(p);
		return true;
	}
	
	function insertAtCursor(field, value) {
		if (document.selection) {
			field.focus();
			sel = document.selection.createRange();
			sel.text = value;
		} else if (field.selectionStart || field.selectionStart == '0') {
			var start = field.selectionStart;
			var end = field.selectionEnd;
			var val = field.value;
			field.value = val.substring(0, start) + value + val.substring(end, val.length);
		} else {
			field.value += value;
		}
	}
	
	this.each(function() {
		$(this).on('change', init);
		$(this).closest(options.container).on('click', 'a.delete', function() {
			$(this).parent().remove();
			return false;
		});
		if (options.pasteTarget) {
			var widgetId = this.id;
			$(options.pasteTarget).on('paste', function(event) {
				var clipboardData = event.clipboardData || event.originalEvent.clipboardData;
				var items = clipboardData.items;
				for (var i = 0; i < items.length; i++) {
					if (isImage(items[i].type)) {
						var file = items[i].getAsFile();
						var ext = file.name.split('.').pop();
						var date = new Date();
						var name = [
							'image',
							date.getUTCHours(),
							date.getUTCMinutes(),
							date.getUTCSeconds()
						].join('-') + '.' + ext;
						file = new File([file], name, {type: file.type});
						if (paste(document.getElementById(widgetId), file)) {
							insertAttachment(file);
						}
						break;
					}
				}
			});
			$(this).closest(options.container).find('.' + options.thumbClass).each(function() {
				var item = $(this);
				bindClick(item, {
					name: item.attr('title'),
				});
			});
		}
	});
}
