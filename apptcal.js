function apptcal_click(name, datef, timef, i, j, immediate_submit)
{
	var e = document.getElementById(name);
	data = e.apptcaldata;

	e = name + '-' + i + '-' + j;
	e = document.getElementById(e);
	if (e.apptcalblocked)
		return;
	e = document.getElementById(datef);
	if (e)
		e.value = data.mdates[i];
	e = document.getElementById(timef);
	if (e) {
		e.value = data.hours[j];
		if (immediate_submit)
			mx_submit_button(e, immediate_submit, 1);
	}
}

function apptcal_label_click(name, jumpto, i)
{
	var e = document.getElementById(name);
	var data = e.apptcaldata;

	document.location = jumpto + data.mdates[i];
}			

function apptcal_page_click(name, prevnext)
{
	var e;
	var basetime;

	e = document.getElementById(name);
	data = e.apptcaldata;
	if (prevnext == 'prev')
		basetime = data.prevbase;
	else
		basetime = data.nextbase;
	var request = ("/svc/json_apptcal.php?basetime=" +
			basetime +
		       "&modality=" + data.modality);
	var d = loadJSONDoc(request);
	var gotdata = function (data) {
		populate_apptcal(name, data);
	}
	var failed = function(err) {
		alert("failed" + err);
	}
	d.addCallbacks(gotdata, failed);
	return false;
}

function populate_apptcal(name, data)
{
	var ii = data.days.length;
	var jj = data.hours.length;
	var maxdups;
	var e;

	e = document.getElementById(name);
	e.apptcaldata = data;
	for (var i = 0; i < ii; i++) {
		e = document.getElementById(name + '-day-' + i);
		e.innerHTML = data.days[i];
		description = data.description[i];
		e.setAttribute('title', description);
		e = document.getElementById(name + '-date-' + i);
		e.innerHTML = data.dates[i]
		e.setAttribute('title', description);
		for (var j = 0; j < jj; j++) {
			e = name + '-' + i + '-' + j;
			e = document.getElementById(e);
			e.apptcalblocked = 0;

			if (data.avail[i][j] <= 0) {
				e.innerHTML = ''; // perhaps '&#20241;'
				e.setAttribute('class', 'ccell blocked');
				e.setAttribute('title', description);
				e.apptcalblocked = 1;
			} else {
				maxdups = data.avail[i][j];
				e.innerHTML = data.appt[i][j];
				e.setAttribute('title', unescape(data.pt[i][j]));
				if (data.appt[i][j] >= maxdups)
					e.setAttribute('class',
							'cell crowded');
				else if (data.appt[i][j] == 0)
					e.setAttribute('class',
							'cell vacant');
				else
					e.setAttribute('class',
							'cell occupied');
			}
		}
	}
}

function load_appt_data(container, table, basetime, modality)
{
	var request = ("/svc/json_apptcal.php?basetime=" + basetime +
		       "&modality=" + modality);
	var d = loadJSONDoc(request);
	var gotdata = function (data) {
		populate_apptcal(table, data);
		var it = document.getElementById(container);
		it.style.display = '';
	}
	var failed = function(err) {
		alert("failed" + err);
	}
	d.addCallbacks(gotdata, failed);
	return false;
}
