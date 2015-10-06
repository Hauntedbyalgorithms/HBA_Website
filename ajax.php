<?php


include_once('cache.php');


//echo 'ok '.$_GET['google_url'].' super';

$cache = new Cache();

$html = $cache::check_cache_for_url( $_GET['google_url'] );


echo $html;

//$html = file_get_contents($_GET['google_url']);


//echo $html;