<?php // -*- coding: euc-jp -*-
// wrap meds and directions arrays
// very primitive.

include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ui_config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/ord_common.php';

// XXX:  i want to replace all list_med with draw_dropdown.
function draw_dropdown($x, $y, $z) {
  list_med($x, $y, $z);
}


class med {
	var $med;
	var $exteral;
	var $error = 0;
	function med($med) {
		$this->med = $med;
		$this->external = ($med["��ʬ"] == "��");
	}
	function get_array(){
		return $this->med;
	}
	function draw($row_num){
		global $__mx_formi_dek;
		global $__uiconfig_u_pharmacy_accepted;
		global $_mx_rx_use_vocab;
		global $_mx_link_mediserve;
		global $_mx_hack_takamiya;
		global $_mx_rx_default_amount;
		$class = $this->external ? 'rxy' : 'rxe';
		$v = $this->med["�쥻�ץ��Ż����������ƥ������̾"];
		$v2 = $this->med["�쥻�ץ��Ż����������ƥॳ���ɡʣ���"];
		$generic_ok = $this->med["generic_ok"] ? "checked" : "";

		print '<input type="hidden" name="med'.$row_num.'medis" value="'.$this->med['medis'].'">';
		print '<input type="hidden" name="med'.$row_num.'�쥻�ץ��Ż����������ƥ������̾" value="'.$v.'">';
		print '<input type="hidden" name="med'.$row_num.'�쥻�ץ��Ż����������ƥॳ���ɡʣ���" value="'.$v2.'">';
		print '<input type="hidden" name="med'.$row_num.'��ʬ" value="'.$this->med['��ʬ'].'">';
		print '<input type="hidden" name="med'.$row_num.'accept" value="'.$this->med['accept'].'">';
		print '<input type="hidden" name="med'.$row_num.'����ñ��" value="'.$this->med['����ñ��'].'">';

		$bg='';
		if($this->error) {
			$class='';
			$bg = ';background-color: #f00;';
		}


		// Row 1, Column 1-4:  Medicine name
		print '<tr class="'.$class.'" style="border: hidden'.$bg.'">';

		if ($_mx_hack_takamiya)
		  print '<td style="border: hidden; font-size: 100%">';
		else
		  print '<td colspan="4" style="border: hidden; font-size: 150%">';
		if ($_mx_link_mediserve)
		  print "<button type=\"button\" onClick=\"javascript:window.open('/svc/mediserve.php?mode=direction&yj={$v3}', '_blank'); return false;\">";

		if(is_null($this->med['accept']) or 
		   !array_key_exists($this->med['accept'],
				     $__uiconfig_u_pharmacy_accepted))
		  print "<font color=red>$v</font>";
		else
		  print $v;
		if ($_mx_link_mediserve)
		  print '</button>';
		if ($_mx_hack_takamiya)
		  print '</td>';
		else
		  print '</td></tr>';


		// Row 2
		if(!$_mx_hack_takamiya)
		  if ($this->unit_warning)
		    print '<tr style="border: hidden; background-color: #fdd;">';
		  else
		    print '<tr class="'.$class.'" style="border: hidden;">';

		// Column 1:  Amount and Unit, ���ߤ���������
		if ($_mx_hack_takamiya)
		  print '<td style="border: hidden; font-size: 100%"><input type="text" maxlength="5" style="width: 3em" class="value" id="med_amount' . $row_num . '" name="med'.($row_num).'����" value="'.($this->med["����"] == "" ? "1" : $this->med["����"]).'"'. $__mx_formi_dek . '>';
		else
		  print '<td style="border: hidden; font-size: 150%"><input type="text" maxlength="5" style="width: 3em" class="value" id="med_amount' . $row_num . '" name="med'.($row_num).'����" value="'.($this->med["����"] == "" ? $_mx_rx_default_amount : $this->med["����"]).'"'. $__mx_formi_dek . '>';
		//draw_dropdown("med".$row_num."unitid",($this->med["unitid"]),"units");
		print " " . $this->med['����ñ��']. " ";
                if ($this->unit_warning)
		      print "[ñ���ѹ�����]";
		print "(<input type=\"text\" name=\"med{$row_num}��ˡʬ��\" ".$__mx_formi_dek."maxlength=\"8\" style=\"width: 5em\" value=\"{$this->med['��ˡʬ��']}\">)";
		print "</td>";

		// Column 2:  ������
		print "<td>";
		if ($_mx_rx_use_vocab)
		  mx_formi_textarea("med{$row_num}����¾������",
				    $this->med['����¾������'],
 				    array('cols' => 40,
 					  'rows' =>1,
 					  'vocab' => array('RX������'),
 					  'icon' => 'vocab2.png'
 					 ));
 
		else
		  print "<input type=\"text\" name=\"med{$row_num}����¾������\" ".$__mx_formi_dek."
            maxlength=\"64\" style=\"width: 17em\" value=\"{$this->med['����¾������']}\">";
		print '</td>';

		// Column 3: ��ȯ�ʲ�  05-10-2012 0--fuka 
		print "<td  style=\"border: hidden\"><input type=\"checkbox\" name=\"med{$row_num}generic_ok\" id=\"med{$row_num}generic_ok\" $__mx_formi_dek value=\"1\" ${generic_ok}></td>";

		// Column 4: ����ܥ���
		print '<td  style="border: hidden">';
		print "<button type=\"submit\" name=\"delcont\" value=\"{$row_num}\">���</button>";
		print '</td>';

		// End of Row2
		print '</tr>';
	}
}

