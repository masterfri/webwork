AppEntityAttribute = function(json, types) {
	this.view = {};
	this.data = {};
	this.ondelete = function() {};
	this.types = types;
	this.fromJSON(json);
	this.render();
	this.afterTypeChange();
	this.afterCollectionChange();
}

AppEntityAttribute.prototype.fromJSON = function(json) {
	this.data = json || {
		'type' : 'char',
		'size' : '100'
	};
}

AppEntityAttribute.prototype.getView = function() {
	return this.view.root;
}

AppEntityAttribute.prototype.getData = function() {
	return this.data;
}

AppEntityAttribute.prototype.onDelete = function(fn) {
	this.ondelete = fn;
}

AppEntityAttribute.prototype.render = function() {
	var that = this;
	this.view.root = $('<div class="panel panel-default app-entity-attr"><div class="panel-body"></div></div>');
	this.view.body = this.view.root.children('.panel-body');
	this.view.body.append('<div class="h"><div class="btn-group" role="group"></div>');
	this.view.required = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-asterisk" title="Required" href="#"></span></button>');
	this.view.readonly = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-lock" title="Readonly" href="#"></span></button>');
	this.view.sortable = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-sort-by-attributes" title="Sortable" href="#"></span></button>');
	this.view.searchable = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-search" title="Searchable" href="#"></span></button>');
	this.view.collection = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-th-large" title="Collection" href="#"></span></button>');
	this.view.name = $('<input type="text" class="form-control mleft attrname" placeholder="Name" />');
	this.view.label = $('<input type="text" class="form-control mleft" placeholder="Label" />');
	this.view.type = $('<select class="form-control mleft"></select>');
	this.view.size = $('<input type="text" class="form-control mleft short" placeholder="Size" />');
	this.view.remove = $('<button type="button" class="btn btn-danger pull-right mleft"><span class="glyphicon glyphicon-trash" title="Delete" href="#"></span></button>');
	this.view.more = $('<button type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-cog" title="More" href="#"></span></button>');
	this.view.body.children('.h').children('.btn-group')
		.append(this.view.required)
		.append(this.view.readonly)
		.append(this.view.sortable)
		.append(this.view.searchable)
		.append(this.view.collection);
	this.view.body.children('.h')
		.append(this.view.name)
		.append(this.view.label)
		.append(this.view.type)
		.append(this.view.size)
		.append(this.view.remove)
		.append(this.view.more);
	this.view.morecont = $('<div class="more-container" style="display: none;"></div>');
	this.view.body.append(this.view.morecont);
	this.view.more.on('click', function() {
		if (that.view.morecont.is(':visible')) {
			that.view.morecont.slideUp();
		} else {
			that.view.morecont.slideDown();
		}
	});
	this.view.description = $('<textarea class="form-control"></textarea>');
	this.view.relation = $('<select class="form-control"></select>')
		.append('<option value="one-to-one">Has one</option>')
		.append('<option value="many-to-one">Belongs to</option>')
		.append('<option value="one-to-many">Has many</option>')
		.append('<option value="many-to-many">Many to many</option>');
	this.view.default = $('<input type="text" class="form-control" />');
	this.view.unsigned = $('<select class="form-control"></select>')
		.append('<option value="0">No</option>')
		.append('<option value="1">Yes</option>');
	this.view.options = $('<textarea class="form-control"></textarea>');
	this.view.body.children('.more-container')
		.append(this.renderRow(this.view.relation, 'Relation'))
		.append(this.renderRow(this.view.unsigned, 'Unsigned'))
		.append(this.renderRow(this.view.options, 'Options'))
		.append(this.renderRow(this.view.default, 'Default value'))
		.append(this.renderRow(this.view.description, 'Description'));
	var groupt;
	if (('std' in this.types) && (this.types.std.length > 0)) {
		group = $('<optgroup label="Standard"></optgroup>');
		this.view.type.append(group);
		this.types.std.forEach(function(item) {
			group.append($('<option></option>').attr('value', item).text(item));
		});
	}
	if (('custom' in this.types) && (this.types.custom.length > 0)) {
		group = $('<optgroup label="Custom"></optgroup>');
		this.view.type.append(group);
		this.types.custom.forEach(function(item) {
			group.append($('<option></option>').attr('value', item).text(item));
		});
	}
	if (('rel' in this.types) && (this.types.rel.length > 0)) {
		group = $('<optgroup label="Relations"></optgroup>');
		this.view.type.append(group);
		this.types.rel.forEach(function(item) {
			group.append($('<option></option>').attr('value', item).text(item));
		});
	}
	this.view.required.on('click', function() {
		if (that.data.required) {
			that.data.required = false;
			that.view.required.removeClass('active');
		} else {
			that.data.required = true;
			that.view.required.addClass('active');
		}
	});
	this.view.readonly.on('click', function() {
		if (that.data.readonly) {
			that.data.readonly = false;
			that.view.readonly.removeClass('active');
		} else {
			that.data.readonly = true;
			that.view.readonly.addClass('active');
		}
	});
	this.view.sortable.on('click', function() {
		if (that.data.sortable) {
			that.data.sortable = false;
			that.view.sortable.removeClass('active');
		} else {
			that.data.sortable = true;
			that.view.sortable.addClass('active');
		}
	});
	this.view.searchable.on('click', function() {
		if (that.data.searchable) {
			that.data.searchable = false;
			that.view.searchable.removeClass('active');
		} else {
			that.data.searchable = true;
			that.view.searchable.addClass('active');
		}
	});
	this.view.collection.on('click', function() {
		if (that.data.collection) {
			that.data.collection = false;
			that.view.collection.removeClass('active');
		} else {
			that.data.collection = true;
			that.view.collection.addClass('active');
		}
		that.afterCollectionChange();
	});
	this.view.name.on('change', function() {
		that.data.name = $.trim(that.view.name.val());
		if (that.view.label.val() == '') {
			that.data.label = that.data.name.split('_').map(function(v) {
				return v.substr(0, 1).toUpperCase() + v.substr(1).toLowerCase();
			}).join(' ');
			that.view.label.val(that.data.label);
		}
	});
	this.view.label.on('change', function() {
		that.data.label = $.trim(that.view.label.val());
	});
	this.view.type.on('change', function() {
		that.data.type = that.view.type.val();
		that.afterTypeChange();
	});
	this.view.size.on('change', function() {
		that.data.size = $.trim(that.view.size.val());
	});
	this.view.description.on('change', function() {
		that.data.description = that.view.description.val();
	});
	this.view.relation.on('change', function() {
		that.data.relation = that.view.relation.val();
	});
	this.view.default.on('change', function() {
		that.data.default = that.view.default.val();
	});
	this.view.unsigned.on('change', function() {
		that.data.unsigned = that.view.unsigned.val() == 1;
	});
	this.view.options.on('change', function() {
		that.data.options = that.view.options.val();
	});
	this.view.remove.on('click', function() {
		if (that.view.remove.is('.confirmation')) {
			that.view.root.remove();
			that.ondelete();
		} else {
			that.view.remove.addClass('confirmation');
			setTimeout(function() {
				that.view.remove.removeClass('confirmation');
			}, 3000);
		}
	});
	if (this.data.required) {
		this.view.required.addClass('active');
	}
	if (this.data.readonly) {
		this.view.readonly.addClass('active');
	}
	if (this.data.sortable) {
		this.view.sortable.addClass('active');
	}
	if (this.data.searchable) {
		this.view.searchable.addClass('active');
	}
	if (this.data.collection) {
		this.view.collection.addClass('active');
	}
	this.view.name.val(this.data.name);
	this.view.label.val(this.data.label);
	this.view.type.val(this.data.type);
	this.view.size.val(this.data.size);
	this.view.description.val(this.data.description);
	this.view.relation.val(this.data.relation);
	this.view.default.val(this.data.default);
	this.view.unsigned.val(this.data.unsigned ? 1 : 0);
	this.view.options.val(this.data.options);
}

