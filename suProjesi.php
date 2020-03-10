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
			var gunlukText = parseInt($('#gunlukTuketim').text());
			if(gunlukText>150){
				$('body').css("background-image", "url(desert.jpg)");
				$( "#imgGosterge" ).remove();
				$( "#birikinti" ).remove();
				$( "#muslukResmi" ).remove();
				$('.gosterge').prepend('<img id="imgGosterge" src="150L_Buyuk.png" />');
				$("#birikintiYazi").text("3 Güney Afrikalının günlük su ihtiyacını tükettiniz. \n Su kullanımınızın sınırı çok aşmaması için dikkat ediniz. \n Detaylar için lütfen tıklayın.");
			}
			else if(gunlukText<=150&&gunlukText>125){
				$( "#imgGosterge" ).remove();
				$('.gosterge').prepend('<img id="imgGosterge" src="150L.png" />');
				$("#birikintiYazi").text("3 Güney Afrikalının günlük su ihtiyacına yaklaştınız. \n Detaylar için lütfen tıklayın.");
			}
			else if(gunlukText<= 125&&gunlukText>100){
				$( "#imgGosterge" ).remove();
				$('.gosterge').prepend('<img id="imgGosterge" src="125L.png" />');
				$("#birikintiYazi").text("2 Güney Afrikalının günlük su ihtiyacını tükettiniz. Detaylar için lütfen tıklayın.");
			}
			else if(gunlukText<= 100&& gunlukText>75){
				$( "#imgGosterge" ).remove();
				$('.gosterge').prepend('<img id="imgGosterge" src="100L.png" />');
				$("#birikintiYazi").text("2 Güney Afrikalının günlük su ihtiyacına yaklaştınız. Detaylar için lütfen tıklayın.");
			}
			else if(gunlukText<= 75&& gunlukText>50){
				$( "#imgGosterge" ).remove();
				$('.gosterge').prepend('<img id="imgGosterge" src="75L.png" />');
				$("#birikintiYazi").text("1 Güney Afrikalının günlük su ihtiyacını tükettiniz. Detaylar için lütfen tıklayın.");
			}
			else if(gunlukText<= 50&& gunlukText>25){
				$( "#imgGosterge" ).remove();
				$('.gosterge').prepend('<img id="imgGosterge" src="50L.png" />');
				$("#birikintiYazi").text("1 Güney Afrikalının günlük su ihtiyacına yaklaştınız. Detaylar için lütfen tıklayın.");
			}
			else if(gunlukText<= 25){
				$( "#imgGosterge" ).remove();
				$('.gosterge').prepend('<img id="imgGosterge" src="25L.png" />');
				$("#birikintiYazi").text("Şu anki su kullanımınız gayet iyi. Detaylar için lütfen tıklayın.");	
			}
			
			$("#birikintiYazi").click(function(){
				$(".bilgilendirme").slideDown(1000);
				$("html, body").animate({
					scrollTop: 1500
				}, 600);
			});
			
			setInterval(function(){get_data()},5000);
				function get_data()
				{
					jQuery.ajax({
						type:"GET",
						url: "deneme.php",
						data:"",
						beforeSend: function() {
						},
						complete: function() {
						},
						success:function(data) {
							$(".sag").html(data);
						}
					});
				}
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
			
		<div class="gosterge">
			<image id="imgGosterge" src=""><h2 id="gostergeYazi">Günlük Tüketim Göstergesi</h2></image>
		</div>
		
		<div class="footer">
			<image id="birikinti" src="footer.png">
			<p id="birikintiYazi"></p>
			<div class="bilgilendirme">
				<div class="bilgiKart"><image src="water-tap.png"></image><p>25 litre, dişlerinizi fırçalarken açık bıraktığınız musluktan 2 dakikada giden sudur.</p></div>
				<div class="bilgiKart"><image src="toilet.png"></image><p>25 litre, ortalama 2 kere sifon çektiğimizde harcadığımız sudur.</p></div>
				<div class="bilgiKart"><image src="washing-machine.png"></image><p>25 litre, 8 kilo hacimli bir çamaşır makinesinin harcadığı suyun yarısıdır.</p></div><br>
				<div class="bilgiKart"><image src="dishwasher.png"></image><p>25 litre, elde bulaşık yıkarken ilk 2 dakikada harcanan sudur.</p></div>
				<div class="bilgiKart"><image src="bathtub.png"></image><p>25 litre, duş almaya başladığınız ilk dakikada harcadığınız sudur.</p></div>
				<div class="bilgiKart"><image src="african.png"></image><p>25 litre, Cape Town'da bir kişinin yarım günlük suyudur.</p></div>
				<div class="bilgilendirme2">
				<p class="bilgilendirme2Yazi">* Kuyucuk Gölü, Eğirdir Gölü, Akşehir Gölü, Ereğli Sazlıkları, Burdur Gölü... <br/>
				Türkiye'de son 50 yılda 36 göl kurudu. <br/>
				Gelecek nesilden çalacağımız sıradaki göl hangisi?<br/><br/>
				* Her yıl sudan kaynaklanan nedenlerden dolayı 3.5 milyon insan hayatını kaybetmektedir.<br/><br/>
				* Her yıl sudan kaynaklanan nedenlerden dolayı 3.5 milyon insan hayatını kaybetmekte.<br/><br/>
				* Kullandığınız her fazladan litre, gelecekten çalınan bir gündür.<br/><br/>
				* Hindistan'da son 20 yılda kuraklık yüzünden borçlanan yaklaşık 300.000 çiftçi intihar etti.<br/>
				Sonra biri, yerel su saklama tekniklerini kullanarak 5 nehri hayata döndürdü ve 1000 köye suyu geri getirdi.<br/><br/>
				* Peru'da, çöl denebilecek bir alanda soluduğumuz havayı içme suyuna dönüştüren bir reklam panosu yapıldı.<br/><br/>
				* Ortalama bir Amerikan golf sahası günlük 1,181,048 litre su tüketiyor.<br/><br/>
				* Sadece Amerika'da her yıl en az 83,279,059 litre su plastik şişelerde kalıyor ve çöp sahasına atılıyor.<br/><br/>
				* Avustralya'da çıkan yangın sonrası, çok su tükettiği gerekçesiyle develerin telef edilmesi kararı alındı.<br/><br/>
				* Damlayan bir musluk, 1 yılda ortalama 11.000 litre su kaybına neden olur.<br/><br/>
				* Dünyada kirli su nedeniyle her saat başı 200 çocuk ölüyor.<br/><br/>
				* Kahve tanelerini yetiştirip bir fincan kahve yapmak için yaklaşık olarak 140 litre su gerekir.<br/><br/>
				* Dünya nüfusunun %85'i, yerkürenin en kurak bölgelerinde yaşamını sürdürmeye çalışmaktadır.<br/><br/>
				* Bir araştırmaya göre, suyun varlığının olduğu yerlerde yaşamak daha sakin, daha mutlu ve yaratıcı bir hayat sürmemize sebep olabilir. Geleceğe bu şansı verelim.</p>
				</div>
				
			</div>
		</div>
		
<?phpmysqli_close($conn); ?>
</body>
</html>