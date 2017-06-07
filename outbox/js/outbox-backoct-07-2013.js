var regex = /[\:*?"<>|]/;
/*For Login validation*/
function validate_login(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmLogin.EmailLogin.value == ''){
		document.getElementById('lblEmailLogin').innerHTML = 'This field is required';
		document.frmLogin.EmailLogin.focus();
		return false;
	}else{
		document.getElementById('lblEmailLogin').innerHTML = '';
	}
	if(!document.frmLogin.EmailLogin.value.match(emailExp)){
		document.getElementById('lblEmailLogin').innerHTML = "Required Valid Email ID.";
		document.frmLogin.EmailLogin.focus();
		return false;
	}
	else{
		document.getElementById('lblEmailLogin').innerHTML = '';
	}
	if(document.frmLogin.PasswordLogin.value == ''){
		document.getElementById('lblPasswordLogin').innerHTML = 'This field is required';
		document.frmLogin.PasswordLogin.focus();
		return false;
	}else{
		document.getElementById('lblPasswordLogin').innerHTML = '';
	}	
}
/*For Login password*/
function validate_forgotpassword(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmForgot.ForgotEmail.value == ''){
		document.getElementById('lblForgotEmail').innerHTML = 'This field is required';
		document.frmForgot.ForgotEmail.focus();
		return false;
	}else{
		document.getElementById('lblForgotEmail').innerHTML = '';
	}
	if(!document.frmForgot.ForgotEmail.value.match(emailExp)){
		document.getElementById('lblForgotEmail').innerHTML = "Required Valid Email ID.";
		document.frmForgot.ForgotEmail.focus();
		return false;
	}
	else{
		document.getElementById('lblForgotEmail').innerHTML = '';
	}
}
//for Profile validation
function validate_profile(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmProfile.ProfileUserName.value == ''){
		document.getElementById('lblProfileUserName').innerHTML = 'This field is required';
		document.frmProfile.ProfileUserName.focus();
		return false;
	}else{
		document.getElementById('lblProfileUserName').innerHTML = '';
	}	
	if(document.frmProfile.ProfileEmailID.value == ''){
		document.getElementById('lblProfileEmailID').innerHTML = 'This field is required';
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
	if(!document.frmProfile.ProfileEmailID.value.match(emailExp)){
		document.getElementById('lblProfileEmailID').innerHTML = "Required Valid Email ID.";
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}
	else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
	if(document.frmProfile.ProfileMobile.value == ''){
		document.getElementById('lblProfileMobile').innerHTML = 'This field is required';
		document.frmProfile.ProfileMobile.focus();
		return false;
	}else{
		document.getElementById('lblProfileMobile').innerHTML = '';
	}
	if(document.frmProfile.ProfileCity.value == ''){
		document.getElementById('lblProfileCity').innerHTML = 'This field is required';
		document.frmProfile.ProfileCity.focus();
		return false;
	}else{
		document.getElementById('lblProfileCity').innerHTML = '';
	}
	if(document.frmProfile.ProfileState.value == ''){
		document.getElementById('lblProfileState').innerHTML = 'This field is required';
		document.frmProfile.ProfileState.focus();
		return false;
	}else{
		document.getElementById('lblProfileState').innerHTML = '';
	}
	if(document.frmProfile.SiteUrl.value == ''){
		document.getElementById('lblSiteUrl').innerHTML = 'This field is required';
		document.frmProfile.SiteUrl.focus();
		return false;
	}else{
		document.getElementById('lblSiteUrl').innerHTML = '';
	}					
}
//for Password Validation
function validate_password(){
	var formName=document.frmPassword;
	if(formName.ProfilePassword.value == "")
	{
		document.getElementById("lblProfilePassword").innerHTML='Enter Password';
		formName.ProfilePassword.focus();
		return false;
	}
	else
	{
		document.getElementById("lblProfilePassword").innerHTML='';
	}
	if(formName.ProfileConfirmPassword.value == "")
	{
		document.getElementById("lblProfileConfirmPassword").innerHTML='Enter Confirm Password';
		formName.ProfileConfirmPassword.focus();
		return false;
	}
	else
	{
		document.getElementById("lblProfileConfirmPassword").innerHTML='';
	}
	
	if(formName.ProfilePassword.value != formName.ProfileConfirmPassword.value)
	{
		document.getElementById("lblProfileConfirmPassword").innerHTML='Password Missmatch';
		formName.ProfileConfirmPassword.focus()
		return false;
	}
	else
	{
		document.getElementById("lblProfileConfirmPassword").innerHTML='';
	}
}
//for client validation
function validate_client(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmProfile.ProfileUserName.value == ''){
		document.getElementById('lblProfileUserName').innerHTML = 'This field is required';
		document.frmProfile.ProfileUserName.focus();
		return false;
	}else{
		document.getElementById('lblProfileUserName').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileUserName.value)){
		document.getElementById('lblProfileUserName').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileUserName.focus();
		return false;
	}else{
		document.getElementById('lblProfileUserName').innerHTML = '';
	}		
	if(document.frmProfile.ProfileContactName.value == ''){
		document.getElementById('lblProfileContactName').innerHTML = 'This field is required';
		document.frmProfile.ProfileContactName.focus();
		return false;
	}else{
		document.getElementById('lblProfileContactName').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileContactName.value)){
		document.getElementById('lblProfileContactName').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileContactName.focus();
		return false;
	}else{
		document.getElementById('lblProfileContactName').innerHTML = '';
	}
	if(document.frmProfile.ProfileEmailID.value == ''){
		document.getElementById('lblProfileEmailID').innerHTML = 'This field is required';
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
	if(!document.frmProfile.ProfileEmailID.value.match(emailExp)){
		document.getElementById('lblProfileEmailID').innerHTML = "Required Valid Email ID.";
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}
	else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
/*	if(document.frmProfile.ProfilePassword.value == ''){
		document.getElementById('lblProfilePassword').innerHTML = 'This field is required';
		document.frmProfile.ProfilePassword.focus();
		return false;
	}else{
		document.getElementById('lblProfilePassword').innerHTML = '';
	}*/
	if(document.frmProfile.ProfileMobile.value == ''){
		document.getElementById('lblProfileMobile').innerHTML = 'This field is required';
		document.frmProfile.ProfileMobile.focus();
		return false;
	}else{
		document.getElementById('lblProfileMobile').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileMobile.value)){
		document.getElementById('lblProfileMobile').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileMobile.focus();
		return false;
	}else{
		document.getElementById('lblProfileMobile').innerHTML = '';
	}
	if(document.frmProfile.ProfileFaxno.value == ''){
		document.getElementById('lblProfileFaxno').innerHTML = 'This field is required';
		document.frmProfile.ProfileFaxno.focus();
		return false;
	}else{
		document.getElementById('lblProfileFaxno').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileFaxno.value)){
		document.getElementById('lblProfileFaxno').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileFaxno.focus();
		return false;
	}else{
		document.getElementById('lblProfileFaxno').innerHTML = '';
	}
	if(document.frmProfile.ProfileAddress.value == ''){
		document.getElementById('lblProfileAddress').innerHTML = 'This field is required';
		document.frmProfile.ProfileAddress.focus();
		return false;
	}else{
		document.getElementById('lblProfileAddress').innerHTML = '';
	}
	if(document.frmProfile.ProfileCity.value == ''){
		document.getElementById('lblProfileCity').innerHTML = 'This field is required';
		document.frmProfile.ProfileCity.focus();
		return false;
	}else{
		document.getElementById('lblProfileCity').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileCity.value)){
		document.getElementById('lblProfileCity').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileCity.focus();
		return false;
	}else{
		document.getElementById('lblProfileCity').innerHTML = '';
	}
	if(document.frmProfile.ProfileState.value == ''){
		document.getElementById('lblProfileState').innerHTML = 'This field is required';
		document.frmProfile.ProfileState.focus();
		return false;
	}else{
		document.getElementById('lblProfileState').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileState.value)){
		document.getElementById('lblProfileState').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileState.focus();
		return false;
	}else{
		document.getElementById('lblProfileState').innerHTML = '';
	}
	if(document.frmProfile.ProfileZipcode.value == ''){
		document.getElementById('lblProfileZipcode').innerHTML = 'This field is required';
		document.frmProfile.ProfileZipcode.focus();
		return false;
	}else{
		document.getElementById('lblProfileZipcode').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileZipcode.value)){
		document.getElementById('lblProfileZipcode').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileZipcode.focus();
		return false;
	}else{
		document.getElementById('lblProfileZipcode').innerHTML = '';
	}
