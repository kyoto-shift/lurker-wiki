<?php
class Elements
{
	public function __construct() {}

	// Affiche l'en tete
	public function displayEntete($infosServeur, $nom, $intro)
	{
	echo "<div id='entete'>";
		echo "<div id='texte'>";
			ptln('<h2>'.$nom.'</h2>');
   			echo $intro;
   		echo "</div>";

   		echo "<div id='logos'>";
			echo "<div id='logoA'>";
				IF ($infosServeur->get64() != "") { echo $infosServeur->get64();}
			echo "</div>";

			echo "<div id='logoB'>";
				IF ($infosServeur->getLogoOs() != "") { echo $infosServeur->getLogoOs();}
			echo "</div>";

			echo "<div id='logoC'>";
				IF (preg_match("#2 Duo#i",$infosServeur->getModelCpu())) {echo "<img src='./lib/plugins/serverinfos/img/core2.png' align='right' width=100% />";}
				IF (preg_match("#i7#i",$infosServeur->getModelCpu())) {echo "<img src=./lib/plugins/serverinfos/img/i7.png align='right' width=100% />";}
				IF (preg_match("#i5#i",$infosServeur->getModelCpu())) {echo "<img src='./lib/plugins/serverinfos/img/i5.png' align='right' width=100% />";}
				IF (preg_match("#i3#i",$infosServeur->getModelCpu())) {echo "<img src='./lib/plugins/serverinfos/img/i3.png' align='right' width=100% />";}
				IF (preg_match("#atom#i",$infosServeur->getMarkCpu())) {echo "<img src='./lib/plugins/serverinfos/img/atom.png' align='right' width=100% />";}
				IF (preg_match("#arm#i",$infosServeur->getMarkCpu())) {echo "<img src='./lib/plugins/serverinfos/img/rpi.png' align='right' width=100% />";}
				IF (preg_match("#xeon#i",$infosServeur->getMarkCpu())) {echo "<img src=./lib/plugins/serverinfos/img/xeon.png align='right' width=100% />";}
			echo "</div>";

			echo "<div id='logoD'>";
			   	$revision = $infosServeur->getRevisionPi();
   				IF (!empty($revision))
   				{
   					echo $infosServeur->getModelPi($revision);
   				}

			echo "</div>";

		echo "</div>";
	echo "</div>";
	}

	// Affiche le systeme
	public function displaySystem($infosServeur, $system_os, $server_os, $distrib, $vmac)
	{
		echo "<div id='categorie'>";
			ptln('<h3>'.$system_os.'</h3>');
		echo "</div>";

		// Noms
		echo "<div id='categorie_contenu' class='categorie'>";
			echo "<div id='categorie_contenu_os'><strong>".$server_os."</strong></div>";
			echo "<div id='categorie_contenu_system'><strong>".$distrib."</strong></div>";
			if (PHP_OS == "Darwin")
			{
				echo "<div id='categorie_contenu_vmac'><strong>".$vmac."</strong></div>";
			}
		echo "</div>";

		// Contenus
		echo "<div id='categorie_contenu' class='valeur'>";
			echo "<div id='categorie_contenu_os'>".PHP_OS."</div>";
			echo "<div id='categorie_contenu_system'>".$infosServeur->getSystem();
				if (PHP_OS == "Darwin") {echo $infosServeur->getMacVersion();}
			echo "</div>";
			if (PHP_OS == "Darwin")
			{
				echo "<div id='categorie_contenu_vmac'>".$infosServeur->getMacComputer()."</div>";
			}
		echo "</div>";
	}

	// Affiche Categories Hardware
	public function displayHwCategories($cpu, $ram, $dd)
	{
		echo "<div id='categorie_contenu' class='categorie'>";
			echo "<div id='categorie_contenu_cpu'><strong>".$cpu."</strong></div>";
			echo "<div id='categorie_contenu_ram'><strong>".$ram."</strong></div>";
			echo "<div id='categorie_contenu_dd'><strong>".$dd."</strong></div>";
		echo "</div>";
	}

