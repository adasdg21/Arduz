<?php
$page['title']="Arduz Online - Panel";
$page['header'] = '';
$page['head'] = '<h1>Crear Clan</h1>';
template_header();
if ($_SESSION['loggedE']=="1")
{
?>
<div style="clear:both;">
</div>
<div class="caja">
	<div class="caja_l">
		<div class="caja_r">
			<div class="caja_t">
			<img src="images/ai.jpg" style="float:left;"/>
			<img src="images/ad.jpg" style="float:right;"/>
				<div class="caja_b">
					<div id="Inicio">
					<h1>Crear clan</h1>
					
<?php
//print_r($_SESSION);
function isasd($Subject)
{
if( preg_match("/[a-zA-Z ]$/",$Subject))
return true;
else
return false;
}

$error="";
$name="";

if (isset($_POST['name'])) {
	$name=$_POST['name'];
}

$query = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `pjs` WHERE `nick` = '".$_SESSION['Nick']."'"));
if ($query['codigo']==$_SESSION['passwd'])
{
	if (intval($query['clan'])==0)
	{
		echo '
		<h2>Paso 1</h2><b>Juntar los puntos necesarios para crear el clan, para crear el clan, nesecit&aacute;s 500.000 puntos, que se descontar&aacute;n de tu personaje.<br/>Ten&eacute;s '.$query['puntos'].' puntos.';
		if (intval($query['puntos'])>499999)
		{
			if(isset($_POST['name']) and isset($_POST['pin']))
			{
				if(strlen($_POST['name'])>2 and strlen($_POST['name'])<14)
				{
					if (isasd($_POST['name'])==true)
					{
						if($query['PIN']==$_POST['pin'])
						{
							$clan=mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `clanes` WHERE `Nombre`='".$_POST['name']."';"));
							if($clan['Nombre']=="")
							{
								mysqli_query($dbconn,'INSERT INTO `clanes` (`ID`, `Nombre`, `puntos`, `matados`, `muertos`, `rank_puntos`, `rank_puntos_old`, `rank_mm`, `rank_mm_old`, `fundador`, `miembros`, `lvl`) VALUES (NULL, \''.$_POST['name'].'\', \'0\', \'0\', \'0\', \'0\', \'0\', \'0\', \'0\', \''.$query['nick'].'\', \'1\', \'1\');');
								$clanid=mysqli_insert_id();
								mysqli_query($dbconn,"UPDATE pjs SET clan='$clanid',puntos=puntos-'500000' WHERE ID='$query[ID]'");
								$imprimir=false;
							} else {
								$error='<b style="color:red">El clan ya existe.</b><br/>';
								$imprimir=true;
							}						
						} else {
							$error='<b style="color:red">Pin incorrecta.</b><br/>';
							$imprimir=true;
						}
					} else {
						$error='<b style="color:red">El nombre tiene caracteres invalidos.</b><br/>';
						$imprimir=true;
					}
				} else {
					$error='<b style="color:red">El nombre es muy corto o muy largo.</b><br/>';
					$imprimir=true;
				}
			} else {$imprimir=true;}
			
			if($imprimir==true)
			{
				echo '
				<h2>Paso 2</h2>
				<b>&bull; Elegir el nombre del clan.</b><br/><br/>
				<b>&bull; Ten&eacute; cuidado en este paso, se te descontar&aacute;n 500.000 puntos, esto te har&aacute; bajar varios puntos en el ranking de puntos.</b><br/><br/>
				<b>&bull; El clan reci&eacute;n creado admite 5 personajes, este numero puede aumentar a cambio de puntos de clan, estos no bajan tu ranking, son la suma de todos los conseguidos EN EL CLAN.</b><br/><br/>
				<form method="POST" action="" id="formulario">'.$error.'
				<label for="name">Nombre del Clan</label>
				<span class="input">
				<input type="text" name="name" id="name" value="'.$name.'" maxlength="13"></span>
				<label for="pin">Clave pin:</label>
				<span class="input"><input type="password" name="pin" id="pin" maxlength="27" value=""></span>
				<div style="clear:both;"></div>
				<div><input type="submit" name="submit" value="Crear clan!" style="float:right;"><br/></div>
				</form>';
			} else {
				echo '
				<h2>Paso 2</h2><b>Hecho.</b>
				<h2>Finalizado!</h2>
				<b>El clan '.$name.', ahora sos el lider del clan, Suerte!</b>';
			}
		}
		
	} else {
		header("Location: index.php?a=panel");
	}
} else {
	$_SESSION['loggedE']="0";$_SESSION['nick']="";$_SESSION['passwd']="";
	header("Location: index.php?a=mi_cuenta");
}

?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
template_footer();
} else {
header("Location: index.php?a=mi_cuenta");
}
?>