/*	if(document.frmProfile.ProfileLocation.value == ''){
		document.getElementById('lblProfileLocation').innerHTML = 'This field is required';
		document.frmProfile.ProfileLocation.focus();
		return false;
	}else{
		document.getElementById('lblProfileLocation').innerHTML = '';
	}*/
	/*if(document.frmProfile.ProfilePhoto.value == ''){
		document.getElementById('lblProfilePhoto').innerHTML = 'This field is required';
		document.frmProfile.ProfilePhoto.focus();
		return false;
	}else{
		document.getElementById('lblProfilePhoto').innerHTML = '';
	}*/
}
//for client Edit validation
function validate_editclient(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmProfile.ProfileUserName.value == ''){
		document.getElementById('lblProfileUserName').innerHTML = 'This field is required';
		document.frmProfile.ProfileUserName.focus();
		return false;
	}else{
		document.getElementById('lblProfileUserName').innerHTML = '';
	}	
	if(regex.test(document.frmProfile.ProfileUserName.value)){
		document.getElementById('lblProfileUserName').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileUserName.focus();
		return false;
	}else{
		document.getElementById('lblProfileUserName').innerHTML = '';
	}
	if(document.frmProfile.ProfileContactName.value == ''){
		document.getElementById('lblProfileContactName').innerHTML = 'This field is required';
		document.frmProfile.ProfileContactName.focus();
		return false;
	}else{
		document.getElementById('lblProfileContactName').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileContactName.value)){
		document.getElementById('lblProfileContactName').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileContactName.focus();
		return false;
	}else{
		document.getElementById('lblProfileContactName').innerHTML = '';
	}
	if(document.frmProfile.ProfileEmailID.value == ''){
		document.getElementById('lblProfileEmailID').innerHTML = 'This field is required';
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
	if(!document.frmProfile.ProfileEmailID.value.match(emailExp)){
		document.getElementById('lblProfileEmailID').innerHTML = "Required Valid Email ID.";
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}
	else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
	/*if(document.frmProfile.ProfilePassword.value == ''){
		document.getElementById('lblProfilePassword').innerHTML = 'This field is required';
		document.frmProfile.ProfilePassword.focus();
		return false;
	}else{
		document.getElementById('lblProfilePassword').innerHTML = '';
	}*/
	if(document.frmProfile.ProfileMobile.value == ''){
		document.getElementById('lblProfileMobile').innerHTML = 'This field is required';
		document.frmProfile.ProfileMobile.focus();
		return false;
	}else{
		document.getElementById('lblProfileMobile').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileMobile.value)){
		document.getElementById('lblProfileMobile').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileMobile.focus();
		return false;
	}else{
		document.getElementById('lblProfileMobile').innerHTML = '';
	}
	if(document.frmProfile.ProfileFaxno.value == ''){
		document.getElementById('lblProfileFaxno').innerHTML = 'This field is required';
		document.frmProfile.ProfileFaxno.focus();
		return false;
	}else{
		document.getElementById('lblProfileFaxno').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileFaxno.value)){
		document.getElementById('lblProfileFaxno').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileFaxno.focus();
		return false;
	}else{
		document.getElementById('lblProfileFaxno').innerHTML = '';
	}
	if(document.frmProfile.ProfileAddress.value == ''){
		document.getElementById('lblProfileAddress').innerHTML = 'This field is required';
		document.frmProfile.ProfileAddress.focus();
		return false;
	}else{
		document.getElementById('lblProfileAddress').innerHTML = '';
	}
	if(document.frmProfile.ProfileCity.value == ''){
		document.getElementById('lblProfileCity').innerHTML = 'This field is required';
		document.frmProfile.ProfileCity.focus();
		return false;
	}else{
		document.getElementById('lblProfileCity').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileCity.value)){
		document.getElementById('lblProfileCity').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileCity.focus();
		return false;
	}else{
		document.getElementById('lblProfileCity').innerHTML = '';
	}
	if(document.frmProfile.ProfileState.value == ''){
		document.getElementById('lblProfileState').innerHTML = 'This field is required';
		document.frmProfile.ProfileState.focus();
		return false;
	}else{
		document.getElementById('lblProfileState').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileState.value)){
		document.getElementById('lblProfileState').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileState.focus();
		return false;
	}else{
		document.getElementById('lblProfileState').innerHTML = '';
	}
	if(document.frmProfile.ProfileZipcode.value == ''){
		document.getElementById('lblProfileZipcode').innerHTML = 'This field is required';
		document.frmProfile.ProfileZipcode.focus();
		return false;
	}else{
		document.getElementById('lblProfileZipcode').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileZipcode.value)){
		document.getElementById('lblProfileZipcode').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileZipcode.focus();
		return false;
	}else{
		document.getElementById('lblProfileZipcode').innerHTML = '';
	}
	/*if(document.frmProfile.ProfileLocation.value == ''){
		document.getElementById('lblProfileLocation').innerHTML = 'This field is required';
		document.frmProfile.ProfileLocation.focus();
		return false;
	}else{
		document.getElementById('lblProfileLocation').innerHTML = '';
	}*/
	/*if(document.frmProfile.ProfilePhoto.value == ''){
		document.getElementById('lblProfilePhoto').innerHTML = 'This field is required';
		document.frmProfile.ProfilePhoto.focus();
		return false;
	}else{
		document.getElementById('lblProfilePhoto').innerHTML = '';
	}*/
}
//for user validation
function validate_user(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmProfile.ProfileUserName.value == ''){
		document.getElementById('lblProfileUserName').innerHTML = 'This field is required';
		document.frmProfile.ProfileUserName.focus();
		return false;
	}else{
		document.getElementById('lblProfileUserName').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileUserName.value)){
		document.getElementById('lblProfileUserName').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileUserName.focus();
		return false;
	}else{
		document.getElementById('lblProfileUserName').innerHTML = '';
	}	
	if(document.frmProfile.ProfileEmailID.value == ''){
		document.getElementById('lblProfileEmailID').innerHTML = 'This field is required';
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
	if(!document.frmProfile.ProfileEmailID.value.match(emailExp)){
		document.getElementById('lblProfileEmailID').innerHTML = "Required Valid Email ID.";
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}
	else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
	if(document.frmProfile.ProfilePassword.value == ''){
		document.getElementById('lblProfilePassword').innerHTML = 'This field is required';
		document.frmProfile.ProfilePassword.focus();
		return false;
	}else{
		document.getElementById('lblProfilePassword').innerHTML = '';
	}
	if(document.frmProfile.ProfileMobile.value == ''){
		document.getElementById('lblProfileMobile').innerHTML = 'This field is required';
		document.frmProfile.ProfileMobile.focus();
		return false;
	}else{
		document.getElementById('lblProfileMobile').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileMobile.value)){
		document.getElementById('lblProfileMobile').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileMobile.focus();
		return false;
	}else{
		document.getElementById('lblProfileMobile').innerHTML = '';
	}	
	if(document.frmProfile.ProfileCity.value == ''){
		document.getElementById('lblProfileCity').innerHTML = 'This field is required';
		document.frmProfile.ProfileCity.focus();
		return false;
	}else{
		document.getElementById('lblProfileCity').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileCity.value)){
		document.getElementById('lblProfileCity').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileCity.focus();
		return false;
	}else{
		document.getElementById('lblProfileCity').innerHTML = '';
	}	
	if(document.frmProfile.ProfileState.value == ''){
		document.getElementById('lblProfileState').innerHTML = 'This field is required';
		document.frmProfile.ProfileState.focus();
		return false;
	}else{
		document.getElementById('lblProfileState').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileState.value)){
		document.getElementById('lblProfileState').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileState.focus();
		return false;
	}else{
		document.getElementById('lblProfileState').innerHTML = '';
	}	
	if(document.frmProfile.ProfilePhoto.value == ''){
		document.getElementById('lblProfilePhoto').innerHTML = 'This field is required';
		document.frmProfile.ProfilePhoto.focus();
		return false;
	}else{
		document.getElementById('lblProfilePhoto').innerHTML = '';
	}	
	if(document.frmProfile.ProfilePhoto.value != ''){
		var str=document.getElementById('ProfilePhoto').value;
		var bigstr=str.lastIndexOf(".");
		var ext=str.substring(bigstr+1);
		if(ext == 'jpg' || ext == 'JPG' || ext == 'jpeg' || ext == 'JPEG' || ext == 'png' || ext == 'PNG' || ext == 'gif' || ext == 'GIF' ){
			document.getElementById('lblProfilePhoto').innerHTML = '';
		}else{
			document.getElementById('lblProfilePhoto').innerHTML = 'Please enter a valid image';
			return false;
		}
	}	
