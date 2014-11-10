<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/chartdirector/phpchartdir.php';

$pt_oid = $_REQUEST['pt_oid'];
if(is_null($pt_oid) or !mx_authenticate_cookie_quick_validate()) {
#NEEDSWORK: should show 'access denied' kind of image
  return;
 }

# Data for the chart
$stmt = <<<SQL
  SELECT extract(epoch from cast("ÆüÉÕ"||' '||"Â¬Äê»þ¹ï" as timestamp)) as t,"¿ÈÄ¹","ÂÎ½Å","ÂÎ²¹","·ì°µ(¾å)","·ì°µ(²¼)","Ì®Çï","¸ÆµÛ¿ô",
       "¼ç¿©ÀÝ¼èÎÌ","Éû¿©ÀÝ¼èÎÌ","ÊØ(²ó¿ô)","ÊØ(À­¾õ)","Ç¢(²ó¿ô)","Ç¢(À­¾õ)","¥³¥á¥ó¥È"
FROM "¥Ð¥¤¥¿¥ë¥Ç¡¼¥¿É½"
  WHERE "´µ¼Ô"=${pt_oid} AND "Superseded" IS NULL
SQL;
$db = mx_db_connect();
$rs = mx_db_fetch_all(&$db, $stmt);
foreach($rs as $r) {
  $dates[] = chartTime2($r['t']);

  $v = $r['¿ÈÄ¹'];
  if(is_null($r['¿ÈÄ¹']))
    $v = $data_pulse[count($data_height) -1];
  $data_height[] = $v;

  $v = $r['ÂÎ½Å'];
  if(is_null($r['ÂÎ½Å']))
    $v = $data_pulse[count($data_weight) -1];
  $data_weight[] = $v;

  $v = $r['ÂÎ²¹'];
  if(is_null($r['ÂÎ²¹']))
    $v = $data_pulse[count($data_temp) -1];
  $data_temp[] = $v;

  $v = $r['Ì®Çï'];
  if(is_null($r['Ì®Çï']))
    $v = $data_pulse[count($data_pulse) -1];
  $data_pulse[] =  $v;

}

# Labels for the chart


# Create a XYChart object of size 600 x 300 pixels, with a grey (eeeeee) background,
# a black border, and 1 pixel 3D border effect
$c = new XYChart(600, 300, 0xeeeeee, 0x000000, 1);

# Add a title box to the chart using 15 pts Arial Bold Italic font, with blue
# (aaaaff) background

#$textBoxObj = $c->addTitle("Multiple Axes Demonstration", "arialbi.ttf", 15);
#$textBoxObj->setBackground(0xaaaaff);

# Set the plotarea at (100, 70) and of size 400 x 180 pixels, with white background.
# Turn on both horizontal and vertical grid lines with light grey color (cccccc)
$plotAreaObj = $c->setPlotArea(100, 70, 400, 180, 0xffffff);
$plotAreaObj->setGridColor(0xcccccc, 0xcccccc);

# Add a legend box at (300, 70) (top center of the chart) with horizontal layout. Use
# 8 pts Arial Bold font. Set the background and border color to Transparent.
$legendBox = $c->addLegend(300, 70, false, "arialbd.ttf", 8);
$legendBox->setAlignment(BottomCenter);
$legendBox->setBackground(Transparent, Transparent);

# Set the labels on the x axis.
#$c->xAxis->setLabels($dates);
$c->xAxis->setDateScale3();
# Display 1 out of 3 labels on the x-axis.
#$c->xAxis->setLabelStep(3);

# Add a title to the x-axis
#$c->xAxis->setTitle("Date/Time");
$c->xAxis->setLabelFormat("{value|m/dd\nhh:nn}");

# Add a title on top of the primary (left) y axis.
$c->yAxis->setLabelFormat("{value|1,.}");
$textBoxObj = $c->yAxis->setTitle("Weight\nkg");
$textBoxObj->setAlignment(TopLeft2);
# Set the axis, label and title colors for the primary y axis to red (c00000) to
# match the first data set
$c->yAxis->setColors(0xcc0000, 0xcc0000, 0xcc0000);

# Add a title on top of the secondary (right) y axis.
$c->yAxis2->setLabelFormat("{value|1,.}");
$textBoxObj = $c->yAxis2->setTitle("Temp\nC");
$textBoxObj->setAlignment(TopRight2);
# Set the axis, label and title colors for the secondary y axis to green (00800000)
# to match the second data set
$c->yAxis2->setColors(0x008000, 0x008000, 0x008000);

# Add the third y-axis at 50 pixels to the left of the plot area
$leftAxis = $c->addAxis(Left, 50);
# Add a title on top of the third y axis.
$leftAxis->setLabelFormat("{value|1,.}");
$textBoxObj = $leftAxis->setTitle("Height\ncm");
$textBoxObj->setAlignment(TopLeft2);
# Set the axis, label and title colors for the third y axis to blue (0000cc) to match
# the third data set
$leftAxis->setColors(0x0000cc, 0x0000cc, 0x0000cc);

# Add the fouth y-axis at 50 pixels to the right of the plot area
$rightAxis = $c->addAxis(Right, 50);
$rightAxis->setLabelFormat("{value|1,.}");
# Add a title on top of the fourth y axis.
$textBoxObj = $rightAxis->setTitle("Pulse\nbeat/min");
$textBoxObj->setAlignment(TopRight2);
# Set the axis, label and title colors for the fourth y axis to purple (880088) to
# match the fourth data set
$rightAxis->setColors(0x880088, 0x880088, 0x880088);

# Add a line layer to for the first data set using red (c00000) color, with a line
# width of 2 pixels
$layer0 = $c->addLineLayer($data_weight, 0xcc0000, "Weight");
$layer0->setLineWidth(2);
$layer0->setXData($dates);

# Add a line layer to for the second data set using green (00c0000) color, with a
# line width of 2 pixels. Bind the layer to the secondary y-axis.
$layer1 = $c->addLineLayer($data_temp, 0x008000, "Temp");
$layer1->setLineWidth(2);
$layer1->setUseYAxis2();
$layer1->setXData($dates);
# Add a line layer to for the third data set using blue (0000cc) color, with a line
# width of 2 pixels. Bind the layer to the third y-axis.
$layer2 = $c->addLineLayer($data_height, 0x0000cc, "Height");
$layer2->setLineWidth(2);
$layer2->setUseYAxis($leftAxis);
$layer2->setXData($dates);

# Add a line layer to for the fourth data set using purple (880088) color, with a
# line width of 2 pixels. Bind the layer to the fourth y-axis.
$layer3 = $c->addLineLayer($data_pulse, 0x880088, "Pulse");
$layer3->setLineWidth(2);
$layer3->setUseYAxis($rightAxis);
$layer3->setXData($dates);

# output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>