class direction {
	var $med;
	var $error;
	function direction($med) {
		$this->med = $med;
	}

	function get_array(){
		return $this->med;
	}

	function draw($row_num){
		global $__mx_formi_dek;
		global $_mx_hack_takamiya;
		$class='rxo';
		$bg='';
		if($this->error) {
			$class='';
			$bg = ';background-color: #f00';
		}
		// Row 1
		print '<tr class="'.$class.'" style="border: hidden'.$bg.'">';

		// Column 1-3:  yoho
		if ($_mx_hack_takamiya)
		  print '<td  colspan="4" style="border: hidden">';
		else
		  print '<td  colspan="3" style="border: hidden">';
		draw_dropdown("med".$row_num."freqid",$this->med["freqid"],"freq");

		// days
		print '<input type="text" maxlength="3" style="width: 3em" class="value" id="dir_day' . $row_num . '" name="med'.($row_num).'����" value="'.$this->med["����"].'"'.$__mx_formi_dek . ">";
		if(array_key_exists("����", $this->med) && !is_null($this->med["����"])){
		  print "<select name=\"med".$row_num."����\">";
		  /*
		  print "<option value=-1 ";
		  if( $this->med["����"] == -1)
		    print "selected";
		  print "></option>";
		  */
		  print "<option value=0 ";
		  if( $this->med["����"] == 0)
		    print "selected";
		  print ">��ʬ</option>";
		  print "<option value=1 ";
		  if( $this->med["����"] == 1)
		    print "selected";
		  print ">��ʬ</option>";
		  print "</select>";
		}else{
		  print '��ʬ';
		}

		print "<input type=\"hidden\" name=\"med{$row_num}row_num\" value=\"{$row_num}\">";
		print "<input type=\"checkbox\" name=\"med{$row_num}����\" value=\"1\"".($this->med['����'] == 1 ? "checked" : ""). $__mx_formi_dek .">�嵭�����񲽡�";

		print '</td>';

		// Column 5: ����ܥ���
		print '<td  style="border: hidden">';
		print "<button type=\"submit\" name=\"delcont\" value=\"{$row_num}\">���</button>";
		print '</td>';
		print '</tr>';
	}
}

//-----------------------------------------------------------
// shots
//-----------------------------------------------------------

