function vocabularyuse(mydiv, selector, myinput, myactivator, mytitle, nl)
{
	// number of fixed params this function takes
	var base = 6
	var a = Array()
	for (var i = base; i < arguments.length; i++)
		a[i-base] = arguments[i];
	me = document.getElementById(mydiv)
	other = document.getElementById(selector)
	findPosXY(document.getElementById(myactivator))
	other.style.left = foundLeft
	other.style.top = foundTop
	other.style.display = ''
	other.titlestring = mytitle
	other.nl = nl
	vocabularystart(other, document.getElementById(myinput), me, a);
}

function vocabularyaccept(t, force)
{
	var chosen = ''
	var filled = 1
	var select = ''
	for (var i = 0; i < t.cols; i++) {
		var it = t.result[i].innerHTML;
		if (it == '')
			filled = 0;
		chosen = chosen + it
	}
	if (filled || force) {
		var ta = t.dest
		var v = ta.value;
		if (chosen != '' && t.nl && ta.value != '')
			 chosen = ', ' + chosen;
		t.destdiv.style.display = ''
		t.style.display = 'none'
		var s = v.substring(0, ta.selectionStart) +
			chosen +
			v.substring(ta.selectionEnd, v.length)
		ta.value = s;
		return 1
	}
	return 0
}

function vocabularypicked()
{
	var t = this.vocabulary
	var r = t.request
	this.target.innerHTML = this.innerHTML

	if (vocabularyaccept(t, 0))
		return
	for (var i = 0; i < t.cols; i++)
		r = r + '&select' + i + '=' + t.result[i].innerHTML;

	var d = loadJSONDoc(r);
	var gotdata = function (obj) {
		vocabularyshell(obj, t);
	}
	var failed = function(err) {
		alert("failed" + err);
	}
	d.addCallbacks(gotdata, failed);
	return false;
}

function vocabularycancel(t)
{
	t.style.display = 'none'
	t.innerHTML = ''
}

function vocabularyshell(obj, t)
{
	var table = document.createElement('table');
	var row0 = document.createElement('tr');
	var row1 = document.createElement('tr');
	var row2 = document.createElement('tr');

	t.innerHTML = '<span class="title">' + t.titlestring + ' </span>';

	t.appendChild(table);
	table.appendChild(row0);
	table.appendChild(row1);
	table.appendChild(row2);
	t.cols = obj.length
	t.result = Array(obj.length)

	row0.setAttribute('class', 'r0');
	row1.setAttribute('class', 'r1');
	row2.setAttribute('class', 'r2');

	for (var i = 0; i < obj.length; i++) {
		var col0 = document.createElement('td');
		row0.appendChild(col0);
		col0.innerHTML = obj[i].label;

		var col1 = document.createElement('td');
		var div = document.createElement('div');
		row1.appendChild(col1);
		col1.appendChild(div);

		var col2 = document.createElement('td');
		row2.appendChild(col2);
		col2.innerHTML = obj[i].select
		t.result[i] = col2

		for (var j = 0; j < obj[i].choice.length; j++) {
			var div1 = document.createElement('div');
			var span = document.createElement('span');
			div.appendChild(div1);
			div1.appendChild(span);
			span.innerHTML = obj[i].choice[j];
			span.onclick = vocabularypicked;
			span.i = i;
			span.j = j;
			span.target = col2
			span.vocabulary = t
		}

	}

	var insn = document.createElement('div');
	insn.setAttribute('class', 'insn');

	var accept = document.createElement('span');
	var cancel = document.createElement('span');

	accept.onclick = function () { vocabularyaccept(t, 1); };
	accept.innerHTML = 'Ok';

	cancel.onclick = function () { vocabularycancel(t); };
	cancel.innerHTML = 'Cancel';

	insn.appendChild(accept);
	insn.appendChild(cancel);
	t.appendChild(insn)
}


function vocabularystart(target, dest, destdiv, words) {
	var t = target;
	t.dest = dest
	t.destdiv = destdiv

	args = "";
	for (var i = 0; i < words.length; i++) {
		if (i)
			args = args + '&';
		args = args + 'vocab' + i + '=' + words[i]
	}
	t.request = "/svc/json_vocabulary.php?" + args;
	var d = loadJSONDoc(t.request);
	var gotdata = function (obj) {
		vocabularyshell(obj, t);
	}
	var failed = function(err) {
		alert("failed" + err);
	}
	d.addCallbacks(gotdata, failed);
	return false;
}
