<?php
class InfosServer
{
	private $_osServer;

	public function osServer()
	{
		return $this->_osServer;
	}

	public function setOsServer()
	{
		$this->_osServer = PHP_OS;
	}

	public function __construct()
	{
		$this->setOsServer();

	}
		// Infos intermediaires CPU pour windows (car tps de chargement long)
		public function getWinCpu()
		{
			exec("powershell -executionpolicy remotesigned -command gwmi win32_Processor name,DataWidth", $tmp);
			return $tmp;
		}

		// Logo OS
		public function getLogoOs()
		{
			$os = $this->osServer();	// OS installé

			IF ($os == 'Darwin')		// Pour Macintosh
				{$logo_os	= "<img src='./lib/plugins/serverinfos/img/apple.jpg' width=100% alt='logo Apple' />";}

			IF ($os == 'Linux') 		// Pour Linux
				{
					exec('lsb_release -ds', $distrib);

					if (preg_match("#debian#i",$distrib[0]))
					{
						$logo_os = "<img src='./lib/plugins/serverinfos/img/debian.png' width=100% />";
					}
       				 elseif (preg_match("#ubuntu#i",$distrib[0]))
					{
						$logo_os = "<img src='./lib/plugins/serverinfos/img/ubuntu.png' width=100% />";
					}
					 else
					 {
      					$logo_os = "<img src='./lib/plugins/serverinfos/img/linux.png' width=100% />";
      				}
				}

			 IF ($os == "WINNT")
				{$logo_os = "<img src='./lib/plugins/serverinfos/img/windows.jpg' width=100% />"; }

			echo $logo_os;
		}

		// Nom du systeme
		public function getSystem()
		{
			$os = $this->osServer();	// OS installé

			IF ($os == 'Darwin')		// Pour Macintosh
			{
      				exec("sw_vers", $os_type);
      				$os_tmp 	= explode('ProductName:',$os_type[0]);
      				$systeme 		= $os_tmp[1];
			}

			IF ($os == 'Linux') 		// Pour Linux
			{
      			exec('lsb_release -ds | cut -d" " -f1,3,4', $distrib);
				$systeme = $distrib[0];
			}

			IF ($os == 'WINNT')
			{
				exec('powershell -executionpolicy remotesigned -command (gwmi -class Win32_OperatingSystem).Caption', $cpu_tmp64);
				$exp = explode(' ', $cpu_tmp64[0],3);
				$systeme = $exp[2];
			}
      		return $systeme;
		}

		// Version OS pour MAC
		public function getMacVersion()
		{
      		exec("sw_vers", $os_type);
      		$vos_tmp 	= explode('ProductVersion:',$os_type[1]);
      		$vos 		= $vos_tmp[1];

      		return $vos;
		}

		// Modele MACINTOSH
		public function getMacComputer()
		{
			exec("sysctl -n hw.model", $modelComputer);
			return $modelComputer[0];
		}

		// No Revision pour R-Pi
		public function getRevisionPi()
		{
			$os 		= $this->osServer();
			$model_tmp	= explode('@', $this->getTmp());

			IF (preg_match("#arm#i",$model_tmp[0]) AND $os == 'Linux')
			{
				exec("sed -n '/^Revision/p' /proc/cpuinfo", $piTmp);
				$tmp = explode(':', $piTmp[0]);
				return $tmp[1];
			}
		}

