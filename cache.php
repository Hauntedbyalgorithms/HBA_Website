<?php

// CACHE CLASS 
// 
// https://docs.google.com/document/d/1B39QMKT0W_-mWLlkQ4368Tf3J6j3uptH3MQKx6Jh1Xo/pub
// https/__docs.google.com_document_d_1B39QMKT0W_-mWLlkQ4368Tf3J6j3uptH3MQKx6Jh1Xo_pub.json

include_once('config.php');



// on inclue la librairie simple_html_dom.php qui permet de sélectionner des balises + id + classes assez précisément
require_once 'libs/simple_html_dom.php';
// on inclue la librairie htmlpurifier qui permet de nettoyer du code html et notamment de fermer correctement les balises
require_once 'libs/htmlpurifier-4.6.0-lite/library/HTMLPurifier.auto.php';






/**
* 
*/
class Cache
{

	function __construct()
	{
		# code...
	}

	static function check_cache_for_url($url = null){

		$file_name = CACHE_PATH.self::cleanup_name($url);
		

		if(!is_file($file_name) ){

			self::create_cache_for_url( $url );

		}else{

			$init_time = filemtime( $file_name );

			if(time()-$init_time > 600){ // 10 minutes de cache (10 x 60 sec)

				self::create_cache_for_url( $url );
			}

		}
		
		return file_get_contents( $file_name );

	}


	static function create_cache_for_url($url = null){

		$html_from_file = file_get_contents($url);


		// on configure htmlpurifier
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', 'em,strong,a');
		$config->set('AutoFormat.AutoParagraph', true);
		$config->set('AutoFormat.RemoveEmpty',true);
		$config->set('AutoFormat.RemoveEmpty.RemoveNbsp',true);

		$purifier = new HTMLPurifier();


		$html = str_get_html( $html_from_file );

		$html_export = '';

		foreach($html->find('div#contents') as $element){
			$html_export .= $element;
		}


		//$html_export = $purifier->purify( $html_export );


		$file_name = CACHE_PATH.self::cleanup_name($url);


		file_put_contents($file_name, $html_export);

	}

	static function cleanup_name($url){

		$name = str_replace('/', '_', $url);

		return $name.'.json';
	}

}