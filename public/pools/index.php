<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DEMO</title>
	<link rel="stylesheet" type="text/css" href="/css/theme.css">
</head>
<body>

<div id="header">
	<h1><a href="/">FastCGI Client Demos</a> - PHP-FPM Pools</h1>
</div>

<div id="left">
	<div class="buttons">
		<label for="pool">Pool:</label>
		<select id="pool">
			<option value="www-network-socket">Default www network socket (tcp://web:9000)</option>
			<option value="static-network-socket">Static network socket (tcp://web:9001)</option>
			<option value="on-demand-unix-socket">On-Demand unix domain socket (/var/run/php-uds.sock)</option>
		</select>
		<br>
		<br>
		Create:
		<button type="button" id="single">Single PDF</button>
		<button type="button" id="multipleOrdered">Multiple PDF (ordered)</button>
		<button type="button" id="multipleResponsive">Multiple PDF (reactive)</button>
	</div>

	<div id="output"></div>

</div>
<div id="right">
	<div id="pools">
		<div id="pool-www-network-socket"><?= trim(
				file_get_contents( __DIR__ . '/../../.docker/php/web/dynamic-network-socket.pool.conf' )
			) ?></div>
		<div id="pool-static-network-socket"><?= trim(
				file_get_contents( __DIR__ . '/../../.docker/php/web/static-network-socket.pool.conf' )
			) ?></div>
		<div id="pool-on-demand-unix-socket"><?= trim(
				file_get_contents( __DIR__ . '/../../.docker/php/web/on-demand-unix-domain-socket.pool.conf' )
			) ?></div>
	</div>
	<div id="processes"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="/js/theme.js"></script>

</body>
</html>
