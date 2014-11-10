<?php // -*- mode: php; coding: euc-japan -*-
// do not include anything else here...

/*

   $spec is array(colname => label)
   $source is array(colname => string) where string is
   the template string to be compiled.
   Returns a tuple (form, desc) but sets $error.

   Example::

	template_compile_compile(array(
'SO' => '主訴',
'S1' => '現症病歴',
'A' => '診断'),
			    array(
'SO' => 'かぜ',
'S1' => '<<< (空欄) ||| きのう ||| おととい ||| 一週間前
>>>から熱<<< (空欄) ||| 37.0 ||| 37.4 ||| 37.8 ||| 38.2
>>>度ある。
<<< 頭が痛い ||| 頭は痛くない
>>><<< はき気がする ||| はき気はしない
>>>',
'A' => '<<< (空欄) ||| かぜ ||| インフルエンザ ||| 急性肺炎 >>>'),
			    &$errs);

 */
function template_compile($spec, $source, &$error)
{
	$val_ix = 0;
	$form = '';
	$desc = array();
	foreach ($spec as $col => $label) {
		$src = array_key_exists($col, $source) ? $source[$col] : '';
		$error = '';
		template_compile_compile_one(&$val_ix, $col, $label,
					     &$form, &$desc, $src, &$error);
		if ($error != '')
			return false;
	}
	return array($form, $desc);
}

function template_split_choices($src)
{
	$result = array();
	$from = 0;
	$to = strlen($src);
	while ($from < $to) {
		$bar = strpos($src, "\n", $from);
		if (($bar === false) || ($to <= $bar)) {
			$piece = trim(substr($src, $from, $to-$from));
			$result[] = $piece;
			break;
		}
		$piece = trim(substr($src, $from, $bar-$from));
		$result[] = $piece;
		$from = $bar + 1;
	}

	$has_empty = 0;
	foreach ($result as $t) {
		if ($t == '')
			$has_empty = 1;
	}
	if ($has_empty) {
		$rr = $result;
		$result = array('');
		foreach ($rr as $t) {
			if ($t == '')
				continue;
			$result[] = $t;
		}
	}
	return $result;
}

function template_compile_append_one($piece, &$text, &$form, $nl)
{
	$text .= "$piece";
	$form .= htmlspecialchars($piece);
	if ($nl) {
		$text .= "\n";
		$form .= "<br />\n";
	}
}

function template_compile_plain_text($piece, &$text, &$form)
{
	$piece = explode("\n", $piece);
	for ($i = 0; $i < count($piece); $i++) {
		$t = trim($piece[$i]);
		template_compile_append_one($t, &$text, &$form,
					    ($i < count($piece) - 1));
	}
}

function template_compile_multi($choice, &$ix, &$form, &$text)
{
	foreach ($choice as $it) {
		if ($it == '')
			continue;
		else {
			$lbl = htmlspecialchars($it);
			$att = $lbl; /* NEEDSWORK */
		}
		$text .= "%%$ix%%";
		$it = htmlspecialchars($it);
		$form .= ("<input\n type=\"hidden\" " .
			  "name=\"lbl-$ix\" value=\"$att\"\n>" .
			  "<input type=\"checkbox\" " .
			  "name=\"val-$ix\">$lbl");
		$ix++;
	}
}

function template_compile_radio($choice, &$ix, &$form, &$text)
{
	foreach ($choice as $it) {
		if ($it == '')
			continue;
		else {
			$lbl = htmlspecialchars($it);
			$att = $lbl; /* NEEDSWORK */
		}
		$it = htmlspecialchars($it);
		$form .= ("<input type=\"radio\" " .
			  "value=\"$it\" " .
			  "name=\"val-$ix\">$lbl");
	}
	$text .= "%%$ix%%";
	$ix++;
}

function template_compile_select($choice, &$ix, &$form, &$text)
{
	$form .= "<select name=\"val-$ix\">\n";
	foreach ($choice as $it) {
		if ($it == '')
			$lbl = "(空欄)";
		else
			$lbl = htmlspecialchars($it);
		$it = htmlspecialchars($it);
		$form .= "<option value=\"$it\">$lbl</option>\n";
	}
	$form .= "</select>";
	$text .= "%%$ix%%";
	$ix++;
}

