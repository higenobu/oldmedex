<?php
// List editor
// XXX: should integrate with SimpleObject?

class list_editor {
	var $l;
	var $cursor_pos;
	var $count;
        var $header;
	function list_editor() {
		$this->l = array();
		$this->cursor_pos = 0;
		$this->count = 0;
	}

	function get_list() {
		return $this->l;
	}

	function set_cursor($row_num) {
		$this->cursor_pos = $row_num;
	}

	function set_error($row_num) {
		$this->l[$row_num]->error = 1;
	}

	function add($o) {
		$this->l[] = $o;
		$this->count = count($this->l);
	}

	function insert_at_cursor($o) {
		if(count($this->l) > 0){
			$this->l = array_merge(array_merge(
					array_slice($this->l, 0,
						$this->cursor_pos),
					array($o)),
					array_slice($this->l,
						$this->cursor_pos));
		}else{
			$this->l = array($o);
		}
		$this->focusme = $this->cursor_pos;
		$this->cursor_pos++;
		$this->count = count($this->l);
	}

	function delete($row_num) {
		// Rx application doesn't use this for delete
		array_splice($this->l, $row_num, 1);
		if ($row_num < $this->cursor_pos)
			$this->cursor_pos--;
		$this->count = count($this->l);
	}

	function draw_head() {
		print '<TABLE class="listofrxrows">';
		if($this->header)
		  print "<tr>".$this->header."</tr>";
	}
	function draw_tail() {
		print '</TABLE>';
	}
	function draw_row_head() {
		print '<TR>';
	}
	function draw_row_tail() {
		print '</TR>';
	}

	function draw_cursor($row_num) {
		global $__mx_formi_dek;
		$this->draw_row_head();
		printf ('<TD id="ins_pos%d" valign="middle" colspan="5">', $row_num);
		print '<LABEL><INPUT TYPE=RADIO NAME="cursor_pos" VALUE="'.$row_num.'"';
		if ($this->cursor_pos == $row_num)
			print ' CHECKED';

		print $__mx_formi_dek;
		print '><font color="grey">ここに挿入</font>';
		print '</LABEL></TD>';
		$this->draw_row_tail();
 		print "\n";
	}

	function draw() {
		$this->draw_head();
		$row_num = 0;
		foreach($this->l as $row) {
			$this->draw_cursor($row_num);
			$row->draw($row_num);
			$row_num += 1;
		}
		$this->draw_cursor($row_num);
		$this->draw_tail();
	}

}

?>
