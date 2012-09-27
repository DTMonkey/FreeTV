<?php
	include_once('php/free_tv_library.php');
	
	$parser = new MediaParser();
	
	$episodes;
	
	if( isset($_POST["name"]) && isset($_POST["season"]))
	{
		$name 		= 	$_POST["name"];
		$season 	= 	$_POST["season"];

		$episodes = $parser->getShow($name, $season);
	} 
	else
	{
		$episodes = $parser->getShow("Boston Legal", 2);
	}	
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery.pageslide.css" />

	<script src="js/jquery-1.8.2.min.js" type="text/javascript"></script>
	
	<script type='text/javascript' src='js/jwplayer.js'></script>

	<script src="js/jquery.scrollTo-1.4.3.1-min.js" type="text/javascript"></script>
	<script src="js/jquery.localscroll-1.2.7-min.js" type="text/javascript"></script>
	<script src="js/jquery.serialScroll-1.2.2-min.js" type="text/javascript"></script>
	<script src="js/jquery.pageslide.min.js" type="text/javascript"></script>
	<script src="js/jquery.hotkeys-0.7.9.min.js" type="text/javascript"></script>
	
	<script src="js/navigation.js" type="text/javascript"></script>
</head>
<body>

<a href="#modal" class="second">Search</a>

<div id="modal">
	<h2>Search</h2>
	
	<p>Search any show by name and season.</p>
	
	<form class="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<label id="name" for="name">Show Name: </label>
		<input type="text" name="name" />	</br>
		
		<label for="season">Season: </label>	
		<input type="text" name="season" />	</br>		

		<input type="submit" />
	</form>
</div>

<script src="js/jquery.pageslide.min.js"></script>

<script>        
    /* Slide to the left, and make it model (you'll have to call $.pageslide.close() to close) */
    $(".second").pageslide({ direction: "right", modal: true });
	$(".second").click(function() {
		$("#name").click();
		trigger({ id : window.location.hash.substr(1) });
	});
</script>

<div id="slider">
  <ul class="navigation">
	<?php
		foreach($episodes as $episode) {
			$num = $episode->mNum;
			echo "<li><a href='#$num'>$num</a></li>";
		}
	?>
  </ul>

  <!-- element with overflow applied -->
  <div class="scroll">
    <!-- the element that will be scrolled during the effect -->
    <div class="scrollContainer">
      <!-- our individual panels -->
	  <?php
		foreach($episodes as $episode) {
			$num = $episode->mNum;
			
			echo "<div class='panel' id='$num'><H3>".$episode->mTitle."</H3>".
					$episode->mAirDate."</br></br>".$episode->mInfo."</br>";
			
			foreach($episode->mLinks as $link) 
				echo '<a href="' . $link . '"> Link </a>';
					
			echo "</div>";
		}
	  ?>
    </div>
  </div>
  
  <center>
	<div id="vidcont"> </div>
  </center>
</div>
</body>
</html>