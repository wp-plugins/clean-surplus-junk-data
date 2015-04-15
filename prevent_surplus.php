<?php
/*
Plugin Name: Clean Surpluss (junk database data)
Description: Safely cleans & prevents unnecessary, needless data from Wordpress.  Removes AUTO-SAVES and AUTO-DRAFTED revisions,useless posts from database. VERY lightweight plugin,see description under Settings. (P.S.  OTHER MUST-HAVE PLUGINS FOR EVERYONE: http://bitly.com/MWPLUGINS  )
Version: 1.0.1
Author: selnomeria
Other authors: Multiple Authors
 */


// --------------- MAIN FUNCTION --------------
		//--------------------disable AUTOSAVE and REVISIONS----------------------
		// add this define in wp-config.php
		//define('WP_POST_REVISIONS', false);
		//add_action( 'wp_print_scripts', 'disable_autosave' );
		//function disable_autosave() { wp_deregister_script('autosave'); }
		
		
function cleendb()
{
	global $wpdb;
	if (get_option('cleandb_1')=='y')
		{ $del= $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'"); }
	if (get_option('cleandb_2')=='y')
		{ $del= $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'revision'"); }
	if (get_option('cleandb_3')=='y')
		{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_rss%'");}
	if (get_option('cleandb_4')=='y')
		{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_rss_%'");}
	if (get_option('cleandb_5')=='y')
		{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_feed_%'");}
	if (get_option('cleandb_6')=='y')
		{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_feed_%'");}
	if (get_option('cleandb_7')=='y')
		{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_dash_%'");}
	if (get_option('cleandb_8')=='y')
		{ $del= $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_dash_%'");}
}




// --------------- JUST AN USER INTERFACE --------------
if (is_admin())
{

	// ------------------------- ADD PAGE IN SETTINGS menu ---------------------------
	add_action('admin_menu', 'cln_pg_clb');function cln_pg_clb() { add_submenu_page('options-general.php', 'Clean Surplus', 'Clean Surplus', 'manage_options', 'cln-db', 'cleanspr_func' ); 
	}

	function validate_pageload($value, $action_name)
	{
		if ( 	!isset($value) || !wp_verify_nonce($value, $action_name) ) {
			die("not allowed due to internal_error_151");
		}
	}	
	
	
	function cleanspr_func() 
	{
		global $wpdb;
		
		//if user clicked "Perform Clean"
		if (isset($_GET['perform_clean'])) { cleendb(); }
		
		//When settings are UPDATED
		if (isset($_POST['makeee_upd']))
		{
			validate_pageload($_POST['makeee_upd'],'csp_upd');
			
			update_option('cleandb_1',$_POST['c1']);	update_option('cleandb_2',$_POST['c2']);		update_option('cleandb_3',$_POST['c3']);
			update_option('cleandb_4',$_POST['c4']);	update_option('cleandb_5',$_POST['c5']);		update_option('cleandb_6',$_POST['c6']);
			update_option('cleandb_7',$_POST['c7']);	update_option('cleandb_8',$_POST['c8']);
			
			if (isset($_POST['enabl_autoclen'])){
				update_option('db_enable_auto_clean',$_POST['enabl_autoclen']);
			}
		}

	
?>


		<style type="text/css"> 
		p.heding{	font-size:16px;	font-weight:bold;	margin:0 0 50px 0px;	}
		a.prf{	background-color: #F4B0BE;  border: 4px solid #F4B0BE; border-radius: 5px; font-size: 30px;
			height: 80px;	position:relative; left:30%; top: 80px; text-align: center; width: 400px;}
		</style>
		<div class="mainnnn">
			<p class="heding">Prevent&Clean unnecessary fields from Database</p>
			<br/>
			(as an adive, there exists a good/better plugin, called <b><a href="http://wordpress.org/plugins/wp-clean-up/" target="_blank"> WP Clean Up</a></b> )
			<br/>
			
			<form action="" method="POST" style="margin:30px 0 0 0;">
			<table><tbody>
			<tr><td><b>What Fields to be cleaned</b></td><td>&nbsp;</td></tr>
			<tr>
				<td>Auto-Draft</td>
				<td><input type="hidden" name="c1" value="n" checked /><input type="checkbox" name="c1" value="y" <?php if (get_option('cleandb_1')=='y') {echo 'checked';} ?> /></td>
			</tr>
			<tr>
				<td>revision</td>
				<td><input type="hidden" name="c2" value="n" checked /><input type="checkbox" name="c2" value="y"  <?php if (get_option('cleandb_2')=='y') {echo 'checked';} ?> /></td>
			</tr>
			<tr>
				<td>_transient_timeout_rss%</td>
				<td><input type="hidden" name="c3" value="n" checked /><input type="checkbox" name="c3" value="y"  <?php if (get_option('cleandb_3')=='y') {echo 'checked';} ?> /></td>
			</tr>
			<tr>
				<td>_transient_rss_%</td>
				<td><input type="hidden" name="c4" value="n" checked /><input type="checkbox" name="c4" value="y"  <?php if (get_option('cleandb_4')=='y') {echo 'checked';} ?> /></td>
			</tr>
			<tr>
				<td>_transient_timeout_feed_%</td>
				<td><input type="hidden" name="c5" value="n" checked /><input type="checkbox" name="c5" value="y"  <?php if (get_option('cleandb_5')=='y') {echo 'checked';} ?> /></td>
			</tr>
			<tr>
				<td>_transient_feed_%</td>
				<td><input type="hidden" name="c6" value="n" checked /><input type="checkbox" name="c6" value="y"  <?php if (get_option('cleandb_6')=='y') {echo 'checked';} ?> /></td>
			</tr>
			<tr>
				<td>_transient_dash_%</td>
				<td><input type="hidden" name="c7" value="n" checked /><input type="checkbox" name="c7" value="y"  <?php if (get_option('cleandb_7')=='y') {echo 'checked';} ?> /></td>
			</tr>
			<tr>
				<td>_transient_timeout_dash_%</td>
				<td><input type="hidden" name="c8" value="n" checked /><input type="checkbox" name="c8" value="y"  <?php if (get_option('cleandb_8')=='y') {echo 'checked';} ?> /></td>
			</tr>
			</tbody></table>
			
			
			<p>
			enable AUTO-PERFORM CLEAN once in a day <input type="hidden" name="enabl_autoclen" value="n" checked /><input type="checkbox" name="enabl_autoclen" <?php if (get_option('db_enable_auto_clean')=='y') {echo 'checked';} ?> />
			</p>
			
			<br/><input type="submit" value="SAVE" /> <input type="hidden" name="makeee_upd" value="<?php echo wp_create_nonce('csp_upd');?>" /> 
			</form>
			<p class="notesss"> NOTE: The plugin can clean AUTO-DRAFT, REVISION posts(in most cases,they are intended for just a one-time use, and after it, they are kept in database with no reason) and some other extra,unnecessary, useless data ( In most cases, all of the above fields are surplus, unnecessary and old records,auto-saved suggestions,ADs and etc...). If you are experienced programmer, then you can investigate each field's definition in google, and will see what they do. However, if you are unsure, you can download mysql backup to your pc before cleaning. There exists good plugins for backup/restore databases. 
			<br/> (p.s. Although there exists another better plugin, called "WP-Clean", I made this current plugin("Clean surplus") for quick usage.)</p>
			
			<div class="prf"><a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'&perform_clean=okk';?>" class="prf"> Perform Clean Now !</a></div>
		</div>

		
<?php 
	
	} // END PLUGIN PAGE
	
	
	// auto-clean in 7 days
	if ( get_option('db_enable_auto_clean')=='y' && (get_option('db_last_auto_cnl') < time()- 7*24*60*60) )  
	{
		cleendb();
		update_option('db_last_auto_cnl',time());
	}
} //end IF_ADMIN
?>
