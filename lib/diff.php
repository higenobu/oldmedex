<?php // -*- mode: php; coding: euc-japan -*-
require_once 'Text/Diff.php';
require_once 'Text/Diff/Renderer/unified.php';

class Mx_Diff {
	var $encoding = 'EUC-JP';
	var $new_open = '<span class="revctl-new">';
	var $old_open = '<span class="revctl-old">';
	var $new_close = '</span>';
	var $old_close = '</span>';

	function Mx_Diff($old, $new) {
		$old = $this->prepare_input($old);
		$new = $this->prepare_input($new);
		$ctx = count($old) + count($new);
		$arg = array($old, $new);
		$diff = new Text_Diff('auto', $arg);
		$ren = new Text_Diff_Renderer_unified(
			array('leading_context_lines' => $ctx,
			      'trailing_context_lines' => $ctx)
			);
		$diff = explode("\n", $ren->render($diff));
		$old = array();
		$new = array();
		$unified = array();
		$cnt = count($diff);
		$old_marked = 0;
		$new_marked = 0;

		/* Discard $diff[0] as it is "@@ -l,k +m,n @@" header */
		for ($i = 1; $i < $cnt; $i++) {
			$s = $diff[$i];
			$sign = mb_substr($s, 0, 1);
			$ch = mb_substr($s, 1);
			if (mb_substr($ch, 0, 1) == ".")
				$ch = mb_substr($ch, 1);
			if ($ch == "")
				$ch = "<br />";
			else
				$ch = htmlspecialchars($ch);
			switch ($sign) {
			case " ":
				if ($new_marked) {
					$new[] = $this->new_close;
					$unified[] = $this->new_close;
				}
				if ($old_marked) {
					$old[] = $this->old_close;
					$unified[] = $this->old_close;
				}
				$old[] = $ch;
				$new[] = $ch;
				$old_marked = $new_marked = 0;
				break;
			case "+":
				if ($old_marked) {
					$old[] = $this->old_close;
					$unified[] = $this->old_close;
					$old_marked = 0;
				}
				if (!$new_marked) {
					$new[] = $this->new_open;
					$unified[] = $this->new_open;
					$new_marked = 1;
				}
				$new[] = $ch;
				break;
			case "-":
				if ($new_marked) {
					$new[] = $this->new_close;
					$unified[] = $this->new_close;
					$new_marked = 0;
				}
				if (!$old_marked) {
					$old[] = $this->old_open;
					$unified[] = $this->old_open;
					$old_marked = 1;
				}
				$old[] = $ch;
				break;
			}
			$unified[] = $ch;
		}
		if ($new_marked) {
			$new[] = $this->new_close;
			$unified[] = $this->new_close;
		}
		if ($old_marked) {
			$old[] = $this->old_close;
			$unified[] = $this->old_close;
		}
		$this->unified = implode("", $unified);
		$this->old = implode("", $old);
		$this->new = implode("", $new);
	}

	function render($type='unified') {
		switch ($type) {
		case 'unified':
			return $this->unified;
		default:
		case 'both':
			return ('<div>' .
				$this->old .
				'</div>' .
				'вк' .
				'<div>' .
				$this->new .
				'</div>');
		}
	}

	function prepare_input($s) {
		$len = mb_strlen($s, $this->encoding);
		$ret = array();
		for ($i = 0; $i < $len; $i++) {
			$ch = mb_substr($s, $i, 1, $this->encoding);
			/* Dot stuffing */
			if ($ch == "\n" || mb_substr($ch, 0, 1) == ".")
				$ch = ".$ch";
			$ret[] = $ch;
		}
		return $ret;
	}
}
?>
