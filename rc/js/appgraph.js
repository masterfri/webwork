AppGraph = function(element, settings) {
	this.settings = {
		'width': 800,
		'titleFont': {'fill': '#444477', 'size': 14},
		'titleMargin': 5,
		'attributesFont': {'fill': '#447744', 'size': 12, 'leading': 1.9},
		'boxSeparator': {'width': 1, 'color': '#eeeeee'},
		'boxSeparatorOffset': 1,
		'typeFill': '#999999',
		'boxFill': '#fafafa',
		'boxStroke': {'width': 2, 'color': '#aaaaaa'},
		'boxRadius': 5,
		'boxPadding': {'x': 10, 'y': 10},
		'boxSpacing': {'x': 30, 'y': 40},
		'graphPadding': {'x': 30, 'y': 30},
		'markerSize': 8,
		'attributeMarkerOffset': -4,
		'nodeMarkerOffset': 20,
		'linkColor1': '#fafafa',
		'linkColor2': '#aaaaaa',
		'linkLineWidth': 2,
		'memorizePositions': true,
		'memoryPrefix': ''
	};
	for (var k in settings) {
		this.settings[k] = settings[k];
	}
	if (!window.localStorage) {
		this.settings.memorizePositions = false;
	}
	this.cursor = {
		'x': this.settings.graphPadding.x, 
		'y': this.settings.graphPadding.y, 
		'h': 0
	};
	this.nodes = {};
	this.connections = [];
	this.svg = new SVG(element).width(this.settings.width);
	this.linksGroup = this.svg.group();
	this.nodesGroup = this.svg.group();
	this.markersGroup = this.svg.group();
}

AppGraph.LINK_BELONG = 1;
AppGraph.LINK_HAS = 2;
AppGraph.LINK_CONNECTED = 3;
AppGraph.LINK_ASSOCIATED = 4;

AppGraph.prototype.addNode = function(data) {
	var node = new AppGraph.Node(this, data, this.cursor.x, this.cursor.y);
	this.nodes[data.name] = node;
	if (!node.recallPosition()) {
		var nodebox = node.bbox();
		if (this.cursor.x + nodebox.w > this.svg.width()) {
			this.nextRow();
			node.translate(this.cursor.x, this.cursor.y);
		}
		this.cursor.x += nodebox.w + this.settings.boxSpacing.x;
		this.cursor.h = Math.max(this.cursor.h, nodebox.h);
	}
}

AppGraph.prototype.hasNode = function(name) {
	return this.nodes[name] !== undefined;
}

AppGraph.prototype.nextRow = function() {
	this.cursor.x = this.settings.graphPadding.x;
	this.cursor.y += this.cursor.h + this.settings.boxSpacing.y;
	this.cursor.h = 0;
}