	// Affiche Noms des Sous-categories Hardware
	public function displayHwSCategories($cpu_mark, $cpu_model, $cpu_freq, $total, $dd_free, $dd_used)
	{
		echo "<div id='categorie_contenu' class='categorie'>";
			echo "<div id='categorie_contenu_cpu'>";
				echo "<div id='categorie_contenu_cpu_mark'><strong>".$cpu_mark."</strong></div>";
				echo "<div id='categorie_contenu_cpu_model'><strong>".$cpu_model."</strong></div>";
				echo "<div id='categorie_contenu_cpu_freq'><strong>".$cpu_freq."</strong></div>";
			echo "</div>";

			echo "<div id='categorie_contenu_ram'>";
				echo "<div id='categorie_contenu_ram_total'><strong>".$total."</strong></div>";
			echo "</div>";

			echo "<div id='categorie_contenu_dd'>";
				echo "<div id='categorie_contenu_cpu_mark'><strong>".$total."</strong></div>";
				echo "<div id='categorie_contenu_cpu_model'><strong>".$dd_free."</strong></div>";
				echo "<div id='categorie_contenu_cpu_freq'><strong>".$dd_used."</strong></div>";
			echo "</div>";
		echo "</div>";
	}

	// Affiche Valeurs des Sous-categories Hardware
	public function displayHwSCategoriesValues(InfosServer $infosServeur)
	{
		echo "<div id='categorie_contenu' class='valeur'>";
			echo "<div id='categorie_contenu_cpu'>";
				echo "<div id='categorie_contenu_cpu_mark'>".$infosServeur->getMarkCpu()."</div>";
				echo "<div id='categorie_contenu_cpu_model'>".$infosServeur->getModelCpu()."</div>";
				echo "<div id='categorie_contenu_cpu_freq'>".$infosServeur->getFreqCpu()."</div>";
			echo "</div>";

			echo "<div id='categorie_contenu_ram'>";
				echo "<div id='categorie_contenu_ram_total'>".$infosServeur->getRam()."</div>";
			echo "</div>";

			echo "<div id='categorie_contenu_dd'>";
				echo "<div id='categorie_contenu_cpu_mark'>".$infosServeur->getDdTotal()."</div>";
				echo "<div id='categorie_contenu_cpu_model'>".$infosServeur->getDdFree()."</div>";
				echo "<div id='categorie_contenu_cpu_freq'>".$infosServeur->getDdUsed()."</div>";
			echo "</div>";
		echo "</div>";
	}

	// Affiche Categories Web
	public function displayWebCategories($ip, $serverName, $serverWeb, $ServerPort)
	{
		echo "<div id='categorie_contenu' class='categorie'>";
			echo "<div id='categorie_contenu_web'><strong>".$ip."</strong></div>";
			echo "<div id='categorie_contenu_web'><strong>".$serverName."</strong></div>";
			echo "<div id='categorie_contenu_web'><strong>".$serverWeb."</strong></div>";
			echo "<div id='categorie_contenu_web'><strong>".$ServerPort."</strong></div>";
		echo "</div>";
	}

	// Affiche Valeurs Web
	public function displayWebValues()
	{
		$tmp_web 		= explode(" ", $_SERVER['SERVER_SOFTWARE']);
		$webserver		= $tmp_web[0].$tmp_web[1];

		echo "<div id='categorie_contenu' class='valeur'>";
			echo "<div id='categorie_contenu_web'>".$_SERVER['SERVER_ADDR']."</div>";
			echo "<div id='categorie_contenu_web'>".$_SERVER['SERVER_NAME']."</div>";
			echo "<div id='categorie_contenu_web'>".$webserver."</div>";
			echo "<div id='categorie_contenu_web'>".$_SERVER['SERVER_PORT']."</div>";
		echo "</div>";
	}

	// Affiche Categories Php
	public function displayPhpCategories($version, $uploadmaxfilesize, $postmaxsize, $memorylimit)
	{
		echo "<div id='categorie_contenu' class='categorie'>";
			echo "<div id='categorie_contenu_web'><strong>".$version."</strong></div>";
			echo "<div id='categorie_contenu_web'><strong>".$uploadmaxfilesize."</strong></div>";
			echo "<div id='categorie_contenu_web'><strong>".$postmaxsize."</strong></div>";
			echo "<div id='categorie_contenu_web'><strong>".$memorylimit."</strong></div>";
		echo "</div>";
	}

