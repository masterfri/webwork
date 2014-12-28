$.fn.fileSelect = function(options) {
	options = $.extend({}, {
		multiple: false,
		container: '.file-select-container',
		itemCssClass: 'file-select-item',
		maxfiles: false,
		accept: false,
		previewImages: false,
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
	
	function createItem(input, file) {
		var i = $('<span></span>');
		i.addClass(options.itemCssClass)
			.attr('data-type', file.type);
		if (isImage(file.type) && options.previewImages && window.FileReader) {
			var tmb = $('<img class="thumbnail" />').attr('title', file.name);
			var reader = new FileReader();
			reader.onload = function (e) {
				tmb.attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
			i.append(tmb).addClass('has-preview');
		} else {
			i.text(file.name);
		}
		i.append('<a href="#" class="delete">&times</a>');
		$(input)
			.detach()
			.removeAttr('id')
			.appendTo(i);
		return i;
	}
	
	function init() {
		if (this.files.length) {
			var file = this.files[0];
			if (options.accept !== false) {
				if (options.accept.indexOf(file.type) == -1) {
					discard(this);
					alert('File type is not allowed: ' + file.type);
					return true;
				}
			}
			if (options.multiple && false !== options.maxfiles) {
				if (countSelected(this) >= options.maxfiles) {
					discard(this);
					alert('Please, select up to ' + options.maxfiles + ' files');
					return true;
				}
			}
			if (!options.multiple) {
				cleanup(this);
			}
			var p = $(this).parent(), c = $(this).clone();
			var i = createItem(this, file);
			p.append(c);
			i.insertBefore(p);
			c.on('change', init);
		}
	}
	
	this.each(function() {
		$(this).on('change', init);
		$(this).closest(options.container).on('click', 'a.delete', function() {
			$(this).parent().remove();
			return false;
		});
	});
}