AppGraph.prototype.connect = function(node1, node2, attr1, attr2, type) {
	if (typeof node1 == 'string') {
		node1 = this.nodes[node1];
	}
	if (typeof node2 == 'string') {
		node2 = this.nodes[node2];
	}
	if (node1 == undefined || node2 == undefined) {
		return false;
	}
	if (this.connected(node1, node2, attr1, attr2)) {
		return;
	}
	
	this.connections.push([[node1, attr1], [node2, attr2]]);
	
	type = type || AppGraph.LINK_ASSOCIATED;
	
	var that = this;
	var p1 = node1.getConnectPoint(attr1);
	var p2 = node2.getConnectPoint(attr2);
	var nearest = this.getNearestPoints(p1, p2);
	p1 = nearest[0];
	p2 = nearest[1];
	var cp1 = this.markersGroup
		.circle(this.settings.markerSize)
		.center(p1.x, p1.y);
	var cp2 = this.markersGroup
		.circle(this.settings.markerSize)
		.center(p2.x, p2.y);
	if (type == AppGraph.LINK_HAS) {
		cp1.fill(this.settings.linkColor1).stroke({
			'width': this.settings.linkLineWidth,
			'color': this.settings.linkColor2
		});
	} else {
		cp1.fill(this.settings.linkColor2)
	}
	if (type == AppGraph.LINK_BELONG) {
		cp2.fill(this.settings.linkColor1).stroke({
			'width': this.settings.linkLineWidth,
			'color': this.settings.linkColor2
		});
	} else {
		cp2.fill(this.settings.linkColor2)
	}
	
	var path = this.linksGroup
		.path(this.makePath(p1.x, p1.y, p1.x + p1.cx, p1.y + p1.cy, p2.x + p2.cx, p2.y + p2.cy, p2.x, p2.y))
		.fill('none')
		.stroke({
			'width': this.settings.linkLineWidth,
			'color': this.settings.linkColor2
		});
	
	var updatepath = function() {
		p1 = node1.getConnectPoint(attr1, p1.n);
		p2 = node2.getConnectPoint(attr2, p2.n);
		nearest = that.getNearestPoints(p1, p2);
		p1 = nearest[0];
		p2 = nearest[1];
		cp1.center(p1.x, p1.y);
		cp2.center(p2.x, p2.y);
		path.plot(that.makePath(p1.x, p1.y, p1.x + p1.cx, p1.y + p1.cy, p2.x + p2.cx, p2.y + p2.cy, p2.x, p2.y));
		that.adjustHeight();
	}
	node1.on('dragmove', updatepath);
	node2.on('dragmove', updatepath);
	node1.on('dragend', function() {
		node1.memorizePosition();
	});
	node2.on('dragend', function() {
		node2.memorizePosition();
	});
}

AppGraph.prototype.connected = function(node1, node2, attr1, attr2) {
	for (var i = 0; i < this.connections.length; i++) {
		var con = this.connections[i];
		if (con[0][0] === node1 && con[1][0] === node2 && con[0][1] === attr1 && con[1][1] === attr2) {
			return true;
		}
		if (con[0][0] === node2 && con[1][0] === node1 && con[0][1] === attr2 && con[1][1] === attr1) {
			return true;
		}
	}
	return false;
} 

AppGraph.prototype.getNearestPoints = function(p1, p2) {
	var vars = [];
	vars.push({
		'p': [p1[0], p2[0]],
		'd': this.getDistance(p1[0], p2[0])
	});
	vars.push({
		'p': [p1[0], p2[1]],
		'd': this.getDistance(p1[0], p2[1])
	});
	vars.push({
		'p': [p1[1], p2[0]],
		'd': this.getDistance(p1[1], p2[0])
	});
	vars.push({
		'p': [p1[1], p2[1]],
		'd': this.getDistance(p1[1], p2[1])
	});
	vars.sort(function(a, b) {
		return a.d - b.d;
	});
	return vars[0].p;
}

AppGraph.prototype.getDistance = function(p1, p2) {
	return (p1.x - p2.x) * (p1.x - p2.x) + (p1.y - p2.y) * (p1.y - p2.y);
}

AppGraph.prototype.makePath = function(x1, y1, cx1, cy1, cx2, cy2, x2, y2) {
	return ['M', x1, y1, 'C', cx1, cy1, cx2, cy2, x2, y2].join(' ');
}

AppGraph.prototype.adjustHeight = function() {
	var box = this.svg.bbox();
	this.svg.height(box.h + box.y);
}

