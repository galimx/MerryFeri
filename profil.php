<?php
error_reporting(0);
defined('_JEXEC') or die;
$db =& JFactory::getDBO();
$user =& JFactory::getUser();

//zacetek diva profilGlavni
echo "<div class='profilGlavni'>";
//zacetek diva leve strani profila
echo "<div class='profilLevo'>";
if ($_POST["shrani"] == "Shrani") 
	{	
		if (is_uploaded_file($_FILES["slika"]["tmp_name"]))
		{
			$datoteka  = addslashes (fread (fopen ($_FILES["slika"]["tmp_name"], "r"), 
						 filesize ($_FILES["slika"]["tmp_name"])));
			$imeSlike = $_FILES["slika"]["name"];
			$velikost = $_FILES["slika"]["size"];
			$vrsta = $_FILES["slika"]["type"];		
			$koncnica = explode(".", $imeSlike);
			$Velikost = $velikost / 1024; //izracunamo velikost v kilobajtih
			$MB = 5;
			if($Velikost<=1024 && ($koncnica[1] == "jpg" || $koncnica[1] == "png" || $koncnica[1] == "jpeg" || $koncnica[1] == "bmp" || $koncnica[1] == "gif"))
			{
					$sql = "SELECT * FROM slike WHERE user_id=".$user->id;
					$db->setQuery($sql);
					$db->query();
					$slika_obstaja = $db->getNumRows();
				if($slika_obstaja == 0)
				{
					$sql = "INSERT INTO slike "
						 . "(slika, vrsta, user_id) ";
					$sql.= "VALUES (";
					$sql.= "'{$datoteka}', '{$vrsta}', ".$user->id.")";
					$db->setQuery($sql);
					$db->query();
					$izpis = "<br/>Uspesno ste shranili sliko profila!";
				}
				else
				{
					$sql = "UPDATE slike SET slika='{$datoteka}', vrsta='{$vrsta}' WHERE user_id=" . $user->id;
					$db->setQuery($sql);
					$db->query();
					$izpis = "<br/>Uspesno ste posodobili sliko profila!";
				}
			}
			else
			{
				echo "Neuspešno! Maximalna velikost datoteke je 1MB!<br/>Dovoljeni formati slik so: jpg, png, jpeg, bmp.";
			}
		}
	//konec shranjevanja nove slike
	
	$query = "UPDATE profil SET kraj='" . $_POST["kraj1"]. "', stan='" . $_POST["stan1"]. "', spol='" . $_POST["spol1"]. "', sola='" . $_POST["sola1"]. "', poklic='" . $_POST["poklic1"]. "'  WHERE user_id=" . $user->id;
	$db->setQuery($query);
	$db->query();
	
	$query = "UPDATE jos_users SET name='" . $_POST["imepriimek"]. "' WHERE id=" . $user->id;
	$db->setQuery($query);
	$db->query();
	
	
	if($_POST["email1"] == $_POST["potrdiemail1"]) {
		$query1 = "UPDATE jos_users SET email='" . $_POST["email1"] . "' WHERE id=" . $user->id;
		$db->setQuery($query1);
		$db->query();
	}
	else 
		$opozorilo = "E-mail se ne ujema!";
}
if(isset($_GET["id"]))
{
	//echo "1";
	$sql = "SELECT * FROM profil WHERE user_id=".$_GET["id"]."";
}
else
{
	//echo "2";
	$sql = "SELECT * FROM profil WHERE user_id=".$user->id."";
}
$db->setQuery($sql);
$profil = $db->loadAssocList();
$db->query();
$st_vrstic = $db->getNumRows();

if($st_vrstic!=0)
	$sql = "SELECT * FROM jos_users WHERE id=".$profil[0]["user_id"]."";
else
{
	if(isset($_GET["id"]))
		$sql = "SELECT * FROM jos_users WHERE id=".$_GET["id"]."";
	else
		$sql = "SELECT * FROM jos_users WHERE id=".$user->id."";
}

$db->setQuery($sql);
$uporabnik = $db->loadAssocList();
$db->query();

