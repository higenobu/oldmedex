<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

class static_generator {
	var $generator = 'u/manage/static-generate.php';

	function static_generator() {

		$pathinfo = $_SERVER['PATH_INFO'];
		if ($pathinfo == '') {
			$this->generate();
			return;
		}
		$m = array();
		$pathinfo = "/tmp$pathinfo";
		if (!preg_match('/^(.*)\/medex\.zip$/',
				$pathinfo, &$m)) {
			print "Cannot download";
			return;
		}
		$tmpdir = $m[1];
		$fd = fopen($pathinfo, "r");
		if (!$fd) {
			print "Cannot open zip";
			return;
		}
		header("content-type: application/x-zip");
		while (!feof($fd)) {
			$data = fread($fd, 4096);
			print "$data";
			flush();
			ob_flush();
		}
		fclose($fd);
		unlink($pathinfo);
		rmdir($tmpdir);
	}

	function generate() {
		global $_mx_site_url;
		global $_mx_resource_dir, $_mx_js_files, $_mx_css_files;

		$m = array();
		preg_match('/^(\/au\/[^\/]*\/)/', $_SERVER['PHP_SELF'], &$m);
		$prefix = $_mx_site_url;
		while (substr($prefix, -1) == '/')
			$prefix = substr($prefix, 0, -1);
		$this->prefix = $prefix . $m[1];

		$this->urls = array();
		foreach ($_mx_js_files as $js)
			$this->push("$_mx_resource_dir/$js.js",
				    "rsrc/$js.js");
		foreach ($_mx_css_files as $css)
			$this->push("$_mx_resource_dir/$css.css",
				    "rsrc/$css.css");

		$this->db = mx_db_connect();
		$this->since = NULL;
		if ($_REQUEST['since']) {
			$this->since = $_REQUEST['since'];
			if (mx_db_validate_date($this->since)) {
				print "Bad date spec $since";
				return;
			}
		}
		if ($this->has_new_patients()) {
			$this->push('karte/index.htm');
		}
		$this->check_karte();
		$this->emit();
	}

	function emit() {
		global $_mx_staticgen_cmd;

		$proc = proc_open($_mx_staticgen_cmd,
				  array(0 => array('pipe', 'r'),
					1 => array('pipe', 'w'),
					2 => array('file', '/tmp/err', 'a')),
				  $pipes);

		$fd = $pipes[0];
		$prefix = $this->prefix;
		$today = mx_today_string(0);
		fwrite($fd, "prefix = $prefix\n");
		fwrite($fd, "today = $today\n");
		foreach ($this->urls as $dest => $url) {
			fwrite($fd, "page = $dest	$url\n");
		}
		fclose($fd);

		$fd = $pipes[1];
		$medex_zip = fread($fd, 4096);
		fclose($fd);
		proc_close($proc);

		$m = array();
		if (!preg_match('/\/tmp\/(.*\/medex\.zip)$/',
				$medex_zip, &$m)) {
			print "Cannot download |$medex_zip|";
			return;
		}
		$zip = $m[1];
		$me = preg_replace('/^(?:.*\/)/', '', $_SERVER['PHP_SELF']);
		mx_http_redirect("$me/$zip");
	}

	function push($url, $dest=NULL) {
		if (is_null($dest)) {
			$dest = $url;
			$url = $this->generator . "/$url";
		}
		$this->urls[$dest] = $url;
	}

	function has_new_patients() {
		return 1;
	}

	function check_karte() {
		if (is_null($this->since)) {
			$since_1 = 'TRUE';
			$since_2 = 'TRUE';

			/*
			 * Send all patients, even ones without
			 * any Karte record yet.
			 */
			$stmt = <<<SQL
SELECT "ObjectID"
FROM "患者台帳"
WHERE "Superseded" IS NULL
SQL;
			$result = pg_fetch_all(pg_query($this->db, $stmt));
			if (!$result)
				return;
			foreach ($result as $d) {
				$pt = $d['ObjectID'];
				$this->push("karte/$pt/index.htm");
			}
		} else {
			$since = mx_db_sql_quote($this->since);
			$since_1 = "\"日付\" >= $since ";
			$since_2 = "B.\"Superseded\" >= $since ";
		}

		$stmt = <<<SQL
SELECT A."患者", A."ObjectID",
       A."I1", A."I2", A."I3", A."I4",
       A."I5", A."I6", A."I7", A."I8"
FROM "カルテデモ表" A
WHERE
	("Superseded" IS NULL AND $since_1) OR
	EXISTS (
		SELECT 1
		FROM "カルテデモ表" B
		WHERE
			B."Superseded" IS NOT NULL AND $since_2 AND
			B."ID" = A."ObjectID"
	)
ORDER BY "患者", "ObjectID";
SQL;

		$result = pg_fetch_all(pg_query($this->db, $stmt));
		if (!$result)
			return;
		$seen_pt = array();
		$seen_bm = array();
		foreach ($result as $d) {
			$pt = $d['患者'];
			$oid = $d['ObjectID'];

			/*
			 * When sending all, we already have sent
			 * all the patients.  Otherwise send only
			 * the ones that we haven't.
			 */
			if (!is_null($this->since) &&
			    !array_key_exists($pt, $seen_pt)) {
				$this->push("karte/$pt/index.htm");
				$seen_pt[$pt] = 1;
			}
			$this->push("karte/$pt/$oid.htm");
			for ($i = 1; $i <= 8; $i++) {
				$bm = $d["I$i"];
				if (is_null($bm))
					continue;
				if (array_key_exists($bm, $seen_bm))
					continue;
				$this->push("blobmedia.php/$bm/x.jpg",
					    "blob/$bm.jpg");
				$seen_bm[$bm] = 1;
			}
		}

	}
}

new static_generator();

?>
