<?php

class PluginDashboardConfig extends CommonDBTM {

   static protected $notable = true;
   
   /**
    * @see CommonGLPI::getMenuName()
   **/
   static function getMenuName() {
      return __('Dashboard');
   }
   
   /**
    *  @see CommonGLPI::getMenuContent()
    *
    *  @since version 0.5.6
   **/
   static function getMenuContent() {
   	global $CFG_GLPI;
   
   	$menu = array();

      $menu['title']   = __('Dashboard');
      $menu['page']    = '/plugins/dashboard/front/index.php';
   	return $menu;
   }
    

}

function plugin_init_dashboard() {
  
   global $PLUGIN_HOOKS, $LANG ;
   
//   $menuentry = 'front/index.php'; 
       
   $PLUGIN_HOOKS['csrf_compliant']['dashboard'] = true;   
//   $PLUGIN_HOOKS['menu_entry']['dashboard']     = $menuentry;

	$PLUGIN_HOOKS["menu_toadd"]['dashboard'] = array('plugins'  => 'PluginDashboardConfig');
 
   //$PLUGIN_HOOKS['helpdesk_menu_entry']['dashboard']     = $menuentry;
   
//  $PLUGIN_HOOKS['config_page']['dashboard'] = 'front/index.php';
   $PLUGIN_HOOKS['config_page']['dashboard'] = 'front/index.php';
                
}


function plugin_version_dashboard(){
	global $DB, $LANG;

	return array('name'			=> __('Dashboard','dashboard'),
					'version' 			=> '0.5.6',
					'author'			   => '<a href="mailto:glpidashboard@gmail.com"> Stevenes Donato </b> </a>',
					'license'		 	=> 'GPLv2+',
					'homepage'			=> 'https://forge.indepnet.net/projects/dashboard',
					'minGlpiVersion'	=> '0.85'
					);
}

function plugin_dashboard_check_prerequisites(){
        if (GLPI_VERSION>=0.85){
                return true;
        } else {
                echo "GLPI version NOT compatible. Requires GLPI 0.85";
        }
}


function plugin_dashboard_check_config($verbose=false){
	if ($verbose) {
		echo 'Installed / not configured';
	}
	return true;
}


?>