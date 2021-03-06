<?php
/*
Template Name: Edit Member Details
* @name		Edit Member Details
* @type		PHP page
* @desc		Edit Member Details
*/
if (!headers_sent()) {
header("Cache-control: private"); //IE 6 Fix
}
global $wpdb;
/* Get User Info ******************************************/ 
global $current_user, $wp_roles;
get_currentuserinfo();
// Get Settings
$rb_agency_options_arr = get_option('rb_agency_options');
	$rb_agency_option_profilenaming 		= (int)$rb_agency_options_arr['rb_agency_option_profilenaming'];
$rb_agency_interact_options_arr = get_option('rb_agencyinteract_options');
	$rb_agencyinteract_option_registerallow = (int)$rb_agency_interact_options_arr['rb_agencyinteract_option_registerallow'];
	
$rb_agency_option_inactive_profile_on_update = (int)$rb_agency_options_arr['rb_agency_option_inactive_profile_on_update'];
$rb_agencyinteract_option_registerapproval = (int)$rb_agency_interact_options_arr['rb_agencyinteract_option_registerapproval'];

// Declare alert
$alert = "";

// Were they users or agents?
$profiletype = (int)get_user_meta($current_user->ID, "rb_agency_interact_profiletype", true);
if(get_user_meta($current_user->ID, 'rb_agency_interact_clientdata', true)) {$profiletypetext = __("Agent/Producer", RBAGENCY_interact_TEXTDOMAIN); } else {$profiletypetext = __("Model/Talent", RBAGENCY_interact_TEXTDOMAIN); }

	// Change Title
	add_filter('wp_title', 'rb_agencyinteractive_override_title', 10, 2);
		function rb_agencyinteractive_override_title(){
			return __("Manage Profile", RBAGENCY_interact_TEXTDOMAIN);
		}

	/* Load the registration file. */
	//require_once( ABSPATH . WPINC . '/registration.php' );
	require_once( ABSPATH . 'wp-admin/includes' . '/template.php' ); // this is only for the selected() function

