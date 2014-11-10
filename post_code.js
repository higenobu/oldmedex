	function outputZipLink(resArray)
	{
		var list ='';
		for(i=0 ; i<resArray.length ; i++)
		{
			var s = '';
			s = "<a href='javascript:copyadr(\""+ resArray[i].zip+"\",\"" +resArray[i].pref + "\",\"" +resArray[i].city + "\",\"" + resArray[i].block + "\")'>" ;
			s += resArray[i].zip + ' ' + resArray[i].pref+resArray[i].city+resArray[i].block;
			s += "</a><br>" ;
			list += s;
		}
		return list;
	}


	function copyadr(zip, pref, city, block)
	{
		document.zipform.zip.value=zip
		document.zipform.pref.value=pref
		document.zipform.city.value=city
		if(block != 'xxx')
			document.zipform.block.value=block
		document.getElementById('selzip').innerHTML=''
	}
	
	function checkCode(zip){
		if(zip.length>7 || !zip.match(/^([0-9]*)$/) || zip.charAt(zip.length-1)=='-'){ 
			document.zipform.zip.value=zip.substr(0,zip.length-1) ; return false
		} else {
			return true
		}
	}

	var zip;
	var prefecture;
	var city;
	var block;
	function PostCodePopup(_zip, _prefecture, _city, _block) {
		zip = _zip;
		prefecture = _prefecture;
		city = _city;
		block = _block;

		day = new Date();
		id = day.getTime();
		URL='/post_code.php';
		eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=300,height=600');");


	}

	function setPrefCityBlock(popup) {
		o = window.document;
		o.getElementById(zip).value = popup.zipform.zip.value;
		o.getElementById(prefecture).value = popup.zipform.pref.value;
		o.getElementById(city).value = popup.zipform.city.value;
		o.getElementById(block).value = popup.zipform.block.value;
	}

	var id;
	function lookup_post_code(zip) {
		clearTimeout(id);
		if(!checkCode(zip)) return;
		if(document.zipform.zip.value.length < 3) return;

		id = setTimeout(do_lookup, 500, zip)
	}

	function do_lookup(zip) {
		var url = '/svc/json_post_code.php?zip='+zip; // returns array in json obj
		var d = loadJSONDoc(url);
		var gotData = function (obj) {
			ary = obj.post_code;
			document.getElementById('selzip').innerHTML='';
			document.zipform.pref.value = '';
			document.zipform.city.value = '';
			document.zipform.block.value = '';
			if( ary.length == 0){
			} else if( ary.length == 1 ){
				copyadr( ary[0].zip , ary[0].pref, ary[0].city, ary[0].block );
			} else if( ary.length > 1 ){
				document.getElementById('selzip').innerHTML=outputZipLink(ary);
				if(obj.more != null) {
					document.getElementById('selzip').innerHTML += obj.more;
				}
			}
		};

		var dataFetchFailed = function(err) {
			alert("Data Fetch failed");
		};
		d.addCallbacks(gotData, dataFetchFailed);
	}