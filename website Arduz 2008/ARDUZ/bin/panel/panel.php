<?php
$page['title']="Arduz Online - Panel";
$page['head']="<h1>Panel de tu cuenta</h1>";
$page['header'] = '<h1 style="margin-top:10px;">Panel del personaje</h1><a href="?a=salir"><b>Salir</b></a>';
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
					
					
<?php
//print_r($_SESSION);
$query = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `pjs` WHERE `nick` = '".$_SESSION['Nick']."'"));
if ($query['codigo']==$_SESSION['passwd'])
{
	echo '<h2>Informaci&oacute;n</h2>
<b>Personaje: '.$query['nick'].'</b><br/>
<b>Rondas jugadas: '.$query['partidos'].'</b><br/>
<b>Puntos: '.$query['puntos'].'</b><br/>
<b>Frags: '.$query['frags'].'</b><br/>
<b>Muertes: '.$query['muertes'].'</b><br/>
<h2>Clan</h2>';
	if (intval($query['clan'])>0)
	{
		$clan=mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `clanes` WHERE `ID`='".$query['clan']."';"));
		$admina=' - <a href="?a=panel-clan"><b>[Panel del Clan]</b></a>';
		
		if ($query['nick']!=$clan['fundador']){
			if ($_REQUEST['j']=="salirclan")
			{
				mysqli_query($dbconn,"UPDATE pjs SET clan='0' WHERE ID='".$query['ID']."'");
				mysqli_query($dbconn,"UPDATE clanes SET miembros=miembros-1 WHERE ID='".$query['clan']."'");
				$dds=true;
				echo "<big>Has salido del clan.</big>";
			}
		}

		if($dds==false) {echo '
<b>Clan: '.$clan['Nombre'].'</b><br/>
<b>Puntos del clan: '.$clan['puntos'].'</b><br/>
<b>Frags del clan: '.$clan['matados'].'</b><br/>
<b>Muertes del clan: '.$clan['muertos'].'</b><br/>
<b>Lider del clan: '.$clan['fundador'].$admina.'</b><br/>';}

	} else {
		echo '
<b>No perteneces a ning&uacute;n Clan!</b><br/>
<a href="?a=panel-crear-clan"><b>[Crear Clan!]</b></a>';
	}
	if ($query['GM']=="1")
	{
		
		if(isset($_POST['adve']) and isset($_POST['nick']) and isset($_POST['adv']))
		{
		
		$asdd=mysqli_query($dbconn,"SELECT Bantxt FROM pjs WHERE nick='$_POST[nick]'");
		if (mysqli_num_rows($asdd)>0)
		{
			$jor=mysqli_fetch_array($asdd);
			$jor['Bantxt'].='<br/>'.$_POST['adv'];
			$tmp=mysqli_query($dbconn,"UPDATE pjs SET `Bantxt` = '$jor[Bantxt]', `Ban`='1' WHERE `nick`='$_POST[nick]'");
			$resu=mysqli_num_rows($tmp);
			echo mysqli_error($dbconn);
		}
		}
		
		if($resu==0)
		{
		echo '<h2>Advertencias y observaciones.</h2><form method="post"><input type="text" name="nick" value="NICK"/><input type="text" name="adv" value="COMENTARIO-ADVERTENCIA"/><input type="submit" name="adve" value="Enviar!(OJO)"/></form>';
		} else {
		echo '<h2>Advertencias y observaciones.</h2>Agregada correctamente..';
		}
	}
} else {
	$_SESSION['loggedE']="0";$_SESSION['GM']="0";$_SESSION['nick']="";$_SESSION['passwd']="";
	header("Location: index.php?a=mi_cuenta");
}
/*print_r ($query);
print_r ($clan);
print_r ($_SESSION);//*/
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