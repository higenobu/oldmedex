<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';

function blob_contents($type, $data)
{
	$length = strlen($data);
	header("content-type: $type");
	header("length: $length");
	print $data;
}

function nosuch_blob()
{
	$dr = $_SERVER['DOCUMENT_ROOT'];
	$stop = file_get_contents("$dr/images/stop.png");
	blob_contents('image/png', $stop);
}

function blobmedia_generic_extmedia($media_id)
{
	$db = mx_db_connect();
	$ext_media = '';
	$ext_type = mx_db_fetch_extmedia(&$db, &$ext_media, $media_id);
	if (is_null($ext_type))
		nosuch_blob();
	else
		blob_contents($ext_type, $ext_media);
}

function blobmedia_drawapp($name)
{
	$builtin = $_SERVER['DOCUMENT_ROOT'] . '/medimg/' . $name;
	if (file_exists($builtin)) {
		$contents = file_get_contents($builtin);
		blob_contents('image/jpg', $contents);
		return;
	}

	$match = array();
	if (preg_match('/^(\d+)$/', $name, &$match)) {
		blobmedia_generic_extmedia($name);
		return;
	}

	header("content-type: text/plain");
	print "drawapp $name\n";
}

if (array_key_exists('testing', $_REQUEST)) {
	header("content-type: text/plain");
	var_dump($_SERVER);
	var_dump($_REQUEST);
}
else if (array_key_exists('exists', $_REQUEST)) {
	$db = mx_db_connect();
	$media_id = $_REQUEST['exists'];
	$blob_type = mx_db_blobmedia_exists(&$db, $media_id);

	header("content-type: text/plain");
	if (is_null($blob_type))
		print "Not yet";
	else
		print "Exists";
}
else if (array_key_exists('PATH_INFO', $_SERVER)) {
	$path_info = $_SERVER['PATH_INFO'];
	$match = array();

	if (preg_match('/^\/(\d+)\//', $path_info, &$match))
		return blobmedia_generic_extmedia($match[1]);

	if (preg_match('/^\/drawapp\/(.+)$/', $path_info, &$match))
		return blobmedia_drawapp($match[1]);

	header("content-type: text/plain");
	print "$path_info\n";
	return;
}
else if (!array_key_exists('id', $_REQUEST) || !$_REQUEST['id']) {
	nosuch_blob();
}
else {
	$db = mx_db_connect();
	$media_id = $_REQUEST['id'];
	$blob_media = '';
	$blob_type = mx_db_fetch_blobmedia(&$db, &$blob_media, $media_id);
	if (is_null($blob_type)) {
		nosuch_blob();
	}
	else {
		blob_contents($blob_type, $blob_media);
	}
}
?>
