<?php
     
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". get_bloginfo("wpurl") ."/profile-member/account/\" style=\"width: 400px;\">\n";
	echo "<input type=\"hidden\" id=\"ProfileContactEmail\" name=\"ProfileContactEmail\" value=\"". $current_user->user_email ."\" />\n";
	echo "<input type=\"hidden\" id=\"ProfileUserLinked\" name=\"ProfileUserLinked\" value=\"". $current_user->id ."\" />\n";
	
	
	
	echo " <table class=\"form-table\">\n";
	echo "  <tbody>\n";
	echo "    <tr>\n";
	echo "		<td scope=\"row\" colspan=\"2\"><h3>". __("Contact Information", rb_agency_TEXTDOMAIN) ."</h3></th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("First Name", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactNameFirst\" name=\"ProfileContactNameFirst\" value=\"". $current_user->first_name ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Last Name", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactNameLast\" name=\"ProfileContactNameLast\" value=\"". $current_user->last_name ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Phone", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactEmail\" name=\"ProfileContactPhoneHome\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	// Public Information
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" colspan=\"2\"><h3>". __("Public Information", rb_agencyinteract_TEXTDOMAIN) ."</h3>The following information may appear in profile pages.</th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Gender", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td><select name=\"ProfileGender\" id=\"ProfileGender\">\n";
				$query= "SELECT GenderID, GenderTitle FROM " .  table_agency_data_gender . " GROUP BY GenderTitle ";
				echo "<option value=\"\">All Gender</option>";
				$queryShowGender = mysql_query($query);
				while($dataShowGender = mysql_fetch_assoc($queryShowGender)){
					echo "<option value=\"".$dataShowGender["GenderID"]."\" >".$dataShowGender["GenderTitle"]."</option>";
				 }
	echo "		  </select>\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Birthdate", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
				  /* Month */ 
				  $monthName = array(1=> "January", "February", "March","April", "May", "June", "July", "August","September", "October", "November", "December"); 
	echo "		  <select name=\"ProfileDateBirth_Month\" id=\"ProfileDateBirth_Month\">\n";
					for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++ ) { 	
	echo "			<option value=\"". $currentMonth ."\">". $monthName[$currentMonth] ."</option>\n";
					}
	echo "		  </select>\n";

				  /* Day */ 
	echo "		  <select name=\"ProfileDateBirth_Day\" id=\"ProfileDateBirth_Day\">\n";
					for ($currentDay = 1; $currentDay <= 31; $currentDay++ ) { 	
	echo "			<option value=\"". $currentDay ."\">". $currentDay ."</option>\n";
					}
	echo "		  </select>\n";

				  /* Year */ 
	echo "		  <select name=\"ProfileDateBirth_Year\" id=\"ProfileDateBirth_Year\">\n";
					for ($currentYear = 1940; $currentYear <= 2010; $currentYear++ ) { 	
	echo "			<option value=\"". $currentYear ."\">". $currentYear ."</option>\n";
					}
	echo "		  </select>\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	// Private Information
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\" colspan=\"2\"><h3>". __("Private Information", rb_agencyinteract_TEXTDOMAIN) ."</h3>". __("The following information will NOT appear in public areas and is for administrative use only.", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Parent (if minor)", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileContactParent\" name=\"ProfileContactParent\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Street", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationStreet\" name=\"ProfileLocationStreet\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("City", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationCity\" name=\"ProfileLocationCity\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("State", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationState\" name=\"ProfileLocationState\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Zip", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationZip\" name=\"ProfileLocationZip\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Country", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"ProfileLocationCountry\" name=\"ProfileLocationCountry\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
		 // Load Customfields
		 
			      $rb_agency_options_arr = get_option('rb_agency_options');
		$rb_agency_option_unittype  			= $rb_agency_options_arr['rb_agency_option_unittype'];
		$rb_agency_option_profilenaming 		= (int)$rb_agency_options_arr['rb_agency_option_profilenaming'];
		$rb_agency_option_locationtimezone 		= (int)$rb_agency_options_arr['rb_agency_option_locationtimezone'];
	

		
	$query3 = "SELECT * FROM ". table_agency_customfields ." WHERE ProfileCustomView = 0 AND ProfileCustomShowRegistration = 1 ORDER BY ProfileCustomOrder";
	$results3 = mysql_query($query3) or die(mysql_error());
	$count3 = mysql_num_rows($results3);
	$arrayCustomFieldsValues = array();
	$keyval = explode("|",strtok("_",$_SESSION["rb_agency_new_registeredUser"]));
	//echo  $_SESSION["rb_agency_new_registeredUser"];
	//echo "<br/>";
	/*
	//_163|Japanese|_164|Medium|_165|Brown|_166|Black
	for($a = 0; $a<=(int)(count($keyval)-1)/2; $a++){
		if($a%2==0){
		  $_SESSION["registereduser"] ["CustomID".strtok("_",$keyval[$a-1])] = $keyval[$a];
		 // Half step back - then get the string val and insert current index value.
		
		}else{
		  $_SESSION["registereduser"] ["CustomID".strtok("_",$keyval[$a])] = "";
		} 
		
		
	}
	
	print_r($_SESSION["rb_registereduser"]);
	*/

	while ($data3 = mysql_fetch_assoc($results3)) {
	echo "<tr valign=\"top\">";
		$ProfileCustomTitle = $data3['ProfileCustomTitle'];
		$ProfileCustomType  = $data3['ProfileCustomType'];
	
			 // SET Label for Measurements
			 // Imperial(in/lb), Metrics(ft/kg)
			 $rb_agency_options_arr = get_option('rb_agency_options');
			  $rb_agency_option_unittype  = $rb_agency_options_arr['rb_agency_option_unittype'];
			  $measurements_label = "";
			 if ($ProfileCustomType == 7) { //measurements field type
			            if($data3['ProfileCustomOptions'] ==1){ //1 = Imperial(in/lb)
						if($rb_agency_option_unittype == 1){
						    $measurements_label  ="<em>(In Feet)</em>";
						}else{
						   $measurements_label  ="<em>(In Kilos)</em>";
						 }
					}elseif($data3['ProfileCustomOptions'] ==2){ // 2 = Metrics(ft/kg)
						if($rb_agency_option_unittype == 1){
						    
						    $measurements_label  ="<em>(In Inches)</em>";
						}else{
						     $measurements_label  ="<em>(In Pounds)</em>";
						}
					}
					
			 }  
		 echo "<td scope=\"row\">";		 
		 echo " ". __( $data3['ProfileCustomTitle'].$measurements_label, rb_agencyinteract_TEXTDOMAIN) ."\n";		  
		  echo "</td>";	
		 echo "<td>";	
			if ($ProfileCustomType == 1) { //TEXT
			
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
						
			} elseif ($ProfileCustomType == 2) { // Min Max
			
				$ProfileCustomOptions_String = str_replace(",",":",strtok(strtok($data3['ProfileCustomOptions'],"}"),"{"));
				list($ProfileCustomOptions_Min_label,$ProfileCustomOptions_Min_value,$ProfileCustomOptions_Max_label,$ProfileCustomOptions_Max_value) = explode(":",$ProfileCustomOptions_String);
			 
				if (!empty($ProfileCustomOptions_Min_value) && !empty($ProfileCustomOptions_Max_value)) {
						echo "<br /><br /> <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Min_value ."\" />\n";
						echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomOptions_Max_value ."\" /><br />\n";
				} else {
						echo "<br /><br />  <label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Min", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data3['ProfileCustomID']]."\" />\n";
						echo "<br /><br /><br /><label for=\"ProfileCustomLabel_min\" style=\"text-align:right;\">". __("Max", rb_agency_TEXTDOMAIN) . "&nbsp;&nbsp;</label>\n";
						echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"".$_SESSION["ProfileCustomID". $data3['ProfileCustomID']]."\" /><br />\n";
				}
			 
			} elseif ($ProfileCustomType == 3) {  // Drop Down
				
				list($option1,$option2) = explode(":",$data3['ProfileCustomOptions']);	
					
				$data = explode("|",$option1);
				$data2 = explode("|",$option2);
				
				echo "<label>".$data[0]."</label>";
				echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\">\n";
				echo "<option value=\"\">--</option>";
					$pos = 0;
					foreach($data as $val1){
						
						if($val1 != end($data) && $val1 != $data[0]){
						
								 if ($val1 == $ProfileCustomValue ) {
										$isSelected = "selected=\"selected\"";
										echo "<option value=\"".$val1."\" ".$isSelected .">".$val1."</option>";
								 } else {
										echo "<option value=\"".$val1."\" >".$val1."</option>";
								 }
					
						}
					}
					echo "</select>\n";
					
					
				if (!empty($data2) && !empty($option2)) {
					echo "<label>".$data2[0]."</label>";
				
						$pos2 = 0;
						echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\">\n";
						echo "<option value=\"\">--</option>";
						foreach($data2 as $val2){
								if($val2 != end($data2) && $val2 !=  $data2[0]){
									echo "<option value=\"".$val2."\" ". selected($val2, $ProfileCustomValue) ." >".$val2."</option>";
								}
							}
						echo "</select>\n";
				}
			} elseif ($ProfileCustomType == 4) {
				
				echo "<textarea style=\"width: 100%; min-height: 300px;\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">". $ProfileCustomValue ."</textarea>";
				
			} elseif ($ProfileCustomType == 5) {
			
				$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);
				echo "<div style=\"width:300px;float:left;\">";
				foreach($array_customOptions_values as $val){
					 $xplode = explode(",",$ProfileCustomValue);
					 echo "<label><input type=\"checkbox\" value=\"". $val."\"   "; if(in_array($val,$xplode)){ echo "checked=\"checked\""; } echo" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" />";
					 echo "". $val."</label>";
				}      echo "<br/>";
				echo "</div>";
				   
			} elseif ($ProfileCustomType == 6) {
				
				$array_customOptions_values = explode("|",$data3['ProfileCustomOptions']);
				
				foreach($array_customOptions_values as $val){
					
					 echo "<input type=\"radio\" value=\"". $val."\" "; checked($val, $ProfileCustomValue); echo" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."[]\" />";
					 echo "<span>". $val."</span><br/>";
				}
			}elseif ($ProfileCustomType == 7) { //Imperial/Metrics
			
					 if($data3['ProfileCustomOptions']==1){
												    if($rb_agency_option_unittype == 1){
														echo "<select name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\">\n";
															if (empty($ProfileCustomValue)) {
														echo " 				<option value=\"\">--</option>\n";
															}
															
															$i=36;
															$heightraw = 0;
															$heightfeet = 0;
															$heightinch = 0;
															while($i<=90)  { 
															  $heightraw = $i;
															  $heightfeet = floor($heightraw/12);
															  $heightinch = $heightraw - floor($heightfeet*12);
														echo " <option value=\"". $i ."\" ". selected($ProfileCustomValue, $i) .">". $heightfeet ." ft ". $heightinch ." in</option>\n";
															  $i++;
															}
														echo " </select>\n";
												    }else{
													    echo "	 <input type=\"text\" id=\"ProfileStatHeight\" name=\"ProfileStatHeight\" value=\"". $ProfileCustomValue ."\" />\n";
												    }
						 }else{
										   
										  echo "<input type=\"text\" name=\"ProfileCustomID". $data3['ProfileCustomID'] ."\" value=\"". $ProfileCustomValue ."\" /><br />\n";
										
						}
						
			}
									
	    echo "       </p>\n";
		echo "    </td>\n";
		echo "  </tr>\n";	
        }// End while

	
	
	$rb_agencyinteract_options_arr = get_option('rb_agencyinteract_options');
	$rb_agencyinteract_option_registerallow = (int)$rb_agencyinteract_options_arr['rb_agencyinteract_option_registerallow'];

	
	  if ($rb_agencyinteract_option_registerallow  == 1) {
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\">". __("Username(cannot be changed.)", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		if(isset($current_user->user_login)){
		echo "			<input type=\"text\" id=\"ProfileUsername\"  disabled=\"disabled\" value=\"".$current_user->user_login."\" />\n";
		echo "                  <input type=\"hidden\" name=\"ProfileUsername\" value=\"".$current_user->user_login."\"  />";
		}else{
		echo "			<input type=\"text\" id=\"ProfileUsername\"  name=\"ProfileUsername\" value=\"\" />\n";	
		}
		echo "		</td>\n";
		echo "	  </tr>\n";
	 }
	
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Password (Leave blank to keep same password)", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"password\" id=\"ProfilePassword\" name=\"ProfilePassword\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Password (Retype to Confirm)", rb_agencyinteract_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"password\" id=\"ProfilePasswordConfirm\" name=\"ProfilePasswordConfirm\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Save and Continue", rb_restaurant_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	echo "</form>\n";
?>