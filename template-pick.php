<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

class template_category {

	function template_category($name, $pos) {
		$this->name = $name;
		$this->subcategory = array();
		$this->template = array();
		$this->pos = $pos;
	}

	function add_subcategory($name) {
		if (array_key_exists($name, $this->subcategory))
			return;
		$pos = $this->pos . '-' . count($this->subcategory);
		$this->subcategory[$name] =
			new template_category($name, $pos);
	}

	function add_template($name, $objectid) {
		if (array_key_exists($name, $this->template))
			return;
		$pos = $this->pos . '-' . count($this->template);
		$this->template[$name] = array('category' => $this->name,
					       'name' => $name,
					       'ObjectID' => $objectid,
					       'pos' => $pos);
	}

}

class template_pick_application {

	// Input: ID (from the main application that points at the appstate)
	//        use-template (final selection)

	// The form should come back to this page and then we need to
	// redirect it to template-fill with state and use-template.

	function add_template($tuple) {
		$bin = &$this->template;
		$category = $tuple['category'];
		$catname = '';
		foreach (explode('/', $category) as $elem) {
			$bin->add_subcategory($elem);
			$bin = &$bin->subcategory[$elem];
		}
		$bin->add_template($tuple['name'], $tuple['ObjectID']);
	}

	// Return list of templates in parsed format.
	function fetch_templates(&$db, $ID) {
		$stmt = <<<SQL
SELECT T."ObjectID" as "ObjectID", T.name as "name", T.category as "category"
FROM mx_appstate AS S
JOIN mx_template AS T
ON S.application = T.application AND
T."Superseded" IS NULL AND (T.disabled != 'Y' OR T.disabled IS NULL)
WHERE S.id = $ID
ORDER BY "category", "name"
SQL;
		$sth = pg_query($db, $stmt);
		$result = pg_fetch_all($sth);
		$parsed = 0;
		$single = NULL;
		foreach ($result as $tuple) {
			$this->add_template($tuple);
			$parsed++;
			if ($parsed != 1)
				$single = 0;
			else
				$single = $tuple['ObjectID'];
		}
		return $single;
	}

	function template_picked() {
		$ID = $_REQUEST['ID'];
		$template = $_REQUEST['use-template'];
		mx_http_redirect('/au/' . $_SERVER['URL_PREFIX_COOKIE'] .
				 '/template-fill.php?'.
				 "state=$ID&use-template=$template");
	}

	function resume_application() {
		if ($this->debug) {
			header("Content-type: text/plain\n");
			print "\n";
			print "Debug mode\n";
		}

		$id = $_REQUEST['ID'];
		$db = mx_db_connect();
		template_restore_appstate(&$db, $id, 'Cancel', $this->debug);
	}

	function main() {
		global $_mx_resource_dir;

		$this->u = mx_authenticate_user();
		$this->auth = mx_authorization();
		if (! $this->auth[0])
			return mx_authorization_error($this->auth);
		if (array_key_exists('use-template', $_REQUEST))
			return $this->template_picked();
		if (array_key_exists('Cancel', $_REQUEST))
			return $this->resume_application();

		$this->template = new template_category('', '0');

		$ID = $_REQUEST['ID'];
		$db = mx_db_connect();
		$single = $this->fetch_templates(&$db, $ID);
		if ($single) {
			mx_http_redirect('/au/' .
					 $_SERVER['URL_PREFIX_COOKIE'] .
					 '/template-fill.php?mode=single&'.
					 "state=$ID&use-template=$single");
			return;
		}
		else if (is_null($single)) {
			/* there is _no_ template for the application */
			return $this->resume_application();
		}

		mx_html_head($this->auth[1], 1);
		print "</head>\n";
		print "<form method=\"POST\" action=\"template-pick.php\">\n";
		mx_formi_hidden('ID', $_REQUEST[ID]);

		mx_titlespan('テンプレート選択');
		$this->draw_list();

		mx_formi_submit('Cancel', 'キャンセル',
				'<img src="/' . $_mx_resource_dir .
				'/images/rollback_button.png">');

		print "</form></body></html>\n";
	}

	function draw_list() {
		print "<div class=\"foldTemplate\">\n";
		$this->draw_list_1($this->template, 0);
		print "</div>\n";
	}

	function draw_list_1($category, $level=0) {
		global $_mx_resource_dir;

		$p = $category->pos;
		$in = '';
		for ($i = 0; $i < $level; $i++)
			$in .= '   ';
		$ti = "/$_mx_resource_dir/images/template.png";
		if ($level) {
			print "$in<!-- category: ";
			print $category->name;
			print " -->\n";
			if ($level < 2) {
				$shi = "/$_mx_resource_dir/images/show.png";
				$shc = '';
			}
			else {
				$shi = "/$_mx_resource_dir/images/hide.png";
				$shc = ' style="display:none"';
			}
			print "$in<div>\n";
			print "$in <a href=\"javascript:void(0)\"\n";
			print "$in  onclick=\"show_hide('$p','$_mx_resource_dir')\"\n";
			print "$in ><img id=\"SHC-$p\" src=\"$shi\"\n";
			print "$in   width=\"18\" height=\"18\"\n";
			print "$in   border=\"0\" alt=\"\" />";
			print htmlspecialchars($category->name);
			print "</a>\n";
			print "$in</div>\n";
			print "$in<div$shc id=\"SHD-$p\">\n";
		}

		print "$in <ul>\n";
		if (count($category->template)) {
			foreach ($category->template as $t) {
				print "$in  <li>\n";
				print "$in   <img src=\"$ti\"";
				print " width=\"18\" height=\"18\"\n";
				print "$in     border=\"0\" alt=\"\" />";
				print "<button class=\"plain\" ";
				print "name=\"use-template\" ";
				print "value=\"" . $t['ObjectID'] . "\"";
				print "title=\"テンプレート選択\"\n";
				print "$in   ><span class=\"link\">";
				print htmlspecialchars($t['name']);
				print "</span></button>\n";
				print "$in  </li>\n";
			}
		}
		if (count($category->subcategory)) {
			foreach ($category->subcategory as $s) {
				print "$in  <li>\n";
				$this->draw_list_1($s, $level + 1);
				print "$in  </li>\n";
			}
		}
		print "$in </ul>\n";
		print "$in</div>\n";
	}
}

$t = new template_pick_application();
$t->main();
?>