// Form Post
if (isset($_POST['action'])) {
	$ProfileID					=$_POST['ProfileID'];
	$ProfileUsername			=$_POST['ProfileUsername'];
	$ProfilePassword			=$_POST['ProfilePassword'];
	$ProfilePasswordConfirm		=$_POST['ProfilePasswordConfirm'];
	$ProfileUserLinked			=$_POST['ProfileUserLinked'];
	$ProfileContactNameFirst	=trim($_POST['ProfileContactNameFirst']);
	$ProfileContactNameLast		=trim($_POST['ProfileContactNameLast']);
	$ProfileContactDisplay		=trim($_POST['ProfileContactDisplay']);

 	if (empty($ProfileContactDisplay)) { // Probably a new record... 
		if ($rb_agency_option_profilenaming == 0) {
			$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
		} elseif ($rb_agency_option_profilenaming == 1) {
			$ProfileContactDisplay = $ProfileContactNameFirst . " ". substr($ProfileContactNameLast, 0, 1);
		} elseif ($rb_agency_option_profilenaming == 2) {
			$error .= "<b><i>".LabelSingular. __( " must have a display name identified", RBAGENCY_interact_TEXTDOMAIN) . ".</i></b><br>";
			$have_error = true;
		} elseif ($rb_agency_option_profilenaming == 3) { // by firstname
			$ProfileContactDisplay = "ID ". $ProfileID;
		} elseif ($rb_agency_option_profilenaming == 4) {
                        $ProfileContactDisplay = $ProfileContactNameFirst;
          }
  	}

	$ProfileGallery				=$_POST['ProfileGallery'];

  	if (empty($ProfileGallery)) { // Probably a new record... 
		$ProfileGallery = RBAgency_Common::format_stripchars($ProfileContactDisplay); 
  	}

	$ProfileContactEmail		=$_POST['ProfileContactEmail'];
	$ProfileContactWebsite		=$_POST['ProfileContactWebsite'];
	$ProfileContactLinkFacebook	=$_POST['ProfileContactLinkFacebook'];
	$ProfileContactLinkTwitter	=$_POST['ProfileContactLinkTwitter'];
	$ProfileContactLinkYoutube	=$_POST['ProfileContactLinkYoutube'];
	$ProfileContactLinkFlickr	=$_POST['ProfileContactLinkFlickr'];
	$ProfileContactPhoneHome	=$_POST['ProfileContactPhoneHome'];
	$ProfileContactPhoneCell	=$_POST['ProfileContactPhoneCell'];
	$ProfileContactPhoneWork	=$_POST['ProfileContactPhoneWork'];
	$ProfileGender			=$_POST['ProfileGender'];
	$ProfileType			=$_POST['ProfileType'];
	$ProfileDateBirth				=$_POST['ProfileDateBirth'];
	$ProfileLocationStreet		=$_POST['ProfileLocationStreet'];
	$ProfileLocationCity		=RBAgency_Common::format_propercase($_POST['ProfileLocationCity']);
	$ProfileLocationState		=strtoupper($_POST['ProfileLocationState']);
	$ProfileLocationZip		=$_POST['ProfileLocationZip'];
	$ProfileLocationCountry		=$_POST['ProfileLocationCountry'];
	$ProfileLanguage			=$_POST['ProfileLanguage'];

	/* if ($rb_agencyinteract_option_registerapproval == 1) {

		// 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
		$ProfileIsActive			= 0; 
	} else {
		$ProfileIsActive			= 3; 
	}
	 */
	

	// Error checking
	$have_error = false;
	if(trim($ProfileContactNameFirst) == ""){
		$error .= "<b><i>".__("Name is required.", RBAGENCY_interact_TEXTDOMAIN) ."</i></b><br>";
		$have_error = true;
	}

	/* Update user password. */
	if ( !empty($ProfilePassword) && !empty($ProfilePasswordConfirm) ) {
		if ( $ProfilePassword == $ProfilePasswordConfirm ) {
			wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $ProfilePassword ) ) );
		} else {
			$have_error = true;
			$error .= __("The passwords you entered do not match.  Your password was not updated.", RBAGENCY_interact_TEXTDOMAIN);
		}
	}

	// Get Post State
	$action = $_POST['action'];

	// *************************************************************************************************** //
	// Add Record
	if($action == 'addRecord'){
		
		
		$_pending_update = $rb_agency_option_inactive_profile_on_update;
		$_registerapproval = $rb_agencyinteract_option_registerapproval;
		//manually approve(0)
		if($_registerapproval == 0){
			if($_pending_update == 1){
				$ProfileIsActive = 3;
			}else{
				$ProfileIsActive = 0;//inactive
			}
		}else{
			//automatic but do not allow the active as default..
			if($_pending_update != 1){
				$ProfileIsActive = 0; //inactive
			}else{
				$ProfileIsActive = 3;//pending
			}
		}
		
		if(!$have_error){
			//$ProfileIsActive		= 3; // in upper part of codes - initialize
			$ProfileIsFeatured	= 0;
			$ProfileIsPromoted	= 0;
			$ProfileStatHits		= 0;
			$ProfileDateBirth			= $_POST['ProfileDateBirth'];
			//$ProfileGallery 		= rb_agency_interact_checkdir($ProfileGallery); // Check directory existence , create if does not exist.
			$ProfileGallery = rb_agency_createdir($ProfileGallery);
			// Create Record
			$insert = "INSERT INTO " . table_agency_profile .
			" (ProfileUserLinked,ProfileGallery,ProfileContactDisplay,ProfileContactNameFirst,ProfileContactNameLast,
				ProfileContactEmail,ProfileContactWebsite,ProfileGender,ProfileType, ProfileDateBirth,
				ProfileContactLinkFacebook,ProfileContactLinkTwitter,ProfileContactLinkYoutube,ProfileContactLinkFlickr,
				ProfileLocationStreet,ProfileLocationCity,ProfileLocationState,ProfileLocationZip,ProfileLocationCountry,
				ProfileContactPhoneHome, ProfileContactPhoneCell, ProfileContactPhoneWork,
				ProfileDateUpdated,ProfileIsActive)" .
			"VALUES (". $ProfileUserLinked . 
					   ",'" . $wpdb->escape($ProfileGallery) . "','" . 
				$wpdb->escape($ProfileContactDisplay) . 
				"','" . $wpdb->escape($ProfileContactNameFirst) . "','" . 
				$wpdb->escape($ProfileContactNameLast) . 
				"','" . $wpdb->escape($ProfileContactEmail) . "','" . 
				$wpdb->escape($ProfileContactWebsite) . "','" . 
				$wpdb->escape($ProfileGender) .  "','" .
				$wpdb->escape($ProfileType) .  "','" .
				$wpdb->escape($ProfileDateBirth) . "','" . 
				$wpdb->escape($ProfileContactLinkFacebook) . "','" . 
				$wpdb->escape($ProfileContactLinkTwitter) . "','" . 
				$wpdb->escape($ProfileContactLinkYoutube) . "','" . 
				$wpdb->escape($ProfileContactLinkFlickr) . "','" . 
				$wpdb->escape($ProfileLocationStreet) . "','" . 
				$wpdb->escape($ProfileLocationCity) . "','" . 
				$wpdb->escape($ProfileLocationState) . "','" . 
				$wpdb->escape($ProfileLocationZip) . "','" . 
				$wpdb->escape($ProfileLocationCountry) . "','" . 
				$wpdb->escape($ProfileContactPhoneHome) . "','" . 
				$wpdb->escape($ProfileContactPhoneCell) . "','" . 
				$wpdb->escape($ProfileContactPhoneWork) . "',now(), ". 
				$ProfileIsActive .")";

				$results = $wpdb->query($insert);
              $ProfileID = $wpdb->insert_id;

              //user meta social media
             foreach($_POST['profile_social_media_name'] as $k=>$v){
				if(!empty($v) && !empty($_POST['profile_social_media_url'][$k])){
					add_user_meta($ProfileID,'SocialMediaName_'.$v,$v);
				}								
			}

			//user meta social media url
			foreach($_POST['profile_social_media_url'] as $k=>$v){
				if(!empty($v) && !empty($_POST['profile_social_media_name'][$k])){
					add_user_meta($ProfileID,'SocialMediaURL_'.$_POST['profile_social_media_name'][$k],$v);
				}

			}
 
			// Add New Custom Field Values
			$pos = 0;
			foreach($_POST as $key => $value) {
					   
				if ((substr($key, 0, 15) == "ProfileCustomID") && (isset($value) && !empty($value))) {

					$pos++; 
					if($pos == 1){
						// Remove Old Custom Field Values
						$delete1 = "DELETE FROM " . table_agency_customfield_mux . " WHERE ProfileID = \"". $ProfileID ."\"";
						$results1 = $wpdb->query($delete1);
					}

					$ProfileCustomID = substr($key, 15);
					if(is_array($value)){
						$value =  implode(",",$value);
					}
					if(!empty($value)){
						$insert1 = "INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomValue)" . "VALUES ('" . $ProfileID . "','" . $ProfileCustomID . "','" . $value . "')";
						$results1 = $wpdb->query($insert1);
					}
				}
			}
			/* Update WordPress user information. */
			update_usermeta( $current_user->ID, 'first_name', esc_attr( $ProfileContactNameFirst ) );
			update_usermeta( $current_user->ID, 'last_name', esc_attr( $ProfileContactNameLast ) );
			update_usermeta( $current_user->ID, 'nickname', esc_attr( $ProfileContactDisplay ) );
			update_usermeta( $current_user->ID, 'display_name', esc_attr( $ProfileContactDisplay ) );
			update_usermeta( $current_user->ID, 'user_email', esc_attr( $ProfileContactEmail ) );
			update_usermeta( $current_user->ID, 'rb_agency_interact_pgender', esc_attr( $ProfileGender ) );

			  #DEBUG
			  #echo "<script>alert('".$ProfileUsername."');<\/script>";
			// Link to Wordpress user_meta

			if ( username_exists( $ProfileUsername) ) {

				$isLinked =  $wpdb->query("UPDATE ". table_agency_profile ." SET ProfileUserLinked =  ". $current_user->ID ." WHERE ProfileID = ".$ProfileID." ");
				if($isLinked){

					wp_redirect(get_bloginfo("wpurl") . "/profile-member/manage/");

				} else {
						$user_data = array(
							'ID' => $current_user->ID,
							'user_pass' => wp_generate_password(),
							'user_login' => $ProfileUsername,
							'user_email' => $ProfileContactEmail,
							'display_name' => $ProfileContactDisplay,
							'first_name' => $ProfileContactNameFirst,
							'last_name' => $ProfileContactNameLast,
							'role' =>  get_option('default_role') // Use default role or another role, e.g. 'editor'
						);
						$user_id = wp_insert_user( $user_data );
						wp_set_password($ProfilePassword, $user_id);
					}

			// Set Display Name as Record ID (We have to do this after so we know what record ID to use... right ;)
			//if ($rb_agency_option_profilenaming == 3) {
				/*$ProfileContactDisplay = "ID-". $ProfileID;
				$ProfileGallery = "ID". $ProfileID."-";*/

				if (empty($ProfileContactDisplay)) { // Probably a new record... 
					if ($rb_agency_option_profilenaming == 0) {
						$ProfileContactDisplay = $ProfileContactNameFirst . " " . $ProfileContactNameLast;
					} elseif ($rb_agency_option_profilenaming == 1) {
						// If John-D already exists, make John-D-1
						for ($i = 'a', $j = 1; $j <= 26; $i++, $j++) {
							if (isset($ar) && in_array($i, $ar)){
								$ProfileContactDisplay = $ProfileContactNameFirst . " " . $i .'-'. $j;
							} else {
								$ProfileContactDisplay = $ProfileContactNameFirst . " " . substr($ProfileContactNameLast, 0, 1);
							}
						}

					} elseif ($rb_agency_option_profilenaming == 2) {
						$errorValidation['rb_agency_option_profilenaming'] = "<b><i>".LabelSingular . __(" must have a display name identified", RBAGENCY_TEXTDOMAIN) . ".</i></b><br>";
						//$have_error = true;
						$ProfileContactDisplay = $ProfileContactNameFirst . " " . $ProfileContactNameLast;
					} elseif ($rb_agency_option_profilenaming == 3) {
						$ProfileContactDisplay = "ID " . $ProfileID;
					} elseif ($rb_agency_option_profilenaming == 4) {
						$ProfileContactDisplay = $ProfileContactNameFirst;
					} elseif ($rb_agency_option_profilenaming == 5) {
						$ProfileContactDisplay = $ProfileContactNameLast;
					}
				}

				$update = $wpdb->query("UPDATE " . table_agency_profile . " SET ProfileContactDisplay='". $ProfileContactDisplay. "', ProfileGallery='". $ProfileGallery. "' WHERE ProfileID='". $ProfileID ."'");
				$updated = $wpdb->query($update);
			//}

			$alerts = "<div id=\"message\" class=\"updated\"><p>". __("New Profile added successfully", RBAGENCY_interact_TEXTDOMAIN) ."!</p></div>"; 
			/* Redirect so the page will show updated info. */
			if ( !$error ) {
				
				wp_redirect(get_bloginfo("wpurl") . "/profile-member/manage/");
			}
		} else {

   		$alerts = "<div id=\"message\" class=\"error\"><p>". __("Error creating record, please ensure you have filled out all required fields.", RBAGENCY_interact_TEXTDOMAIN) ."<br />". $error ."</p></div>"; 
		}


		}
	}
	elseif($action == 'editRecord'){
		// *************************************************************************************************** //
		// Edit Record

			if(!$have_error){

				//nevermind if your admin
				$ProfileStatus = 0;
				if($rb_agency_option_inactive_profile_on_update == 1){
					//nevermind if your admin
					if(is_user_logged_in() && current_user_can( 'edit_posts' )){
						$ProfileStatus = 1;//stay active admin account
					}else{
						$ProfileStatus = 3; //inactive
						
					}
				}else{
					// get user current status so theres no changes would be happen,
					$ProfileStatus = $wpdb->get_var("SELECT  ProfileIsActive FROM " . table_agency_profile . " WHERE ProfileID=$ProfileID");
						
				}
				
				if (empty($ProfileContactDisplay)) { // Probably a new record... 
					if ($rb_agency_option_profilenaming == 0) {
						$ProfileContactDisplay = $ProfileContactNameFirst . " " . $ProfileContactNameLast;
					} elseif ($rb_agency_option_profilenaming == 1) {
						// If John-D already exists, make John-D-1
						for ($i = 'a', $j = 1; $j <= 26; $i++, $j++) {
							if (isset($ar) && in_array($i, $ar)){
								$ProfileContactDisplay = $ProfileContactNameFirst . " " . $i .'-'. $j;
							} else {
								$ProfileContactDisplay = $ProfileContactNameFirst . " " . substr($ProfileContactNameLast, 0, 1);
							}
						}

					} elseif ($rb_agency_option_profilenaming == 2) {
						$errorValidation['rb_agency_option_profilenaming'] = "<b><i>".LabelSingular . __(" must have a display name identified", RBAGENCY_TEXTDOMAIN) . ".</i></b><br>";
						//$have_error = true;
						$ProfileContactDisplay = $ProfileContactNameFirst . " ". $ProfileContactNameLast;
					} elseif ($rb_agency_option_profilenaming == 3) {
						$ProfileContactDisplay = "ID " . $ProfileID;
					} elseif ($rb_agency_option_profilenaming == 4) {
						$ProfileContactDisplay = $ProfileContactNameFirst;
					} elseif ($rb_agency_option_profilenaming == 5) {
						$ProfileContactDisplay = $ProfileContactNameLast;
					}
				}
				
				
				$UpdateNotificationsClass = new RBAgency_Interact_Update_Notifications();
				$request = [
					'ProfileID' =>$ProfileID,
					'ProfileContactDisplay' =>$ProfileContactDisplay,
					'ProfileContactNameFirst' => $ProfileContactNameFirst,
					'ProfileContactNameLast' => $ProfileContactNameLast,
					'ProfileContactEmail'	=> $ProfileContactEmail,
					'ProfileContactWebsite' => $ProfileContactWebsite,
					'ProfileContactLinkFacebook' => $ProfileContactLinkFacebook,
					'ProfileContactLinkTwitter' => $ProfileContactLinkTwitter,
					'ProfileContactLinkYoutube' => $ProfileContactLinkYoutube,
					'ProfileContactLinkFlickr' => $ProfileContactLinkFlickr,
					'ProfileContactPhoneHome' => $ProfileContactPhoneHome,
					'ProfileContactPhoneCell' => $ProfileContactPhoneCell,
					'ProfileContactPhoneWork' => $ProfileContactPhoneWork,
					'ProfileGender' => $ProfileGender,
					'ProfileDateBirth' => $ProfileDateBirth,
					'ProfileLocationStreet' => $ProfileLocationStreet,
					'ProfileLocationCity' => $ProfileLocationCity,
					'ProfileLocationState' => $ProfileLocationState,
					'ProfileLocationZip' => $ProfileLocationZip,
					'ProfileLocationCountry' => $ProfileLocationCountry
				];
				$primaryInfoResult = $UpdateNotificationsClass->checkPrimaryInfoChanges($request);
				
				if(!empty($primaryInfoResult)){					
					$user_id = $primaryInfoResult['ProfileUserLinked'];
					foreach($primaryInfoResult as $key=>$val){
						if($key != 'ProfileUserLinked'){
							update_user_meta( $user_id, 'updated_'.$key, $val );
						}						
					}
				}

				$request = $UpdateNotificationsClass->generateRequestCustomFields();
				$request['ProfileID'] = $ProfileID;
				$customFieldResult = $UpdateNotificationsClass->checkCustomFieldChanges($request);
				if(!empty($customFieldResult)){					
					@$user_id = $customFieldResult['ProfileUserLinked'];
					foreach(@$customFieldResult as $key=>$val){
						if($key != 'ProfileUserLinked'){
							update_user_meta( $user_id, 'updated_'.$key, $val );							
						}						
					}
				}
				

				rb_interact_sendadmin_pending_info($ProfileID);
				
				
				// Update Record
				$update = "UPDATE " . table_agency_profile . " SET 
				ProfileContactDisplay='" . $wpdb->escape($ProfileContactDisplay) . "',
				ProfileContactNameFirst='" . $wpdb->escape($ProfileContactNameFirst) . "',
				ProfileContactNameLast='" . $wpdb->escape($ProfileContactNameLast) . "',
				ProfileContactEmail='" . $wpdb->escape($ProfileContactEmail) . "',
				ProfileContactWebsite='" . $wpdb->escape($ProfileContactWebsite) . "',
				ProfileContactLinkFacebook='" . $wpdb->escape($ProfileContactLinkFacebook) . "',
				ProfileContactLinkTwitter='" . $wpdb->escape($ProfileContactLinkTwitter) . "',
				ProfileContactLinkYoutube='" . $wpdb->escape($ProfileContactLinkYoutube) . "',
				ProfileContactLinkFlickr='" . $wpdb->escape($ProfileContactLinkFlickr) . "',
				ProfileContactPhoneHome='" . $wpdb->escape($ProfileContactPhoneHome) . "',
				ProfileContactPhoneCell='" . $wpdb->escape($ProfileContactPhoneCell) . "',
				ProfileContactPhoneWork='" . $wpdb->escape($ProfileContactPhoneWork) . "',
				ProfileGender='" . $wpdb->escape($ProfileGender) . "',
				ProfileDateBirth ='" . $wpdb->escape($ProfileDateBirth) . "',
				ProfileLocationStreet='" . $wpdb->escape($ProfileLocationStreet) . "',
				ProfileLocationCity='" . $wpdb->escape($ProfileLocationCity) . "',
				ProfileLocationState='" . $wpdb->escape($ProfileLocationState) . "',
				ProfileLocationZip ='" . $wpdb->escape($ProfileLocationZip) . "',
				ProfileLocationCountry='" . $wpdb->escape($ProfileLocationCountry) . "',
				ProfileIsActive='" . $ProfileStatus. "',
				ProfileDateUpdated=now()
				WHERE ProfileID=$ProfileID";
				$results = $wpdb->query($update);     

				
				update_usermeta( $current_user->ID, 'first_name', esc_attr( $ProfileContactNameFirst ) );
				update_usermeta( $current_user->ID, 'last_name', esc_attr( $ProfileContactNameLast ) );
				update_usermeta( $current_user->ID, 'nickname', esc_attr( $ProfileContactDisplay ) );
				update_usermeta( $current_user->ID, 'display_name', esc_attr( $ProfileContactDisplay ) );
				update_usermeta( $current_user->ID, 'user_email', esc_attr( $ProfileContactEmail ) );
				update_usermeta( $current_user->ID, 'rb_agency_interact_pgender', esc_attr( $ProfileGender ) );

				$list = array();
				$custom_social_media = rb_get_custom_social_media();
				foreach($custom_social_media as $social){
					array_push($list,$social["SocialMedia_Name"]);
				}
				foreach($list as $k=>$v){
					@$wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'SocialMediaURL_".$v."' AND user_id = ".$ProfileID);
					@$wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'SocialMediaName_".$v."' AND user_id = ".$ProfileID);
				}

				if(isset($_POST['profile_social_media_name'])){
					foreach($_POST['profile_social_media_name'] as $k=>$v){
						if(!empty($v) && !empty($_POST['profile_social_media_url'][$k])){
							add_user_meta($ProfileID,'SocialMediaName_'.$v,$v);
						}								
					}
				}
				

				//user meta social media url
				if(isset($_POST['profile_social_media_url'])){
					foreach($_POST['profile_social_media_url'] as $k=>$v){
						if(!empty($v) && !empty($_POST['profile_social_media_name'][$k])){
							add_user_meta($ProfileID,'SocialMediaURL_'.$_POST['profile_social_media_name'][$k],$v);
						}

					}
				}
				// Add New Custom Field Values
				
				foreach($_POST as $key => $value) {


					if ((substr($key, 0, 15) == "ProfileCustomID") && (isset($value) && !empty($value))) {

							$ProfileCustomID = substr($key, 15);

						// Remove Old Custom Field Values
						$delete1 = "DELETE FROM " . table_agency_customfield_mux . " WHERE ProfileCustomID = ". $ProfileCustomID ." AND ProfileID = ".$ProfileID."";
						$results1 = $wpdb->query($delete1);


						if(is_array($value)){
							$value = array_unique($value);
							$value =  implode(",",$value);
						}
						if(!empty($value)){
							$insert1 = "INSERT INTO " . table_agency_customfield_mux . " (ProfileID,ProfileCustomID,ProfileCustomValue)" . "VALUES ('" . $ProfileID . "','" . $ProfileCustomID . "','" . $value . "')";
							$results1 = $wpdb->query($insert1);
						}
					}
				}

				$alerts = "<div id=\"message\" class=\"updated\"><p>". __("Profile updated successfully", RBAGENCY_interact_TEXTDOMAIN) ."!</a></p></div>";
				
				//exist user should be in pending page
				$old_exist_user = get_user_meta( $current_user->ID, 'rb_agency_interact_clientold', true);
	            if(!empty($old_exist_user)){
	               wp_redirect( $rb_agency_interact_WPURL ."/profile-member/pending/?e" );
	                exit;
	            }
				wp_redirect( $rb_agency_interact_WPURL ."/profile-member/manage/" );
			} else {
				$alerts = "<div id=\"message\" class=\"error\"><p>". __("Error updating record, please ensure you have filled out all required fields.", RBAGENCY_interact_TEXTDOMAIN) ."<br />". $error ."</p></div>"; 
			}

	}

}


