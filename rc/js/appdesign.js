AppEntityAttribute = function(json, types, refs) {
	this.view = {};
	this.data = {};
	this.ondelete = function() {};
	this.types = types;
	this.refs = refs;
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

AppEntityAttribute.prototype.isRelation = function() {
	if (('rel' in this.types) && (this.types.rel.length > 0)) {
		return (this.types.rel.indexOf(this.data.type) != -1);
	}
	return false;
}

AppEntityAttribute.prototype.getData = function() {
	return this.data;
}

AppEntityAttribute.prototype.onDelete = function(fn) {
	this.ondelete = fn;
}

AppEntityAttribute.prototype.render = function() {
	var that = this;
	this.view.root = $('<div class="app-entity-attr"><div class="panel-body"></div></div>');
	this.view.body = this.view.root.children('.panel-body');
	this.view.body.append('<div class="h"><div class="btn-group" role="group"></div>');
	this.view.required = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-asterisk" title="' + this.t('required') + '" href="#"></span></button>');
	this.view.readonly = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-lock" title="' + this.t('readonly') + '" href="#"></span></button>');
	this.view.sortable = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-sort-by-attributes" title="' + this.t('sortable') + '" href="#"></span></button>');
	this.view.searchable = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-search" title="' + this.t('searchable') + '" href="#"></span></button>');
	this.view.collection = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-th-large" title="' + this.t('collection') + '" href="#"></span></button>');
	this.view.tableview = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-align-justify" title="' + this.t('table_view') + '" href="#"></span></button>');
	this.view.detailview = $('<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-list-alt" title="' + this.t('detailed_view') + '" href="#"></span></button>');
	this.view.name = $('<input type="text" class="form-control mleft attrname" placeholder="' + this.t('name') + '" />');
	this.view.label = $('<input type="text" class="form-control mleft" placeholder="' + this.t('label') + '" />');
	this.view.type = $('<select class="form-control mleft"></select>');
	this.view.size = $('<input type="text" class="form-control mleft short" placeholder="' + this.t('size') + '" />');
	this.view.remove = $('<button type="button" class="btn btn-danger pull-right mleft"><span class="glyphicon glyphicon-trash" title="' + this.t('delete') + '" href="#"></span><span class="confirm-msg">' + this.t('confirm') + '</span></button>');
	this.view.more = $('<button type="button" class="btn btn-default pull-right"><span class="glyphicon glyphicon-cog" title="' + this.t('more') + '" href="#"></span></button>');
	this.view.body.children('.h').children('.btn-group')
		.append(this.view.required)
		.append(this.view.readonly)
		.append(this.view.sortable)
		.append(this.view.searchable)
		.append(this.view.collection)
		.append(this.view.tableview)
		.append(this.view.detailview);
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
	this.view.subordinate = $('<select class="form-control"></select>')
		.append('<option value="0">' + this.t('no') + '</option>')
		.append('<option value="1">' + this.t('yes') + '</option>');
	this.view.description = $('<textarea class="form-control"></textarea>');
	this.view.relation = $('<select class="form-control"></select>')
		.append('<option value="has-one">' + this.t('has_one') + '</option>')
		.append('<option value="belongs-to-one">' + this.t('belongs_to_one') + '</option>')
		.append('<option value="has-many">' + this.t('has_many') + '</option>')
		.append('<option value="belongs-to-many">' + this.t('belongs_to_many') + '</option>');
	this.view.backref = $('<select class="form-control"></select>');
	this.view.default = $('<input type="text" class="form-control" />');
	this.view.unsigned = $('<select class="form-control"></select>')
		.append('<option value="0">' + this.t('no') + '</option>')
		.append('<option value="1">' + this.t('yes') + '</option>');
	this.view.options = $('<textarea class="form-control"></textarea>');
	this.view.body.children('.more-container')
		.append(this.renderRow(this.view.relation, this.t('relation')))
		.append(this.renderRow(this.view.subordinate, this.t('subordinate')))
		.append(this.renderRow(this.view.backref, this.t('back_reference')))
		.append(this.renderRow(this.view.unsigned, this.t('unsigned')))
		.append(this.renderRow(this.view.options, this.t('options')))
		.append(this.renderRow(this.view.default, this.t('default_value')))
		.append(this.renderRow(this.view.description, this.t('description')));
	var groupt;
	if (('std' in this.types) && (this.types.std.length > 0)) {
		group = $('<optgroup label="' + this.t('standard') + '"></optgroup>');
		this.view.type.append(group);
		this.types.std.forEach(function(item) {
			group.append($('<option></option>').attr('value', item).text(item));
		});
	}
	if (('custom' in this.types) && (this.types.custom.length > 0)) {
		group = $('<optgroup label="' + this.t('custom') + '"></optgroup>');
		this.view.type.append(group);
		this.types.custom.forEach(function(item) {
			group.append($('<option></option>').attr('value', item).text(item));
		});
	}
	if (('rel' in this.types) && (this.types.rel.length > 0)) {
		group = $('<optgroup label="' + this.t('relations') + '"></optgroup>');
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
	this.view.tableview.on('click', function() {
		if (that.data.tableview) {
			that.data.tableview = false;
			that.view.tableview.removeClass('active');
		} else {
			that.data.tableview = true;
			that.view.tableview.addClass('active');
		}
	});
	this.view.detailview.on('click', function() {
		if (that.data.detailview) {
			that.data.detailview = false;
			that.view.detailview.removeClass('active');
		} else {
			that.data.detailview = true;
			that.view.detailview.addClass('active');
		}
	});
	this.view.name.on('change', function() {
		that.data.name = $.trim(that.view.name.val());
		if (that.view.label.val() == '') {
			that.data.label = AppEntityAttribute.nameToLabel(that.data.name);
			that.view.label.val(that.data.label);
		}
	});
	this.view.label.on('change', function() {
		that.data.label = $.trim(that.view.label.val());
		if (that.view.name.val() == '') {
			that.data.name = AppEntityAttribute.labelToName(that.data.label);
			that.view.name.val(that.data.name);
		}
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
		that.afterRelationChanged();
	});
	this.view.backref.on('change', function() {
		that.data.backref = that.view.backref.val();
		that.afterBackrefChanged();
	});
	this.view.default.on('change', function() {
		that.data.default = that.view.default.val();
	});
	this.view.unsigned.on('change', function() {
		that.data.unsigned = that.view.unsigned.val() == 1;
	});
	this.view.subordinate.on('change', function() {
		that.data.subordinate = that.view.subordinate.val() == 1;
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
	if (this.data.tableview) {
		this.view.tableview.addClass('active');
	}
	if (this.data.detailview) {
		this.view.detailview.addClass('active');
	}
	this.view.name.val(this.data.name);
	this.view.label.val(this.data.label);
	this.view.type.val(this.data.type);
	this.view.size.val(this.data.size);
	this.view.description.val(this.data.description);
	this.view.relation.val(this.data.relation);
	this.view.backref.val(this.data.backref);
	this.view.default.val(this.data.default);
	this.view.unsigned.val(this.data.unsigned ? 1 : 0);
	this.view.options.val(this.data.options);
	this.view.subordinate.val(this.data.subordinate ? 1 : 0);
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
			this.view.backref.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').show();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			this.view.subordinate.closest('.form-group').hide();
			delete this.data.size;
			delete this.data.relation;
			delete this.data.backref;
			delete this.data.options;
			delete this.data.subordinate;
			return;
		case 'decimal':
			this.view.size.show();
			this.view.relation.closest('.form-group').hide();
			this.view.backref.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').show();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			this.view.subordinate.closest('.form-group').hide();
			delete this.data.relation;
			delete this.data.backref;
			delete this.data.options;
			delete this.data.subordinate;
			return;
		case 'char':
			this.view.size.show();
			this.view.relation.closest('.form-group').hide();
			this.view.backref.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').hide();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			this.view.subordinate.closest('.form-group').hide();
			delete this.data.relation;
			delete this.data.backref;
			delete this.data.unsigned;
			delete this.data.options;
			delete this.data.subordinate;
			return;
		case 'text':
			this.view.size.hide();
			this.view.relation.closest('.form-group').hide();
			this.view.backref.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').hide();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			this.view.subordinate.closest('.form-group').hide();
			delete this.data.size;
			delete this.data.relation;
			delete this.data.backref;
			delete this.data.unsigned;
			delete this.data.options;
			delete this.data.subordinate;
			return;
		case 'bool':
			this.view.size.hide();
			this.view.relation.closest('.form-group').hide();
			this.view.backref.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').hide();
			this.view.options.closest('.form-group').hide();
			this.view.default.closest('.form-group').show();
			this.view.subordinate.closest('.form-group').hide();
			delete this.data.size;
			delete this.data.relation;
			delete this.data.backref;
			delete this.data.unsigned;
			delete this.data.options;
			delete this.data.subordinate;
			return;
		case 'option':
		case 'enum':
			this.view.size.hide();
			this.view.relation.closest('.form-group').hide();
			this.view.backref.closest('.form-group').hide();
			this.view.unsigned.closest('.form-group').hide();
			this.view.options.closest('.form-group').show();
			this.view.default.closest('.form-group').show();
			this.view.subordinate.closest('.form-group').hide();
			delete this.data.size;
			delete this.data.relation;
			delete this.data.backref;
			delete this.data.unsigned;
			delete this.data.subordinate;
			return;
	}
	if (this.isRelation()) {
		this.view.size.hide();
		this.view.relation.closest('.form-group').show();
		this.view.backref.closest('.form-group').show();
		this.view.unsigned.closest('.form-group').hide();
		this.view.options.closest('.form-group').hide();
		this.view.default.closest('.form-group').hide();
		delete this.data.size;
		delete this.data.unsigned;
		delete this.data.options;
		delete this.data.default;
		this.loadReferences();
		this.fixRelationType();
		this.afterRelationChanged();
		return;
	}
	this.view.size.hide();
	this.view.relation.closest('.form-group').hide();
	this.view.backref.closest('.form-group').hide();
	this.view.unsigned.closest('.form-group').hide();
	this.view.options.closest('.form-group').hide();
	this.view.default.closest('.form-group').hide();
	this.view.subordinate.closest('.form-group').hide();
	delete this.data.size;
	delete this.data.relation;
	delete this.data.backref;
	delete this.data.unsigned;
	delete this.data.options;
	delete this.data.default;
	delete this.data.subordinate;
}

AppEntityAttribute.prototype.afterRelationChanged = function() {
	if (this.data.relation == 'has-many' || this.data.relation == 'belongs-to-many' || this.data.relation == 'has-one') {
		this.view.subordinate.closest('.form-group').show();
	} else {
		this.view.subordinate.closest('.form-group').hide();
		delete this.data.subordinate;
	}
}

AppEntityAttribute.prototype.loadReferences = function() {
	if (this.refs !== false) {
		var has = false;
		this.view.backref.children().remove();
		var opt = $('<option value="">' + this.t('none') + '</option>');
		this.view.backref.append(opt);
		for (var type in this.refs) {
			if (type === this.data.type) {
				for (var attr in this.refs[type]) {
					opt = $('<option></option>');
					opt.text(attr).attr('value', attr);
					this.view.backref.append(opt);
					has = true;
				}
			}
		}
		if (has) {
			if (this.data.backref) {
				this.view.backref.val(this.data.backref);
			}
		} else {
			this.view.backref.closest('.form-group').hide();
		}
	}
}

AppEntityAttribute.prototype.afterCollectionChange = function() {
	if (this.data.collection) {
		this.fixRelationType();
		this.view.relation.children('[value="belongs-to-one"],[value="has-one"]').attr('disabled', 'disabled');
	} else {
		this.view.relation.children().removeAttr('disabled');
	}
}

AppEntityAttribute.prototype.afterBackrefChanged = function() {
	this.fixRelationType();
}

AppEntityAttribute.prototype.fixRelationType = function() {
	if (this.isRelation()) {
		var backrefcol = undefined;
		if (this.data.backref && (this.data.type in this.refs)) {
			backrefcol = this.refs[this.data.type][this.data.backref];
		}
		if (this.data.collection) {
			if (backrefcol === true) {
				this.view.relation.val('belongs-to-many');
				this.data.relation = 'belongs-to-many';
			} else if (backrefcol === false) {
				this.view.relation.val('has-many');
				this.data.relation = 'has-many';
			} else if (this.data.relation == 'belongs-to-one') {
				this.view.relation.val('belongs-to-many');
				this.data.relation = 'belongs-to-many';
			}
		} else {
			if (backrefcol === true) {
				this.view.relation.val('belongs-to-one');
				this.data.relation = 'belongs-to-one';
			} else if (this.data.relation == 'has-many') {
				this.view.relation.val('has-one');
				this.data.relation = 'has-one';
			} else if (this.data.relation == 'belongs-to-many') {
				this.view.relation.val('belongs-to-one');
				this.data.relation = 'belongs-to-one';
			}
		}
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

AppEntityAttribute.strings = {
	'required' : 'Required',
	'readonly' : 'Readonly',
	'sortable' : 'Sortable',
	'searchable' : 'Searchable',
	'collection' : 'Collection',
	'table_view' : 'Table view',
	'detailed_view' : 'Detailed view',
	'name' : 'Name',
	'label' : 'Label',
	'size': 'Size',
	'delete' : 'Delete',
	'more': 'More',
	'no' : 'No',
	'yes' : 'Yes',
	'none' : 'None',
	'has_one' : 'Has one',
	'belongs_to_one' : 'Belongs to one',
	'has_many' : 'Has many',
	'belongs_to_many' : 'Belongs to many',
	'relation' : 'Relation',
	'subordinate' : 'Subordinate',
	'back_reference' : 'Back reference',
	'unsigned' : 'Unsigned',
	'options' : 'Options',
	'default_value' : 'Default value',
	'description' : 'Description',
	'standard' : 'Standard',
	'custom' : 'Custom',
	'relations' : 'Relations',
	'confirm' : 'Confirm'
};

AppEntityAttribute.prototype.t = function(str) {
	return AppEntityAttribute.strings[str] || str;
}

AppEntityAttribute.labelToName = function(label) {
	var name = label.toLowerCase()
		.replace(/[(][^)]*[)]/g, '_')
		.replace(/[^a-zA-Z0-9_]/g, '_')
		.replace(/_+/g, '_')
		.replace(/^_+/, '')
		.replace(/_+$/, '')
		.split('_')
		.filter(function(v) {
			return ['a', 'an', 'the', 'are', 'is', 'do', 'does', 'will'].indexOf(v) == -1;
		})
		.join('_');
	while (name.length > 60) {
		var tmp = name.split('_');
		tmp.pop();
		name = tmp.join('_');
	}
	return name;
};

AppEntityAttribute.nameToLabel = function(name) {
	return name.split('_').map(function(v) {
		return v.substr(0, 1).toUpperCase() + v.substr(1).toLowerCase();
	}).join(' ');
};