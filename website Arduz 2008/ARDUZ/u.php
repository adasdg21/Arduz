<?php

	$dbconn = mysqli_connect('localhost', 'root', '', 'noicoder_sake');

	$versiones = "0.1.2";
	$IP="127.0.0.1";//getRealIP();
	$codigo="";
	$str="";

	for ($i = 1; $i <= 6; $i++) {
	   	$codigo= $codigo . chr(rand(65,90));
	}
	if (isset($_REQUEST['datos']) && $_REQUEST['a']=="upd")
	{
		$temp1=mzdecode($_REQUEST['datos']);
		if (isset($_REQUEST['pipo'])) { echo $temp1; }
		$puedo=false;
		$temp1 = explode("/*/",$temp1);
		$serverdata=explode("~",$temp1[0]);
			mysqli_query($dbconn,"DELETE FROM `servers` WHERE `ultima` < '".(time()-900)."'");
			$query=mysqli_query($dbconn,"SELECT * FROM `servers` WHERE `IP` = '$IP'");
			if (mysqli_num_rows($query)>0)
			{
				$res=mysqli_fetch_array($query);
				if ($serverdata[0]==$res['keysec'])
				{   //UPDATEAR EL NUEVO KEY-SEG
					$query=mysqli_query($dbconn,"UPDATE `servers` SET `keysec`='$codigo',`ultima`='".time()."' WHERE `IP` = '$IP'");
					$puedo=true;
					$ServerID=$res['ID'];
					$decrypt=$serverdata[0];
					$str.="@".$codigo."@";
				} else {
					echo "PEDIRNUEVO";
				}
			}
		if ($puedo==true)
		{
			$arraypen = explode("@",mzdecode($temp1[1],$decrypt));
			
			foreach($arraypen as $datos)
			{
  
				[$UIDinSV, 
				$usuario, 
				$password, 
				$puntos, 
				$frags, 
				$muertes] = explode("~", $datos);
				
				$query = mysqli_query($dbconn,"SELECT * FROM `pjs` WHERE `nick` = '".strtoupper($usuario)."'");
				if (isset($usuario))
				{
					if (mysqli_num_rows($query)>0)
					{
						$res=mysqli_fetch_array($query);
						if (utf8_encode(base64_encode($res['codigo']))==$password)
						{
							$sql="
							UPDATE
								pjs
							SET
								frags=frags+'".intval($frags)."',
								muertes=muertes+'".intval($muertes)."',
								partidos=partidos+'1',
								puntos=puntos+'".intval($puntos)."',
								ultimologin='".time()."',
								ultimosv='$ServerID'
							WHERE
								nick='".$usuario."'";
							mysqli_query($dbconn,$sql);
							$add = "1ç";

							if (intval($res['clan'])>0)
							{
								$clan=mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `clanes` WHERE `ID`='".$res['clan']."';"));
								$add.=$clan['Nombre'];
								mysqli_query($dbconn,"UPDATE `clanes` SET `matados`=`matados` + ".intval($frags).",`muertos` = `muertos` + ".intval($muertes).",`puntos` = `puntos` + ".intval($puntos)." WHERE `ID`='".$res['clan']."';");
							}

							$add .= "ç".$res['Items']."ç".$res['GM']."ç".$res['BAN'];
						} else {
							$add = "0";
						}
					} else {
						$add = "2";
					}
					$str.="|ç".$UIDinSV."ç".$add."ç".mysqli_error($dbconn);
				}
			}
		}
		echo "UPD".$str;
		
	}
	elseif ( isset($_REQUEST['datos']) && $_REQUEST['a']=="crear" )
	{
		mysqli_query($dbconn,"DELETE FROM `servers` WHERE `ultima` < '".(time()-900)."'");
		$query=mysqli_query($dbconn,"SELECT * FROM `servers` WHERE `IP` = '$IP'");
		if (mysqli_num_rows($query)==0)
		{
			$temp1=mzdecode($_REQUEST['datos']);
			$serverdata=explode("~",$temp1);
			if ($serverdata[5]!=$versiones)
			{
			echo "MSG@Hay una nueva version del servidor disponible en http://ao.noicoder.com/ (version actual:$versiones ,tu version: $serverdata[5])@";
			}else{
			$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$query=mysqli_query($dbconn,"INSERT INTO `servers` SET `keysec`='$codigo',`ultima`='".time()."',`inicio`='".time()."', `IP` = '$IP',`Nombre`='$serverdata[0]',`Mapa`='$serverdata[1]',`Players`='$serverdata[2]',`PORT`='$serverdata[3]',`hamachi`='$serverdata[4]', `HOST`='$hostname'");
			echo "CREAR@OK@".$codigo."@";
			}	
		} else {
			echo "CREAR@YATA";
		}
	}
	elseif ($_REQUEST['a']=="ping")
	{
		mysqli_query($dbconn,"DELETE FROM `servers` WHERE `ultima` < '".(time()-900)."'");
		$query=mysqli_query($dbconn,"SELECT * FROM `servers` WHERE `IP` = '$IP'");
		$svdata=mysqli_fetch_array($query);
		if (!isset($_REQUEST['version']))
		{
			if (mysqli_num_rows($query)>0)
			{
				$temp1=mzdecode($_REQUEST['datos']);
				$serverdata=explode("~",$temp1);
				$query=mysqli_query($dbconn,"UPDATE `servers` SET `keysec`='$codigo',`ultima`='".time()."',`Nombre`='$serverdata[0]',`Mapa`='$serverdata[1]',`Players`='$serverdata[2]' WHERE `IP`='$IP'");
				echo "PING@OK@".$codigo."@";
			} else {
				echo "PING@NO";
			}
		} else {
			if (mysqli_num_rows($query)>0)
			{
				$dtos="";
				if (isset($_REQUEST['datos'])) {
					$dtos=$_REQUEST['datos'];
				}
				$temp1=mzdecode($dtos);
				$serverdata=explode("~",$temp1);
				$sqladd="";
				foreach($serverdata as $datos)
				{
					$cato = explode("=",$datos);
					if ($cato[0]=="SERVERNAME") {$sqladd .= " ,`Nombre`='$cato[1]'";}
					if ($cato[0]=="SERVERMAP") {$sqladd .= " ,`Mapa`='$cato[1]'";}
					if ($cato[0]=="SERVERUSERS") {$sqladd .= " ,`players`='$cato[1]'";}
				}
				$query=mysqli_query($dbconn,"UPDATE `servers` SET `keysec`='$codigo',`ultima`='".time()."'$sqladd WHERE `IP`='$IP'");
				echo "PING@OK@".$codigo."@";
			} else {
				echo "PING@NO";
			}		
		}
	}
	elseif ($_REQUEST['a']=="borra")
	{
		$query=mysqli_query($dbconn,"DELETE FROM `servers` WHERE `IP` = '$IP'");
		if (mysqli_num_rows($query)>0)
		{
		echo "BORRA@OK";
		} else {
		echo "BORRA@NOTA";
		}
	}
	elseif ($_REQUEST['a']=="list")
	{
		$query=mysqli_query($dbconn,"SELECT * FROM `servers` ORDER BY `players` DESC");
		if ($_REQUEST['v']==$versiones)
		{
			if (mysqli_num_rows($query)>0)
			{
				while ($carlos=mysqli_fetch_array($query))
				{
					echo '@|'.$carlos['IP']."ç".$carlos['PORT']."ç".$carlos['Nombre']."ç".$carlos['Mapa']."ç".$carlos['players']."/20";
					if ($_REQUEST['hamachi']!="0"){
						if (strlen($carlos['hamachi'])>7){
							echo '@|'.$carlos['hamachi']."ç".$carlos['PORT']."ç".$carlos['Nombre']." - Via Hamachiç".$carlos['Mapa']." - Via Hamachiç".$carlos['players']."/20";
						}
					}
				}
				echo '@|';
			} else {
				echo "NOTA";
			}
		} else {
			echo "NUEVAVERSION_@|0.0.0.0ç0çHAY UNA NUEVA VERSION DISPONIBLE EN LA WEBçççç@|0.0.0.0ç0çDESCARGALA ENTRANDO A www.ao.noicoder.comçççç";
		}
	}
	else
	{
		if (!isset($_REQUEST['pipo'])) { header("Location: index.php");}
	}
