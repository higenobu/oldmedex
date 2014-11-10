<?php
$path = dirname(__FILE__) . '/m3ok';
function do_post_request($url)
{
  $fp = fopen($url, 'r');
  if(!$fp)
	return NULL;
  $meta_data = stream_get_meta_data($fp);
  return $meta_data;
}

$url = 'https://www.m3.com/login/login';
if( do_post_request($url) ) {
  // network is reachable
  system("touch ${path}");
} else{
  system("rm -f ${path}");
}
?>
