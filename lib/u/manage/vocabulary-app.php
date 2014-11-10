<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/sta.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/vocabulary.php';

class vocabulary_edit_application extends single_table_application {

	var $_browse_only = 1; // no "New" "Edit" etc controls please
	var $vocab_title = 'ƒÍ∑ø ∏'; # '∏Ï◊√';

	function setup_widgets () {
		single_table_application::setup_widgets();

		if (mx_check_request('VocabularyConsolidate'))
			$this->consolidate();

		if (mx_check_request('OkToCommit'))
			$this->commit_it();

		if (!mx_check_request('BackToPickWord') &&
		    mx_check_request('WordID') &&
		    mx_check_request('WordGroup') &&
		    mx_check_request('WordDep') &&
		    mx_check_request('VocabID') &&
		    mx_check_request('VocabLabel') &&
		    !is_null(mx_check_request('VocabDep'))) {
			$this->edit_word = array(mx_check_request('WordID'),
						 mx_check_request('WordGroup'),
						 mx_check_request('WordDep'));
			$this->edit_vocab = array(mx_check_request('VocabID'),
						  mx_check_request('VocabLabel'),
						  mx_check_request('VocabDep'));
		} else if (!mx_check_request('BackToPickVocab') &&
			   mx_check_request('VocabID') &&
			   mx_check_request('VocabLabel') &&
			   !is_null(mx_check_request('VocabDep'))) {
			$this->edit_vocab = array(mx_check_request('VocabID'),
						  mx_check_request('VocabLabel'),
						  mx_check_request('VocabDep'),
						  );
		} else {
			$w = new list_of_vocab('lov-');
			if ($w->changed() && $w->chosen()) {
				$a = $w->chosen();
				$this->edit_vocab = mx_form_unescape_key($a);
			} else {
				$this->vocab_picker = $w;
			}
		}

		if (!$this->edit_vocab) {
			$this->action = 'draw_initial';
			return;
		}

		$w = new list_of_vocab_words('low-', $this->edit_vocab);
		if ($w->changed() && $w->chosen()) {
			$this->edit_word = mx_form_unescape_key($w->chosen());
		}

		if (!$this->edit_word) {
			$this->word_picker = $w;
			$this->action = 'pick_wordset';
			return;
		}

		$this->action = 'edit_wordset';

	}

	function left_pane () {
		$action = $this->action;
		$this->$action();
	}

	function draw_initial () {
		mx_titlespan($this->vocab_title);
		print "<hr />\n";
		$this->vocab_picker->draw();
	}

	function pick_wordset () {
		$vocab_object_id = $this->edit_vocab[0];
		$vocab_label = $this->edit_vocab[1];
		$vocab_depend = $this->edit_vocab[2];

		mx_formi_hidden('VocabID', $vocab_object_id);
		mx_formi_hidden('VocabLabel', $vocab_label);
		mx_formi_hidden('VocabDep', $vocab_depend);
		mx_titlespan($this->vocab_title . ':' .
			     htmlspecialchars($vocab_label));
		mx_formi_submit('BackToPickVocab', 'BackToPickVocab', 'Ã·§Î');

		print "<hr />\n";
		$this->word_picker->draw();

	}

	function edit_wordset () {
		$vocab_object_id = $this->edit_vocab[0];
		$vocab_label = $this->edit_vocab[1];
		$vocab_depend = $this->edit_vocab[2];
		$word_object_id = $this->edit_word[0];
		$word_group = $this->edit_word[1];
		$word_depend = $this->edit_word[2];

		mx_formi_hidden('VocabID', $vocab_object_id);
		mx_formi_hidden('VocabLabel', $vocab_label);
		mx_formi_hidden('VocabDep', $vocab_depend);
		mx_formi_hidden('WordID', $word_object_id);
		mx_formi_hidden('WordDep', $word_depend);
		$title = $this->vocab_title . ':' . $vocab_label;
		if (trim($word_depend) != '') {
			$w = str_replace("\n", "°¶", trim($word_depend));
			$title .= "($w)";
		}
		mx_titlespan(htmlspecialchars($title));

		mx_formi_submit('BackToPickWord', 'BackToPickWord', 'Ã·§Î');

		print "<hr />\n";
		mx_formi_textarea('WordGroup', $word_group,
				  array('cols' => 60, 'rows' => 30));
		print "<hr />\n";
		mx_formi_submit('OkToCommit', 'OkToCommit', '≈–œø¥∞Œª');
	}

	function commit_it() {
		$word_object_id = mx_check_request('WordID');
		$wg = mx_check_request('WordGroup');
		$w = array();
		foreach (explode("\n", $wg) as $e) {
			if (trim($e) == '')
				continue;
			$w[] = $e;
		}
		$wg = mx_db_sql_quote("\n" . implode("\n", $w) . "\n");
		$stmt = <<<SQL
UPDATE "∏Ï∂Á∑≤" SET "∏Ï∂Á∑≤" = $wg
WHERE "ObjectID" = $word_object_id
SQL;
		print "<!-- $stmt -->";

		$db = mx_db_connect();
		pg_query($db, $stmt);

		/* Let it go back to pick_wordset */
		$_REQUEST['BackToPickWord'] = 1;

	}

	function consolidate() {
		__lib_u_manage_vocabulary_consolidate();
	}

}
?>
