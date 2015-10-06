<?php

// https://docs.google.com/document/d/1B39QMKT0W_-mWLlkQ4368Tf3J6j3uptH3MQKx6Jh1Xo/pub
// https/__docs.google.com_document_d_1B39QMKT0W_-mWLlkQ4368Tf3J6j3uptH3MQKx6Jh1Xo_pub.json

include_once('config.php');

$spine = json_decode(file_get_contents('spine.json'));


?><!DOCTYPE html>
<html>
<head>
	<title>Haunted by algorithms</title>
	<script src="js/jquery-1.11.3.min.js"></script>
</head>
<body>

	<div id="loader">Chargement du contenu…</div>

	<ul id="menu">
		<?php //print_r($spine); ?>
	
		<?php foreach($spine->menu as $item){


			echo "<li><a href=\"{$item->url}\">{$item->titre}</a></li>";


		} ?>

	</ul>

	<div id="result"></div>

	<script>

		$(document).ready(function(){

			$("#loader").hide();

			$("#menu li>a").click(function(event){

				event.preventDefault();

				$("#loader").show();

				console.log( $(this).attr('href') );

				var _google_url = $(this).attr('href') ;

				$.ajax({

					url:"ajax.php",
					data:{
						google_url: _google_url
					}

				}).done(function( data ) {
					console.log(data);


					$("#result").html(data);

					$("#loader").hide();
				});

			});

		})

	</script>

</body>
</html>