AppGraph.Node = function(parent, data, x, y) {
	var that = this;
	this.name = data.name;
	this.connections = 0;
	this.attributes = {};
	this.parent = parent;
	this.group = parent.nodesGroup.group().translate(x, y).draggy();
	this.title = (new SVG.Text)
		.text(data.name)
		.translate(parent.settings.boxPadding.x, parent.settings.boxPadding.y)
		.font(parent.settings.titleFont);
	var titlebox = this.title.bbox();
	this.content = (new SVG.Text)
		.translate(titlebox.x + parent.settings.boxPadding.x, titlebox.y + titlebox.h + parent.settings.boxPadding.y + parent.settings.titleMargin)
		.text(function(add) {
		data.attributes.forEach(function(attr) {
			var attribute = add.tspan(function(addattr) {
				addattr.tspan(attr.name);
				addattr.tspan(' ');
				addattr.tspan(attr.type).fill(parent.settings.typeFill);
				if (attr.collection) {
					addattr.tspan(' [1..N]').fill(parent.settings.typeFill);
				}
			}).newLine();
			that.attributes[attr.name] = attribute;
		});
	}).font(parent.settings.attributesFont);
	var contentbox = this.content.bbox();
	var boxw = Math.max(contentbox.w, titlebox.w) + 2 * parent.settings.boxPadding.x;
	var boxh = contentbox.y + contentbox.h + titlebox.h + parent.settings.titleMargin + 2 * parent.settings.boxPadding.y;
	this.group.rect(boxw, boxh)
		.fill(parent.settings.boxFill)
		.stroke(parent.settings.boxStroke)
		.radius(parent.settings.boxRadius)
		.translate(titlebox.x, titlebox.y);
		this.group.add(this.title);
		this.group.add(this.content);
	for (var attr in this.attributes) {
		var node = this.attributes[attr].node;
		var extent = node.getExtentOfChar(0);
		var y = node.hasAttribute('dy') ? parseInt(node.getAttribute('dy'), 10) : 0;
		y += extent.y + contentbox.y - parent.settings.boxSeparatorOffset;
		this.group.line(contentbox.x + 1, y, contentbox.x + boxw - 1, y).stroke(parent.settings.boxSeparator);
	}
}

AppGraph.Node.prototype.bbox = function() {
	return this.group.bbox();
}

AppGraph.Node.prototype.on = function(event, callback) {
	return this.group.on(event, callback);
}

AppGraph.Node.prototype.translate = function(x, y) {
	return this.group.translate(x, y);
}

AppGraph.Node.prototype.getConnectPoint = function(attr, numcon) {
	if (attr != undefined && this.attributes[attr] != undefined) {
		var node = this.attributes[attr].node;
		var extent = node.getExtentOfChar(0);
		var tbox = this.content.tbox();
		var y = tbox.y + extent.y + this.parent.settings.attributeMarkerOffset;
		return [{
			'x': tbox.x - this.parent.settings.boxPadding.x,
			'y': y,
			'cx': -50,
			'cy': 0,
			'n': numcon
		}, {
			'x': tbox.x + tbox.w + this.parent.settings.boxPadding.x,
			'y': y,
			'cx': 50,
			'cy': 0,
			'n': numcon
		}];
	} else {
		var tbox = this.group.tbox();
		if (numcon === undefined) {
			numcon = this.connections++;
		}
		var offset = this.parent.settings.nodeMarkerOffset + (this.parent.settings.markerSize + 1) * numcon;
		return [{
			'x': tbox.x + offset,
			'y': tbox.y,
			'cx': 0,
			'cy': -50,
			'n': numcon
		}, {
			'x': tbox.x + offset,
			'y': tbox.y + tbox.h,
			'cx': 0,
			'cy': 50,
			'n': numcon
		}];
	}
}

AppGraph.Node.prototype.memorizePosition = function() {
	if (this.parent.settings.memorizePositions) {
		var key = this.parent.settings.memoryPrefix + this.name;
		var box = this.group.tbox();
		localStorage.setItem(key, box.x + ' ' + box.y);
	}
}

AppGraph.Node.prototype.recallPosition = function() {
	if (this.parent.settings.memorizePositions) {
		var key = this.parent.settings.memoryPrefix + this.name;
		if (localStorage.hasOwnProperty(key)) {
			var pos = localStorage.getItem(key).split(' ');
			this.translate(parseFloat(pos[0]), parseFloat(pos[1]));
			return true;
		}
	}
	return false;
}