	// Affiche Valeurs php
	public function displayPhpValues()
	{
		echo "<div id='categorie_contenu' class='valeur'>";
			echo "<div id='categorie_contenu_web'>".phpversion()."</div>";
			echo "<div id='categorie_contenu_web'>".ini_get('upload_max_filesize')."</div>";
			echo "<div id='categorie_contenu_web'>".ini_get('post_max_size')."</div>";
			echo "<div id='categorie_contenu_web'>".ini_get('memory_limit')."</div>";
		echo "</div>";
	}

	// Affiche chemins fichiers php
	public function displayPhpPath($docroot, $pathini)
	{
		//Categories
		echo "<div id='categorie_contenu' class='categorie'>";
			echo "<div id='categorie_contenu_php'><strong>".$docroot."</strong></div>";
			echo "<div id='categorie_contenu_php'><strong>".$pathini."</strong></div>";
		echo "</div>";

		// Valeurs
		echo "<div id='categorie_contenu' class='valeur'>";
			echo "<div id='categorie_contenu_php'>".$_SERVER['DOCUMENT_ROOT']."</div>";
			echo "<div id='categorie_contenu_php'>".php_ini_loaded_file()."</div>";
		echo "</div>";
	}

	// Affiche Categories Hardware
	public function displayServicesCategories()
	{
		echo "<div id='categorie_contenu' class='categorie'>";
			echo "<div id='categorie_contenu_service'><strong>DNS</strong></div>";
			echo "<div id='categorie_contenu_service'><strong>DHCP</strong></div>";
			echo "<div id='categorie_contenu_service'><strong>SAMBA</strong></div>";
			echo "<div id='categorie_contenu_service'><strong>SSH</strong></div>";
			echo "<div id='categorie_contenu_service'><strong>FTP</strong></div>";
			echo "<div id='categorie_contenu_service'><strong>RADIUS</strong></div>";
			echo "<div id='categorie_contenu_service'><strong>MySQL</strong></div>";
		echo "</div>";
	}

	// Affiche Status serveurs
	public function displayServicesValues()
	{
		exec("dpkg -l | grep 'ii  mysql-server '", $installDns);			exec("pidof named", $statusDns);
		exec("dpkg -l | grep 'ii  isc-dhcp-server '", $installDhcp);	exec("pidof dhcpd", $statusDhcp);
		exec("dpkg -l | grep 'ii  samba '", $installSamba);						exec("pidof smbd", $statusSamba);
		exec("dpkg -l | grep 'ii  openssh-server '", $installSsh);		exec("pidof sshd", $statusSsh);
		exec("dpkg -l | grep 'ii  proftpd-basic '", $installFtp);			exec("pidof ftpd", $statusFtp);
		exec("dpkg -l | grep 'ii  freeradius '", $installRadius);			exec("pidof freeradius", $statusRadius);
		exec("dpkg -l | grep 'ii  mysql-server '", $installMysql);		exec("pidof mysqld", $statusMysql);
		$none = "<span class='none'>none</span>";
		$ok 	= "<span class='online'>OK</span>";
		$stop = "<span class='offline'>STOP</span>";

		echo "<div id='categorie_contenu' class='valeur'>";
			echo "<div id='categorie_contenu_service'>";
				IF (empty($installDns[0])) {echo $none;}
					ELSE {
								IF (empty($statusDns[0])) {echo $stop;} ELSE {echo $ok;}
								}
			echo "</div>";
			echo "<div id='categorie_contenu_service'>";
				IF (empty($installDhcp[0])) {echo $none;}
					ELSE {
								IF (empty($statusDhcp[0])) {echo $stop;} ELSE {echo $ok;}
								}
			echo "</div>";
			echo "<div id='categorie_contenu_service'>";
				IF (empty($installSamba[0])) {echo $none;}
					ELSE {
								IF (empty($statusSamba[0])) {echo $stop;} ELSE {echo $ok;}
								}
			echo "</div>";
			echo "<div id='categorie_contenu_service'>";
				IF (empty($installSsh[0])) {echo $none;}
					ELSE {
								IF (empty($statusSsh[0])) {echo $stop;} ELSE {echo $ok;}
								}
			echo "</div>";
			echo "<div id='categorie_contenu_service'>";
				IF (empty($installFtp[0])) {echo $none;}
					ELSE {
								IF (empty($statusFtp[0])) {echo $stop;} ELSE {echo $ok;}
								}
			echo "</div>";
			echo "<div id='categorie_contenu_service'>";
				IF (empty($installRadius[0])) {echo $none;}
					ELSE {
								IF (empty($statusRadius[0])) {echo $stop;} ELSE {echo $ok;}
								}
			echo "</div>";
			echo "<div id='categorie_contenu_service'>";
				IF (empty($installMysql[0])) {echo $none;}
					ELSE {
								IF (empty($statusMysql[0])) {echo $stop;} ELSE {echo $ok;}
								}
			echo "</div>";
		echo "</div>";
	}