function template_compile_begin($choice, &$group_stack, &$group_sp,
				&$ix, &$form, &$text, &$err)
{
	if (count($choice) != 1) {
		$err = "<<<:begin name>>> needs exactly one name";
		return;
	}
	$name = $choice[0];
	$lbl = htmlspecialchars($name);
	$att = htmlspecialchars($name);
	$form .= ("<div><div class=\"fold_group\"><input type=\"checkbox\" ".
		  "id=\"grp-$ix\" name=\"val-$ix\"\n" .
		  " onchange=\"foldDiv('grp-div-$ix', 'grp-$ix')\">$lbl" .
		  "<input\n type=\"hidden\" name=\"lbl-$ix\" " .
		  "value=\"$att\"\n></div>" .
		  "<div id=\"grp-div-$ix\" class=\"foldable_group\" " .
		  "style=\"display: none;\">");
	$text .= "%%$ix%%";
	$group_stack[$group_sp] = array('index' => $ix, 'name' => $name);
	$group_sp++;
	$ix++;
}

function template_compile_end($choice, &$group_stack, &$group_sp,
			      &$ix, &$form, &$text, &$err)
{
	$groups = $group_sp;
	if (!$groups) {
		$err = "<<<:end>>> without <<<:begin name>>>";
		return;
	}
	if (count($choice)) {
		$err = "<<<:end>>> does not take parameters";
		return;
	}
	$info = $group_stack[$groups-1];

	$begin = $info['index'];
	$name = $info['name'];

	$form .= ("<input type=\"hidden\" name=\"end-$begin\"" .
		  " value=\"$ix\"></div></div><!--\n" .
		  "end of $name -->"
		);
	$text .= "%%$ix%%";
	$ix++;
	$group_sp--;
}

function template_compile_compile_one(&$ix, $col, $label,
				      &$form_, &$desc, $src, &$err)
{
	$pos = 0;
	$len = strlen($src);
	$text = '';
	$form = '<tr><td>' . htmlspecialchars($label) . '</td><td>';
	$group_stack = array();
	$group_sp = 0;

	while ($pos < $len) {
		$opn = strpos($src, '<<<', $pos);

		if ($opn === false)
			break;

		$clo = strpos($src, '>>>', $pos);
		if ($clo === false) {
			$err = "In $col, <<< at $opn is not closed: $str";
			return;
		}

		$piece = substr($src, $pos, $opn - $pos);
		template_compile_plain_text($piece, &$text, &$form);

		$special = substr($src, $opn + 3, 1);
		if ($special == ':') {
			/*
			 * spelled out specials, such as
			 * <<<:db ... >>>, <<<:begin name>>> or
			 * <<<:end>>>
			 */
			$spos = strpos($src, ' ', $opn + 3);
			if (($spos === false) || ($clo < $spos))
				$spos = $clo;
			$special = substr($src, $opn + 3, $spos - $opn - 3);
		}
		else if ($special == '*')
			$spos = $opn + 4;
		else {
			$special = '';
			$spos = $opn + 3;
		}
		$piece = substr($src, $spos, $clo - $spos);

		$piece = str_replace('|||', "\n", $piece);
		$choice = template_split_choices($piece);

		if ($special == '*' || $special == ':multi') {
			template_compile_multi($choice, &$ix, &$form, &$text,
					       &$err);
		}
		else if ($special == ':radio') {
			template_compile_radio($choice, &$ix, &$form, &$text,
					       &$err);
		}
		else if ($special == ':begin')
			template_compile_begin($choice,
					       &$group_stack, &$group_sp,
					       &$ix, &$form, &$text, &$err);
		else if ($special == ':end')
			template_compile_end($choice,
					     &$group_stack, &$group_sp,
					     &$ix, &$form, &$text, &$err);
		else if ((1 < count($choice)) ||
			 (count($choice) == 1 && $choice[0] != '')) {
			template_compile_select($choice, &$ix, &$form, &$text,
						&$err);
		}
		else {
			/* plain entry box */
			$text .= "%%$ix%%";
			$form .= ("<input type=\"text\" " .
				  "name=\"val-$ix\">");
			$ix++;
		}
		if ($err != '') {
			$err = "Error for $col in $piece:\n$err";
			return;
		}
		$pos = $clo + 3;
	}

	while ($group_sp > 0) {
		/* autoclose leftover :begin */
		template_compile_end(array(),
				     &$group_stack, &$group_sp,
				     &$ix, &$form, &$text, &$err);
	}

	$piece = substr($src, $pos);
	template_compile_plain_text($piece, &$text, &$form);
	$form .= "</td></tr>\n";
	if (trim($text) != '') {
		$form_ .= $form;
		$desc[$col] = $text;
	}
}

?>