AppEntityAttribute.prototype.renderRow = function(input, label) {
	var row = $('<div class="form-group"><label class="col-sm-3 control-label"></label><div class="col-sm-9 col-input"></div></div>');
	row.children('.control-label').text(label);
	row.children('.col-input').append(input);
	return row;
}

AppEntityAttribute.prototype.afterTypeChange = function() {
	switch (this.data.type) {
		case 'int':
			this.view.size.hide();
			this.view.relation.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').show();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			delete this.data.size;
			delete this.data.relation;
			delete this.data.options;
			return;
		case 'decimal':
			this.view.size.show();
			this.view.relation.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').show();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			delete this.data.relation;
			delete this.data.options;
			return;
		case 'char':
			this.view.size.show();
			this.view.relation.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').hide();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			delete this.data.relation;
			delete this.data.unsigned;
			delete this.data.options;
			return;
		case 'text':
			this.view.size.hide();
			this.view.relation.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').hide();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			delete this.data.size;
			delete this.data.relation;
			delete this.data.unsigned;
			delete this.data.options;
			return;
		case 'bool':
			this.view.size.hide();
			this.view.relation.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').hide();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			delete this.data.size;
			delete this.data.relation;
			delete this.data.unsigned;
			delete this.data.options;
			return;
		case 'option':
		case 'enum':
			this.view.size.hide();
			this.view.relation.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').hide();
			this.view.options.closest('.form-group').show();
			this.view.default.closest('.form-group').show();
			delete this.data.size;
			delete this.data.relation;
			delete this.data.unsigned;
			return;
	}
	if (('rel' in this.types) && (this.types.rel.length > 0)) {
		if (this.types.rel.indexOf(this.data.type) != -1) {
			this.view.size.hide();
			this.view.relation.closest('.form-group').show();
			this.view.unsigned.closest('.form-group').hide();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').hide();
			delete this.data.size;
			delete this.data.unsigned;
			delete this.data.options;
			delete this.data.default;
			if (this.data.collection) {
				this.view.relation.val('one-to-many');
				this.data.relation = 'one-to-many';
			} else {
				this.view.relation.val('many-to-one');
				this.data.relation = 'many-to-one';
			}
			return;
		}
	}
	this.view.size.hide();
	this.view.relation.closest('.form-group').hide();
	this.view.unsigned.closest('.form-group').hide();
	this.view.options.closest('.form-group').hide();
	this.view.default.closest('.form-group').hide();
	delete this.data.size;
	delete this.data.relation;
	delete this.data.unsigned;
	delete this.data.options;
	delete this.data.default;
}

