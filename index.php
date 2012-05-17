<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>REZERVIRAJ.SI &raquo; <?php if (isset($_GET["loc"])) echo $_GET["loc"];?></title>
<link rel="shortcut icon" href="images/favicon.ico">
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
-->
</style>
<script language="javascript">
</script>
</head>

<body>
<?php
	error_reporting(0);
	session_start();
	
	include('connect.php');
	
	if (!$_SESSION["logged"]) {
		Header("Location: login.php");
	}
?>
<div id="menu">
    <ul>
        <li><a href="index.php?loc=spored">Spored</a></li>
        <li><a href="index.php?loc=ustvari">Ustvari</a></li>
        <li><a href="index.php?loc=profil">Profil</a></li>
        <li><a href="index.php?loc=link">Povezave</a></li>
        <li><a href="index.php?loc=onas">O nas</a></li>
    </ul>
</div>

<div id="header">
	<h1><a href="index.php"><img src="images/favicon.ico" style="width:16px; height:16px;"> rezerviraj.si</a></h1>
</div>

<div id="content">
	<div id="colOne">
    	<?php
		if (!isset($_GET["loc"]) || $_GET["loc"] == "spored") {
	
		$gete = mysql_query("SELECT * FROM events ORDER BY `datum` ASC") or die(mysql_error());
			
			echo "<strong>&raquo; Spored</strong>";
			echo '</br></br>';
			echo "<table>";
				while ($rowe = mysql_fetch_array($gete)) {
					echo '
					<strong style=" margin-left:66px; font-size:16px">' . $rowe["naziv"] . '</strong><br /><hr />
					<table>
						<tr>
							<td valign="top" align="left">
								<a href="index.php?loc=details&event='.$rowe["ID"].'"><img src="images/events.png" style="width:50px; height:50px;"></a>
							</td>
							<td valign="top" class="spored_vsebina">
								Lokacija: <strong>' . $rowe["lokacija"] . '</strong><br />
								Datum: <strong>' . $rowe["datum"] . '</strong><br>
								Št. vseh mest: <strong>' . ($rowe["vrst"]*$rowe["sedezev"]-$rezerviranih) . '</strong>
								<p>Opis: <strong>' . substr($rowe["opis"], 0, 200) . ' ... </strong></p>
							</td>
						</tr>
					</table>
					<br />';
				}
			echo "</table>";
		}
		if ($_GET["loc"] == "ustvari") {
			$getStopnja = @mysql_query("SELECT stopnja FROM user WHERE username='" . $_SESSION["logged"] . "'") or die(mysql_error());
			$stopnja = mysql_fetch_array($getStopnja, MYSQL_BOTH);
			
			if($stopnja[0] == 1) {
				echo "<strong>&raquo; Ustvari dogodek</strong>";
				echo '<br /><br />';
				echo '<form name="form" method="post">
						<table>
							<tr>
								<td>Naziv: </td>
								<td><input name="naziv" type="text"></td>
							</tr>
							<tr>
								<td>Lokacija: </td>
								<td><input name="lokacija" type="text"></td>
							</tr>
							<tr>	
								<td>Datum: </td>
								<td><input name="datum" type="text"> (npr. 2000-01-31 20:30:00)</td>
							</tr>
							<tr>	
								<td>Dvorana (vrste/sedeži): </td>
								<td><select name="vrsta">';
								for ($v = 1; $v <= 20; $v++)
								echo "<option>" . $v . "</option>";
								echo '</select> / <select name="sedez">';
								for ($s = 1; $s <= 20; $s++)
								echo "<option>" . $s . "</option>";
					echo '		</select></td>
							</tr>
							<tr>
								<td>Opis: </td>
								<td><textarea name="opis" rows="5" cols="40"></textarea></td>
							</tr>
							<tr>
								<td>Youtube trailer: </td>
								<td><input size="40" name="yt" type="text"></td>
							</tr>
							<tr>	
								<td></td>
								<td><input class="gumb" type="submit" name="shrani" value="Shrani"></td>
							</tr>
						</table>
					</form>';
					
			//preveri če so izpolnjena vsa polja
			if ($_POST["naziv"] == "" || $_POST["lokacija"] == "" || $_POST["datum"] == "" || $_POST["opis"] == "" || $_POST["yt"] == "") {
				echo '<p style="color:#F00; font-size:10px;">Opozorilo: Izpolni vsa polja!</p>';
			}
			else {
				$date = date('yyyy-mm-dd', $_POST["datum"]);
	
				$query = "INSERT INTO events (naziv, lokacija, datum, vrst, sedezev, opis, trailer, ustvaril) VALUES ('" . $_POST["naziv"] ."', '" . $_POST["lokacija"] . "', '" . $_POST["datum"] . "', " . $_POST["vrsta"] . ", " . $_POST["sedez"] . ", '" . $_POST["opis"] . "', '" . $_POST["yt"] . "', '" . $_SESSION["logged"] . "')";
				mysql_query($query);
				}
			}
			else {
				echo '<p style="color:#F00; font-size:10px;">Za ustvarjanje dogodkov nimaš pravic!</p>';
				echo '<p style="color:#F00; font-size:10px;">Piši na: david.vrbancic1990@gmail.com</p>';
			}	
		}
		
		if ($_GET["loc"] == "profil") {
			if (isset($_GET["user"]))
				$getp = mysql_query("SELECT * FROM user WHERE username='" . $_GET["user"] . "'") or die(mysql_error());
			else
				$getp = mysql_query("SELECT * FROM user WHERE username='" . $_SESSION["logged"] . "'") or die(mysql_error());
			
			echo "<strong>&raquo; Profil</strong>";
			echo '</br></br>';
			echo "<table>";
				while ($rowp = mysql_fetch_array($getp)) {
					echo '
					<table>
						<tr>
							<td>
								<p><img src="images/user_green_big.png" style="width:150px; height:150px;"></p>
							</td>
							<td width="285px" valign="top" style="padding-left:20px">
								<p style="font-size:20px; margin:0px; padding:0px"><strong>' . $rowp["username"] . '</strong></p>
								<hr style="border-top:.5px solid #E6F0E7"; color:white" />
								<p>Ime: <strong>' . $rowp["ime"] . '</strong></p>
								<p>Priimek: <strong>' . $rowp["priimek"] . '</strong></p>
								<p>E-mail: <strong>' . $rowp["email"] . '</strong></p>';
						if (!isset($_GET["user"]) || $_GET["user"] == $_SESSION["logged"])		
						  echo '<form name="form" method="post">
									<input class="gumb" type="submit" name="uredi" value="Uredi">
								</form>';
						  echo'		
							</td>
						</tr>
					</table>';
				}
			echo "</table>";
			
			//SHRANI UREJANJE
			if ($_POST["shrani"] == "Shrani") {
					if($_POST["ime"] != "")
					@mysql_query("UPDATE user SET ime = '". $_POST["ime"] ."' WHERE username='" . $_SESSION["logged"] . "'");
					if($_POST["priimek"] != "")
					@mysql_query("UPDATE user SET priimek = '". $_POST["priimek"] ."' WHERE username='" . $_SESSION["logged"] . "'");
					if($_POST["email"] != "")
					@mysql_query("UPDATE user SET email = '". $_POST["email"] ."' WHERE username='" . $_SESSION["logged"] . "'");
					if($_POST["geslo"] != "")
					@mysql_query("UPDATE user SET geslo = '". md5($_POST["geslo"]) ."' WHERE username='" . $_SESSION["logged"] . "'");
					
					echo '<p style="color:#F00; font-size:10px;">Profil posodobljen!</p>';
					
			}
			
			//UREDI PROFIL			
			if ($_POST["uredi"] == "Uredi") {
				$getp = mysql_query("SELECT * FROM user WHERE username='" . $_SESSION["logged"] . "'") or die(mysql_error());

				while ($rowp = mysql_fetch_array($getp)) {
					echo "<strong>&raquo; Urejanje profila</strong>";
					echo '<br /><br />
					<form name="form" method="post">
						<table>
							<tr>
								<td align="right">Ime: </td>
								<td style="color:#F00; font-size:10px; valign="top""><input size="30" name="ime" type="text" value="' . $rowp["ime"]. '"></td>
							</tr>
							<tr>
								<td align="right">Priimek: </td>
								<td style="color:#F00; font-size:10px; valign="top""><input size="30" name="priimek" type="text" value="' . $rowp["priimek"]. '"></td>
							</tr>
							<tr>
								<td align="right">Email: </td>
								<td style="color:#F00; font-size:10px; valign="top""><input size="30" name="email" type="text" value="' . $rowp["email"]. '"></td>
							</tr>
							<tr>
								<td align="right">Geslo: </td>
								<td><input size="30" name="geslo" type="password"></td>
							</tr>
							<tr>
								<td></td>
								<td><input class="gumb"  type="submit" name="shrani" value="Shrani"></td>
							</tr>				
						</table>
					</form>';
				}
			}
		}
			
		if ($_GET["loc"] == "link") {
			echo "<strong>&raquo; Povezave</strong>";
			
			echo '<ul>
					<li><a href="http://www.partymax.si/si/">Partymax</a></li>
					<li><a href="http://www.kolosej.si/">Kolosej</a></li>
					<li><a href="http://www.planet-tus.si/">Planet Tuš</a></li>
					<li><a href="http://www.imdb.com/">imdb</a></li>
				</ul>';
		}
		if ($_GET["loc"] == "onas") {
			echo "Za vas pripravljamo stran preko katere boste lahko posredovali rezervacije za določene predstave/filme iz svojega nasladnjača.";
		}
		?>
	</div>
	<div id="colTwo">
		<?php
		if (!isset($_GET["loc"]) || $_GET["loc"] == "spored") {
			echo "&raquo; Premiere";
			echo "<p>Diktator</p>
				  <p>Premiera filma bo v četrtek, 17.5. ob 20. uri</p>";
		    echo $_COOKIE["logged"];;
		}
		if ($_GET["loc"] == "ustvari") {
			echo "&raquo; Uredi dogodek";
			
		}
		if ($_GET["loc"] == "profil") {
			echo "<strong>&raquo; Ostali uporabniki</strong><br /><br />";
				
	    	$getO = mysql_query("SELECT * FROM user WHERE NOT username='" . $_SESSION["logged"] . "'") or die(mysql_error());
				
			echo "<div class=\"scroll\">
					<ul>";
					while ($rowO = mysql_fetch_array($getO)) {
						echo '<li style="margin:0; padding:0"><a href="index.php?loc=profil&user=' . $rowO["username"] . '">' . $rowO["ime"] . ' ' . $rowO["priimek"] . '</a></li>';
					}
			echo "  </ul>
				  </div>";
		}
		if ($_GET["loc"] == "link") {
			
		}
		if ($_GET["loc"] == "onas") {
			echo "&raquo; Kontakt:<br />";
			echo "david.vrbancic1990@gmail.com";
		}
		?>
	</div>
<div style="clear: both;">&nbsp;</div>
</div>
<div id="footer">
<p>Prijavljen kot, <a href="index.php?loc=profil"> <?php echo $_SESSION["logged"]; ?> </a> (<a href="logout.php">Odjava</a>)</p>
</div>
<div style="font-size: 0.8em; text-align: center; margin-top: 1em; margin-bottom: 1em;"></div>
</body></html>