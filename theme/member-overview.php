<?php
/*
Template Name: Member Details
 * @name		Member Details
 * @type		PHP page
 * @desc		Member Details
*/

session_start();
header("Cache-control: private"); //IE 6 Fix
global $wpdb;

/* Get User Info ******************************************/ 
global $current_user;
get_currentuserinfo();

// Get Settings
$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_profilenaming 		= (int)$rb_agency_options_arr['rb_agency_option_profilenaming'];
$rb_agencyinteract_options_arr = get_option('rb_agencyinteract_options');
	$rb_agencyinteract_option_registerallow = (int)$rb_agencyinteract_options_arr['rb_agencyinteract_option_registerallow'];
$rb_agencyinteract_option_overviewpagedetails = (int)$rb_agencyinteract_options_arr['rb_agencyinteract_option_overviewpagedetails'];


// Were they users or agents?
$profiletype = (int)get_user_meta($current_user->id, "rb_agency_interact_profiletype", true);
if ($profiletype == 1) { $profiletypetext = __("Agent/Producer", rb_agencyinteract_TEXTDOMAIN); } else { $profiletypetext = __("Model/Talent", rb_agencyinteract_TEXTDOMAIN); }

// Change Title
add_filter('wp_title', 'rb_agencyinteractive_override_title', 10, 2);
	function rb_agencyinteractive_override_title(){
		return "Member Overview";
	}


/* Display Page ******************************************/ 
get_header();
	
	echo "<div id=\"container\" class=\"one-column rb-agency-interact rb-agency-interact-overview\">\n";
	echo "  <div id=\"content\">\n";
	
		// ****************************************************************************************** //
		// Check if User is Logged in or not
		if (is_user_logged_in()) { 
			
			/// Show registration steps
			echo "<div id=\"profile-steps\">Profile Setup: Step 1 of 4</div>\n";
			
			echo "<div id=\"profile-manage\" class=\"profile-admin overview\">\n";
				
			/* Check if the user is regsitered *****************************************/ 
			$sql = "SELECT ProfileID FROM ". table_agency_profile ." WHERE ProfileUserLinked =  ". $current_user->ID ."";
			$results = mysql_query($sql);
			$count = mysql_num_rows($results);
			if ($count > 0) {

			  // Menu
			  include("include-menu.php"); 	
			  echo " <div class=\"profile-overview-inner inner\">\n";
			  
			  while ($data = mysql_fetch_array($results)) {
				  
				echo "	 <div class=\"welcome\">\n";
			
				echo "	 <h1>". __("Welcome Back", rb_agencyinteract_TEXTDOMAIN) ." ". $current_user->first_name ."!</h1>";
				// Record Exists
			
				/* Show account information here *****************************************/
				 
				echo "	 <div class=\"account\">\n"; // .account
				echo "      <div><a href=\"account/\">Edit Your Account Details</a></div>\n";
				echo "      <div><a href=\"manage/\">Manage Your Profile Information</a></div>\n";
				echo "      <div><a href=\"media/\">Manage Photos and Media</a></div>\n";
				echo "      <div><a href=\"subscription/\">Manage your Subscription</a></div>\n";
				echo "	 </div>\n";
						
			  } // is there record?
				
			  echo "	 <div id=\"subscription-customtext\">\n";
							$Page = get_page($rb_agencyinteract_option_overviewpagedetails);
			  echo		 apply_filters('the_content', $Page->post_content);
			  echo "	 </div>";
			  echo " </div>\n"; // .profile-manage-inner
			  
			// No Record Exists, register them
			} else {
					
					echo "<h1>". __("Welcome", rb_agencyinteract_TEXTDOMAIN) ." ". $current_user->first_name ."!</h1>";

					if ($profiletype == 1) {
						echo "". __("We have you registered as", rb_agencyinteract_TEXTDOMAIN) ." <strong>". $profiletypetext ."</strong>";
						echo "<h2><a href=\"". $rb_agencyinteract_WPURL ."/profile-search/\">". __("Begin Your Search", rb_agencyinteract_TEXTDOMAIN) ."</a></h2>";
						
						echo "  <div id=\"subscription-customtext\">\n";
							$Page = get_page($rb_agencyinteract_option_subscribepagedetails);
							echo apply_filters('the_content', $Page->post_content);
						echo " </div>";

					} else {
					  if ($rb_agencyinteract_option_registerallow == 1) {
						// Users CAN register themselves
						echo "". __("We have you registered as", rb_agencyinteract_TEXTDOMAIN) ." <strong>". $profiletypetext ."</strong>";
						echo "<h2>". __("Setup Your Profile", rb_agencyinteract_TEXTDOMAIN) ."</h2>";
						
						// Register Profile
						include("include-profileregister.php"); 	
						
						
					  } else {
						// Cant register
						echo "<strong>". __("Self registration is not permitted.", rb_agencyinteract_TEXTDOMAIN) ."</strong>";
					  }
					}
					
			}
			
			echo "	 </div>\n";
			

				
			echo "</div>\n"; // #profile-manage
		} else {
			// Show Login Form
			include("include-login.php"); 	
		}
		
	echo "  </div><!-- #content -->\n";
	echo "</div><!-- #container -->\n";
	
// Get Sidebar 
$rb_agencyinteract_options_arr = get_option('rb_agencyinteract_options');
	$rb_agencyinteract_option_profilemanage_sidebar = $rb_agencyinteract_options_arr['rb_agencyinteract_option_profilemanage_sidebar'];
	$LayoutType = "";
	if ($rb_agencyinteract_option_profilemanage_sidebar) {
		echo "	<div id=\"profile-sidebar\" class=\"manage\">\n";
			$LayoutType = "profile";
			get_sidebar(); 
		echo "	</div>\n";
	}
// Get Footer
get_footer();
?>