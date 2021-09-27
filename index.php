<?php session_start() ?>
<!DOCTYPE html>

<html lang="fr">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title> APP DUPPLIS </title>

		<!-- CSS -->
		<link rel="stylesheet" href="css/app_dupplis.css" type="text/css" media="screen">
	</head>

	<body>
		<main>
			<div id="form_container">
				<div id="form_title">
					APP DUPPLIS
				</div>
				
				<form action="pages/convert_file.php" method="post" enctype="multipart/form-data" id="myForm">
					<label for="fileToConvert" class="btn"> LOAD DUPPLIS.CSV </label>
					<input type="file" id="fileToConvert" name="fileToConvert" style="visibility: hidden;">
					<div id="fileName"></div>

					<br /><br />
					<input type="text" id="defaultWebTag" name="defaultWebTag" class="required" placeholder="DEFAULT WEBTAG" value="<?=$_SESSION["defaultWebTag"]?>">
					<br />
					<button type="submit" class="btn"> CONVERT DUPPLIS.CSV </button>
				</form>

				<div class="error" style="display: <?php echo(strlen($_SESSION["error"]) ? "block" : "none"); ?>">
					<?=$_SESSION["error"];?>
				</div>
			</div>
		</main>

		<!-- SCRIPT -->
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

		<script type="text/javascript">
			let	form_error = "<?=$_SESSION["error"];?>";
		</script>
		<script type="text/javascript" src="js/app_dupplis.js"></script>
	</body>

</html>
