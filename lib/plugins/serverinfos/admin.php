<?php
/**
 * DokuWiki Plugin serverinfos (Admin Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  laurent bisson <bisson.lau@wanadoo.fr>
 *
 */
/* Historique
*		v1.5.1 / 14 Juin 2017
*		 - icone pour processeur Xeon
*
*		v1.5 / 2 Juillet 2016
*		 - Correction d'un bug d'affichage de la fréquence cpu
*		 - Nouveau logo pour cpu Intel Atom
*		 - Affichage de l'état des services sous linux:
*       dns, dhcp, samba, ftp, ssh, radius, mysql
*
*  		v1.4 / 24 Avril 2016
*		 - Codé en orienté objet
*		 - Amélioration de la compatibilité avec windows
*		 - Présentation des données sous forme de tableau
*		 - Compatibilité avec Raspberry pi
*/

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class admin_plugin_serverinfos extends DokuWiki_Admin_Plugin
{
    /**
     * @return bool true if only access for superuser, false is for superusers and moderators
     */
    public function forAdminOnly() {
        return true;
    }

    /**
     * Should carry out any processing required by the plugin.
     */
    public function handle() {
    }

    /**
     * Render HTML output, e.g. helpful text and a form
     */
    public function html()
    {
		echo "<link rel='stylesheet' media='screen' type='text/css' id='css'  href='style.css' />";
		echo "<div id='corps'>";
			echo "<div id='infos'>";

			// On enregistre notre autoload.
			function chargerClasse($classname)
			{
  				require 'lib/'.$classname.'.class.php';
			}

			spl_autoload_register('chargerClasse');

			$infosServeur 	= new InfosServer();
			$elements		= new Elements();

			// Calcul uptime
			$uptime = $elements->getTime();

		// En-tete
    		$elements->displayEntete($infosServeur, $this->getLang('name'),$this->locale_xhtml('intro'));

		//
		// UPTIME
		//
			// Affiche données UPTIME si OS différent de windows
			IF (PHP_OS != "WINNT")
			{
			// Affiche la catégorie
			$elements->displayHead($this->getLang('vauptime'));

			// Affiche les valeurs
			echo "<div id='categorie_contenu' class='categorie'>";

						// Noms
						echo "<div id='categorie_contenu' class='categorie'>";
							echo "<div id='categorie_contenu_php'><strong>".$this->getLang('server_local_time')."</strong></div>";
							echo "<div id='categorie_contenu_php'><strong>".$this->getLang('server_itil_uptime')."</strong></div>";
						echo "</div>";

							// Contenus
						echo "<div id='categorie_contenu' class='valeur'>";
							echo "<div id='categorie_contenu_php'>".$elements->getLocalTime($uptime)."</div>";
							echo "<div id='categorie_contenu_php'>".$elements->getUpTime($uptime, $this->getLang('day'), $this->getLang('hour'), $this->getLang('minute'))."</div>"; // $ITIL
						echo "</div>";

			echo "</div>";
			}

		// SYSTEM
				$elements->displaySystem($infosServeur, $this->getLang('system'), $this->getLang('server_os'),
				$this->getLang('distrib'), $this->getLang('vmac'));

		// HARDWARE
			// Affiche la categorie
			$elements->displayHead($this->getLang('hw'));

			// Categorie
			$elements->displayHwCategories($this->getLang('cpu'), $this->getLang('ram'), $this->getLang('dd'));

			// Nom contenus
			$elements->displayHwSCategories($this->getLang('cpu_mark'), $this->getLang('cpu_model'), $this->getLang('cpu_freq'),
				$this->getLang('total'), $this->getLang('dd_free'), $this->getLang('dd_used'));

			// Valeurs contenus
			$elements->displayHwSCategoriesValues($infosServeur);

		// SERVEUR WEB
			// Affiche la categorie
			$elements->displayHead($this->getLang('web'));

			// Categorie
			$elements->displayWebCategories($this->getLang('server_ip'), $this->getLang('server_name'),
				$this->getLang('server_web'), $this->getLang('server_port'));

			// Valeurs
			$elements->displayWebValues();

		// SERVEUR PHP
			// Affiche la categorie
			$elements->displayHead($this->getLang('vaphp'));

			// Categorie
			$elements->displayPhpCategories($this->getLang('php_version'), $this->getLang('uploadmaxfilesize'),
				$this->getLang('postmaxsize'), $this->getLang('memorylimit'));

			// Valeurs
			$elements->displayPhpValues();

			// Chemins php
			$elements->displayPhpPath($this->getLang('document_root'), $this->getLang('path_toini'));
      
      IF (PHP_OS == "Linux")
      {
		      // AFFICHE LES SERVICES
			      // Affiche la categorie
			      $elements->displayHead($this->getLang('services'));

			      // Categories
			      $elements->displayServicesCategories();

			      // Valeurs
			      $elements->displayServicesValues();
      }

	echo "</div><!ferme infos>";

echo "</div><!ferme corps>";
    }
}

?>