AppEntityAttribute.prototype.afterCollectionChange = function() {
	if (this.data.collection) {
		if (this.data.relation == 'many-to-one') {
			this.view.relation.val('many-to-many');
			this.data.relation = 'many-to-many';
		} else if (this.data.relation == 'one-to-one') {
			this.view.relation.val('one-to-many');
			this.data.relation = 'one-to-many';
		}
		this.view.relation.children('[value="many-to-one"],[value="one-to-one"]').attr('disabled', 'disabled');
	} else {
		this.view.relation.children().removeAttr('disabled');
	}
}

AppEntityAttribute.prototype.isValid = function() {
	if (this.data.name == undefined || $.trim(this.data.name) == '') {
		this.markInvalid(this.view.name);
		return false;
	}
	if (!(new RegExp('^[a-z_][a-z0-9_]*$', 'i')).test(this.data.name)) {
		this.markInvalid(this.view.name);
		return false;
	}
	if (this.data.label == undefined || $.trim(this.data.label) == '') {
		this.markInvalid(this.view.label);
		return false;
	}
	if (this.data.type == undefined || $.trim(this.data.type) == '') {
		this.markInvalid(this.view.type);
		return false;
	}
	if ((this.data.type == 'enum' || this.data.type == 'option') && (this.data.options == undefined || $.trim(this.data.options) == '')) {
		this.markInvalid(this.view.options);
		return false;
	}
	return true;
}

AppEntityAttribute.prototype.markInvalid = function(field) {
	var root = this.view.root;
	root.addClass('invalid');
	field.addClass('invalid');
	field.off('focus').on('focus', function() {
		field.off('focus');
		root.removeClass('invalid');
		field.removeClass('invalid');
	});
}
