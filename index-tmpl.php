<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/index.php';

function draw_application_map_tmpl(&$apps, $auth)
{
?>
<table class="map">
<tr>
 <td class="spacer"></td>
 <td class="plain" colspan="4"><a href="logout.php"><img
 src="/images/logout_button.png"></a
><?php mx_draw_userinfo($auth) ?></td>
 <td class="cell"><?php
 draw_applink('S', $apps);
 draw_applink('M', $apps, '</br>');
 ?>
 <td class="spacer"></td>
 <td class="cell"><?php draw_applink('1', $apps) ?></td>
</tr>
<tr>
 <td class="spacer" colspan="8"><img src="images/arrow-1-to-2.png"
     width="920" height="32"
 /></td>
</tr>
<tr>
 <td class="spacer-bottom" rowspan="4"><img
    src="images/arrow-back-to-2.png" width="32" height="360"
 /></td>
 <td class="cell"><?php draw_applink('2', $apps) ?></td>
 <td class="spacer"><img src="images/rt-arrow32.png"
     width="32" height="32" /></td>
 <td class="cell" colspan="3"><?php
			   draw_applink('C', $apps) ?></td>
 <td class="spacer"><img src="images/rt-arrow32.png"
     width="32" height="32" /></td>
 <td class="cell"><?php draw_applink('3', $apps) ?></td>
</tr>
<tr height="32">
 <td class="spacer" colspan="7"><img src="images/arrow-1-to-2.png"
     width="920" height="32"
 /></td>
</tr>
<tr>
 <td class="cell"><?php draw_applink('4', $apps) ?></td>
 <td class="spacer"><img src="images/rt-arrow32.png"
     width="32" height="32" /></td>
 <td class="cell"><?php draw_applink('5', $apps) ?></td>
 <td class="spacer"><img src="images/rt-arrow32.png"
     width="32" height="32" /></td>
 <td class="cell"><?php draw_applink('6', $apps) ?></td>
 <td class="spacer"><img src="images/rt-arrow32.png"
     width="32" height="32" /></td>
 <td class="cell"><?php draw_applink('7', $apps) ?></td>
</tr>
<tr height="32">
 <td class="spacer" colspan="7"><img src="images/arrow-7-to-back.png"
     width="920" height="32"
 /></td>
</tr>
<?php if (is_array($apps['D'])) { ?>
<tr>
 <td class="spacer"></td>
 <td class="plain"><?php draw_applink('D', $apps) ?></td>
 <td class="spacer" colspan="6"></td>
</tr>
<?php } ?>
</table>
<?php
}
?>
