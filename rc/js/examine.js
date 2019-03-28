const Timer = function(elem, now, started, time) {
	this.elem = elem;
	this.value = time - parseInt((now.getTime() - started.getTime()) / 1000);
	this.interval = null;
	this.display();
}

Timer.prototype.run = function() {
	var thiz = this;
	this.interval = setInterval(function() {
		thiz.tick();
		thiz.display();
	}, 1000);
}

Timer.prototype.display = function() {
	if (this.value > 0) {
		this.elem.removeClass('out');
		var min = parseInt(this.value / 60);
		var sec = this.value % 60;
		if (sec < 10) {
			sec = '0' + sec;
		}
		this.elem.text(min + ':' + sec);
	} else {
		this.elem.text('0:00')
		this.elem.addClass('out');
	}
}

Timer.prototype.tick = function() {
	this.value--;
	if (this.value <= 0) {
		clearInterval(this.interval);
		this.interval = null;
	}
}

Timer.prototype.shutdown = function() {
	if (this.interval != null) {
		clearInterval(this.interval);
		this.interval = null;
	}
	this.elem.text('-:--');
}

var recentTimer = null;

function processCode(selector) {
	$(selector).each(function() {
		var lang = false;
		var innerHTML = this.innerHTML.replace(new RegExp('@codeblock:[a-zA-Z0-9-]+'), function(res) {
			lang = res.split(':')[1];
			return '';
		}).replace(new RegExp('^(\\n)+|(\\n)+$', 'g'), '');
		if (lang) {
			var code = document.createElement('code');
			code.className = 'language-' + lang;
			code.innerHTML = innerHTML;
			Prism.highlightElement(code);
			this.innerHTML = '';
			$(this).append(code);
		} else {
			this.innerHTML = innerHTML;
		}
	});
}

function sendAnswer(answer) {
	setTimeout(function() {
		$(answer.form).submit();
	}, 100);
}

$.ajaxBindings.on('question.loaded', function() {
	var data = $('#content > .question').data();
	var start = new Date(data.timeQuestioned);
	var now = new Date(data.timeNow);
	var time = data.questionTime;
	
	if (recentTimer) {
		recentTimer.shutdown();
	}
	
	recentTimer = new Timer($('#timer'), now, start, time);
	recentTimer.run();

	$('#header-content').show();
	
	processCode('#content > .question pre');
});

$.ajaxBindings.on('exam.finished', function() {
	$('#header-content').hide();
});