/*	if(document.frmProfile.TechDrLicenseNo.value == ''){
		document.getElementById('lblTechDrLicenseNo').innerHTML = 'This field is required';
		document.frmProfile.TechDrLicenseNo.focus();
		return false;
	}else{
		document.getElementById('lblTechDrLicenseNo').innerHTML = '';
	}*/				
}
//for user validation
function validate_edituser(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmProfile.ProfileUserName.value == ''){
		document.getElementById('lblProfileUserName').innerHTML = 'This field is required';
		document.frmProfile.ProfileUserName.focus();
		return false;
	}else{
		document.getElementById('lblProfileUserName').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileUserName.value)){
		document.getElementById('lblProfileUserName').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileUserName.focus();
		return false;
	}else{
		document.getElementById('lblProfileUserName').innerHTML = '';
	}		
	if(document.frmProfile.ProfileEmailID.value == ''){
		document.getElementById('lblProfileEmailID').innerHTML = 'This field is required';
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
	if(!document.frmProfile.ProfileEmailID.value.match(emailExp)){
		document.getElementById('lblProfileEmailID').innerHTML = "Required Valid Email ID.";
		document.frmProfile.ProfileEmailID.focus();
		return false;
	}
	else{
		document.getElementById('lblProfileEmailID').innerHTML = '';
	}
	if(document.frmProfile.ProfilePassword.value == ''){
		document.getElementById('lblProfilePassword').innerHTML = 'This field is required';
		document.frmProfile.ProfilePassword.focus();
		return false;
	}else{
		document.getElementById('lblProfilePassword').innerHTML = '';
	}
	if(document.frmProfile.ProfileMobile.value == ''){
		document.getElementById('lblProfileMobile').innerHTML = 'This field is required';
		document.frmProfile.ProfileMobile.focus();
		return false;
	}else{
		document.getElementById('lblProfileMobile').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileMobile.value)){
		document.getElementById('lblProfileMobile').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileMobile.focus();
		return false;
	}else{
		document.getElementById('lblProfileMobile').innerHTML = '';
	}	
	if(document.frmProfile.ProfileCity.value == ''){
		document.getElementById('lblProfileCity').innerHTML = 'This field is required';
		document.frmProfile.ProfileCity.focus();
		return false;
	}else{
		document.getElementById('lblProfileCity').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileCity.value)){
		document.getElementById('lblProfileCity').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileCity.focus();
		return false;
	}else{
		document.getElementById('lblProfileCity').innerHTML = '';
	}	
	if(document.frmProfile.ProfileState.value == ''){
		document.getElementById('lblProfileState').innerHTML = 'This field is required';
		document.frmProfile.ProfileState.focus();
		return false;
	}else{
		document.getElementById('lblProfileState').innerHTML = '';
	}
	if(regex.test(document.frmProfile.ProfileState.value)){
		document.getElementById('lblProfileState').innerHTML = 'This field contains special chars';
		document.frmProfile.ProfileState.focus();
		return false;
	}else{
		document.getElementById('lblProfileState').innerHTML = '';
	}
	if(document.frmProfile.ProfilePhoto.value != ''){
		var str=document.getElementById('ProfilePhoto').value;
		var bigstr=str.lastIndexOf(".");
		var ext=str.substring(bigstr+1);
		if(ext == 'jpg' || ext == 'JPG' || ext == 'jpeg' || ext == 'JPEG' || ext == 'png' || ext == 'PNG' || ext == 'gif' || ext == 'GIF' ){
			document.getElementById('lblProfilePhoto').innerHTML = '';
		}else{
			document.getElementById('lblProfilePhoto').innerHTML = 'Please enter a valid image';
			return false;
		}
	}	
	/*if(document.frmProfile.ProfilePhoto.value == ''){
		document.getElementById('lblProfilePhoto').innerHTML = 'This field is required';
		document.frmProfile.ProfilePhoto.focus();
		return false;
	}else{
		document.getElementById('lblProfilePhoto').innerHTML = '';
	}		*/			
}
//for technician validation
function validate_tech(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmTech.TechFirstName.value == ''){
		document.getElementById('lblTechFirstName').innerHTML = 'This field is required';
		document.frmTech.TechFirstName.focus();
		return false;
	}else{
		document.getElementById('lblTechFirstName').innerHTML = '';
	}
	if(document.frmTech.TechMiddleName.value == ''){
		document.getElementById('lblTechMiddleName').innerHTML = 'This field is required';
		document.frmTech.TechMiddleName.focus();
		return false;
	}else{
		document.getElementById('lblTechMiddleName').innerHTML = '';
	}	
	if(document.frmTech.TechLastName.value == ''){
		document.getElementById('lblTechLastName').innerHTML = 'This field is required';
		document.frmTech.TechLastName.focus();
		return false;
	}else{
		document.getElementById('lblTechLastName').innerHTML = '';
	}		
	if(document.frmTech.TechEmailID.value == ''){
		document.getElementById('lblTechEmailID').innerHTML = 'This field is required';
		document.frmTech.TechEmailID.focus();
		return false;
	}else{
		document.getElementById('lblTechEmailID').innerHTML = '';
	}
	if(!document.frmTech.TechEmailID.value.match(emailExp)){
		document.getElementById('lblTechEmailID').innerHTML = "Required Valid Email ID.";
		document.frmTech.TechEmailID.focus();
		return false;
	}
	else{
		document.getElementById('lblTechEmailID').innerHTML = '';
	}
	if(document.frmTech.TechContactNo.value == ''){
		document.getElementById('lblTechContactNo').innerHTML = 'This field is required';
		document.frmTech.TechContactNo.focus();
		return false;
	}else{
		document.getElementById('lblTechContactNo').innerHTML = '';
	}
	if(document.frmTech.TechAltPhone.value == ''){
		document.getElementById('lblTechAltPhone').innerHTML = 'This field is required';
		document.frmTech.TechAltPhone.focus();
		return false;
	}else{
		document.getElementById('lblTechAltPhone').innerHTML = '';
	}
	if(document.frmTech.TechAddress.value == ''){
		document.getElementById('lblTechAddress').innerHTML = 'This field is required';
		document.frmTech.TechAddress.focus();
		return false;
	}else{
		document.getElementById('lblTechAddress').innerHTML = '';
	}
	if(document.frmTech.TechCity.value == ''){
		document.getElementById('lblTechCity').innerHTML = 'This field is required';
		document.frmTech.TechCity.focus();
		return false;
	}else{
		document.getElementById('lblTechCity').innerHTML = '';
	}
	if(document.frmTech.TechState.value == ''){
		document.getElementById('lblTechState').innerHTML = 'This field is required';
		document.frmTech.TechState.focus();
		return false;
	}else{
		document.getElementById('lblTechState').innerHTML = '';
	}
	if(document.frmTech.TechZipcode.value == ''){
		document.getElementById('lblTechZipcode').innerHTML = 'This field is required';
		document.frmTech.TechZipcode.focus();
		return false;
	}else{
		document.getElementById('lblTechZipcode').innerHTML = '';
	}
	if(document.frmTech.TechDateBirth.value == ''){
		document.getElementById('lblTechDateBirth').innerHTML = 'This field is required';
		document.frmTech.TechDateBirth.focus();
		return false;
	}else{
		document.getElementById('lblTechDateBirth').innerHTML = '';
	}
	/*if(document.frmTech.TechCompanyName.value == ''){
		document.getElementById('lblTechCompanyName').innerHTML = 'This field is required';
		document.frmTech.TechCompanyName.focus();
		return false;
	}else{
		document.getElementById('lblTechCompanyName').innerHTML = '';
	}*/
	/*if(document.frmTech.TechSSN.value == ''){
		document.getElementById('lblTechSSN').innerHTML = 'This field is required';
		document.frmTech.TechSSN.focus();
		return false;
	}else{
		document.getElementById('lblTechSSN').innerHTML = '';
	}*/
	/*if(document.frmTech.TechFEIN.value == ''){
		document.getElementById('lblTechFEIN').innerHTML = 'This field is required';
		document.frmTech.TechFEIN.focus();
		return false;
	}else{
		document.getElementById('lblTechFEIN').innerHTML = '';
	}*/
	if(document.frmTech.TechPicture.value == ''){
		document.getElementById('lblTechPicture').innerHTML = 'This field is required';
		document.frmTech.TechPicture.focus();
		return false;
	}else{
		document.getElementById('lblTechPicture').innerHTML = '';
	}
	if(document.frmTech.TechPayGrade.value == ''){
		document.getElementById('lblTechPayGrade').innerHTML = 'This field is required';
		document.frmTech.TechPayGrade.focus();
		return false;
	}else{
		document.getElementById('lblTechPayGrade').innerHTML = '';
	}
	var chk=$('input:checkbox[name=TechPayble[]]:checked').length;
	if(chk == 0){
		document.getElementById('lblTechPayble').innerHTML = 'This field is required';
		return false;
	}else{
		document.getElementById('lblTechPayble').innerHTML = '';
	}													
}
//for Add Technician Validation
function validate_techAdd(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmTech.TechFirstName.value == ''){
		document.getElementById('lblTechFirstName').innerHTML = 'This field is required';
		document.frmTech.TechFirstName.focus();
		return false;
	}else{
		document.getElementById('lblTechFirstName').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechFirstName.value)){
		document.getElementById('lblTechFirstName').innerHTML = 'This field contains special chars';
		document.frmTech.TechFirstName.focus();
		return false;
	}else{
		document.getElementById('lblTechFirstName').innerHTML = '';
	}
	if(document.frmTech.TechMiddleName.value == ''){
		document.getElementById('lblTechMiddleName').innerHTML = 'This field is required';
		document.frmTech.TechMiddleName.focus();
		return false;
	}else{
		document.getElementById('lblTechMiddleName').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechMiddleName.value)){
		document.getElementById('lblTechMiddleName').innerHTML = 'This field contains special chars';
		document.frmTech.TechMiddleName.focus();
		return false;
	}else{
		document.getElementById('lblTechMiddleName').innerHTML = '';
	}	
	if(document.frmTech.TechLastName.value == ''){
		document.getElementById('lblTechLastName').innerHTML = 'This field is required';
		document.frmTech.TechLastName.focus();
		return false;
	}else{
		document.getElementById('lblTechLastName').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechLastName.value)){
		document.getElementById('lblTechLastName').innerHTML = 'This field contains special chars';
		document.frmTech.TechLastName.focus();
		return false;
	}else{
		document.getElementById('lblTechLastName').innerHTML = '';
	}			
	if(document.frmTech.TechEmailID.value == ''){
		document.getElementById('lblTechEmailID').innerHTML = 'This field is required';
		document.frmTech.TechEmailID.focus();
		return false;
	}else{
		document.getElementById('lblTechEmailID').innerHTML = '';
	}
	if(!document.frmTech.TechEmailID.value.match(emailExp)){
		document.getElementById('lblTechEmailID').innerHTML = "Required Valid Email ID.";
		document.frmTech.TechEmailID.focus();
		return false;
	}
	else{
		document.getElementById('lblTechEmailID').innerHTML = '';
	}
	if(document.frmTech.TechContactNo.value == ''){
		document.getElementById('lblTechContactNo').innerHTML = 'This field is required';
		document.frmTech.TechContactNo.focus();
		return false;
	}else{
		document.getElementById('lblTechContactNo').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechContactNo.value)){
		document.getElementById('lblTechContactNo').innerHTML = 'This field contains special chars';
		document.frmTech.TechContactNo.focus();
		return false;
	}else{
		document.getElementById('lblTechContactNo').innerHTML = '';
	}	
	/*if(document.frmTech.TechAltPhone.value == ''){
		document.getElementById('lblTechAltPhone').innerHTML = 'This field is required';
		document.frmTech.TechAltPhone.focus();
		return false;
	}else{
		document.getElementById('lblTechAltPhone').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechAltPhone.value)){
		document.getElementById('lblTechAltPhone').innerHTML = 'This field contains special chars';
		document.frmTech.TechAltPhone.focus();
		return false;
	}else{
		document.getElementById('lblTechAltPhone').innerHTML = '';
	}*/	
	if(document.frmTech.TechAddress.value == ''){
		document.getElementById('lblTechAddress').innerHTML = 'This field is required';
		document.frmTech.TechAddress.focus();
		return false;
	}else{
		document.getElementById('lblTechAddress').innerHTML = '';
	}
	if(document.frmTech.TechCity.value == ''){
		document.getElementById('lblTechCity').innerHTML = 'This field is required';
		document.frmTech.TechCity.focus();
		return false;
	}else{
		document.getElementById('lblTechCity').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechCity.value)){
		document.getElementById('lblTechCity').innerHTML = 'This field contains special chars';
		document.frmTech.TechCity.focus();
		return false;
	}else{
		document.getElementById('lblTechCity').innerHTML = '';
	}	
	if(document.frmTech.TechState.value == ''){
		document.getElementById('lblTechState').innerHTML = 'This field is required';
		document.frmTech.TechState.focus();
		return false;
	}else{
		document.getElementById('lblTechState').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechState.value)){
		document.getElementById('lblTechState').innerHTML = 'This field contains special chars';
		document.frmTech.TechState.focus();
		return false;
	}else{
		document.getElementById('lblTechState').innerHTML = '';
	}	
	if(document.frmTech.TechZipcode.value == ''){
		document.getElementById('lblTechZipcode').innerHTML = 'This field is required';
		document.frmTech.TechZipcode.focus();
		return false;
	}else{
		document.getElementById('lblTechZipcode').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechZipcode.value)){
		document.getElementById('lblTechZipcode').innerHTML = 'This field contains special chars';
		document.frmTech.TechZipcode.focus();
		return false;
	}else{
		document.getElementById('lblTechZipcode').innerHTML = '';
	}	
	if(document.frmTech.TechDateBirth.value == ''){
		document.getElementById('lblTechDateBirth').innerHTML = 'This field is required';
		document.frmTech.TechDateBirth.focus();
		return false;
	}else{
		document.getElementById('lblTechDateBirth').innerHTML = '';
	}
	/*if(document.frmTech.TechCompanyName.value == ''){
		document.getElementById('lblTechCompanyName').innerHTML = 'This field is required';
		document.frmTech.TechCompanyName.focus();
		return false;
	}else{
		document.getElementById('lblTechCompanyName').innerHTML = '';
	}*/
	/*if(document.frmTech.TechSSN.value == ''){
		document.getElementById('lblTechSSN').innerHTML = 'This field is required';
		document.frmTech.TechSSN.focus();
		return false;
	}else{
		document.getElementById('lblTechSSN').innerHTML = '';
	}*/
	/*if(document.frmTech.TechFEIN.value == ''){
		document.getElementById('lblTechFEIN').innerHTML = 'This field is required';
		document.frmTech.TechFEIN.focus();
		return false;
	}else{
		document.getElementById('lblTechFEIN').innerHTML = '';
	}*/
	/*if(document.frmTech.TechPicture.value == ''){
		document.getElementById('lblTechPicture').innerHTML = 'This field is required';
		document.frmTech.TechPicture.focus();
		return false;
	}else{
		document.getElementById('lblTechPicture').innerHTML = '';
	}*/
	if(document.frmTech.TechPicture.value != ''){
		var str=document.getElementById('TechPicture').value;
		var bigstr=str.lastIndexOf(".");
		var ext=str.substring(bigstr+1);
		if(ext == 'jpg' || ext == 'JPG' || ext == 'jpeg' || ext == 'JPEG' || ext == 'png' || ext == 'PNG' || ext == 'gif' || ext == 'GIF' ){
			document.getElementById('lblTechPicture').innerHTML = '';
		}else{
			document.getElementById('lblTechPicture').innerHTML = 'Please enter a valid image';
			return false;
		}
	}
	if(document.frmTech.TechPayGrade.value == ''){
		document.getElementById('lblTechPayGrade').innerHTML = 'This field is required';
		document.frmTech.TechPayGrade.focus();
		return false;
	}else{
		document.getElementById('lblTechPayGrade').innerHTML = '';
	}
	var chk=$('input:checkbox[name=TechPayble[]]:checked').length;
	if(chk == 0){
		document.getElementById('lblTechPayble').innerHTML = 'This field is required';
		return false;
	}else{
		document.getElementById('lblTechPayble').innerHTML = '';
	}													
}
//for Edit technician validation
function validate_techEdit(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.frmTech.TechFirstName.value == ''){
		document.getElementById('lblTechFirstName').innerHTML = 'This field is required';
		document.frmTech.TechFirstName.focus();
		return false;
	}else{
		document.getElementById('lblTechFirstName').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechFirstName.value)){
		document.getElementById('lblTechFirstName').innerHTML = 'This field contains special chars';
		document.frmTech.TechFirstName.focus();
		return false;
	}else{
		document.getElementById('lblTechFirstName').innerHTML = '';
	}
	if(document.frmTech.TechMiddleName.value == ''){
		document.getElementById('lblTechMiddleName').innerHTML = 'This field is required';
		document.frmTech.TechMiddleName.focus();
		return false;
	}else{
		document.getElementById('lblTechMiddleName').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechMiddleName.value)){
		document.getElementById('lblTechMiddleName').innerHTML = 'This field contains special chars';
		document.frmTech.TechMiddleName.focus();
		return false;
	}else{
		document.getElementById('lblTechMiddleName').innerHTML = '';
	}	
	if(document.frmTech.TechLastName.value == ''){
		document.getElementById('lblTechLastName').innerHTML = 'This field is required';
		document.frmTech.TechLastName.focus();
		return false;
	}else{
		document.getElementById('lblTechLastName').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechLastName.value)){
		document.getElementById('lblTechLastName').innerHTML = 'This field contains special chars';
		document.frmTech.TechLastName.focus();
		return false;
	}else{
		document.getElementById('lblTechLastName').innerHTML = '';
	}			
	if(document.frmTech.TechEmailID.value == ''){
		document.getElementById('lblTechEmailID').innerHTML = 'This field is required';
		document.frmTech.TechEmailID.focus();
		return false;
	}else{
		document.getElementById('lblTechEmailID').innerHTML = '';
	}
	if(!document.frmTech.TechEmailID.value.match(emailExp)){
		document.getElementById('lblTechEmailID').innerHTML = "Required Valid Email ID.";
		document.frmTech.TechEmailID.focus();
		return false;
	}
	else{
		document.getElementById('lblTechEmailID').innerHTML = '';
	}
	if(document.frmTech.TechContactNo.value == ''){
		document.getElementById('lblTechContactNo').innerHTML = 'This field is required';
		document.frmTech.TechContactNo.focus();
		return false;
	}else{
		document.getElementById('lblTechContactNo').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechContactNo.value)){
		document.getElementById('lblTechContactNo').innerHTML = 'This field contains special chars';
		document.frmTech.TechContactNo.focus();
		return false;
	}else{
		document.getElementById('lblTechContactNo').innerHTML = '';
	}	
	/*if(document.frmTech.TechAltPhone.value == ''){
		document.getElementById('lblTechAltPhone').innerHTML = 'This field is required';
		document.frmTech.TechAltPhone.focus();
		return false;
	}else{
		document.getElementById('lblTechAltPhone').innerHTML = '';
	}*/
	/*if(regex.test(document.frmTech.TechAltPhone.value)){
		document.getElementById('lblTechAltPhone').innerHTML = 'This field contains special chars';
		document.frmTech.TechAltPhone.focus();
		return false;
	}else{
		document.getElementById('lblTechAltPhone').innerHTML = '';
	}*/
	if(document.frmTech.TechPicture.value != ''){
		var str=document.getElementById('TechPicture').value;
		var bigstr=str.lastIndexOf(".");
		var ext=str.substring(bigstr+1);
		if(ext == 'jpg' || ext == 'JPG' || ext == 'jpeg' || ext == 'JPEG' || ext == 'png' || ext == 'PNG' || ext == 'gif' || ext == 'GIF' ){
			document.getElementById('lblTechPicture').innerHTML = '';
		}else{
			document.getElementById('lblTechPicture').innerHTML = 'Please enter a valid image';
			return false;
		}
	}
	if(document.frmTech.TechAddress.value == ''){
		document.getElementById('lblTechAddress').innerHTML = 'This field is required';
		document.frmTech.TechAddress.focus();
		return false;
	}else{
		document.getElementById('lblTechAddress').innerHTML = '';
	}
	if(document.frmTech.TechCity.value == ''){
		document.getElementById('lblTechCity').innerHTML = 'This field is required';
		document.frmTech.TechCity.focus();
		return false;
	}else{
		document.getElementById('lblTechCity').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechCity.value)){
		document.getElementById('lblTechCity').innerHTML = 'This field contains special chars';
		document.frmTech.TechCity.focus();
		return false;
	}else{
		document.getElementById('lblTechCity').innerHTML = '';
	}	
	if(document.frmTech.TechState.value == ''){
		document.getElementById('lblTechState').innerHTML = 'This field is required';
		document.frmTech.TechState.focus();
		return false;
	}else{
		document.getElementById('lblTechState').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechState.value)){
		document.getElementById('lblTechState').innerHTML = 'This field contains special chars';
		document.frmTech.TechState.focus();
		return false;
	}else{
		document.getElementById('lblTechState').innerHTML = '';
	}	
	if(document.frmTech.TechZipcode.value == ''){
		document.getElementById('lblTechZipcode').innerHTML = 'This field is required';
		document.frmTech.TechZipcode.focus();
		return false;
	}else{
		document.getElementById('lblTechZipcode').innerHTML = '';
	}
	if(regex.test(document.frmTech.TechZipcode.value)){
		document.getElementById('lblTechZipcode').innerHTML = 'This field contains special chars';
		document.frmTech.TechZipcode.focus();
		return false;
	}else{
		document.getElementById('lblTechZipcode').innerHTML = '';
	}	
	if(document.frmTech.TechDateBirth.value == ''){
		document.getElementById('lblTechDateBirth').innerHTML = 'This field is required';
		document.frmTech.TechDateBirth.focus();
		return false;
	}else{
		document.getElementById('lblTechDateBirth').innerHTML = '';
	}
	/*if(document.frmTech.TechCompanyName.value == ''){
		document.getElementById('lblTechCompanyName').innerHTML = 'This field is required';
		document.frmTech.TechCompanyName.focus();
		return false;
	}else{
		document.getElementById('lblTechCompanyName').innerHTML = '';
	}*/
	/*if(document.frmTech.TechSSN.value == ''){
		document.getElementById('lblTechSSN').innerHTML = 'This field is required';
		document.frmTech.TechSSN.focus();
		return false;
	}else{
		document.getElementById('lblTechSSN').innerHTML = '';
	}*/
	/*if(document.frmTech.TechFEIN.value == ''){
		document.getElementById('lblTechFEIN').innerHTML = 'This field is required';
		document.frmTech.TechFEIN.focus();
		return false;
	}else{
		document.getElementById('lblTechFEIN').innerHTML = '';
	}*/
	/*if(document.frmTech.TechPicture.value == ''){
		document.getElementById('lblTechPicture').innerHTML = 'This field is required';
		document.frmTech.TechPicture.focus();
		return false;
	}else{
		document.getElementById('lblTechPicture').innerHTML = '';
	}*/
	if(document.frmTech.TechPayGrade.value == ''){
		document.getElementById('lblTechPayGrade').innerHTML = 'This field is required';
		document.frmTech.TechPayGrade.focus();
		return false;
	}else{
		document.getElementById('lblTechPayGrade').innerHTML = '';
	}
	var chk=$('input:checkbox[name=TechPayble[]]:checked').length;
	if(chk == 0){
		document.getElementById('lblTechPayble').innerHTML = 'This field is required';
		return false;
	}else{
		document.getElementById('lblTechPayble').innerHTML = '';
	}													
}
//function for Work Order Validation 
function validate_createjob(){
	
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	if(document.createJob.cmbService.value == ''){
		document.getElementById('lblcmbService').innerHTML = 'This field is required';
		document.createJob.cmbService.focus();
		return false;
	}else{
		document.getElementById('lblcmbService').innerHTML = '';
	}
	//pickup validation
	if(document.createJob.Pickuplocation.value == ''){
		document.getElementById('lblPickuplocation').innerHTML = 'This field is required';
		document.createJob.Pickuplocation.focus();
		return false;
	}else{
		document.getElementById('lblPickuplocation').innerHTML ='';
	}
	if(regex.test(document.createJob.Pickuplocation.value)){
		document.getElementById('lblPickuplocation').innerHTML = 'This field contains special chars';
		document.createJob.Pickuplocation.focus();
		return false;
	}else{
		document.getElementById('lblPickuplocation').innerHTML ='';
	}
	if(document.createJob.City.value == ''){
		document.getElementById('lblCity').innerHTML = 'This field is required';
		document.createJob.City.focus();
		return false;
	}else{
		document.getElementById('lblCity').innerHTML ='';
	}
	if(regex.test(document.createJob.City.value)){
		document.getElementById('lblCity').innerHTML = 'This field contains special chars';
		document.createJob.City.focus();
		return false;
	}else{
		document.getElementById('lblCity').innerHTML ='';
	}
	if(document.createJob.State.value ==''){
		document.getElementById('lblState').innerHTML = 'This field is required';
		document.createJob.State.focus();
		return false;
	}else{
		document.getElementById('lblState').innerHTML ='';
	}
	if(regex.test(document.createJob.State.value)){
		document.getElementById('lblState').innerHTML = 'This field contains special chars';
		document.createJob.State.focus();
		return false;
	}else{
		document.getElementById('lblState').innerHTML ='';
	}
	if(document.createJob.Address.value == ''){
		document.getElementById('lblAddress').innerHTML = 'This field is required';
		document.createJob.Address.focus();
		return false;
	}else{
		document.getElementById('lblAddress').innerHTML = '';
	}
	
	if(document.createJob.Zip.value == ''){
		document.getElementById('lblZip').innerHTML = 'This field is required';
		document.createJob.Zip.focus();
		return false;
	}else{
		document.getElementById('lblZip').innerHTML = '';
	}
	if(regex.test(document.createJob.Zip.value)){
		document.getElementById('lblZip').innerHTML = 'This field contains special chars';
		document.createJob.Zip.focus();
		return false;
	}else{
		document.getElementById('lblZip').innerHTML = '';
	}
	if(document.createJob.Phone.value == ''){
		document.getElementById('lblPhone').innerHTML = 'This field is required';
		document.createJob.Phone.focus();
		return false;
	}else{
		document.getElementById('lblPhone').innerHTML = '';
	}
   if(document.createJob.CustomerName.value == ''){
		document.getElementById('lblCustomerName').innerHTML = 'This field is required';
		document.createJob.CustomerName.focus();
		return false;
	}else{
		document.getElementById('lblCustomerName').innerHTML = '';
	}
	if(regex.test(document.createJob.CustomerName.value)){
		document.getElementById('lblCustomerName').innerHTML = 'This field contains special chars';
		document.createJob.CustomerName.focus();
		return false;
	}else{
		document.getElementById('lblCustomerName').innerHTML = '';
	}
	if(document.createJob.CustomerEmailID.value == ''){
		document.getElementById('lblCustomerEmailID').innerHTML = 'This field is required';
		document.createJob.CustomerEmailID.focus();
		return false;
	}else{
		document.getElementById('lblCustomerEmailID').innerHTML = '';
	}
	if(!document.createJob.CustomerEmailID.value.match(emailExp)){
		document.getElementById('lblCustomerEmailID').innerHTML = "Required Valid Email ID.";
		document.createJob.CustomerEmailID.focus();
		return false;
	}
	else{
		document.getElementById('lblCustomerEmailID').innerHTML = '';
	}
	if(document.createJob.CustAddress.value == ''){
		document.getElementById('lblCustAddress').innerHTML = 'This field is required';
		document.createJob.CustAddress.focus();
		return false;
	}else{
		document.getElementById('lblCustAddress').innerHTML = '';
	}
	if(document.createJob.CustomercontactName.value == ''){
		document.getElementById('lblCustomercontactName').innerHTML = 'This field is required';
		document.createJob.CustomercontactName.focus();
		return false;
	}else{
		document.getElementById('lblCustomercontactName').innerHTML = '';
	}
	
	if(regex.test(document.createJob.CustomercontactName.value)){
		document.getElementById('lblCustomercontactName').innerHTML = 'This field contains special chars';
		document.createJob.CustomercontactName.focus();
		return false;
	}else{
		document.getElementById('lblCustomercontactName').innerHTML = '';
	}
	
	if(document.createJob.CustCity.value == ''){
		document.getElementById('lblCustCity').innerHTML = 'This field is required';
		document.createJob.CustCity.focus();
		return false;
	}else{
		document.getElementById('lblCustCity').innerHTML = '';
	}
	if(regex.test(document.createJob.CustCity.value)){
		document.getElementById('lblCustCity').innerHTML = 'This field contains special chars';
		document.createJob.CustCity.focus();
		return false;
	}else{
		document.getElementById('lblCustCity').innerHTML = '';
	}
	if(document.createJob.CustState.value == ''){
		document.getElementById('lblCustState').innerHTML = 'This field is required';
		document.createJob.CustState.focus();
		return false;
	}else{
		document.getElementById('lblCustState').innerHTML = '';
	}
	if(regex.test(document.createJob.CustState.value)){
		document.getElementById('lblCustState').innerHTML = 'This field contains special chars';
		document.createJob.CustState.focus();
		return false;
	}else{
		document.getElementById('lblCustState').innerHTML = '';
	}
	if(document.createJob.CustZip.value == ''){
		document.getElementById('lblCustZip').innerHTML = 'This field is required';
		document.createJob.CustZip.focus();
		return false;
	}else{
		document.getElementById('lblCustZip').innerHTML = '';
	}
	if(regex.test(document.createJob.CustZip.value)){
		document.getElementById('lblCustZip').innerHTML = 'This field contains special chars';
		document.createJob.CustZip.focus();
		return false;
	}else{
		document.getElementById('lblCustZip').innerHTML = '';
	}
	if(document.createJob.CustPhone.value == ''){
		document.getElementById('lblCustPhone').innerHTML = 'This field is required';
		document.createJob.CustPhone.focus();
		return false;
	}else{
		document.getElementById('lblCustPhone').innerHTML = '';
	}
	if(document.createJob.notes.value == ''){
		document.getElementById('lblnotes').innerHTML = 'This field is required';
		document.createJob.notes.focus();
		return false;
	}else{
		document.getElementById('lblnotes').innerHTML = '';
	}
	if(document.createJob.Worktype1.value == ''){
		document.getElementById('lblWorktype1').innerHTML = 'This field is required';
		document.createJob.Worktype1.focus();
		return false;
	}else{
		document.getElementById('lblWorktype1').innerHTML = '';
	}
	if(document.createJob.Equipment1.value == ''){
		document.getElementById('lblEquipment1').innerHTML = 'This field is required';
		document.createJob.Equipment1.focus();
		return false;
	}else{
		document.getElementById('lblEquipment1').innerHTML = '';
	}
	if(document.createJob.Model1.value == ''){
		document.getElementById('lblModel1').innerHTML = 'This field is required';
		document.createJob.Model1.focus();
		return false;
	}else{
		document.getElementById('lblModel1').innerHTML = '';
	}
	if(document.createJob.Quantity1.value == ''){
		document.getElementById('lblQuantity1').innerHTML = 'This field is required';
		document.createJob.Quantity1.focus();
		return false;
	}else{
		document.getElementById('lblQuantity1').innerHTML = '';
	}
}
//function for Service validation
function validate_Service(){
	if(document.frmService.ProfileServiceName.value == ''){
		document.getElementById('lblProfileServiceName').innerHTML = 'This field is required';
		document.frmService.ProfileServiceName.focus();
		return false;
	}else{
		document.getElementById('lblProfileServiceName').innerHTML = '';
	}
	if(regex.test(document.frmService.ProfileServiceName.value)){
		document.getElementById('lblProfileServiceName').innerHTML = 'This field contains special chars';
		document.frmService.ProfileServiceName.focus();
		return false;
	}else{
		document.getElementById('lblProfileServiceName').innerHTML = '';
	}
}
function validate_editService(){
	if(document.frmService.ProfileServiceName.value == ''){
		document.getElementById('lblProfileServiceName').innerHTML = 'This field is required';
		document.frmService.ProfileServiceName.focus();
		return false;
	}else{
		document.getElementById('lblProfileServiceName').innerHTML = '';
	}
	if(regex.test(document.frmService.ProfileServiceName.value)){
		document.getElementById('lblProfileServiceName').innerHTML = 'This field contains special chars';
		document.frmService.ProfileServiceName.focus();
		return false;
	}else{
		document.getElementById('lblProfileServiceName').innerHTML = '';
	}
}
//function for Jobtype validation
function validate_Jobtype(){
	if(document.frmJobtype.ProfileJobtypeName.value == ''){
		document.getElementById('lblProfileJobtypeName').innerHTML = 'This field is required';
		document.frmJobtype.ProfileJobtypeName.focus();
		return false;
	}else{
		document.getElementById('lblProfileJobtypeName').innerHTML = '';
	}
	if(regex.test(document.frmJobtype.ProfileJobtypeName.value)){
		document.getElementById('lblProfileJobtypeName').innerHTML = 'This field contains special chars';
		document.frmJobtype.ProfileJobtypeName.focus();
		return false;
	}else{
		document.getElementById('lblProfileJobtypeName').innerHTML = '';
	}
}
function validate_editJobtype(){
	if(document.frmJobtype.ProfileJobtypeName.value == ''){
		document.getElementById('lblProfileJobtypeName').innerHTML = 'This field is required';
		document.frmJobtype.ProfileJobtypeName.focus();
		return false;
	}else{
		document.getElementById('lblProfileJobtypeName').innerHTML = '';
	}
	if(regex.test(document.frmJobtype.ProfileJobtypeName.value)){
		document.getElementById('lblProfileJobtypeName').innerHTML = 'This field contains special chars';
		document.frmJobtype.ProfileJobtypeName.focus();
		return false;
	}else{
		document.getElementById('lblProfileJobtypeName').innerHTML = '';
	}
}
//function for validate assigned Technician
function validate_techAssign(){
	if(document.frmtechAssign.SrchTechnician.value == ''){
		document.getElementById('lblSrchTechnician').innerHTML = 'This field is required';
		document.frmtechAssign.SrchTechnician.focus();
		return false;
	}else{
		document.getElementById('lblSrchTechnician').innerHTML = '';
	}
	/*if(document.frmtechAssign.StartDate.value==''){
		document.getElementById('lblStartDate').innerHTML='This field is required';
		<!--document.frmtechAssign.StartDate.focus();-->
		return false;
	}else{
		document.getElementById('lblStartDate').innerHTML='';
	}
	if(document.frmtechAssign.StartTime.value==''){
		document.getElementById('lblStartTime').innerHTML='This field is required';
		<!--document.frmtechAssign.StartTime.focus();-->
		return false;
	}else{
		document.getElementById('lblStartTime').innerHTML='';
	}*/
	var chktechs=$('input:checkbox[name=chkTech[]]:checked').length;
	if(chktechs==0){
		document.getElementById('lblAssign').innerHTML='Please select at least one checkbox';
		return false;
	}else{
		document.getElementById('lblAssign').innerHTML='';
	}
}
//Function for Module
function validate_editModule(){
	if(document.frmModule.daysno.value == ''){
		document.getElementById('lbldaysno').innerHTML = 'This field is required';
		document.frmModule.daysno.focus();
		return false;
	}else{
		document.getElementById('lbldaysno').innerHTML = '';
	}
}
//for Assignment Days
function validate_editDays(){
	if(document.frmModule.daysno.value == ''){
		document.getElementById('lbldaysno').innerHTML = 'This field is required';
		document.frmModule.daysno.focus();
		return false;
	}else{
		document.getElementById('lbldaysno').innerHTML = '';
	}
}
//for Assignment Days
function validate_editAuthorize(){
	if(document.frmAuthorize.api_logid.value == ''){
		document.getElementById('lblapi_logid').innerHTML = 'This field is required';
		document.frmAuthorize.api_logid.focus();
		return false;
	}else{
		document.getElementById('lblapi_logid').innerHTML = '';
	}
	if(document.frmAuthorize.tran_key.value == ''){
		document.getElementById('lbltran_key').innerHTML = 'This field is required';
		document.frmAuthorize.tran_key.focus();
		return false;
	}else{
		document.getElementById('lbltran_key').innerHTML = '';
	}
	var chk=$('input:radio[name=test[]]:checked').length;
	if(chk == 0){
		document.getElementById('lbltest').innerHTML = 'This field is required';
		return false;
	}else{
		document.getElementById('lbltest').innerHTML = '';
	}
}
//function for validate Technician Job Status
function validate_techStatus(){
		if(document.frmTechStatus.cmbStatus.value == ''){
			document.getElementById('lblcmbStatus').innerHTML = 'This field is required';
			document.frmTechStatus.cmbStatus.focus();
			return false;
		}else{
			document.getElementById('lblcmbStatus').innerHTML = '';
		}
		if(document.frmTechStatus.ArrivalDate.value == ''){
			document.getElementById('lblArrivalDate').innerHTML = 'This field is required';
			//document.frmTechStatus.ArrivalDate.focus();
			return false;
		}else{
			document.getElementById('lblArrivalDate').innerHTML = '';
		}
		if(document.frmTechStatus.ArrivalTime.value == ''){
			document.getElementById('lblArrivalTime').innerHTML = 'This field is required';
			//document.frmTechStatus.ArrivalTime.focus();
			return false;
		}else{
			document.getElementById('lblArrivalTime').innerHTML = '';
		}
		if(document.frmTechStatus.DepartTime.value == ''){
			document.getElementById('lblDepartTime').innerHTML = 'This field is required';
			//document.frmTechStatus.DepartTime.focus();
			return false;
		}else{
			document.getElementById('lblDepartTime').innerHTML = '';
		}
		if(document.frmTechStatus.techNotes.value == ''){
			document.getElementById('lbltechNotes').innerHTML = 'This field is required';
			document.frmTechStatus.techNotes.focus();
			return false;
		}else{
			document.getElementById('lbltechNotes').innerHTML = '';
		}
		if(regex.test(document.frmTechStatus.techNotes.value)){
			document.getElementById('lbltechNotes').innerHTML = 'This field contains special chars';
			document.frmTechStatus.techNotes.focus();
			return false;
		}else{
			document.getElementById('lbltechNotes').innerHTML = '';
		}
}
function validate_State(){
		if(document.frmState.ProfileStateCode.value == ''){
			document.getElementById('lblProfileStateCode').innerHTML = 'This field is required';
			document.frmState.ProfileStateCode.focus();
			return false;
		}else{
			document.getElementById('lblProfileStateCode').innerHTML = '';
		}
		if(regex.test(document.frmState.ProfileStateCode.value)){
			document.getElementById('lblProfileStateCode').innerHTML = 'This field contains special chars';
			document.frmState.ProfileStateCode.focus();
			return false;
		}else{
			document.getElementById('lblProfileStateCode').innerHTML = '';
		}
		if(document.frmState.ProfileStateName.value == ''){
			document.getElementById('lblProfileStateName').innerHTML = 'This field is required';
			document.frmState.ProfileStateName.focus();
			return false;
		}else{
			document.getElementById('lblProfileStateName').innerHTML = '';
		}
		if(regex.test(document.frmState.ProfileStateName.value)){
			document.getElementById('lblProfileStateName').innerHTML = 'This field contains special chars';
			document.frmState.ProfileStateName.focus();
			return false;
		}else{
			document.getElementById('lblProfileStateName').innerHTML = '';
		}
}
//for payment receive validation
function validate_receive(){
	if(document.frmReceive.ClientName.value == ''){
		document.getElementById('lblClientName').innerHTML = 'This field is required';
		document.frmReceive.ClientName.focus();
		return false;
	}else{
		document.getElementById('lblClientName').innerHTML = '';
	}
	if(document.frmReceive.ChequeNo.value == ''){
		document.getElementById('lblChequeNo').innerHTML = 'This field is required';
		document.frmReceive.ChequeNo.focus();
		return false;
	}else{
		document.getElementById('lblChequeNo').innerHTML = '';
	}
	if(regex.test(document.frmReceive.ChequeNo.value)){
		document.getElementById('lblChequeNo').innerHTML = 'This field contains special chars';
		document.frmReceive.ChequeNo.focus();
		return false;
	}else{
		document.getElementById('lblChequeNo').innerHTML = '';
	}
	if(document.frmReceive.ReceiveDate.value == ''){
		document.getElementById('lblReceiveDate').innerHTML = 'This field is required';
		document.frmReceive.ReceiveDate.focus();
		return false;
	}else{
		document.getElementById('lblReceiveDate').innerHTML = '';
	}
	if(document.frmReceive.ChequeAmount.value == ''){
		document.getElementById('lblChequeAmount').innerHTML = 'This field is required';
		document.frmReceive.ChequeAmount.focus();
		return false;
	}else{
		document.getElementById('lblChequeAmount').innerHTML = '';
	}
	if(regex.test(document.frmReceive.ChequeAmount.value)){
		document.getElementById('lblChequeAmount').innerHTML = 'This field contains special chars';
		document.frmReceive.ChequeAmount.focus();
		return false;
	}else{
		document.getElementById('lblChequeAmount').innerHTML = '';
	}
	if(document.frmReceive.BankName.value == ''){
		document.getElementById('lblBankName').innerHTML = 'This field is required';
		document.frmReceive.BankName.focus();
		return false;
	}else{
		document.getElementById('lblBankName').innerHTML = '';
	}
	if(regex.test(document.frmReceive.BankName.value)){
		document.getElementById('lblBankName').innerHTML = 'This field contains special chars';
		document.frmReceive.BankName.focus();
		return false;
	}else{
		document.getElementById('lblBankName').innerHTML = '';
	}
	if(document.frmReceive.BankAddress.value == ''){
		document.getElementById('lblBankAddress').innerHTML = 'This field is required';
		document.frmReceive.BankAddress.focus();
		return false;
	}else{
		document.getElementById('lblBankAddress').innerHTML = '';
	}
	if(regex.test(document.frmReceive.BankAddress.value)){
		document.getElementById('lblBankAddress').innerHTML = 'This field contains special chars';
		document.frmReceive.BankAddress.focus();
		return false;
	}else{
		document.getElementById('lblBankAddress').innerHTML = '';
	}
}
//for service price validation
function validate_service_price(){
	if(document.frmPrice.ServiceName.value == ''){
		document.getElementById('lblServiceName').innerHTML = 'This field is required';
		document.frmPrice.ServiceName.focus();
		return false;
	}else{
		document.getElementById('lblServiceName').innerHTML = '';
	}
	if(document.frmPrice.EquipmentName.value == ''){
		document.getElementById('lblEquipmentName').innerHTML = 'This field is required';
		document.frmPrice.EquipmentName.focus();
		return false;
	}else{
		document.getElementById('lblEquipmentName').innerHTML = '';
	}
	if(regex.test(document.frmPrice.EquipmentName.value)){
		document.getElementById('lblEquipmentName').innerHTML = 'This field contains special chars';
		document.frmPrice.EquipmentName.focus();
		return false;
	}else{
		document.getElementById('lblEquipmentName').innerHTML = '';
	}
	var count = document.getElementById('count').value;
	//alert(count);
   	for(var i=1; i <=count; i++){
		if(document.getElementById('OutBoxPrice'+i).value == ''){
			document.getElementById('lblOutBoxPrice'+i).innerHTML = 'This field is required';
			document.getElementById('OutBoxPrice'+i).focus();
			return false;
		}else{
			document.getElementById('lblOutBoxPrice'+i).innerHTML = '';
		}
		if(document.getElementById('PayGradeA'+i).value == ''){
			document.getElementById('lblPayGradeA'+i).innerHTML = 'This field is required';
			document.getElementById('PayGradeA'+i).focus();
			return false;
		}else{
			document.getElementById('lblPayGradeA'+i).innerHTML = '';
		}
		if(document.getElementById('PayGradeB'+i).value == ''){
			document.getElementById('lblPayGradeB'+i).innerHTML = 'This field is required';
			document.getElementById('PayGradeB'+i).focus();
			return false;
		}else{
			document.getElementById('lblPayGradeB'+i).innerHTML = '';
		}
		if(document.getElementById('PayGradeC'+i).value == ''){
			document.getElementById('lblPayGradeC'+i).innerHTML = 'This field is required';
			document.getElementById('PayGradeC'+i).focus();
			return false;
		}else{
			document.getElementById('lblPayGradeC'+i).innerHTML = '';
		}
		if(document.getElementById('PayGradeD'+i).value == ''){
			document.getElementById('lblPayGradeD'+i).innerHTML = 'This field is required';
			document.getElementById('PayGradeD'+i).focus();
			return false;
		}else{
			document.getElementById('lblPayGradeD'+i).innerHTML = '';
		}
	}
}
//function for work validation
function validate_worktype(){
	if(document.frmWorktype.WorkTypeName.value == ''){
		document.getElementById('lblWorkTypeName').innerHTML = 'This field is required';
		document.frmWorktype.WorkTypeName.focus();
		return false;
	}else{
		document.getElementById('lblWorkTypeName').innerHTML = '';
	}
	if(regex.test(document.frmWorktype.WorkTypeName.value)){
		document.getElementById('lblWorkTypeName').innerHTML = 'This field contains special chars';
		document.frmWorktype.WorkTypeName.focus();
		return false;
	}else{
		document.getElementById('lblWorkTypeName').innerHTML = '';
	}
}
//function for Equipment
function validate_equipment(){
	if(document.frmEquipment.serviceName.value == ''){
		document.getElementById('lblServiceName').innerHTML = 'This field is required';
		document.frmEquipment.serviceName.focus();
		return false;
	}else{
		document.getElementById('lblServiceName').innerHTML = '';
	}
	if(regex.test(document.frmEquipment.serviceName.value)){
		document.getElementById('lblServiceName').innerHTML = 'This field contains special chars';
		document.frmEquipment.serviceName.focus();
		return false;
	}else{
		document.getElementById('lblServiceName').innerHTML = '';
	}
	if(document.frmEquipment.equipmentName.value == ''){
		document.getElementById('lblequipmentName').innerHTML = 'This field is required';
		document.frmEquipment.equipmentName.focus();
		return false;
	}else{
		document.getElementById('lblequipmentName').innerHTML = '';
	}
	if(regex.test(document.frmEquipment.equipmentName.value)){
		document.getElementById('lblequipmentName').innerHTML = 'This field contains special chars';
		document.frmEquipment.equipmentName.focus();
		return false;
	}else{
		document.getElementById('lblequipmentName').innerHTML = '';
	}
}
//function for Equipment
function validate_checkout(){
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;	
	var dt = document.getElementById('ExpYear').value;
	var dm = document.getElementById('ExpMonth').value;
	var d = new Date();
	var curr_year = d.getFullYear();
	var curr_month = d.getMonth()+1;
	
	if(document.frmCkeckout.Amount.value == ''){
		document.getElementById('lblAmount').innerHTML = 'This field is required';
		document.frmCkeckout.Amount.focus();
		return false;
	}else{
		document.getElementById('lblAmount').innerHTML = '';
	}
	if(regex.test(document.frmCkeckout.Amount.value)){
		document.getElementById('lblAmount').innerHTML = 'This field contains special chars';
		document.frmCkeckout.Amount.focus();
		return false;
	}else{
		document.getElementById('lblAmount').innerHTML = '';
	}
	if(document.frmCkeckout.CardNumber.value == ''){
		document.getElementById('lblCardNumber').innerHTML = 'This field is required';
		document.frmCkeckout.CardNumber.focus();
		return false;
	}else{
		document.getElementById('lblCardNumber').innerHTML = '';
	}
	if(regex.test(document.frmCkeckout.CardNumber.value)){
		document.getElementById('lblCardNumber').innerHTML = 'This field contains special chars';
		document.frmCkeckout.CardNumber.focus();
		return false;
	}else{
		document.getElementById('lblCardNumber').innerHTML = '';
	}
	if(document.frmCkeckout.ExpMonth.value == ''){
		document.getElementById('lblExpYear').innerHTML = 'This field is required';
		document.frmCkeckout.ExpMonth.focus();
		return false;
	}else{
		document.getElementById('lblExpYear').innerHTML = '';
	}
	if(document.frmCkeckout.ExpYear.value == ''){
		document.getElementById('lblExpYear').innerHTML = 'This field is required';
		document.frmCkeckout.ExpYear.focus();
		return false;
	}else{
		document.getElementById('lblExpYear').innerHTML = '';
	}
	if((dm < curr_month && dt == curr_year) || (dt < curr_year)){
		
		document.getElementById('lblExpYear').innerHTML = "Invalid expiration month and year";
	   	document.frmCkeckout.ExpYear.focus();
		return false; 
	}else{
		document.getElementById('lblExpYear').innerHTML = '';
	}
	if(document.frmCkeckout.ClientName.value == ''){
		document.getElementById('lblClientName').innerHTML = 'This field is required';
		document.frmCkeckout.ClientName.focus();
		return false;
	}else{
		document.getElementById('lblClientName').innerHTML = '';
	}
	if(regex.test(document.frmCkeckout.ClientName.value)){
		document.getElementById('lblClientName').innerHTML = 'This field contains special chars';
		document.frmCkeckout.ClientName.focus();
		return false;
	}else{
		document.getElementById('lblClientName').innerHTML = '';
	}
	if(document.frmCkeckout.ClientEmail.value == ''){
		document.getElementById('lblClientEmail').innerHTML = 'This field is required';
		document.frmCkeckout.ClientEmail.focus();
		return false;
	}else{
		document.getElementById('lblClientEmail').innerHTML = '';
	}
	if(!document.frmCkeckout.ClientEmail.value.match(emailExp)){
		document.getElementById('lblClientEmail').innerHTML = "Required Valid Email ID.";
		document.frmCkeckout.ClientEmail.focus();
		return false;
	}
	else{
		document.getElementById('lblEmailLogin').innerHTML = '';
	}
	if(document.frmCkeckout.ClientAddress.value == ''){
		document.getElementById('lblClientAddress').innerHTML = 'This field is required';
		document.frmCkeckout.ClientAddress.focus();
		return false;
	}else{
		document.getElementById('lblClientAddress').innerHTML = '';
	}
	if(regex.test(document.frmCkeckout.ClientAddress.value)){
		document.getElementById('lblClientAddress').innerHTML = 'This field contains special chars';
		document.frmCkeckout.ClientAddress.focus();
		return false;
	}else{
		document.getElementById('lblClientAddress').innerHTML = '';
	}
	if(document.frmCkeckout.ClientState.value == ''){
		document.getElementById('lblClientState').innerHTML = 'This field is required';
		document.frmCkeckout.ClientState.focus();
		return false;
	}else{
		document.getElementById('lblClientState').innerHTML = '';
	}
	if(document.frmCkeckout.ClientZip.value == ''){
		document.getElementById('lblClientZip').innerHTML = 'This field is required';
		document.frmCkeckout.ClientZip.focus();
		return false;
	}else{
		document.getElementById('lblClientZip').innerHTML = '';
	}
	if(regex.test(document.frmCkeckout.ClientZip.value)){
		document.getElementById('lblClientZip').innerHTML = 'This field contains special chars';
		document.frmCkeckout.ClientZip.focus();
		return false;
	}else{
		document.getElementById('lblClientZip').innerHTML = '';
	}
	
}
//function for only numbers
function onlyNumbers(e){
		var browser = navigator.appName;
		
		if(browser == "Netscape"){
			var keycode = e.which;		
			if((keycode >=48 && keycode <=57) || keycode == 13 || keycode == 8 || keycode == 0)
				return true;
			else
				return false;
		}else{
			if((e.keyCode >=48 && e.keyCode<=57) || e.keycode == 13 || e.keycode == 8 || e.keycode == 0)
				e.returnValue=true;
			else
				e.returnValue=false;		
		}		
	}
