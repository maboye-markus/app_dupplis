<?php
	
	session_start();

	if (isset($_POST) && !empty($_POST["defaultWebTag"])) {
		$defaultWebTag = $_POST["defaultWebTag"];
		$_SESSION["defaultWebTag"] = $_POST["defaultWebTag"];
	}
	else {
		$_SESSION["error"] = "Default webTag is required.";
		die (header("Location: ../index.php"));
	}

	// Check if file is .csv
	$csv_mimetypes = array(
		'text/csv',
		'text/plain',
		'application/csv',
		'text/comma-separated-values',
		'application/excel',
		'application/vnd.ms-excel',
		'application/vnd.msexcel',
		'text/anytext',
		'application/octet-stream',
		'application/txt',
	);

	if (!in_array($_FILES['fileToConvert']['type'], $csv_mimetypes)) {
		$_SESSION["error"] = "Only .csv are allowed.";
		die (header("Location: ../index.php"));
	}

	// Convert into XML
	
	// Parsing csv file
	if (!($csvFile = file($_FILES['fileToConvert']['tmp_name']))) {
		$_SESSION["error"] = "File may not exist.";
		die (header("Location: ../index.php"));
	}

	$data = array();
	foreach ($csvFile as $input) {
		$data[] = str_getcsv($input, $delimiter = ",");
	}

	$dom = new DOMDocument('1.0', 'UTF-8');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;

	// Create main tags
	$dupplis = $dom->createElement('dupplis');

	// Create default tag
	$default = $dom->createElement('default');

	$default_attr = $dom->createAttribute("webTag");
	$default_attr->value = $defaultWebTag;
	$default->appendChild($default_attr);

	$dupplis->appendChild($default);

	function	refaktorValue($value) {
		if (strstr($value, "base"))		return ("deprecated");
		if (strstr($value, "sou"))		return ("utm_source");
		if (strstr($value, "med"))		return ("utm_medium");
		if (strstr($value, "_cam"))		return ("utm_campaign");
		if (strstr($value, "-cam"))		return ("utm_campaign");
		if (strstr($value, " cam"))		return ("utm_campaign");
		if (strstr($value, "cam"))		return ("campagne");
		if (strstr($value, "base"))		return ("base");
		if (strstr($value, "web"))		return ("webTag");
		if (strstr($value, "tag"))		return ("webTag");
		return ("undefined");
	}

	// Get tags' names => "$i = 1" to avoid URL tags and "$n - 1" to avoid URL with utms
	$tags_name = array();
	for ($i = 1, $n = count($data[0]) - 1; $i < $n; $i++) {
		$tags_name[] = trim(str_replace(' ', '_', refaktorValue(strtolower($data[0][$i]))));
	}

	// Create tags value
	$tags = array();
	$tags[] = array();

	$count = count($data);

	for ($i = 1, $n = $count; $i < $n; $i++) {
		for ($j = 0, $m = count($tags_name); $j < $m; $j++) {
			// Create attr
			$tags[$i - 1][$j] = $dom->createAttribute($tags_name[$j]);
			$tags[$i - 1][$j]->value = trim(htmlspecialchars($data[$i][$j + 1]));
		}
	}

	// Create XML structure
	for ($i = 0, $n = $count - 1; $i < $n; $i++) {
		$duppli = $dom->createElement('duppli');
		for ($j = 0, $m = count($tags[$i]); $j < $m; $j++) {
			$duppli->appendChild($tags[$i][$j]);
		}
		$dupplis->appendChild($duppli);
	}
	$dom->appendChild($dupplis);

	// Create XML file
	$tmp_file = tmpfile();
	$tmp_path = stream_get_meta_data($tmp_file)['uri'];
	file_put_contents($tmp_path, $dom->saveXML());

	header("Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . "dupplis.xml" . "\"");

	// Downloading
	readfile($tmp_path);

	// Deleting tmp file
	unlink($tmp_path);

	$_SESSION["error"] = "";
