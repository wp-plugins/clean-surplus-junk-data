<?php
/*
Plugin Name: Clean Surpluss (junk database data)
Description: Safely cleans & prevents unnecessary, needless data from Wordpress.  Removes AUTO-SAVES and AUTO-DRAFTED revisions,useless posts from database. VERY lightweight plugin,see description under Settings. (P.S.  OTHER MUST-HAVE PLUGINS FOR EVERYONE: http://bitly.com/MWPLUGINS  )
Version: 1.1
Author: selnomeria
 */
if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly
define('OptName_vars__csjd', 'cleanlist__csjd');

// --------------- MAIN FUNCTION --------------
// --------------- JUST AN USER INTERFACE --------------
if (is_admin())
{
		//--------------------disable AUTOSAVE and REVISIONS----------------------
		// add this define in wp-config.php
		//define('WP_POST_REVISIONS', false);
		//add_action( 'wp_print_scripts', 'disable_autosave__csjd' );
		//function disable_autosave__csjd() { wp_deregister_script('autosave'); }


	add_action( 'activated_plugin', 'activat_redirect__csjd' ); function activat_redirect__csjd( $plugin ) { 
		if( $plugin == plugin_basename( __FILE__ ) ) {  exit(wp_redirect( admin_url( 'admin.php?page=cln-db')) );  }
	}
	register_activation_hook( __FILE__, 'activation__csjd' );function activation__csjd() { 	global $wpdb;
		update_option( OptName_vars__csjd, array('c1'=>'y',  'c2'=>'y',  'c3'=>'y',  'c4'=>'y',  'c5'=>'y',  'c6'=>'y',  'c7'=>'y',  'c8'=>'y',	'enb_auto_clean'=>'y', 'db_last_auto_cnl'=> time() )); 
	}
	
	function cleendb__csjd(){	$cln_opt = get_option(OptName_vars__csjd);
		global $wpdb;
		if ($cln_opt['c1']=='y')	{ $del= $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'"); }
		if ($cln_opt['c2']=='y')	{ $del= $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'revision'"); }
		if ($cln_opt['c3']=='y')	{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_rss%'");}
		if ($cln_opt['c4']=='y')	{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_rss_%'");}
		if ($cln_opt['c5']=='y')	{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_feed_%'");}
		if ($cln_opt['c6']=='y')	{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_feed_%'");}
		if ($cln_opt['c7']=='y')	{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_dash_%'");}
		if ($cln_opt['c8']=='y')	{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_dash_%'");}
	}

	function validate_pageload__csjd($value, $action_name){
		if ( 	!isset($value) || !wp_verify_nonce($value, $action_name) ) {
			die("not allowed due to restriction_error_151 (". __FILE__);
		}
	}	

	// ------------------------- ADD PAGE IN SETTINGS menu ---------------------------
	add_action('admin_menu', 'cln_pg__csjd');function cln_pg__csjd() { add_submenu_page('options-general.php', 'Clean Surplus', 'Clean Surplus', 'manage_options', 'cln-db', 'cleanspr_func__csjd' ); 	}
	function cleanspr_func__csjd() 	{		global $wpdb;
		
		//if user clicked "Perform Clean"
		if (isset($_GET['perform_clean']))	{ 	validate_pageload__csjd($_GET['makeee_upd2'],'csp_upd2'); cleendb__csjd(); }
		
		//When settings are UPDATED
		if (isset($_POST['makeee_upd']))	{	validate_pageload__csjd($_POST['makeee_upd'],'csp_upd');
			update_option(OptName_vars__csjd, array('c1' => $_POST['c1'],  'c2' => $_POST['c2'],  'c3' => $_POST['c3'],  'c4' => $_POST['c4'],  'c5' => $_POST['c5'],  'c6' => $_POST['c6'],  'c7' => $_POST['c7'],  'c8' => $_POST['c8'],	'enb_auto_clean'=> $_POST['enabl_autoclen'] )   );
		}
		
		$opts = get_option(OptName_vars__csjd, array());
		
	?>
		<style type="text/css"> 
		p.heding{	font-size:16px;	font-weight:bold;	margin:0 0 50px 0px;	}
		a.prf{	background-color: #F4B0BE;  border: 4px solid #F4B0BE; border-radius: 5px; font-size: 30px; height: 80px;	position:relative; left:30%; top: 80px; text-align: center; width: 400px;}
		</style>
		<div class="mainnnn">
			<p class="heding">Safely Clean unnecessary fields from Database</p>
			<br/> (advice: there exists a good/better plugin, called <b><a href="http://wordpress.org/plugins/wp-clean-up/" target="_blank"> WP Clean Up</a></b> )
			<br/>
			<form action="" method="POST" style="margin:30px 0 0 0;">
			<table><tbody>
			<tr>   <td><b>What Fields to be cleaned</b></td>	<td>&nbsp;</td>		</tr>
			<tr>   <td>Auto-Draft</td>							<td><input type="hidden" name="c1" value="n" checked /><input type="checkbox" name="c1" value="y" <?php if ($opts['c1']=='y') {echo 'checked';} ?> /></td>			</tr>
			<tr>   <td>revision</td>							<td><input type="hidden" name="c2" value="n" checked /><input type="checkbox" name="c2" value="y"  <?php if ($opts['c2']=='y') {echo 'checked';} ?> /></td>			</tr>
			<tr>   <td>_transient_timeout_rss%</td>				<td><input type="hidden" name="c3" value="n" checked /><input type="checkbox" name="c3" value="y"  <?php if ($opts['c3']=='y') {echo 'checked';} ?> /></td>			</tr>
			<tr>   <td>_transient_rss_%</td>					<td><input type="hidden" name="c4" value="n" checked /><input type="checkbox" name="c4" value="y"  <?php if ($opts['c4']=='y') {echo 'checked';} ?> /></td>			</tr>
			<tr>   <td>_transient_timeout_feed_%</td>			<td><input type="hidden" name="c5" value="n" checked /><input type="checkbox" name="c5" value="y"  <?php if ($opts['c5']=='y') {echo 'checked';} ?> /></td>			</tr>
			<tr>   <td>_transient_feed_%</td>					<td><input type="hidden" name="c6" value="n" checked /><input type="checkbox" name="c6" value="y"  <?php if ($opts['c6']=='y') {echo 'checked';} ?> /></td>			</tr>
			<tr>   <td>_transient_dash_%</td>					<td><input type="hidden" name="c7" value="n" checked /><input type="checkbox" name="c7" value="y"  <?php if ($opts['c7']=='y') {echo 'checked';} ?> /></td>			</tr>
			<tr>   <td>_transient_timeout_dash_%</td>			<td><input type="hidden" name="c8" value="n" checked /><input type="checkbox" name="c8" value="y"  <?php if ($opts['c8']=='y') {echo 'checked';} ?> /></td>			</tr>
			</tbody></table>
			
			<p>	enable AUTO-PERFORM CLEAN once in a day <input type="hidden" name="enabl_autoclen" value="n" checked /><input type="checkbox" name="enabl_autoclen" <?php if ($opts['enb_auto_clean']=='y') {echo 'checked';} ?> />	</p>		
			
			<br/><input type="submit" value="SAVE" /> <input type="hidden" name="makeee_upd" value="<?php echo wp_create_nonce('csp_upd');?>" /> 
			</form>
			<p class="notesss"> NOTE: The plugin can clean AUTO-DRAFT, REVISION posts(in most cases,they are intended for just a one-time use, and after it, they are kept in database with no reason) and some other extra,unnecessary, useless data ( In most cases, all of the above fields are surplus, unnecessary and old records,auto-saved suggestions,ADs and etc...). If you are experienced programmer, then you can investigate each field's definition in google, and will see what they do. However, if you are unsure, you can download mysql backup to your pc before cleaning. There exists good plugins for backup/restore databases, like <a href="<?php echo home_url();?>/wp-admin/plugin-install.php?tab=search&s=file+manager+database+backup" target="_blank">"File Browser+Database Backup"</a>. 
			<br/> (p.s. Although there exists another better plugin, called "WP-Clean", I made this current plugin("Clean surplus") for quick usage.)</p>
			<div class="prf"><a href="<?php echo $_SERVER['REQUEST_URI'].'&perform_clean=okk&makeee_upd2='.wp_create_nonce('csp_upd2');?>" class="prf"> Perform Clean Now !</a></div>
		</div>

		
	<?php 
	} // END PLUGIN PAGE
	
	
	// auto-clean after 7 days passed
	$opts = get_option(OptName_vars__csjd);
	if ( $opts['enb_auto_clean']=='y' && (time() > $opts['db_last_auto_cnl'] + 7*24*60*60) )  
	{
		cleendb__csjd();
		$opts['db_last_auto_cnl']=time();
		update_option(OptName_vars__csjd, $opts);
	}
} //end IF_ADMIN
?>