/* Display Page ******************************************/ 

// Call Header
echo $rb_header = RBAgency_Common::rb_header();

// Check Sidebar
$rb_agency_interact_options_arr = get_option('rb_agencyinteract_options');
$rb_agencyinteract_option_profilemanage_sidebar = isset($rb_agency_interact_options_arr['rb_agencyinteract_option_profilemanage_sidebar']) ?$rb_agency_interact_options_arr['rb_agencyinteract_option_profilemanage_sidebar']:0;
$content_class = "";
if (is_user_logged_in()) {
	$content_class = "eight";
} else {
	$content_class = "twelve";
}


		// get profile Custom fields value
	echo "<div id=\"container\" class=\"rb-agency-interact-account\">\n";
	echo "  <div id=\"rbcontent\">\n";

		// ****************************************************************************************** //
		// Check if User is Logged in or not
		if (is_user_logged_in()) {

			/*
			 * Set Media to not show to
			 * client/s, agents, producers,
			 */
			$ptype = (int)get_user_meta($current_user->ID, "rb_agency_interact_profiletype", true);
			          $ptype = retrieve_title($ptype);
			$restrict = array('client','clients','agents','agent','producer','producers');
			$rb_agency_new_registeredUser = get_user_meta($current_user->ID,'rb_agency_new_registeredUser');
			if(!empty($rb_agency_new_registeredUser)){
				if(in_array(strtolower($ptype),$restrict)){
					echo "<div id=\"profile-steps\">".__('Profile Setup: Step 1 of 2',RBAGENCY_interact_TEXTDOMAIN)."</div>\n";
				} else {
					echo "<div id=\"profile-steps\">".__('Profile Setup: Step 1 of 3',RBAGENCY_interact_TEXTDOMAIN)."</div>\n";
				}
			}

			echo "<div id=\"profile-manage\" class=\"profile-account\">\n";
			// Menu
			include("include-menu.php"); 
			echo " <div class=\"manage-account manage-content\">\n";
			// Show Errors & Alerts
			if(!empty($alerts))
			echo $alerts;
			/* Check if the user is regsitered *****************************************/ 
			// Verify Record
			$sql = "SELECT ProfileID FROM ". table_agency_profile ." WHERE ProfileUserLinked =  ". $current_user->ID ." ";
			$results = $wpdb->get_results($sql,ARRAY_A);
			$count = $wpdb->num_rows;
			if ($count > 0) {
					foreach ($results as $data) {

					// Manage Profile
					include("include-profileaccount.php"); 


					}// is there record?
			} else {
				if ($rb_agencyinteract_option_registerallow  == 1) {
				// Users CAN register themselves

				// No Record Exists, register them
				echo "<p>". __("Records show you are not currently linked to a model or agency profile.  Lets setup your profile now!", RBAGENCY_interact_TEXTDOMAIN) ."</p>";

				// Register Profile
				include("include-profileregister.php");


				} else {
				// Cant register
				echo "<strong>". __("Self registration is not permitted.", RBAGENCY_interact_TEXTDOMAIN) ."</strong>";
				}

			}
			echo " </div>\n"; // .manage-account
			echo "</div>\n"; // #profile-manage
		} else {
			echo "<p class=\"rbalert warning\">\n";
					_e('You must be logged in to edit your profile.', 'frontendprofile');
			echo "</p><!-- .warning -->\n";
			// Show Login Form
			include("include-login.php"); 
		}

	echo "  </div><!-- #rbcontent -->\n";
	echo "</div><!-- #container -->\n";

	if (is_user_logged_in()) {

		// Get Sidebar 
		$LayoutType = "";
		if ($rb_agencyinteract_option_profilemanage_sidebar) {
			$LayoutType = "profile";
			get_sidebar();
		}
	}

// Call Footer
echo $rb_footer = RBAgency_Common::rb_footer();
?>