		// Type de modele pour R-Pi
		public function getModelPi($revision)
		{
			IF (stristr($revision, "0002")) {$modelPi = 'R-Pi<br>Model B';}
			IF (stristr($revision, "0003")) {$modelPi = 'R-Pi<br>Model B<br>Fuses mode & D14 removed';}
			IF (stristr($revision, "0004")) {$modelPi = 'R-Pi<br>Model B<br>Made by Sony';}
			IF (stristr($revision, "0005")) {$modelPi = 'R-Pi<br>Model B<br>Made by Qisda';}
			IF (stristr($revision, "0006")) {$modelPi = 'R-Pi<br>Model B<br>Made by Egoman';}
			IF (stristr($revision, "0007")) {$modelPi = 'R-Pi<br>Model A<br>Made by Egoman';}
			IF (stristr($revision, "0008")) {$modelPi = 'R-Pi<br>Model A<br>Made by Sony';}
			IF (stristr($revision, "0009")) {$modelPi = 'R-Pi<br>Model A<br>Made by Qisda';}
			IF (stristr($revision, "000d")) {$modelPi = 'R-Pi<br>Model B<br>Made by Egoman';}
			IF (stristr($revision, "000e")) {$modelPi = 'R-Pi<br>Model B<br>Made by Sony';}
			IF (stristr($revision, "000f")) {$modelPi = 'R-Pi<br>Model B<br>Made by Qisda';}
			IF (stristr($revision, "0010")) {$modelPi = 'R-Pi<br>Model B+<br>Made by Sony';}
			IF (stristr($revision, "0011")) {$modelPi = 'R-Pi<br>Model CpM<br>Made by Sony';}
			IF (stristr($revision, "0012")) {$modelPi = 'R-Pi<br>Model A+<br>Made by Sony';}
			IF (stristr($revision, "0013")) {$modelPi = 'R-Pi<br>Model B+';}
     		IF (stristr($revision, "a01041")) {$modelPi = 'R-Pi 2<br>Model B<br>Made by Sony';}
     		IF (stristr($revision, "a21041")) {$modelPi = 'R-Pi 2<br>Model B<br>Made by Embest';}
     		IF (stristr($revision, "900092")) {$modelPi = 'R-Pi Zero<br>Made by Sony';}
     		IF (stristr($revision, "a02082")) {$modelPi = 'R-Pi 3<br>Model B<br>Made by Sony';}
     		IF (stristr($revision, "a22082")) {$modelPi = 'R-Pi 3<br>Model B';}
     		return "<strong>".$modelPi."</strong>";
		}

		// Recupere infos memoire
		public function getRam()
		{
			$os = $this->osServer();	// OS installé

			IF ($os == 'Darwin')		// Pour Macintosh
			{
				exec("sysctl -a hw.memsize", $tmp);
				$memsize = explode(':', $tmp[0]);
				$ram = $memsize[1];
				return round(($ram/1073741824), 2)."Go";
			}

			IF ($os == 'Linux') 		// Pour Linux
			{
				exec("free | grep 'Mem'", $tmpLinux);
				$ram_tmp = explode('Mem:', $tmpLinux[0]);
				$chaine =  $ram_tmp[1];
				$chaine_preg = preg_replace('/\s{2,}/', ' ', $chaine);
				$chaine_tmp = explode(' ',$chaine_preg);
				$ram = $chaine_tmp[1];
				return round(($ram/1048576), 2)."Go";
			}

			IF ($os == 'WINNT') 		// Pour Windows
			{
				exec("powershell -executionpolicy remotesigned -command gwmi win32_computersystem", $tmpWin);
				$exp = explode(':', $tmpWin[7]);
				$ram = $exp[1];
				return round(($ram/1073741824), 2)."Go";
			}
		}

  		// Variable temporaire pour CPU
		public function getTmp()
		{
			$os = $this->osServer();
			IF ($os == "Darwin") {exec("sysctl -n machdep.cpu.brand_string", $tmp);$cpu_tmp = $tmp[0];}
			IF ($os == "Linux") {exec("sed -n '/model name/p' /proc/cpuinfo | uniq", $tmp);$cpu_tmp = $tmp[0];}
			IF ($os == "WINNT")
			{
				$tmp = $this->getWinCpu();
				$exp = explode(':', $tmp[13]);
				$cpu_tmp = $exp[1];
			}

			return $cpu_tmp;
		}

		// CPU 64b
		public function get64()
		{
			$os = $this->osServer();
			IF ($os == "Darwin")
			{
				exec("sysctl -n hw.cpu64bit_capable", $cpu_tmp64);
				$value64 = $cpu_tmp64[0];
			}

			IF ($os == "Linux")
			{
				exec("arch", $test64);
				$cpu_tmp64 = explode('_', $test64[0]);
				IF ($cpu_tmp64[1] == 64) {$value64 = 1;}
			}

			IF ($os == "WINNT")
			{
				$tmp = $this->getWinCpu();
				$cpu_tmp64 = explode(':', $tmp[12]);
				IF ($cpu_tmp64[1] == 64) {$value64 = 1;}
			}

			IF ($value64 == 1) {$logo64 = "<img src='./lib/plugins/serverinfos/img/64b.png' width=100% />";}
				ELSE {$logo64 = "<img src='./lib/plugins/serverinfos/img/32b.png' width=100% />";}

			return $logo64;
		}

