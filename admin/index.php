<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');
require_once '../php/config.php';
require_once '../php/super_pdo/src/database.php';

$database = new Database();
$database->query('SELECT * FROM ' . DEFAULTTABLE . ' WHERE finished = 1 ORDER BY id DESC');
$galleryItems = $database->resultset();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transamerica - Faces Of Finance</title>
    <meta name="viewport" content="width=640, user-scalable=no">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<!-- build:css css/app.css -->
	<link rel="stylesheet/less" type="text/css" href="app.less" />
	<!-- /build -->
	<!-- build:remove -->
	<script type="text/javascript">var less=less||{};less.env='development';</script><!-- LESS refresh workaround -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.3.1/less.min.js"></script>
	<!-- /build -->
</head>
<body>
<div id="gallery">
<?php
	for ($i = 0, $count = count($galleryItems); $i < $count; $i++) {
		//echo '<div class="item"><img class="image-single" src="../img/gallery_placeholder.png" data-src="../uploads/' . $galleryItems[$i]['id'] . '/thumb.jpg" width="255" height="255" /></div>';
		//echo '<div class="item"><img class="image-single" src="../uploads/' . $galleryItems[$i]['id'] . '/thumb.jpg" width="255" height="255" /><i id="delete' . $galleryItems[$i]['id'] . '" class="fa fa-times"></i></div>';
		echo '<div class="item"><img class="image-single" src="../img/gallery_placeholder.png" data-src="../uploads/' . $galleryItems[$i]['id'] . '/thumb.jpg" width="255" height="255" /><i id="delete' . $galleryItems[$i]['id'] . '" class="fa fa-times"></i></div>';
	}
?>
</div>
<script src="jquery.min.js"></script>
<script src="jquery.unveil.min.js"></script>
<script src="app.js"></script>
</body>
</html>