echo "<h1 style=\"font-family:Arial, Helvetica, sans-serif\"><b>" . $uporabnik[0]["name"] . "</b> <span style=\"font-size:12px\">&raquo;</span> <a style=\"font-size:12px\" class=\"povezavab\" href=" .JURI::current()."?option=com_blog&view=blog&oseba=".$uporabnik[0]["id"].">Blog</a> <span style=\"font-size:12px\">&raquo;</span> <a style=\"font-size:12px\" class=\"povezavab\" href=" .JURI::root()."?id=".$uporabnik[0]["id"].">Zid</a></h1>";
echo '<table style="font-family:Arial, Helvetica, sans-serif; font-size:12px">';
if($profil[0]["kraj"] != "")
echo '<tr>
		<td style="text-align:right">
			<b>Domači kraj </b>
		</td>
		<td style="color:#7ca429">
			<b> &raquo; </b>
		</td>
		<td>
			' . $profil[0]["kraj"] . '
		</td>
	</tr>';
if($profil[0]["rojstni_dan"] != "")
echo '<tr>
		<td style="text-align:right">
			<b>Rojstni dan </b>
		</td>
		<td style="color:#7ca429">
			<b> &raquo; </b>
		</td>
		<td>
			' .date('d.m.Y', strtotime($profil[0]["rojstni_dan"])) . '
		</td>
	</tr>';
if($profil[0]["spol"] != "Ne izdam" && $profil[0]["spol"] != "")
echo '<tr>
		<td style="text-align:right">
			<b>Spol </b>
		</td>
		<td style="color:#7ca429">
			<b> &raquo; </b>
		</td>
		<td>
			' . $profil[0]["spol"] . '
		</td>
	</tr>';
if($profil[0]["stan"] != "Ne izdam" && $profil[0]["stan"] != "")
echo '<tr>
		<td style="text-align:right">
			<b>Stan </b> 
		</td>
		<td style="color:#7ca429">
			<b> &raquo; </b>
		</td>
		<td>
			' . $profil[0]["stan"] . '
		</td>
	</tr>';
if($profil[0]["sola"] != "")
echo '<tr>
		<td style="text-align:right">
			<b>Šola </b>
		</td>
		<td style="color:#7ca429">
			<b> &raquo; </b>
		</td>
		<td>
			' . $profil[0]["sola"] . '
		</td>
	</tr>';
if($profil[0]["poklic"] != "")
echo '<tr>
		<td style="text-align:right">
			<b>Poklic </b>
		</td>
		<td style="color:#7ca429">
			<b> &raquo; </b>
		</td>
		<td>
			' . $profil[0]["poklic"] . '
		</td>
	</tr>';
echo '<tr>
		<td style="text-align:right">
			<b>E-mail </b>
		</td>
		<td style="color:#7ca429">
			<b> &raquo; </b>
		</td>
		<td>
			' . $uporabnik[0]["email"] . '
		</td>
	</tr>';
echo '<tr>
		<td style="text-align:right">
			<b>Registriran dne </b>
		</td>
		<td style="color:#7ca429">
			<b> &raquo; </b>
		</td>
		<td>
			' . date('d.m.Y H:i:s', strtotime($uporabnik[0]["registerDate"])) . '
		</td>
	</tr>';
echo '<tr>
		<td style="text-align:right">
			<b>Zadnjič aktiven </b>
		</td>
		<td style="color:#7ca429">
			<b> &raquo; </b>
		</td>
		<td>
			' . date('d.m.Y H:i:s', strtotime($uporabnik[0]["lastvisitDate"])) . '
		</td>
	</tr>';
echo '</table>
		<br />';

//urejanje profila


if(!isset($_GET["id"]) || $_GET["id"] == $user->id)
{
	echo '<form method="post">
			<input name="urejanje" class="button" type="submit" value="Uredi profil" />
		  </form>';
}

if ($opozorilo != "")
echo "<br /><div style=\"color:red\">" . $opozorilo . "<br />Poskusite ponovno.</div>";