		// Marque du CPU
		public function getMarkCpu()
		{
			$os 		= $this->osServer();
			$model_tmp	= explode('@', $this->getTmp());
			$exp		= explode('(TM)', $model_tmp[0]);
			IF ($os == "Darwin") {$mark = $exp[0];}
			IF ($os == "Linux")
			{
				IF (preg_match("#arm#i",$model_tmp[0]))
				{
					$int  = explode(':', $exp[0]);
					$int2 = explode('Processor', $int[1]);
					$mark = $int2[0];
				}
				 ELSE
				 {
				 	$int  = explode(':', $exp[0]);
					$mark = $int[1];
				 }
			}
			IF ($os == "WINNT") {$mark = $exp[0];}

			return $mark;
		}

		// Modele du CPU
		public function getModelCpu()
		{
			$os 		= $this->osServer();
			$model_tmp	= explode('@', $this->getTmp());

			IF (preg_match("#arm#i",$model_tmp[0]) AND $os == 'Linux')
			{
					$int  		= explode(':', $model_tmp[0]);
					$int2		= explode('Processor', $int[1]);
					$modele 	= $int2[1];
			}
			 ELSE
			 {
				$exp		= explode('(TM)', $model_tmp[0]);
				$modele 	= str_replace('CPU', '', $exp[1]);
			 }

			return $modele;
		}

		// Frequence du CPU
		public function getFreqCpu()
		{
			$os 		= $this->osServer();
			$model_tmp	= explode('@', $this->getTmp());

			IF (preg_match("#arm#i",$model_tmp[0]) AND $os == 'Linux')
			{
				exec("cat /sys/devices/system/cpu/cpu0/cpufreq/cpuinfo_max_freq", $tmp);
				$frequence = ($tmp[0]/1000000)."GHz";
			}
			 ELSE {$frequence = $model_tmp[1];}

			return $frequence;
		}

	// espace disque total
	public function getDdTotal()
	{
		$os 		= $this->osServer();	// OS installé
		$model_tmp	= explode('@', $this->getTmp());
		$conv = 1073741824;

		IF ($os == "Darwin")
		{
			$data = disk_total_space("/");
			$total	= round(($data)/$conv,2);
		}

		IF ($os == "Linux")
		{
			$conv  = 1044576;
			exec("df --total | tail -1 | sed 's/  */ /g' | cut -d' ' -f2", $tmp_dd);
			IF (empty($tmp_dd[0])) {$tmp_dd[0] = disk_total_space("/");$conv  = 1073741824;}
			$total = round(($tmp_dd[0])/$conv,2);
		}

		IF ($os == "WINNT")
		{
			$data 	= disk_total_space("c:/");
			$total	= round(($data)/$conv,2);
		}
		return $total."Go";
	}

	// espace disque libre
	public function getDdFree()
	{
		$os = $this->osServer();	// OS installé

		IF ($os == "Darwin")
		{
			$data 	= disk_free_space("/");
			$conv 	= 1073741824;
			$free	= round(($data)/$conv,2);
		}

		IF ($os == "Linux")
		{
			$conv  = 1044576;
			exec("df --total | tail -1 | sed 's/  */ /g' | cut -d' ' -f4", $tmp_dd);
			IF (empty($tmp_dd[0])) {$tmp_dd[0] = disk_free_space("/");$conv  = 1073741824;}
			$free	= round(($tmp_dd[0])/$conv,2);
		}

		IF ($os == "WINNT")
		{
			$data 	= disk_free_space("c:/");
			$conv 	= 1073741824;
			$free	= round(($data)/$conv,2);
		}
		return $free."Go";
	}

	// espace disque occupé
	public function getDdUsed()
	{
		$used = ($this->getDdTotal()) - ($this->getDdFree());
		return $used."Go";
	}

	// Nom du serveur WEB
	public function getWebServer()
	{
		$tmp_web 	= explode(" ", $_SERVER['SERVER_SOFTWARE']);
		$webServer	= $tmp_web[0];

		return $webServer;
	}

		// Version serveur php
	public function getPhpVersion()
	{
		$tmp_php 	= explode("-", phpversion());
		$phpVersion	= $tmp_php[0];

		return $phpVersion;
	}
}
?>
