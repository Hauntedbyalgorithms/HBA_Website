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
	<script src="http://localhost:7076/socket.io/socket.io.js"></script>
	<!--<script src="http://192.168.0.1:167863/socket.io/socket.io.js"></script>-->


	<style>
	*{font-family: monospace; }
	</style>

</head>
<body>

	<form action="">
<p><input type="text" name="reponse_field" id="reponse_field"></p>
<p><input type="submit" value="envoyer" id="reponse_send"></p>
</form>


<div id="discussion"></div>

	<div id="loader">Chargement du contenu…</div>

	<p>ok</p>
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


	io = io.connect();

	// Emit ready event.
	io.emit('ready');


	message("ready");
	

	// Listen for the talk event.
	io.on('talk', function(data) {
	    console.log(data.message);

	    message(data.message, "bot");
	});


	$("#reponse_send").click(function(event){

		event.preventDefault();

		message( $("#reponse_field").val(), "user" );
		io.emit('message', { texte: $("#reponse_field").val()} );

		$("#reponse_field").val("");
	})

		})

	</script>

</body>
</html>