if ($_POST["urejanje"] == "Uredi profil")
{
	
	$sql = "INSERT INTO profil "
		 . "(user_id) ";
	$sql.= "VALUES (".$user->id.")";
	$db->setQuery($sql);
	$db->query();
		
		echo "<br />Uredite polja in shranite!<br /><br />";
		echo '<table style="font-family:Arial, Helvetica, sans-serif; font-size:12px">
				<form method="post" enctype="multipart/form-data">
				<tr>
					<td style="text-align:right">
						<b>Ime in priimek </b> 
					</td>
					<td style="color:#7ca429">
						<b> &raquo; </b>
					</td>
					<td>
						<input class="text" type="text" name="imepriimek" value="' . $uporabnik[0]["name"] . '" />
					</td>
				</tr>
				<tr>
					<td style="text-align:right">
						<b>Domači kraj </b> 
					</td>
					<td style="color:#7ca429">
						<b> &raquo; </b>
					</td>
					<td>
						<input class="text" type="text" name="kraj1" value="' . $profil[0]["kraj"] . '" />
					</td>
				</tr>
				<tr>
					<td style="text-align:right">
						<b>Spol </b> 
					</td>
					<td style="color:#7ca429">
						<b> &raquo; </b>
					</td>
					<td>
						<select name="spol1">
        					<option value="Ne izdam" '; if($profil[0]["spol"] == "Ne izdam") { echo " SELECTED"; } echo '>Ne izdam</option>						
        					<option value="Moški" '; if($profil[0]["spol"] == "Moški") { echo " SELECTED"; } echo '>Moški</option>
							<option value="Ženski" '; if($profil[0]["spol"] == "Ženski") { echo " SELECTED"; } echo '>Ženski</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align:right">
						<b>Stan </b> 
					</td>
					<td style="color:#7ca429">
						<b> &raquo; </b>
					</td>
					<td>
						<select name="stan1">
        					<option value="Ne izdam" '; if($profil[0]["stan"] == "Ne izdam") { echo " SELECTED"; } echo ' >Ne izdam</option>						
        					<option value="Samski" '; if($profil[0]["stan"] == "Samski") { echo " SELECTED"; } echo ' >Samski</option>
							<option value="V zvezi" '; if($profil[0]["stan"] == "V zvezi") { echo " SELECTED"; } echo '>V zvezi</option>
							<option value="Poročen" '; if($profil[0]["stan"] == "Poročen") { echo " SELECTED"; } echo '>Poročen</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="text-align:right">
						<b>Šola </b> 
					</td>
					<td style="color:#7ca429">
						<b> &raquo; </b>
					</td>
					<td>
						<input class="text" type="text" name="sola1" value="' . $profil[0]["sola"] . '" />
					</td>
				</tr>
				<tr>
					<td style="text-align:right">
						<b>Poklic </b> 
					</td>
					<td style="color:#7ca429">
						<b> &raquo; </b>
					</td>
					<td>
						<input class="text" type="text" name="poklic1" value="' . $profil[0]["poklic"] . '" />
					</td>
				</tr>
				<tr>
					<td style="text-align:right">
						<b>E-mail </b> 
					</td>
					<td style="color:#7ca429">
						<b> &raquo; </b>
					</td>
					<td>
						<input class="text" type="text" name="email1" value="' . $uporabnik[0]["email"] . '" />
					</td>
				</tr>
				<tr>
					<td style="text-align:right">
						<b>Potrdi e-mail </b> 
					</td>
					<td style="color:#7ca429">
						<b> &raquo; </b>
					</td>
					<td>
						<input class="text" type="text" name="potrdiemail1" value="' . $uporabnik[0]["email"] . '" />
					</td>
				</tr>
				<tr>
					<td>
						<b>Izberi sliko profila</b>
					</td>
					<td style="color:#7ca429">
						<b> &raquo; </b>
					</td>
					<td>
						<input type="file" name="slika" class="button" />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>
						<input name="shrani" class="button" type="submit" value="Shrani" />
					</td>
				</tr>
		  	  </form>
		</table>';	
	}
if($izpis != "")
echo $izpis;

//konec diva leve strani profila
echo "</div>";
//izpis slike profila
if(isset($_GET["id"]))
$sql = "SELECT * FROM slike WHERE user_id=".$_GET["id"];	
else
$sql = "SELECT * FROM slike WHERE user_id=".$user->id;

$db->setQuery($sql);
$slika = $db->loadAssocList();
$db->query();
$str = base64_encode($slika[0]["slika"]);
//desna stran profila
echo "<div class='profilDesno'>";
echo '<img class="slika" src="data:image/jpeg;base64,'.$str.'">';
echo "</div>";
//konec diva profil
echo  "</div>";
?>