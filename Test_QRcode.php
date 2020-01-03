

<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRGame</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="qrcode.js"></script>
</head>

<body>
<header>
<h1>Test_QRcode </h1>
</header>

<div id="qrcode"></div>;

<script type="text/javascript">
var qrcode = new QRCode(document.getElementById("qrcode"), {
	
   
   text: "https://QRProblems.org/QRP/getGamePblmNum.php?game_id=116",
   // text: "https://QRGame.org/?game_id=116",
   // text: "http://jindo.dev.naver.com/collie",
	width: 128,
	height: 128,
	colorDark : "#000000",
	colorLight : "#ffffff",
	correctLevel : QRCode.CorrectLevel.H
});
</script>