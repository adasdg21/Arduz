<?php
		$page['title']="Ranking de personajes - Arduz";
		$page['head']="";
		$page['header'] = '<div id="nav"><a href="#" title="Usuarios" class="ccc">Personajes</a><a href="#" title="Clanes" class="ccc">Clanes</a>
</div>';
		template_header();

		$result = mysqli_query($_SESSION['dbconn'],'SELECT `num` FROM `configuracion` WHERE `cfg`=\'s\';');

		$num=0;
		$o=0;
 
 
		if ($result) {
			$sqlnum=mysqli_fetch_array($result);
			$num = $sqlnum['num'];
		}
		 
		$arrClanes = mysqli_query($_SESSION['dbconn'],"SELECT * FROM `clanes`;");
		$clanes=array();

		if ($arrClanes) {
			while ($fila = mysqli_fetch_assoc($arrClanes)) {
				$clanes[]=$fila;				
			}
		}
		$totclanes=count($clanes);
		
		echo '<div style="clear:both;"></div>
<div class="caja">
	<div class="caja_l">
		<div class="caja_r">
			<div class="caja_t">
			<img src="images/ai.jpg" style="float:left;"/>
			<img src="images/ad.jpg" style="float:right;"/>
				<div class="caja_b">';
	echo '<div id="Usuarios" class="hiddencontent"><h2>Ranking de usuarios</h2>';
		$order="puntos";
		$caca="_puntos";
		if (isset($_POST['by'])) {			
			if ($_POST['by']=="frags")
			{
				$order="frags";
				$caca="_frags";
			}
		}
		
		if (isset($_POST['rank']) && isset($_POST['ve']))
		{
			$valu = intval($_POST['rank']);
			$valu = ($valu-1) * 100;
			if ($valu < 0) {$valu = 0;}
			$sql = mysqli_query($_SESSION['dbconn'],'SELECT * FROM `pjs` ORDER BY `'.$order.'` DESC LIMIT '.$valu.', 100;');
		} else {

			$sql = mysqli_query($_SESSION['dbconn'],'SELECT * FROM `pjs` ORDER BY `'.$order.'` DESC LIMIT 0, 100;');
		}
		
		echo '<form action="index.php?a=ranking" method="POST">Usuarios online (registrados): '.u_online().'<br/><select name="rank">';
		$cantidad=round($num/100)+1;
		$valu=0;

		for ($i = 1; $i < $cantidad+1;$i++)
		{
			$valuu=100 * ($i-1);
			if ($valuu < 1) {$valuu = 1;}
			$valui=$valuu + 100;
			echo '<option value="'.$i.'">Puestos '.$valuu.' - '.$valui.'</option>';
		}
		//echo '</select><input type="submit" value="Ver!" name="ve"/></form></div><table class="rank"><tr><td style="width:10px;" class="rh"></td><td style="width:200px;" class="rh">Nick</td><td style="width:100px;" class="rh">Pesos/dolares</td><td style="width:80px;" class="rh">Grupo</td><td style="width:90px;" class="rh">Puntos</td></tr>';
		echo '</select><select name="by"><option value="puntos">Ordenar por Puntos</option><option value="frags">Ordenar por Frags</option></select><input class="boto" type="submit" value="Ver!" name="ve"/></form><table class="rank"><tr><td style="width:30px;" class="rh"></td><td style="width:200px;" class="rh">Nick</td><td style="width:90px;" class="rh">Puntos</td><td style="width:45px;" class="rh">Frags</td><td style="width:45px;" class="rh">Muertes</td></tr>';
		$ii=$valu+1;

		while ($datos = mysqli_fetch_array($sql))
		{
				
			if($o>0 && $fad=true){
				$o--;
			}			
			
		$ranking =  $datos['rank'.$caca.'_old'] - $ii - 1;
			
			if ($datos['ultimologin'] > (time()-900)){
				$nikex = ' style="background:#094115;"';
				$estadox=" <b style='color:#090;'>Online</b>";
			} else {
				$estadox=" Offline";
			}
			
			if ($datos['rank'.$caca.'_old']>0)
			{
				if ($ranking == "0"){
					$masme = " <b style='color:#888;'>* </b>";
					$sh = '<b style="color:#888;">*</b>';
				} elseif ($ranking < "0"){
					$masme = " <b style='color:red;'> $ranking</b>";
					$sh = '<b style="color:#f00;">-</b>';
				} elseif ($ranking > "0"){
					$masme = " <b style='color:#0f0;'>+ $ranking</b>";
					$sh = '<b style="color:#0f0;">+</b>';
				}
			}
			
			$nike = $datos['nick'];

			if ($datos['Ban'] != "") {
				$nike = '<a title="'.$datos['Bantxt'].'" class="tooltip"><span style="text-decoration:underline;">'.$nike;
				$nike = $nike . '</span></a>';
			} elseif ($datos['GM'] == "1") {
				$nike = '<a title="Game Master" class="tooltip"><span style="color:#0c0;">'.$nike;
				$nike = $nike . '</span></a>';
			} elseif ($datos['GM'] == "2") {
				$nike = '<a title="Centinela" class="tooltip"><span style="color:yellow;">'.$nike;
				$nike = $nike . '</span></a>';
			}
			$clanx="";
			$nikex="";
			if (intval($datos['clan'])>0 && $totclanes > 0)
			{
				$datos['clan']=$datos['clan']-1;
				$clanx=' <a title="<b>'.$clanes[$datos['clan']]['Nombre'].'</b> #'.$clanes[$datos['clan']]['rank_puntos'].'<br/><b>Puntos: '.$clanes[$datos['clan']]['puntos'].'</b><br/><b>Frags: '.$clanes[$datos['clan']]['matados'].'</b><br/><b>Miembros: '.$clanes[$datos['clan']]['miembros'].'</b>" href="#'.$ii.'" class="tooltip rica">&lt;'.$clanes[$datos['clan']]['Nombre'].'&gt;</a>';
			}
			$add="";
			if($_SESSION['GM']=="1" || $_SESSION['GM']=="2")
			{
					$add=infopj($datos);
			}
			echo "<tr>
<td class='ranktit'$nikex><a class='tooltip' style='diaplay:block;' title=\"$masme$estadox$add\">$ii $sh</a></td>
<td><span class='r1c0'>$nike</span>$clanx</td>
<td>$datos[puntos]</td>
<td>$datos[frags]</td>
<td>$datos[muertes]</td>
</tr>";
			$nikex="";
			$ii++;
		}
		echo '</table></div>';
		echo '<div id="Clanes" class="hiddencontent"><h2>Ranking de clanes.</h2>';
		$qery=mysqli_query($_SESSION['dbconn'],"SELECT * FROM `clanes` ORDER BY puntos DESC");
		$o=9;
		while ($c=mysqli_fetch_array($qery))
		{
			if($o>0){$o--;}
			
			$ac='<span style="float:right;font-size:9pt;font-family:tahoma,verdana"><a class="tooltip" title="Necesitas loguearte para mandar solicitud a los clanes.">Solicitar ingreso</a></span>';
			
			if ($_SESSION['loggedE']=="1" AND $_SESSION['Clan']==0)
			{
			$ac='<span style="float:right;font-size:9pt;font-family:tahoma,verdana"><a href="?a=panel-clan&b='.$c['ID'].'">Solicitar ingreso</a></span>';
			}
			elseif ($_SESSION['Clan']!=0 AND $_SESSION['loggedE']=="1")
			{
			$ac='<span style="float:right;font-size:9pt;font-family:tahoma,verdana"><a class="tooltip" title="Ya ten&eacute;s clan.">Solicitar ingreso</a></span>';
			}
			
			echo '<div style="font-size:'.(10+$o).'pt;display:block;font-weight:bold;"><a title="<b>'.$c['Nombre'].'</b> #'.$c['rank_puntos'].'<br/><b>Puntos: '.$c['puntos'].'</b><br/><b>Frags: '.$c['matados'].'</b><br/><b>Miembros: '.$c['miembros'].'</b>" href="#'.$ii.'" class="tooltip" style="color:white;float:left;">&lt;'.$c['Nombre'].'&gt;</a>'.$ac.'</div><div style="clear:both;"></div>';
		}
		echo '</div>
			</div>
		</div>
	</div>
</div>';
		template_footer();
?>