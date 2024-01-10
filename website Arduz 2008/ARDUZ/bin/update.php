<?php
	
	$dbconn = mysqli_connect('localhost', 'root', '', 'noicoder_sake');

	$temp1=$_REQUEST['datos'];
	$arraypen = explode("@",$temp1);
	foreach($arraypen as $datos)
	{
		list($UIDinSV, $usuario, $password, $puntos, $frags, $muertes) = explode("_._", $datos);
		$query=mysqli_query($dbconn,"SELECT * FROM `pjs` WHERE `nick` = '".$usuario."'");
		if (isset($usuario))
		{
			if (mysql_num_rows($query)>0)
			{
				$res=mysql_fetch_array($query);
				if ($res['codigo']==$password)
				{
				mysqli_query($dbconn,"UPDATE `pjs` SET `frags` = `frags` + ".intval($frags).",`muertes` = `muertes` + ".intval($muertes).",`partidos` = `partidos` + '1',`puntos` = `puntos` + '".intval($puntos)."', `ultimologin`='".time()."' WHERE `nick` = '".$usuario."' AND `codigo` = '".$password."'");
					$add = "1";
				} else {
					$add = "0";
				}
			} else {
				$add = "2";
			}
			$str.="@�".$UIDinSV."�".$add;
		}
	}
	echo $str;
	exit();
?>