//validate phone number
function validatephone(xxxxx) {
	 var maintainplus = '';
 	var numval = xxxxx.value
 	if ( numval.charAt(0)=='+' ){ var maintainplus = '+';}
 	curphonevar = numval.replace(/[\\A-Za-z!"£$%^&*+_={};:'@#~,.¦\/<>?|`¬\]\[]/g,'');
 	xxxxx.value = maintainplus + curphonevar;
 	var maintainplus = '';
 	xxxxx.focus;
}
//function to validate amount field
function extractNumber(obj, decimalPlaces, allowNegative){
	var temp = obj.value;
	
	// avoid changing things if already formatted correctly
	var reg0Str = '[0-9]*';
	if (decimalPlaces > 0) {
		reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';
	} else if (decimalPlaces < 0) {
		reg0Str += '\\.?[0-9]*';
	}
	reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
	reg0Str = reg0Str + '$';
	var reg0 = new RegExp(reg0Str);
	if (reg0.test(temp)) return true;

	// first replace all non numbers
	var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
	var reg1 = new RegExp(reg1Str, 'g');
	temp = temp.replace(reg1, '');

	if (allowNegative) {
		// replace extra negative
		var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
		var reg2 = /-/g;
		temp = temp.replace(reg2, '');
		if (hasNegative) temp = '-' + temp;
	}
	
	if (decimalPlaces != 0) {
		var reg3 = /\./g;
		var reg3Array = reg3.exec(temp);
		if (reg3Array != null) {
			// keep only first occurrence of .
			//  and the number of places specified by decimalPlaces or the entire string if decimalPlaces < 0
			var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
			reg3Right = reg3Right.replace(reg3, '');
			reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
			temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
		}
	}
	
	obj.value = temp;
}
