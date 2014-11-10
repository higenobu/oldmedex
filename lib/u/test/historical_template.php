<?php
$template_header = <<<TEMPLATE_HEADER
<HTML>
<HEAD>
<META http-equiv="Content-Style-Type" content="text/css">
	<STYLE>
		<!-- 
		BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { font-size:9.0pt; font-family : "£Í£Ó £ĞÌÀÄ«";}
		HR { page-break-after: always; border: 0;}
		-->
	</STYLE>
<TITLE>¸¡ºº·ë²Ì»ş·ÏÎóÉ½</TITLE>
</HEAD>
<BODY text="#000000">
TEMPLATE_HEADER;

$template = <<<TEMPLATE
<TABLE frame="VOID" cellspacing="0" rules="NONE">
  <TBODY>
    <TR>
     <TD colspan="15" align="center"><B><FONT SIZE="+2">¸¡¡¡ºº¡¡·ë¡¡²Ì</FONT></B></TD>
    </TR>   <TR>
      <TD colspan="8" align="right" valign="bottom">ºîÀ®Æü¡§</TD>
      <TD valign="bottom">${creation_date}</TD>
    </TR>
    <TR>
      <TD style="border-top: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" width="150">ID:${patient_id}</TD>
      <TD style="border-top: 1.5pt solid #000000; border-left: 1pt solid #000000;" width="60" nowrap>°ÍÍê¸µ</TD>
		  <TD style="border-top: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" width="120" align="center">${pt_group_0}</TD>
      <TD style="border-top: 1.5pt solid #000000; border-left: 1pt solid #000000;" colspan="2" width="120" align="center">${pt_group_1}</TD>
      <TD style="border-top: 1.5pt solid #000000; border-left: 1pt solid #000000;" colspan="2" width="120" align="center">${pt_group_2}</TD>
      <TD style="border-top: 1.5pt solid #000000; border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" width="100" rowspan="4">&nbsp</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;">${patient_name}</TD>
      <TD style="border-left: 1pt solid #000000;" nowrap>°ÍÍê°å</TD>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2">${doctor_0}</TD>
      <TD style="border-left: 1pt solid #000000;" colspan="2" width="120">${doctor_1}</TD>
      <TD style="border-left: 1pt solid #000000;" colspan="2" width="120">${doctor_2}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;">${patient_sex}</TD>
      <TD style="border-left: 1pt solid #000000;" nowrap>¸¡ººÆü</TD>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2">${sample_date_0}</TD>
      <TD style="border-left: 1pt solid #000000;" colspan="2" width="120">${sample_date_1}</TD>
      <TD style="border-left: 1pt solid #000000;" colspan="2" width="120">${sample_date_2}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" width="150" valign="top">${patient_wdob}</TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;" nowrap>Ç¯Îğ</TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2">${patient_age_0}ºĞ</TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;" colspan="2" width="120">${patient_age_1}ºĞ</TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;" colspan="2" width="120">${patient_age_2}ºĞ</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" bgcolor="#c8ffc8" align="center"><B>¹àÌÜÌ¾</B></TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" align="center" width="80" bgcolor="#c8ffc8"><B>·ë²ÌÃÍ</B></TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;" align="center" width="40" bgcolor="#c8ffc8"><B>H/L</B></TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;" align="center" width="80" bgcolor="#c8ffc8"><B>·ë²ÌÃÍ</B></TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;" align="center" width="40" bgcolor="#c8ffc8"><B>H/L</B></TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;" align="center" width="80" bgcolor="#c8ffc8"><B>·ë²ÌÃÍ</B></TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1pt solid #000000;" align="center" width="40" bgcolor="#c8ffc8"><B>H/L</B></TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="center" width="100" bgcolor="#c8ffc8"><B>´ğ½àÈÏ°ÏÃÍ</B></TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_0}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_0_0}</TD>
      <TD align="center">${test_decision_0_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_0_1}</TD>
      <TD align="center">${test_decision_0_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_0_2}</TD>
      <TD align="center">${test_decision_0_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_0}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_1}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_1_0}</TD>
      <TD align="center">${test_decision_1_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_1_1}</TD>
      <TD align="center">${test_decision_1_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_1_2}</TD>
      <TD align="center">${test_decision_1_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_1}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_2}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_2_0}</TD>
      <TD align="center">${test_decision_2_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_2_1}</TD>
      <TD align="center">${test_decision_2_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_2_2}</TD>
      <TD align="center">${test_decision_2_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_2}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_3}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_3_0}</TD>
      <TD align="center">${test_decision_3_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_3_1}</TD>
      <TD align="center">${test_decision_3_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_3_2}</TD>
      <TD align="center">${test_decision_3_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_3}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_4}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" align="right">${test_result_4_0}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_4_0}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_4_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; " align="center">${test_decision_4_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_4_2}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_4_2}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_4}</TD>
    </TR>
   <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_5}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_5_0}</TD>
      <TD align="center">${test_decision_5_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_5_1}</TD>
      <TD align="center">${test_decision_5_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_5_2}</TD>
      <TD align="center">${test_decision_5_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_5}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_6}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_6_0}</TD>
      <TD align="center">${test_decision_6_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_6_1}</TD>
      <TD align="center">${test_decision_6_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_6_2}</TD>
      <TD align="center">${test_decision_6_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_6}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_7}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_7_0}</TD>
      <TD align="center">${test_decision_7_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_7_1}</TD>
      <TD align="center">${test_decision_7_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_7_2}</TD>
      <TD align="center">${test_decision_7_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_7}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_8}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_8_0}</TD>
      <TD align="center">${test_decision_8_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_8_1}</TD>
      <TD align="center">${test_decision_8_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_8_2}</TD>
      <TD align="center">${test_decision_8_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_8}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_9}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" align="right">${test_result_9_0}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_9_0}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_9_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; " align="center">${test_decision_9_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_9_2}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_9_2}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_9}</TD>
    </TR>
   <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_10}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_10_0}</TD>
      <TD align="center">${test_decision_10_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_10_1}</TD>
      <TD align="center">${test_decision_10_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_10_2}</TD>
      <TD align="center">${test_decision_10_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_10}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_11}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_11_0}</TD>
      <TD align="center">${test_decision_11_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_11_1}</TD>
      <TD align="center">${test_decision_11_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_11_2}</TD>
      <TD align="center">${test_decision_11_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_11}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_12}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_12_0}</TD>
      <TD align="center">${test_decision_12_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_12_1}</TD>
      <TD align="center">${test_decision_12_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_12_2}</TD>
      <TD align="center">${test_decision_12_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_12}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_13}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_13_0}</TD>
      <TD align="center">${test_decision_13_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_13_1}</TD>
      <TD align="center">${test_decision_13_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_13_2}</TD>
      <TD align="center">${test_decision_13_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_13}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_14}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" align="right">${test_result_14_0}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_14_0}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_14_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; " align="center">${test_decision_14_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_14_2}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_14_2}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_14}</TD>
    </TR>
   <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_15}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_15_0}</TD>
      <TD align="center">${test_decision_15_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_15_1}</TD>
      <TD align="center">${test_decision_15_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_15_2}</TD>
      <TD align="center">${test_decision_15_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_15}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_16}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_16_0}</TD>
      <TD align="center">${test_decision_16_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_16_1}</TD>
      <TD align="center">${test_decision_16_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_16_2}</TD>
      <TD align="center">${test_decision_16_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_16}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_17}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_17_0}</TD>
      <TD align="center">${test_decision_17_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_17_1}</TD>
      <TD align="center">${test_decision_17_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_17_2}</TD>
      <TD align="center">${test_decision_17_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_17}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_18}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_18_0}</TD>
      <TD align="center">${test_decision_18_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_18_1}</TD>
      <TD align="center">${test_decision_18_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_18_2}</TD>
      <TD align="center">${test_decision_18_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_18}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_19}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" align="right">${test_result_19_0}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_19_0}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_19_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; " align="center">${test_decision_19_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_19_2}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_19_2}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_19}</TD>
    </TR>
   <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_20}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_20_0}</TD>
      <TD align="center">${test_decision_20_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_20_1}</TD>
      <TD align="center">${test_decision_20_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_20_2}</TD>
      <TD align="center">${test_decision_20_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_20}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_21}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_21_0}</TD>
      <TD align="center">${test_decision_21_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_21_1}</TD>
      <TD align="center">${test_decision_21_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_21_2}</TD>
      <TD align="center">${test_decision_21_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_21}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_22}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_22_0}</TD>
      <TD align="center">${test_decision_22_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_22_1}</TD>
      <TD align="center">${test_decision_22_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_22_2}</TD>
      <TD align="center">${test_decision_22_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_22}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_23}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_23_0}</TD>
      <TD align="center">${test_decision_23_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_23_1}</TD>
      <TD align="center">${test_decision_23_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_23_2}</TD>
      <TD align="center">${test_decision_23_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_23}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_24}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" align="right">${test_result_24_0}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_24_0}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_24_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; " align="center">${test_decision_24_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_24_2}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_24_2}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_24}</TD>
    </TR>
   <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_25}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_25_0}</TD>
      <TD align="center">${test_decision_25_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_25_1}</TD>
      <TD align="center">${test_decision_25_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_25_2}</TD>
      <TD align="center">${test_decision_25_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_25}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_26}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_26_0}</TD>
      <TD align="center">${test_decision_26_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_26_1}</TD>
      <TD align="center">${test_decision_26_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_26_2}</TD>
      <TD align="center">${test_decision_26_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_26}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_27}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_27_0}</TD>
      <TD align="center">${test_decision_27_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_27_1}</TD>
      <TD align="center">${test_decision_27_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_27_2}</TD>
      <TD align="center">${test_decision_27_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_27}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_28}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_28_0}</TD>
      <TD align="center">${test_decision_28_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_28_1}</TD>
      <TD align="center">${test_decision_28_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_28_2}</TD>
      <TD align="center">${test_decision_28_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_28}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_29}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" align="right">${test_result_29_0}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_29_0}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_29_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; " align="center">${test_decision_29_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_29_2}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_29_2}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_29}</TD>
    </TR>
   <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_30}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_30_0}</TD>
      <TD align="center">${test_decision_30_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_30_1}</TD>
      <TD align="center">${test_decision_30_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_30_2}</TD>
      <TD align="center">${test_decision_30_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_30}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_31}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_31_0}</TD>
      <TD align="center">${test_decision_31_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_31_1}</TD>
      <TD align="center">${test_decision_31_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_31_2}</TD>
      <TD align="center">${test_decision_31_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_31}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_32}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_32_0}</TD>
      <TD align="center">${test_decision_32_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_32_1}</TD>
      <TD align="center">${test_decision_32_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_32_2}</TD>
      <TD align="center">${test_decision_32_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_32}</TD>
    </TR>
    <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_33}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_33_0}</TD>
      <TD align="center">${test_decision_33_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_33_1}</TD>
      <TD align="center">${test_decision_33_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_33_2}</TD>
      <TD align="center">${test_decision_33_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_33}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_34}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" align="right">${test_result_34_0}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_34_0}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_34_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; " align="center">${test_decision_34_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_34_2}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_34_2}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_34}</TD>
    </TR>
   <TR>
      <TD style="border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_35}</TD>
      <TD style="border-left: 1.5pt solid #000000;" align="right">${test_result_35_0}</TD>
      <TD align="center">${test_decision_35_0}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_35_1}</TD>
      <TD align="center">${test_decision_35_1}</TD>
      <TD style="border-left: 1pt solid #000000;" align="right">${test_result_35_2}</TD>
      <TD align="center">${test_decision_35_2}</TD>
      <TD style="border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_35}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${test_item_36}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" align="right">${test_result_36_0}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_36_0}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_36_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; " align="center">${test_decision_36_1}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000;" align="right">${test_result_36_2}</TD>
      <TD style="border-bottom: 1pt solid #000000;" align="center">${test_decision_36_2}</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left">${test_normal_36}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${sample_date_0}¸¡ºº¼¼¥³¥á¥ó¥È</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left" colspan="7">${comment_0}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${sample_date_1}¸¡ºº¼¼¥³¥á¥ó¥È</TD>
      <TD style="border-bottom: 1pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left" colspan="7">${comment_1}</TD>
    </TR>
    <TR>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000;" colspan="2" align="left">${sample_date_2}¸¡ºº¼¼¥³¥á¥ó¥È</TD>
      <TD style="border-bottom: 1.5pt solid #000000; border-left: 1.5pt solid #000000; border-right: 1.5pt solid #000000;" align="left" colspan="7">${comment_2}</TD>
    </TR>
  </TBODY>
</TABLE>
TEMPLATE;

$template_footer = <<<TEMPLATE_FOOTER
</BODY>
</HTML>
TEMPLATE_FOOTER;
?>