//if (isset($_REQUEST['pipo'])) {echo $IP."<IP<br/>".$codigo.$tema.$temp1[0].$temp1[1]."<br>".$tema;}
	
	
	
	
	function getRealIP()
	{
	   
	   if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
	   {
	      $client_ip =
	         ( !empty($_SERVER['REMOTE_ADDR']) ) ?
	            $_SERVER['REMOTE_ADDR']
	            :
	            ( ( !empty($_ENV['REMOTE_ADDR']) ) ?
	               $_ENV['REMOTE_ADDR']
	               :
	               "unknown" );
	   
	      // los proxys van açadiendo al final de esta cabecera
	      // las direcciones ip que van "ocultando". Para localizar la ip real
	      // del usuario se comienza a mirar por el principio hasta encontrar
	      // una direcciçn ip que no sea del rango privado. En caso de no
	      // encontrarse ninguna se toma como valor el REMOTE_ADDR
	   
	      $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
	   
	      reset($entries);
	      while (list(, $entry) = each($entries))
	      {
	         $entry = trim($entry);
	         if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
	         {
	            // http://www.faqs.org/rfcs/rfc1918.html
	            $private_ip = array(
	                  '/^0\./',
	                  '/^127\.0\.0\.1/',
	                  '/^192\.168\..*/',
	                  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
	                  '/^10\..*/');
	   
	            $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
	   
	            if ($client_ip != $found_ip)
	            {
	               $client_ip = $found_ip;
	               break;
	            }
	         }
	      }
	   }
	   else
	   {
	      $client_ip =
	         ( !empty($_SERVER['REMOTE_ADDR']) ) ?
	            $_SERVER['REMOTE_ADDR']
	            :
	            ( ( !empty($_ENV['REMOTE_ADDR']) ) ?
	               $_ENV['REMOTE_ADDR']
	               :
	               "unknown" );
	   }
	   
	   return $client_ip;
	   
	}

	function mzdecode($str,$code = 'mzbbfdtt')
	{	$temp=base64_decode($str);
		$temp=utf8_encode(str_replace("*7","=",$temp));
		$temp=utf8_encode(str_replace("BB#","H",$temp));
		$temp=utf8_encode(str_replace("/*/","=",$temp));
		$temp=utf8_encode(str_replace("xD","5",$temp));
		$temp=utf8_encode(str_replace("XO","Y",$temp));
		$temp=utf8_encode(str_replace("#7#","F",$temp));
		$temp=utf8_encode(str_replace("WA!","X",$temp));
		$temp=utf8_encode(str_replace(":D!","R",$temp));
		$temp=utf8_encode(base64_decode($temp));
		$temp=utf8_encode(str_replace($code,"",$temp));
		return utf8_encode(base64_decode($temp));
	}
?>