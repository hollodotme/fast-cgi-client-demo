<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DEMO</title>
	<link rel="stylesheet" type="text/css" href="/css/theme.css">
</head>
<body>

<div id="left">
	<div class="buttons">
		Connection:
		<select id="connection">
			<option value="network-socket">Network socket (tcp://web:9001)</option>
			<option value="unix-domain-socket">Unix domain socket (/socket/php-uds.sock)</option>
		</select>
		<br>
		<br>
		Create:
		<button type="button" id="single">Single PDF</button>
		<button type="button" id="multipleOrdered">Multiple PDF (ordered)</button>
		<button type="button" id="multipleResponsive">Multiple PDF (reactive)</button>
	</div>

	<hr>

	<div id="output"></div>

</div>
<div id="right">
	<div id="processes"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="/js/theme.js"></script>

</body>
</html>
