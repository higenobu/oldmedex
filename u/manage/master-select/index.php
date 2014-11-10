<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
$u = mx_authenticate_user();
?><html><head>
<meta http-equiv="content-type" content="text/html; charset=euc-jp" />
<link rel="stylesheet" href="../style.css" />
<title>マスタ行選択</title>
</head>
<body>
<span class="heading">マスタ行選択</span>
<hr />
<ul>
<li><a href="medis-disease.php">Medis病名マスター</a></li>
<li><a href="medis-proc.php">Medis処置マスター</a></li>
<li><a href="medis-medicine.php">Medis医薬品マスター</a></li>
<li><a href="sampletest.php">検体検査マスター</a></li>
</ul>
</body>
</html>