class shot {
	var $med;
	var $exteral;
	var $error = 0;
	function shot($med) {
		$this->med = $med;
		$this->external = ($med["��ʬ"] == "��");
	}
	function get_array(){
		return $this->med;
	}
	function draw($row_num){
		global $__mx_formi_dek;
		global $__uiconfig_u_pharmacy_accepted;
		global $_mx_injection_use_vocab;
		$class = $this->external ? 'rxy' : 'rxe';
		$v = $this->med["�쥻�ץ��Ż����������ƥ������̾"];
		$v2 = $this->med["�쥻�ץ��Ż����������ƥॳ���ɡʣ���"];
		$generic_ok = $this->med["generic_ok"] ? "checked" : "";

		print '<input type="hidden" name="med'.$row_num.'medis" value="'.$this->med['medis'].'">';
		print '<input type="hidden" name="med'.$row_num.'�쥻�ץ��Ż����������ƥ������̾" value="'.$v.'">';
		print '<input type="hidden" name="med'.$row_num.'�쥻�ץ��Ż����������ƥॳ���ɡʣ���" value="'.$v2.'">';
		print '<input type="hidden" name="med'.$row_num.'��ʬ" value="'.$this->med['��ʬ'].'">';
		print '<input type="hidden" name="med'.$row_num.'accept" value="'.$this->med['accept'].'">';
		print '<input type="hidden" name="med'.$row_num.'����ñ��" value="'.$this->med['����ñ��'].'">';

		$bg='';
		if($this->error) {
			$class='';
			$bg = ';background-color: #f00;';
		}

		// Row 1
		print '<tr class="'.$class.'" style="border: hidden'.$bg.'">';

		// Column 1:  Medicine name
		print '<td colspan="4" style="border: hidden; font-size: 150%">';
		if(is_null($this->med['accept']) or 
		   !array_key_exists($this->med['accept'],
				     $__uiconfig_u_pharmacy_accepted))
		  print "<font color=red>$v</font>";
		else
		  print $v;
		print '</td></tr>';

		// Row 2
                if ($this->unit_warning)
		   print '<tr style="border: hidden; background-color: #fdd;">';
                else
		   print '<tr class="'.$class.'" style="border: hidden;">';

		// Column 1:  Amount and Unit, ���ߤ���������
		print '<td style="border: hidden; font-size: 150%"><input type="text" maxlength="5" style="width: 3em" class="value" id="med_amount' . $row_num . '" name="med'.($row_num).'����" value="'.($this->med["����"] == "" ? "1" : $this->med["����"]).'"'. $__mx_formi_dek . '>';

		//draw_dropdown("med".$row_num."unitid",($this->med["unitid"]),"units");
		print " " . $this->med['����ñ��']. " ";
                if ($this->unit_warning)
		      print "[ñ���ѹ�����]";
		print "(<input type=\"text\" name=\"med{$row_num}��ˡʬ��\" ".$__mx_formi_dek."maxlength=\"8\" style=\"width: 5em\" value=\"{$this->med['��ˡʬ��']}\">)";
		print "</td>";

		// Column 2:  ������
		print "<td>";
		if ($_mx_injection_use_vocab)
		  mx_formi_textarea("med{$row_num}����¾������",
				    $this->med['����¾������'],
				    array('cols' => 40,
					  'rows' => 1,
					  'vocab' => array('��ͥ�����'),
					  'icon' => 'vocab2.png'
					));
		else
		  print "<input type=\"text\" name=\"med{$row_num}����¾������\" ".$__mx_formi_dek." maxlength=\"64\" style=\"width: 17em\" value=\"{$this->med['����¾������']}\">";
		print '</td>';

		// Column 3: ��ȯ�ʲ�
		print "<td  style=\"border: hidden\"><input type=\"checkbox\" name=\"med{$row_num}generic_ok\" id=\"med{$row_num}generic_ok\" $__mx_formi_dek value=\"1\" ${generic_ok}></td>";

		// Column 4: ����ܥ���
		print '<td  style="border: hidden">';
		print "<button type=\"submit\" name=\"delcont\" value=\"{$row_num}\">���</button>";
		print '</td>';

		// End of Row2
		print '</tr>';
	}
}

class shots_direction {
	var $med;
	function shots_direction($med) {
		$this->med = $med;
		if(is_null($this->med["����"]))
		  $this->med["����"] = 1;
	}

	function get_array(){
		return $this->med;
	}

	function draw($row_num){
		global $__mx_formi_dek;
		$generic_ok = $this->med["generic_ok"] ? "checked" : "";
		$bg='';
		if($this->error) {
			$class='';
			$bg = ';background-color: #f00';
		}
		// Row 1
		print '<tr class="'.$class.'" style="border: hidden'.$bg.'">';

		// Column 1-3:  dosage
		print '<td colspan="3" style="border: hidden">';
		/*
		draw_dropdown("med".$row_num."dosageid",$this->med["dosageid"],"dosage");
		*/
		
		// method
		draw_dropdown("med".$row_num."methodid",$this->med["methodid"],"method");

		if(array_key_exists("precision", $this->med)) {
		  mx_formi_checkbox("med".$row_num."precision",
				    $this->med["precision"],
				    array('Caption' => "��̩��³�û�"));
		}
		// Column 3: days
		print '<input type="text" maxlength="3" style="width: 3em" class="value" name="med'.($row_num).'����" value="'.$this->med["����"].'"'.$__mx_formi_dek . ">";
		print '��ʬ';
		print '</td>';

		// Column 5: ����ܥ���
		print '<td  style="border: hidden">';
		print "<button type=\"submit\" name=\"delcont\" value=\"{$row_num}\">���</button>";
		print '</td>';
		print '</tr>';
	}
}

?>
