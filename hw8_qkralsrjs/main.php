<?php

	$fbUrl = "https://graph.facebook.com/v2.8/";
	$access_token ="EAADNAoDNw5wBAGXQrmwKXOpMhJT0kPnjd6pUYHdD8AiDk317wchZAvo5G4bWH2H8LG5elSLhXY4EdH7SnI94ZCtFdjOZAoybyfZCc3CIKmLz8VFAebc95W5Nzp3NoYwP1lO5zKaspclyvMp6h7ERtt1ZAqyuVDEcAFZA1NdD9CgAZDZD";

    header("Access-Control-Allow-Origin: *");
    if (isset($_GET)){
    	if (isset($_GET["type"])){
    		$fbUrl .= "search?q=" . $_GET["keyword"] . "&type=";
    		// shows place
    		if($_GET["type"] == "place"){
    			$fbUrl .= $_GET['type'] . "&fields=id,name,picture.width(700).height(700)&center=" . $_GET['latitude'] . "," . $_GET['longitude'] . "&limit=25&after=" . $_GET['offset'] . "&access_token=" . $access_token;
    			echo file_get_contents($fbUrl);
    		}
    		else{
    			// shows user, page and so on.
    			$fbUrl .= $_GET['type'] . "&fields=id,name,picture.width(700).height(700)&limit=25&offset=" . $_GET['offset'] . "&access_token=" . $access_token;
    			echo file_get_contents($fbUrl);
    		}
    	}
    	else{
    		// shows detail
    		$fbUrl .= $_GET['id'] . "?fields=name,picture,albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5){message, created_time}&access_token=" . $access_token;
    		echo file_get_contents($fbUrl);
    	}
    }
        //echo file_get_contents(base64_decode($_GET['q']));
?>