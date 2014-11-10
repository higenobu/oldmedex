<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/nagmail.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/reception/apptmail.php';

class appt_nagmail_batch_base {

	function send_message($db, $purpose, $template, $idata, $row) {

		$subject = $template['subject'];
		$body = $template['body'];
		$template = $template['template'];
		$addressee = $row['¥á¡¼¥ë¥¢¥É¥ì¥¹'];

		$row['Í½Ìó»þ¹ï'] = mx_format_timestamp($row['Í½Ìó»þ¹ï'], 0);
		$row['Í½ÌóÆü»þ'] = $row['Í½Ìó»þ¹ï'];
		$row['Í½ÌóÆü'] = substr($row['Í½Ìó»þ¹ï'], 0, 10);
		$row['ÉÂ±¡Ì¾'] = $idata['HOSPITAL_NAME'];

		_lib_u_manage_nagmail_send_nagmail
			($db, $purpose, $row['rsched_id'], NULL, $template,
			 $addressee, $subject, $body, $row);
	}

	function main() {
		$db = mx_db_connect();

		$cfg = $this->naglist_cfg();
		$ee = appt_list_yet_to_show
			($db,  $cfg['Bottom'], $cfg['Top'], $cfg['Purpose']);
		$count = count($ee);
		if (!$count)
			return 0;

		# Grab one template, the first one.
		$ents = _lib_u_manage_nagmail_find_template($this->purpose);
		if (!$ents)
			return 1;
		$t = $ents[0];
		$idata = mx_get_install_data();
		$purpose = $cfg['Purpose'];

		for ($i = 0; $i < $count; $i++) {
			$row = $ee[$i];
			$name = $row['´µ¼ÔID'];
			print "$purpose $name\n";
			$this->send_message($db, $purpose, $t, $idata, $row);
		}

	}

}

class appt_nagmail_batch_missed extends appt_nagmail_batch_base {
	var $purpose = 'APPT_MISSED';

	function naglist_cfg() {
		$bottom = mx_today_string(-86400*7);
		$top = mx_today_string(-86400*1);
		return array('Bottom' => $bottom,
			     'Top' => $top,
			     'Purpose' => $this->purpose);
	}
}

class appt_nagmail_batch_remind extends appt_nagmail_batch_base {
	var $purpose = 'APPT_REMIND';

	function naglist_cfg() {
		$bottom = mx_today_string(86400*2);
		$top = mx_today_string(86400*8);
		return array('Bottom' => $bottom,
			     'Top' => $top,
			     'Purpose' => $this->purpose);
	}
}

?>
