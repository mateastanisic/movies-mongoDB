<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>searchME</title>
	<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Cutive+Mono|Fascinate+Inline|Montserrat&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"> </script >
</head>
<body>
    <!--<img src="https://raw.githubusercontent.com/mateastanisic/image/master/faded.jpg" alt="Movies" id="image">-->
	<div id="menu" >
        <h1 id="page_name" >movies</h1> <br>
        <!-- imamo opcije:
            1. vrati se na naslovnicu -> javascript u footeru
            2. dodaj komentar (ostajemo na naslovnici) ->controller
            3. pogledaj statistiku -> m/r -> to be done
         -->
        <div class="left">
            <form method="post" action="<?php echo __SITE_URL; ?>/index.php?rt=index/statistics">
                <!-- ispisivanje 25 najnovijih filmova sa mogućnosti komentiranja -->
                <button class="map_reduce_results search_button" type="submit" name="button" value="a" >m/r a</button> <br>
                <button class="map_reduce_results search_button" type="submit" name="button" value="b"  >m/r b</button> <br>
                <button class="map_reduce_results search_button" type="submit" name="button" value="c"  >m/r c</button>
            </form>
        </div>

        <br>
	</div>


