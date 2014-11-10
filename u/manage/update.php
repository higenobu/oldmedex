<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/app-auth.php';

class update_application {

	var $title = '製品の更新';

	function update_application() {
		$this->u = mx_authenticate_user();
		$this->auth = mx_authorization();
	}

	function main() {
		if (!$this->auth[0]) {
			mx_authorization_error($this->auth);
			return;
		}

		if (array_key_exists('start', $_REQUEST))
			$this->start();
		else if (array_key_exists('started', $_REQUEST))
			$this->progress();
		else
			$this->draw();
	}

	function head() {
		mx_html_head($this->auth[1]);
		print "<body><br /><br /><br /><br />\n";
		mx_titlespan($this->title);
		print '<hr />';
	}

	function tail() {
		print "</body></html>\n";
	}

	function draw() {
		$this->head();

		print "<form class=\"login-submit\" method=\"POST\">\n";

		print "<ul>\n";
		print "<li>";
		print "<input type=\"radio\" name=\"media\" value=\"dvd\">";
		print "DVD/CD-ROM から更新";
		print "</li>\n";

		print "<li>";
		print "<input type=\"radio\" name=\"media\" value=\"net\">";
		print "インターネットから更新";
		print "</li>\n";
		print "</ul>\n";

		mx_formi_submit('start', "更新", "更新", "更新");

		print "</form>\n";

		$this->tail();
	}

	function start() {
		global $_mx_pg_port;

		if (file_exists("/var/tmp/update-status"))
			;
		else {
			$r = dirname($_SERVER['DOCUMENT_ROOT']);
			putenv("MEDEX_ROOT=$r");
			if ($_mx_pg_port != '')
				putenv("PGPORT=$_mx_pg_port");

			$cmd = "$r/tools/baseupdate";
			$media = $_REQUEST['media'];
			system("$cmd $media");
		}
		$this->progress();
	}

	function progress_msg($ok, $line) {
		global $_mx_resource_dir;
		$url = "/$_mx_resource_dir/images/update$ok.png";
		print "<img src=\"$url\">";
		print "$line<br />\n";
	}

	function progress_body() {
		$h = fopen("/var/tmp/update-status", "r");
		$ok = 'OK';
		while (!feof($h)) {
			$line = fgets($h);
			$match = array();
			$ok = 'OK';
			if ($line == '')
				break;
			if (preg_match('/^([A-Z]+) (.*)$/', $line, &$match)) {
				$ok = $match[1];
				$line = $match[2];
			}
			$line = htmlspecialchars($line);
			$this->progress_msg($ok, $line);
			if ($ok == 'DONE' || $ok == 'ERR')
				break;
		}
		fclose($h);
		return $ok;
	}

	function progress() {
		$this->head();

		for ($retries = 0; $retries < 10; $retries++) {
			if (!file_exists("/var/tmp/update-status"))
				sleep(1);
			else
				break;
		}
		if (file_exists("/var/tmp/update-status"))
			$ok = $this->progress_body();
		else {
			$this->progress_msg('ERR', "更新サービスは停止中です");
			$ok = 'NOT';
		}

		if ($ok == 'OK') {
			print "<script>\n";
			print "function Redirect(){location.href=";
			print "\"update.php?started=1\";}\n";
			print "setTimeout('Redirect()',1500);\n";
			print "</script>\n";
		}
		else
			unlink("/var/tmp/update-status");

		if ($ok == 'DONE' || $ok == 'ERR') {
			print "<a href=\"../../logout.php\">ログアウト</a>";
		}
		$this->tail();
	}
}

$app = new update_application();
$app->main();
