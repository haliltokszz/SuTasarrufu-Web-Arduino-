<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Su Projesi</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="suProjesiiStyle.css" />
	<link rel="stylesheet" type="text/css" href="Animate.css">
	<link href="https://fonts.googleapis.com/css?family=Cinzel:700&display=swap" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="http://balupton.github.io/jquery-scrollto/lib/jquery-scrollto.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#birikintiYazi").click(function(){
				$(".bilgilendirme").slideDown(1000);
				$("html, body").animate({
					scrollTop: 1500
				}, 600);
			});
		});
	</script>
<?php
	$servername = "localhost";//server ismi
	$database = "su_projesi";//veritabanı adı
	$username = "root";//veritabanı kullanıcı adı
	$password = "F3V3Rh4ck3d";//veritabanı şifre

	$conn = mysqli_connect($servername, $username, $password, $database);//veritabanı bağlantı cümlesi

	$conn->set_charset("utf8");
	date_default_timezone_set('Etc/GMT-3');
	$sorgu = $conn->query("SELECT * FROM su");

	while ($sonuc = $sorgu->fetch_assoc()) {
		$anlikDB = $sonuc['anlikSuTuketimi'];
		$gunlukDB = $sonuc['gunlukSuTuketimi'];
		$haftalikDB = $sonuc['haftalikSuTuketimi'];
		$aylikDB = $sonuc['aylikSuTuketimi'];
	}
	if(date('H:i:s')=="00:00:00"){
		$haftalikDB+=$gunlukDB;
		mysqli_query($conn, "INSERT INTO su (gunlukSuTuketimi) VALUES ('".$gunlukDB."')");
		$gunlukDB=0;
	}
	if(date('l')=="Monday"&&date('H:i:s')=="00:00:00"){
		$aylikDB+=$haftalikDB;
		mysqli_query($conn, "INSERT INTO su (haftalikSuTuketimi) VALUES ('".$haftalikDB."')");
		$haftalikDB=0;
	}if(date('d:H:i:s')=="01:00:00:00"){
		mysqli_query($conn, "INSERT INTO su (aylikSuTuketimi) VALUES ('".$aylikDB."')");
		$aylikDB=0;
	}
	$gunlukFatura = (((($gunlukDB/1000)*4)+4)*1.08)+(($gunlukDB/1000)*0.4);
	$haftalikFatura = (((($haftalikDB/1000)*4)+4)*1.08)+(($haftalikDB/1000)*0.4);
	$aylikFatura = (((($aylikDB/1000)*4)+4)*1.08)+(($aylikDB/1000)*0.4);
	$aylikFatura= round($aylikFatura,2);
   //echo ' Günlük: '. $gunlukDB .' Haftalık: '. $haftalikDB .' Aylık: '. $aylikDB;
?>
</head>
<body>
		<div class="sag">
			<image id="muslukResmi" src="musluk.png"></image>
			<image class="animated bounceInDown" id="yagmurDamlasi" src="damla.png"><h2 class="damlaYazi">Anlık<br>Tüketim</h2> <h1 class="damlaYazi" id="gunluk"><?php echo  $anlikDB; ?>L</h1></image>
			<div class="fatura">
				<image id="dollar" src="dollar.png"></image>
				<h1>Fatura</h1>
				<h2>Günlük: <b id="gunlukTuketim"><?php echo  $gunlukDB; ?></b>L</h2>
				<h2>Haftalık: <b id="haftalikTuketim"><?php echo  $haftalikDB; ?></b>L</h2>
				<h2>Aylık: <b id="aylikTuketim"><?php echo  $aylikDB; ?></b>L</h2>
				<h2 id="faturaBedeli"><?php echo $aylikFatura ?>TL</h2>
			</div>
		</div>

<?phpmysqli_close($conn); ?>
</body>
</html>