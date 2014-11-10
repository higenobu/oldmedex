function getEvent (evt) {
    return (evt) ? evt : ((window.event) ? event : null);
}

function getKeyCode(event) {
    var evt = getEvent(event);
    return ((evt.which) ? evt.which : evt.keyCode);
}

function do_lookup_inc_search(field, t, prefix) {
    e = document.getElementById(prefix+'match_exp');
    if (e && e.checked && (field.value.substr(0,1) != '^'))
	field.value = e.value + field.value

    p_div = getFirstParentByTagAndClassName(field, 'DIV', prefix + 'qbe');
    fc = formContents(p_div);
    fc[0].push('query');
    fc[1].push(field.value);
    fc[0].push('type');
    fc[1].push(t);
    fc[0].push('prefix');
    fc[1].push(prefix);
    fc[0].push(prefix + 'qbe-state');
    fc[1].push('2');
    fc[0].push(prefix + 'qbe-limit-further');
    fc[1].push('1');

    qs = queryString(fc);
    var path = window.location.pathname;
    var m = path.match(/^\/au\/.+?\//);
    if (m == null)
	m = '/';
    var url = m + 'svc/inc_search.php?' + qs;
    var d = loadJSONDoc(url);
    var gotData = function (d) {
	div = document.getElementById(d.prefix + 'qbe_result')
	div.innerHTML='';
	//div.style.color='#ffccff';
	div.innerHTML = d.los;
	div.style.visibility = "visible";
    };
    
    var dataFetchFailed = function(err) {
		alert("Data Fetch failed" + err);
    };
    
    d.addCallbacks(gotData, dataFetchFailed);
}

// roma 2 hiragana
function roma2hiragana(str, delay) {
    var result = [];
    var text = str;
    var rem = '';

    if (delay) {
	var l = str.length;
	var last  = str.substr(l - 1, 1);
	var last2 = str.substr(l - 2, 2);
	if (l > 1 && last2 == 'nn') {
	    text = str;
	    rem = '';
	} else if (l > 1 && last2.match(/^[qwrtyplkjhgfdszxcvbmn]y$/)) {
	    text = str.substr(0, l - 2);
	    rem = last2;
	} else if (l > 0 && last.match(/[qwrtyplkjhgfdszxcvbmn]/)) {
	    text = str.substr(0, l - 1);
	    rem = last;
	}
    }
  
    for (var i = 0; i < text.length;) {
	var o = text.charAt(i);
	var c = o.charCodeAt(0);
	var len = 0;
	if ((c >= 97 && c <= 122) || (c >= 65 && c <= 90) || (c >= 44 && c <= 46)) 
	    len = 4;
	while (len) {
	    var key = text.slice(i, i + len);
	    if (key in IMERoma2KatakanaTable_) {
		var kana = IMERoma2KatakanaTable_[key];
		if (typeof(kana) == 'string') {
		    result.push(kana);
		    i += len;
		} else {
		    result.push(kana[0]);
		    i += (len - kana[1]);
		}
		break;
	    }
	    --len;
	}
    
	if (len == 0) {
	    result.push(o);
	    ++i;
	}
    }
  
    return result.join("") + rem;
}

IMERoma2KatakanaTable_ = {
    ".":"\u3002",",":"\u3001","-":"\u30fc","~":"\u301c",
    "va":"\u3046\u309b\u3041","vi":"\u3046\u309b\u3043","vu":"\u3046\u309b","ve":"\u3046\u309b\u3047","vo":"\u3046\u309b\u3049",
    "vv": ["\u3063",1],"xx": ["\u3063",1],"kk": ["\u3063",1],"gg": ["\u3063",1],
    "ss": ["\u3063",1],"zz": ["\u3063",1],"jj": ["\u3063",1],"tt": ["\u3063",1],
    "dd": ["\u3063",1],"hh": ["\u3063",1],"ff": ["\u3063",1],"bb": ["\u3063",1],
    "pp": ["\u3063",1],"mm": ["\u3063",1],"yy": ["\u3063",1],"rr": ["\u3063",1],
    "ww": ["\u3063",1],"cc": ["\u3063",1],
    "kya":"\u304d\u3083",
    "kyi":"\u304d\u3043",
    "kyu":"\u304d\u3085",
    "kye":"\u304d\u3047",
    "kyo":"\u304d\u3087",
    "gya":"\u304e\u3083","gyi":"\u304e\u3043","gyu":"\u304e\u3085","gye":"\u304e\u3047","gyo":"\u304e\u3087",
    "sya":"\u3057\u3083","syi":"\u3057\u3043","syu":"\u3057\u3085","sye":"\u3057\u3047","syo":"\u3057\u3087",
    "sha":"\u3057\u3083","shi":"\u3057","shu":"\u3057\u3085","she":"\u3057\u3047","sho":"\u3057\u3087",
    "zya":"\u3058\u3083","zyi":"\u3058\u3043","zyu":"\u3058\u3085","zye":"\u3058\u3047","zyo":"\u3058\u3087",
    "tya":"\u3061\u3083","tyi":"\u3061\u3043","tyu":"\u3061\u3085","tye":"\u3061\u3047","tyo":"\u3061\u3087",
    "cha":"\u3061\u3083","chi":"\u3061","chu":"\u3061\u3085","che":"\u3061\u3047","cho":"\u3061\u3087",
    "dya":"\u3062\u3083","dyi":"\u3062\u3043","dyu":"\u3062\u3085","dye":"\u3062\u3047","dyo":"\u3062\u3087",
    "tha":"\u3066\u3083","thi":"\u3066\u3043","thu":"\u3066\u3085","the":"\u3066\u3047","tho":"\u3066\u3087",
    "dha":"\u3067\u3083","dhi":"\u3067\u3043","dhu":"\u3067\u3085","dhe":"\u3067\u3047","dho":"\u3067\u3087",
    "nya":"\u306b\u3083","nyi":"\u306b\u3043","nyu":"\u306b\u3085","nye":"\u306b\u3047","nyo":"\u306b\u3087",
    "jya":"\u3058\u3083","jyi":"\u3058","jyu":"\u3058\u3085","jye":"\u3058\u3047","jyo":"\u3058\u3087",
    "hya":"\u3072\u3083","hyi":"\u3072\u3043","hyu":"\u3072\u3085","hye":"\u3072\u3047","hyo":"\u3072\u3087",
    "bya":"\u3073\u3083","byi":"\u3073\u3043","byu":"\u3073\u3085","bye":"\u3073\u3047","byo":"\u3073\u3087",
    "pya":"\u3074\u3083","pyi":"\u3074\u3043","pyu":"\u3074\u3085","pye":"\u3074\u3047","pyo":"\u3074\u3087",
    "fa":"\u3075\u3041","fi":"\u3075\u3043","fu":"\u3075","fe":"\u3075\u3047","fo":"\u3075\u3049",
    "mya":"\u307f\u3083","myi":"\u307f\u3043","myu":"\u307f\u3085","mye":"\u307f\u3047","myo":"\u307f\u3087",
    "rya":"\u308a\u3083","ryi":"\u308a\u3043","ryu":"\u308a\u3085","rye":"\u308a\u3047","ryo":"\u308a\u3087",
    "n\"":"\u3093","nn":"\u3093","n":"\u3093",
    "a":"\u3042","i":"\u3044","u":"\u3046","e":"\u3048","o":"\u304a",
    "xa":"\u3041","xi":"\u3043","xu":"\u3045","xe":"\u3047","xo":"\u3049",
    "la":"\u3041","li":"\u3043","lu":"\u3045","le":"\u3047","lo":"\u3049",
    "ka":"\u304b","ki":"\u304d","ku":"\u304f","ke":"\u3051","ko":"\u3053",
    "ga":"\u304c","gi":"\u304e","gu":"\u3050","ge":"\u3052","go":"\u3054",
    "sa":"\u3055","si":"\u3057","su":"\u3059","se":"\u305b","so":"\u305d",
    "za":"\u3056","zi":"\u3058","zu":"\u305a","ze":"\u305c","zo":"\u305e",
    "ja":"\u3058\u3083","ji":"\u3058","ju":"\u3058\u3085","je":"\u3058\u3047","jo":"\u3058\u3087",
    "ta":"\u305f","ti":"\u3061","tu":"\u3064","tsu":"\u3064","te":"\u3066","to":"\u3068",
    "da":"\u3060","di":"\u3062","du":"\u3065","de":"\u3067","do":"\u3069",
    "xtu":"\u3063","xtsu":"\u3063",
    "na":"\u306a","ni":"\u306b","nu":"\u306c","ne":"\u306d","no":"\u306e",
    "ha":"\u306f","hi":"\u3072","hu":"\u3075","fu":"\u3075","he":"\u3078","ho":"\u307b",
    "ba":"\u3070","bi":"\u3073","bu":"\u3076","be":"\u3079","bo":"\u307c",
    "pa":"\u3071","pi":"\u3074","pu":"\u3077","pe":"\u307a","po":"\u307d",
    "ma":"\u307e","mi":"\u307f","mu":"\u3080","me":"\u3081","mo":"\u3082",
    "xya":"\u3083","ya":"\u3084","xyu":"\u3085","yu":"\u3086","xyo":"\u3087","yo":"\u3088",
    "ra":"\u3089","ri":"\u308a","ru":"\u308b","re":"\u308c","ro":"\u308d",
    "xwa":"\u308e","wa":"\u308f","wi":"\u3046\u3043","we":"\u3046\u3047","wo":"\u3092"
};

function incSearch(field, event, t, prefix) {
    var evt = getEvent(event);
    var key = getKeyCode(event);
    if(key == 0x20) {
	v = roma2hiragana(field.value, false);
	if (v != field.value)
	    field.value = v;
	do_lookup_inc_search(field, t, prefix);
	return false;
    }else if(key == 13) {  // enter key
	do_lookup_inc_search(field, t, prefix);
	return false;
    }
    
    if( event.charCode){
	t = field.value + String.fromCharCode(event.charCode);
	v = roma2hiragana(t, true);
	if (v != field.value)
	    field.value = v;
	return false;
    }
    return true;
}
