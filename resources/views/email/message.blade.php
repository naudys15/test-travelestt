<html lang="en-ES"> 
	<head>
		<title>Travelestt</title>
		<meta name="description" content=" | " />
		<meta charset="UTF-8" />
	</head>
	<body>
    <img src="http://travelestt.com/assets/images/logo_azul.png" alt="Travelestt" title="Travelestt" width="250px" height="100px">
		<h2>{{$demo->title}}</h2>
		<div>
			<?php
				$message = explode('\n', $demo->message);
				foreach ($message as $message_split){
					echo ''.$message_split.'<br>';
				}
			?>
		</div>
		<div>{{$demo->footer}}</div>
		<br/>
		<i>{{$demo->sender}}</i>
	</body>
</html>