	// Affiche l'entete
	public function displayHead($nom)
	{
		echo "<div id='categorie'>";
			ptln('<h3>'.$nom.'</h3>');
		echo "</div>";
	}

	// Recupere heure
	public function getTime()
	{
		exec('uptime', $data);
		$result_command	= $data[0];
		return $result_command;
	}

	// Affiche l'heure
	public function getLocalTime($timeValue)
	{
		//echo $timeValue;
		$data['up']					= explode('up',$timeValue);
		$data['mins']				= explode('mins',$timeValue);
		$data['duree']				= $data['up'][1];
		$data['server_local_time']	= $data['up'][0];
		return $data['server_local_time'];
	}

	// Affiche l'uptime
	public function getUptime($uptime, $day, $hour, $minute)
	{
		$data['up']					= explode('up',$uptime);
		$data['mins']				= explode('mins',$uptime);
		$data['duree']				= $data['up'][1];
		$data['server_local_time']	= $data['up'][0];

		// timeup between 0 and 59 minutes
		IF (preg_match("#min#i",$uptime))
			{
				$duree					= explode('mins',$data['up'][1]);
				$minutes				= $duree[0];
				if ($minutes>1) { $sm='s'; } else { $sm=''; }
				$ITIL = $minutes." ".($minute).$sm;
				return $ITIL;
			}

		// timeup between 1 and 24 hours
		IF (!preg_match("#sec#i",$uptime) AND !preg_match("#min#i",$uptime) AND !preg_match("#day#i",$uptime))
			{
				$duree_tmp		= explode(',',$data['up'][1]);
				$duree			= explode(':',$duree_tmp[0]);
				$heures			= $duree[0];
				$minutes		= $duree[1];
				if ($heures>1) { $sh='s'; } else { $sh=''; }
				if ($minutes>1) { $sm='s'; } else { $sm=''; }
				$ITIL = $heures." ".$hour.$sh." ".$minutes." ".$minute.$sm;
				return $ITIL;
			}

		// timeup > 1 day
		IF (preg_match("#day#i",$uptime))
		{
			$duree_tmp			= explode('up',$data['up'][1]);
			$days_hours			= explode(',',$duree_tmp[0]);
			$nb_days_tmp		= explode("day",$days_hours[0]);
			$days				= $nb_days_tmp[0];

            if (preg_match("#min#i",$uptime))
            {
                $duree		   = explode('min',$days_hours[1]);
                $minutes       = $duree[0];
                $heures        = "";
            }
             else
            {
				$duree				= explode(':',$days_hours[1]);
				$heures				= $duree[0];
				$minutes			= $duree[1];
            	$display_hours 		= $hour;
            }

            if ($heures >0) {$display_hours = $hour;} else {$display_hours = "";}
			if ($heures>1) { $sh='s'; } else { $sh=''; }
			if ($minutes>1) { $sm='s'; } else { $sm=''; }
			if ($days>1) { $sd='s'; } else { $sd=''; }
			$ITIL = $days.$day.$sd." ".$heures." ".$display_hours.$sh." ".$minutes." ".$minute.$sm;
			return $ITIL;
		}
	}

}
?>
