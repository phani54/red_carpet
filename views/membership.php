<?php ob_start(); error_reporting(0);
require "lib/config.php";
require "lib/secure.php";
$prow = $seth->fetch();
$dob = explode('-',$prow['dob']);
if($prow['gender'] == 'male'){
	$gender_photo = 'male_large.jpg';
} else if($prow['gender'] == 'female'){
	$gender_photo = 'female_large.jpg';
}
function cm2feet($cm)	
{
     $inches = $cm/2.54;
     $feet = intval($inches/12);
     $inches = $inches%12;
     return sprintf('%d.%d"', $feet, $inches);
}

if(isset($_POST) && $_POST['action'] === 'profile_update') {
	//echo print_r($_POST); exit;
	stripcslashes($_POST);
	if(empty($_POST['p_password'])){
		$dob = $_POST['p_yyyy'].'-'.$_POST['p_mm'].'-'.$_POST['p_dd'];
		$from = new DateTime($dob);
		$to = new DateTime('today');
		$age = $from->diff($to)->y;
		$script = "`dob`='".$dob."', `age`= '".$age."', ";
		$filename1 = stripslashes($_FILES['p_image_1']['name']);
		$filename2 = stripslashes($_FILES['p_image_2']['name']);
		if(!empty($filename1)) {
			$image = time()."_".$filename1;
			$img_path="profile_images/".$image;
			if(move_uploaded_file($_FILES['p_image_1']['tmp_name'], $img_path)){
				$script .= "`image_1`='".$image."', ";
				if(file_exists("profile_images/".$prow['image_1'])){
					unlink("profile_images/".$prow['image_1']);
				}
			}
		}
		if(!empty($filename2)) {
			$image = time()."_".$filename2;
			$img_path="profile_images/".$image;
			if(move_uploaded_file($_FILES['p_image_2']['tmp_name'], $img_path)){
				$script .= "`image_2`='".$image."', ";
				if(file_exists("profile_images/".$prow['image_2'])){
					unlink("profile_images/".$prow['image_2']);
				}
			}
		}
		
		unset($_POST['action'], $_POST['p_dd'], $_POST['p_mm'], $_POST['p_yyyy'], $_POST['p_password'], $_POST['p_newpassword']);
		
		foreach($_POST as $key => $value){
			if(!empty($value)){
				$script .= "`".substr($key, 2)."`='".$value."', ";
			}
		}
		$updateQuery = "UPDATE `register` SET ".substr($script,0,-2)." WHERE `user_id`='".$_SESSION['Rcm_auth_session_userid']."'";
		$uth = $db->query($updateQuery);
		if($uth > 0) {
			echo '<script>alert("Profile updated"); window.location.href = "'.URL.'membership/";</script>';
		} else {
			echo '<script>alert("Profile not updated"); window.location.href = "'.URL.'profile/";</script>';
		}
	} else {
		$txtOldPassword = md5(strip_tags($_POST['p_password']));
		$txtPassword = md5(strip_tags($_POST['p_newpassword']));
		if($txtPassword != $txtOldPassword){
			if($txtOldPassword==$prow['password']){
				$sth = $db->query("UPDATE `register` SET `password`='$txtPassword' WHERE `user_id`='".$_SESSION['Rcm_auth_session_userid']."'");
				if($sth > 0){
					echo '<script>alert("Password updated successfully"); window.location.href = "'.URL.'profile/";</script>';
				}
			} else {
				echo '<script>alert("Invalid current password!."); window.location.href = "'.URL.'profile/";</script>';
			}
		} else {
			echo '<script>alert("New Password must different to Current Password"); window.location.href = "'.URL.'profile/";</script>';
		}
	}
}
?>
<!DOCTYPE html>
<html class="no-js" prefix="ng: https://angularjs.org">
<!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="fragment" content="!">
    <title>Redcarpet Matrimony</title>
    <meta name="description" content="Red Carpet Matrimony" />
    <meta name="keywords" content="Red Carpet Matrimony" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,500,500italic,700,700italic,300italic,300' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link rel="icon" href="<?php echo IURL ?>assets/images/icons/favicon_medium.png" type="image/png">
    <link rel="stylesheet" href="<?php echo IURL ?>assets/css/bootstrap/css/bootstrap.min7b30.css" media="screen" type="text/css" />
    <link rel="stylesheet" href="<?php echo IURL ?>assets/css/style2dac.css?v=21" type="text/css" />
    <link rel="stylesheet" href="<?php echo IURL ?>assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo IURL ?>assets/css/jquery.mCustomScrollbar.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo IURL ?>css/side-menu.css" type="text/css" />
    <style>
        .errorCls {
            color: red;
            font: 11px Arial
        }
    </style>
</head>

<body>

    <!--Header Start-->
    <?php require('pages/header.php'); ?>
        <!--Header End-->

        <img src="<?php echo IURL ?>images/banner-inner.jpg" class="img-responsive">

        <br>
        <br>
        <section class="main-wrapper container innerpage-content">
            <article>
                <div class="">
                    <div class="col-sm-12 no-padd">
                        <div class="col-sm-12 gap no-padd">
                            <div class="col-sm-3">
                                <div id="cssmenu1">
                                    <ul>
                                        <li class="active"><a href="#"><span>My Dashboard</span></a></li>
                                        <li class=""><a href="recent-visitors.html"><span>My Profile </span></a></li>
                                        <li class=""><a href="recent-visitors.html"><span>Interests Sent (0) </span></a></li>
                                        <li class=""><a href="recent-visitors.html"><span>Interests Received (0) </span></a></li>
                                        <li class=""><a href="recent-visitors.html"><span>Recent Visitors (0) </span></a></li>
                                        <li class=""><a href="recent-visitors.html"><span>My Favourite Profiles (1) </span></a></li>
                                        <li class=""><a href="matched.html"><span>Matched Profiles</span></a></li>
                                        <li class="last"><a href="#"><span>Contact</span></a></li>
                                    </ul>
                                </div>
                                <br>
                                <img src="<?php echo IURL ?>images/ad.jpg" class="img-responsive">

                                <br>

                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Latest Profiles </h3>
                                    </div>
                                    <div class="panel-body">
                                        <!--div class="row recent">
                                            <div class="col-sm-5 no-padd"><img src="<?php echo IURL ?>assets/images/bride_1.jpg" class="img-responsive"></div>
                                            <div class="col-sm-7"><b>Sri Latha</b>
                                                <br>ID: RC12345
                                                <br>Hyderabad
                                                <br><a href=""><small><b>View Profile</b></small></a></div>
                                        </div-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-9">
							<h2 align="center">PACKAGES <br><small>CHOOSE YOUR MEMBERSHIP PLAN</small></h2>
							<h2 align="center"><small>CALL FOR ANY HELP IN MAKING PAYMENT. ALL PRICES INCLUSIVE OF 18% SERVICE TEX</small></h2>
							<div class="col-md-4 col-md-offset-1">
								<div class="dash-box dash-box-color-1">
									<div class="dash-box-icon">
										<i class="fa fa-user"></i>
									</div>
									<div class="dash-box-body">
										<span class="dash-box-count"><i class="fa fa-inr"></i> 2000</span>
										<span class="dash-box-count">DeLIGHT</span><br>
										<span class="dash-box-title">Contact 30 Profiles</span><br><hr>
										<span class="dash-box-title">Unlimited Views</span><br><hr>
										<span class="dash-box-title">3 Months</span><br><hr>
										<span class="dash-box-title">Send Unlimited personal messages to your matches</span><br><hr>
										<span class="dash-box-title">View and Contact 60 verified Mobile numbers & Send 50 SMS</span><br><hr>
										<span class="dash-box-title">3 months Profile Highlighter FREE (Get noticed easily)</span><br><hr>
										<span class="dash-box-title">View Photos of all matches</span><br><hr>
										<span class="dash-box-title">High profile visibility</span><br><hr>
										<span class="dash-box-title">Chat with Prospects Directly</span><br><hr><br><br><br>
										<span class="dash-box-count"><i class="fa fa-inr"></i> 2000</span>
									</div>
									
									<div class="dash-box-action">
										<button>Select</button>
									</div>				
								</div>
							</div>
							<div class="col-md-4 col-md-offset-2	">
								<div class="dash-box dash-box-color-2">
									<div class="dash-box-icon">
										<i class="fa fa-user"></i>
									</div>
									<div class="dash-box-body">
										<span class="dash-box-count"><i class="fa fa-inr"></i> 3500</span>
										<span class="dash-box-count">EnRICH</span><br>
										<span class="dash-box-title">Contact 70 Profiles</span><br><hr>
										<span class="dash-box-title">Unlimited Views</span><br><hr>
										<span class="dash-box-title">6 Months</span><br><hr>
										<span class="dash-box-title">Send Unlimited personal messages to your matches</span><br><hr>
										<span class="dash-box-title">View and Contact 120 verified Mobile numbers & Send 100 SMS</span><br><hr>
										<span class="dash-box-title">6 months Profile Highlighter FREE (Get noticed easily)</span><br><hr>
										<span class="dash-box-title">View Photos of all matches</span><br><hr>
										<span class="dash-box-title">Enhanced privacy settings</span><br><hr>
										<span class="dash-box-title">High profile visibility</span><br><hr>
										<span class="dash-box-title">Chat with Prospects Directly</span><br><hr>
										<span class="dash-box-count"><i class="fa fa-inr"></i> 3500</span>
									</div>
									
									<div class="dash-box-action">
										<button>Select</button>
									</div>				
								</div>
							</div>
							
							<div class="clearfix"></div>
							
							<div class="panel panel-warning">
								<div class="panel-heading">
									<h3 class="panel-title">You Have Selected</h3>
								</div>
								<div class="panel-body">
									<div class="col-sm-12">
										<p>Membership: DeLIGHT - 3 Months - <i class="fa fa-inr"></i> 3000</p>
									</div>
								</div>
							</div>
							
							<div class="clearfix"></div>
							
							<div class="panel panel-warning">
								<div class="panel-heading">
									<h3 class="panel-title">Select Payment Options</h3>
								</div>
								<div class="panel-body">
									<div class="col-sm-12">
										<div class='payments'>
											<div class='button active'>
											  Cash

											  <svg class="svg-cash" viewBox="0 0 512 512">
												<path class="svg-cash-hand" d="M106.908,147h56.33h96.607c22.139,0,31.855-0.384,37.188-0.055c5.463,0.334,10.715,2.463,17.723,7.55 c7.719,5.749,21.205,9.042,32.086,20.779c10.906,11.666,32.221,35.637,37.277,42.137c5.01,6.46,32.637,72.549-38.697,77.367 c-71.357,4.78-125.639-8.428-137.912-8.914c-12.289-0.485-31.773-5.975-51.424-10.815c-38.977-9.601-36.828-17.492-57.422-19.182 c-20.619-1.691-29.092-3.55-29.092-3.55L79.035,147H106.908z" />
												<path class="svg-cash-money" d="M426.537,100.903c0,0-49.463,56.124-93.143,92.677c-67.695,56.555-160.115,96.963-160.115,96.963 s36.949,80.367,46.809,121.462c53.283-30.399,150.305-93.938,189.299-124.44c35.197-27.506,95.736-99.566,95.736-99.566 L426.537,100.903z" />
												<path class="svg-cash-money-inner" d="M297.721,266.825c0,22.905,18.574,41.479,41.48,41.479 c22.908,0,41.482-18.574,41.482-41.479c0-22.919-18.574-41.481-41.482-41.481C316.295,225.344,297.721,243.906,297.721,266.825" />
												<path class="svg-cash-money-inner" d="M427.875,171.335c7.904,10.497,21.02,15.349,29.307,10.849 c8.346-4.563,8.65-16.735,0.715-27.284c-7.924-10.507-21.049-15.327-29.318-10.825 C420.305,148.616,419.969,160.839,427.875,171.335" />
												<path class="svg-cash-money-inner" d="M218.041,334.839c6.117,11.018,19.02,17.316,28.619,14.096 c9.697-3.181,12.477-14.663,6.301-25.642c-6.143-11.059-19.066-17.317-28.656-14.179 C214.619,312.355,211.881,323.83,218.041,334.839" />
												<path class="svg-cash-hand" d="M258.408,177.271c0,0,11.541,20.897,27.688,23.333c16.107,2.376,47.537,13.668,55.719,14.892 c8.143,1.148,27.1,5.382,27.1,21.029c0,8.212-5.717,34.513-27.863,34.513c-24.717,0-52.084-17.135-60.707-20.498 c-8.588-3.383-34.088-12.718-45.455-9.574c-11.262,3.09-35.111,2.374-50.895,0.941c-15.918-1.473-46.842-9.115-57.725-17.059 c0,0-18.08-9.965,20.4-28.777C185.225,177.282,242.934,162.21,258.408,177.271" />
												<path class="svg-cash-thumb" d="M258.408,177.271c0,0,11.541,20.897,27.688,23.333 c16.107,2.376,47.537,13.668,55.719,14.892c8.143,1.148,27.1,5.382,27.1,21.029c0,8.212-5.717,34.513-27.863,34.513 c-25.951,0-52.084-17.135-60.707-20.498c-8.588-3.383-34.088-12.718-45.455-9.574c-11.262,3.09-35.111,2.374-50.895,0.941 c-15.918-1.473-46.842-9.115-57.725-17.059c0,0-18.08-9.965,20.4-28.777C185.225,177.282,242.934,162.21,258.408,177.271z" />
												<path class="svg-cash-hand" d="M265.518,168.38l-16.447,17.848c0,0-48.854,11.748-74.717,19.733c-25.797,7.985-40.836,11.747-40.836,11.747 s-8.441,16.883-14.053,19.195c-5.65,2.386-20.699-8.41-10.391-31.398c10.391-23.032,20.254-46.032,77.559-47.435 c57.232-1.419,73.191-7.571,78.396,0.425C270.217,166.511,265.518,168.38,265.518,168.38" />
												<path class="svg-cash-shirt-inner" d="M57.346,142c-29.242,42-29.242,135-29.242,135H90V142H57.346z" />
												<path class="svg-cash-shirt-outer" d="M7,122v165.564c16,8.649,63,10.37,63,10.37V121.644c0,0-45,0.484-63,0" />
											  </svg>
											</div>
											<div class='button'>
											  Debit

											  <svg class="svg-debit" viewBox="0 0 32 23">
												<path class="svg-debit-card" d="M1.993 0h28c1.104 0 2 .895 2 2v18.999c0 1.104-.896 2.001-2 2.001h-28c-1.104 0-2-.896-2-2.001v-18.999c0-1.105.895-2 2-2z" />
												<path class="svg-debit-data" d="M12.993 15v2h16v-2h-16zm0 5h10v-2h-10v2zm-4-5h-4c-1.104 0-2 .896-2 2v1c0 1.104.896 2 2 2h4c1.104 0 2-.896 2-2v-1c0-1.104-.896-2-2-2z" />
												<path class="svg-debit-sign" d="M2.993 9h26v3h-26v-3z" />
												<path class="svg-debit-read" d="M-.007 3h32v3h-32v-3z" />
											  </svg>
											</div>
											<div class='button'>
											  Credit Card

											  <svg class="svg-visa" viewBox="0 0 512 512">
												<path class="svg-visa-border" d="M482.722,103.198c13.854,0,25.126,11.271,25.126,25.126v257.9c0,13.854-11.271,25.126-25.126,25.126H30.99 c-13.854,0-25.126-11.271-25.126-25.126v-257.9c0-13.854,11.271-25.126,25.126-25.126H482.722 M482.722,98.198H30.99 c-16.638,0-30.126,13.488-30.126,30.126v257.9c0,16.639,13.488,30.126,30.126,30.126h451.732 c16.639,0,30.126-13.487,30.126-30.126v-257.9C512.848,111.686,499.36,98.198,482.722,98.198L482.722,98.198z" />
												<polygon class="svg-visa-letter" points="190.88,321.104 212.529,194.022 247.182,194.022 225.494,321.104 190.88,321.104" />
												<path class="svg-visa-letter" d="M351.141,197.152c-6.86-2.577-17.617-5.339-31.049-5.339c-34.226,0-58.336,17.234-58.549,41.94 c-0.193,18.256,17.21,28.451,30.351,34.527c13.489,6.231,18.023,10.204,17.966,15.767c-0.097,8.518-10.775,12.403-20.737,12.403 c-13.857,0-21.222-1.918-32.599-6.667l-4.458-2.016l-4.864,28.452c8.082,3.546,23.043,6.618,38.587,6.772 c36.417,0,60.042-17.035,60.313-43.423c0.136-14.447-9.089-25.446-29.071-34.522c-12.113-5.882-19.535-9.802-19.458-15.757 c0-5.281,6.279-10.93,19.846-10.93c11.318-0.179,19.536,2.292,25.912,4.869l3.121,1.468L351.141,197.152L351.141,197.152z" />
												<path class="svg-visa-letter" d="M439.964,194.144h-26.766c-8.295,0-14.496,2.262-18.14,10.538l-51.438,116.47h36.378 c0,0,5.931-15.66,7.287-19.1c3.974,0,39.305,0.059,44.363,0.059c1.027,4.447,4.206,19.041,4.206,19.041h32.152L439.964,194.144 L439.964,194.144z M397.248,276.062c2.868-7.326,13.8-35.53,13.8-35.53c-0.194,0.339,2.849-7.36,4.593-12.132l2.346,10.959 c0,0,6.628,30.336,8.022,36.703H397.248L397.248,276.062z" />
												<path class="svg-visa-letter" d="M161.828,194.114l-33.917,86.667l-3.624-17.607c-6.299-20.312-25.971-42.309-47.968-53.317l31.009,111.149 l36.649-0.048l54.538-126.844H161.828L161.828,194.114z" />
												<path class="svg-visa-corner" d="M96.456,194.037H40.581l-0.426,2.641c43.452,10.523,72.213,35.946,84.133,66.496l-12.133-58.41 C110.062,196.716,103.976,194.318,96.456,194.037L96.456,194.037z" />
											  </svg>

											  <svg class="svg-master" viewBox="0 0 512 512">
												<path class="svg-master-border" d="M482.722,103.198c13.854,0,25.126,11.271,25.126,25.126v257.9c0,13.854-11.271,25.126-25.126,25.126H30.99 c-13.854,0-25.126-11.271-25.126-25.126v-257.9c0-13.854,11.271-25.126,25.126-25.126H482.722 M482.722,98.198H30.99 c-16.638,0-30.126,13.488-30.126,30.126v257.9c0,16.639,13.488,30.126,30.126,30.126h451.732 c16.639,0,30.126-13.487,30.126-30.126v-257.9C512.848,111.686,499.36,98.198,482.722,98.198L482.722,98.198z" />
												<path class="svg-master-circle2" d="M257.568,355.172c22.646,20.867,53.061,33.522,86.14,33.522 c71.037,0,128.538-57.941,128.538-129.207c0-71.482-57.501-129.424-128.538-129.424c-33.079,0-63.493,12.653-86.14,33.522 c-25.972,23.752-42.401,57.943-42.401,95.902C215.167,297.45,231.597,331.642,257.568,355.172L257.568,355.172z" />
												<path class="svg-master-circle1" d="M299.086,245.725c-0.444-4.662-1.331-9.102-2.223-13.764h-78.586 c0.888-4.662,2.217-9.103,3.549-13.543h71.266c-1.558-4.659-3.333-9.323-5.332-13.763h-60.382 c2.22-4.659,4.661-9.323,7.326-13.763h45.51c-2.887-4.662-6.215-9.325-9.769-13.542h-25.975 c3.996-4.883,8.438-9.545,13.097-13.763c-22.863-20.647-53.057-33.522-86.356-33.522c-70.817,0-128.538,57.942-128.538,129.424 c0,71.266,57.721,129.207,128.538,129.207c33.3,0,63.493-12.655,86.356-33.522l0,0l0,0c4.665-4.221,8.882-8.66,12.878-13.544 h-25.975c-3.552-4.439-6.66-8.879-9.767-13.763h45.51c2.885-4.439,5.327-8.879,7.546-13.764h-60.382 c-2.001-4.439-3.996-8.88-5.552-13.544h71.266c1.553-4.439,2.661-9.1,3.771-13.763c0.892-4.439,1.778-9.104,2.223-13.764 c0.443-4.44,0.666-8.879,0.666-13.544C299.752,254.828,299.529,250.165,299.086,245.725L299.086,245.725z" />
												<path class="svg-master-letter" d="M342.599,229.742l-2.443,14.207 c-4.886-2.441-8.438-3.552-12.434-3.552c-10.433,0-17.76,10.212-17.76,24.644c0,9.987,4.885,15.982,13.098,15.982 c3.33,0,7.326-1.106,11.766-3.332l-2.441,14.876c-5.106,1.332-8.436,2-12.209,2c-15.096,0-24.421-10.88-24.421-28.419 c0-23.309,12.877-39.735,31.302-39.735c2.441,0,4.662,0.222,6.44,0.666l5.549,1.332 C340.822,229.076,341.264,229.298,342.599,229.742L342.599,229.742z" />
												<path class="svg-master-letter" d="M297.755,239.509c-0.444,0-0.892,0-1.333,0 c-4.665,0-7.327,2.22-11.546,8.66l1.331-8.216h-12.651l-8.658,53.282h13.984c5.106-32.635,6.438-38.187,13.098-38.187 c0.443,0,0.443,0,0.888,0c1.332-6.436,3.108-11.1,5.33-15.318L297.755,239.509L297.755,239.509z" />
												<path class="svg-master-letter" d="M217.387,292.566c-3.771,1.332-6.878,1.775-9.987,1.775 c-7.105,0-11.102-3.995-11.102-11.762c0-1.332,0.222-3.113,0.444-4.664l0.889-5.328l0.665-4.221l5.997-36.406h13.763l-1.557,7.992 h7.104l-1.775,13.1h-7.104l-3.771,22.198c-0.224,0.889-0.224,1.552-0.224,2.221c0,2.664,1.332,3.776,4.664,3.776 c1.551,0,2.886,0,3.774-0.444L217.387,292.566L217.387,292.566z" />
												<path class="svg-master-letter" d="M163.887,256.824c0,6.66,3.107,11.323,10.433,14.876 c5.773,2.663,6.661,3.551,6.661,5.771c0,3.332-2.441,4.884-7.992,4.884c-4.218,0-7.992-0.664-12.432-1.995l-2,12.206l0.667,0.225 l2.443,0.444c0.887,0.219,1.998,0.444,3.774,0.444c3.108,0.443,5.771,0.443,7.548,0.443c14.652,0,21.534-5.551,21.534-17.76 c0-7.328-2.886-11.548-9.768-14.875c-5.994-2.663-6.661-3.333-6.661-5.771c0-2.888,2.443-4.221,6.883-4.221 c2.663,0,6.438,0.225,9.989,0.669l1.998-12.212c-3.552-0.666-9.101-1.111-12.209-1.111 C169.214,238.842,163.665,247.056,163.887,256.824L163.887,256.824z" />
												<path class="svg-master-letter" d="M448.935,293.235h-13.097l0.665-5.109 c-3.773,3.996-7.77,5.772-12.875,5.772c-10.215,0-16.874-8.654-16.874-21.979c0-17.758,10.435-32.854,22.646-32.854 c5.55,0,9.546,2.442,13.319,7.328l3.108-18.652h13.766L448.935,293.235L448.935,293.235z M428.511,280.804 c6.438,0,10.879-7.554,10.879-17.982c0-6.886-2.443-10.437-7.325-10.437c-6.217,0-10.881,7.327-10.881,17.759 C421.184,277.251,423.628,280.804,428.511,280.804L428.511,280.804z" />
												<path class="svg-master-letter" d="M260.013,292.122c-4.883,1.558-9.322,2.22-14.432,2.22 c-15.538,0-23.53-8.211-23.53-23.974c0-18.203,10.211-31.748,24.2-31.748c11.542,0,18.868,7.548,18.868,19.315 c0,3.996-0.445,7.768-1.776,13.321h-27.529c-0.222,0.662-0.222,1.106-0.222,1.55c0,6.222,4.218,9.329,12.21,9.329 c5.107,0,9.547-0.888,14.432-3.332L260.013,292.122L260.013,292.122z M252.241,260.375c0-1.107,0-1.994,0-2.663 c0-4.44-2.439-6.881-6.66-6.881c-4.439,0-7.547,3.331-8.879,9.544H252.241L252.241,260.375z" />
												<polygon class="svg-master-letter" points="110.828,293.235 97.065,293.235 105.056,242.839 87.074,293.235 77.527,293.235 76.418,243.282 67.981,293.235 55.106,293.235 65.984,227.741 85.964,227.741 86.63,268.367 99.949,227.741 121.706,227.741 110.828,293.235 " />
												<path class="svg-master-letter" d="M145.238,269.48c-1.332,0-1.998-0.226-3.107-0.226 c-7.771,0-11.767,2.889-11.767,8.217c0,3.332,1.776,5.328,4.884,5.328C141.021,282.8,145.017,277.472,145.238,269.48 L145.238,269.48z M155.45,293.235h-11.544l0.222-5.554c-3.552,4.44-8.215,6.441-14.652,6.441c-7.547,0-12.653-5.771-12.653-14.433 c0-13.1,8.879-20.646,24.418-20.646c1.554,0,3.554,0.224,5.773,0.443c0.444-1.775,0.444-2.438,0.444-3.327 c0-3.551-2.441-4.883-8.88-4.883c-3.996,0-8.436,0.444-11.543,1.332l-1.998,0.663l-1.332,0.224l1.998-11.988 c6.881-1.999,11.545-2.666,16.65-2.666c11.987,0,18.426,5.329,18.426,15.542c0,2.664-0.222,4.659-1.109,10.655l-3.11,18.872 l-0.444,3.327l-0.222,2.664l-0.221,1.999L155.45,293.235L155.45,293.235z" />
												<path class="svg-master-letter" d="M365.019,269.48c-1.555,0-2.22-0.226-3.108-0.226 c-7.991,0-11.987,2.889-11.987,8.217c0,3.332,1.998,5.328,5.106,5.328C360.579,282.8,364.797,277.472,365.019,269.48 L365.019,269.48z M375.229,293.235h-11.543l0.222-5.554c-3.551,4.44-8.213,6.441-14.65,6.441c-7.548,0-12.653-5.771-12.653-14.433 c0-13.1,8.879-20.646,24.418-20.646c1.554,0,3.552,0.224,5.551,0.443c0.443-1.775,0.665-2.438,0.665-3.327 c0-3.551-2.441-4.883-8.88-4.883c-3.995,0-8.656,0.444-11.766,1.332l-1.775,0.663l-1.332,0.224l1.998-11.988 c6.882-1.999,11.543-2.666,16.648-2.666c11.988,0,18.206,5.329,18.206,15.542c0,2.664,0,4.659-1.113,10.655l-2.883,18.872 l-0.446,3.327l-0.443,2.664l-0.223,1.999V293.235L375.229,293.235z" />
												<path class="svg-master-letter" d="M412.526,239.509c-0.444,0-0.889,0-1.332,0 c-4.662,0-7.325,2.22-11.544,8.66l1.331-8.216H388.33l-8.438,53.282h13.765c5.106-32.635,6.438-38.187,13.098-38.187 c0.444,0,0.444,0,0.889,0c1.331-6.436,3.107-11.1,5.327-15.318L412.526,239.509L412.526,239.509z" />
											  </svg>

											  <svg class="svg-amex" viewBox="0 0 512 512">
												<path class="svg-amex-border" d="M482.722,103.198c13.854,0,25.126,11.271,25.126,25.126v257.9c0,13.854-11.271,25.125-25.126,25.125H30.99 c-13.854,0-25.126-11.271-25.126-25.125v-257.9c0-13.854,11.271-25.126,25.126-25.126H482.722 M482.722,98.198H30.99 c-16.638,0-30.126,13.488-30.126,30.126v257.9c0,16.639,13.488,30.125,30.126,30.125h451.731c16.64,0,30.126-13.486,30.126-30.125 v-257.9C512.848,111.686,499.36,98.198,482.722,98.198L482.722,98.198z" />
												<path class="svg-amex-letter" d="M263.488,241.026v-10.115c0,0-0.535-7.92-8.802-7.92h-12.852v18.035h-13.193v-51.994 h32.03c0,0,16.718-2.024,16.718,15.218c0,8.975-7.391,12.139-7.391,12.139s6.416,2.997,6.416,11.795v12.841L263.488,241.026 M241.835,210.762h13.643c3.961,0,7.219-2.201,7.219-4.926c0-2.73-3.258-4.93-7.219-4.93h-13.643V210.762L241.835,210.762" />
												<path class="svg-amex-letter" d="M419.223,241.026l-20.938-34.752v34.752h-11.352h-2.119h-12.394l-4.668-10.908h-24.105 l-4.583,10.908H326.92h-2.196h-6.339c0,0-17.768-2.549-17.768-24.545c0-28.862,20.154-27.716,20.851-27.893l16.278,0.444v11.7 l-13.367,0.172c0,0-8.715,0-9.775,11.355c-0.124,1.303-0.181,2.501-0.172,3.614c0.048,17.577,15.258,12.12,15.754,11.958 l16.364-38.799h18.473l19.792,47.159v-47.159h18.654l20.68,34.227v-34.227h13.29v51.994H419.223 M349.279,216.834h12.84 l-6.329-15.486L349.279,216.834L349.279,216.834" />
												<path class="svg-amex-letter" d="M159.92,241.026v-35.893l-16.536,35.893h-10.821l-16.631-35.72v35.72h-11.524h-1.762 H89.888l-4.573-10.908H61.208l-4.664,10.908h-14.43l21.906-51.994h18.569l20.058,47.774v-47.774h20.852l14.521,32.289 l14.436-32.289h20.851v51.994H159.92 M66.836,216.834h12.845l-6.42-15.486L66.836,216.834L66.836,216.834" />
												<polyline class="svg-amex-letter" points="180.6,241.026 180.6,189.033 221.786,189.033 221.786,201.172 193.89,201.172 193.89,209.177 221.08,209.177 221.08,220.968 193.89,220.968 193.89,229.77 221.786,229.77 221.786,241.026 180.6,241.026" />
												<polyline class="svg-amex-letter" points="282.85,241.203 282.85,189.033 296.13,189.033 296.13,241.203 282.85,241.203" />
												<path class="svg-amex-letter" d="M314.432,329.189v-10.121c0,0-0.533-7.914-8.802-7.914h-12.842v18.035h-13.204v-51.998 h32.03c0,0,16.718-2.023,16.718,15.223c0,8.975-7.389,12.145-7.389,12.145s6.34,2.988,6.34,11.78v12.851H314.432 M292.788,298.925h13.644c3.952,0,7.208-2.194,7.208-4.926c0-2.729-3.256-4.927-7.208-4.927h-13.644V298.925L292.788,298.925" />
												<path class="svg-amex-letter" d="M226.445,329.104h-11.533l-13.977-15.4l-14.091,15.4h-8.001h-41.091v-52.086h41.091 h6.951l15.142,16.543l15.219-16.457h10.119v-0.086h32.022c0,0,16.718-1.851,16.718,15.301c0,15.313-5.548,20.067-22.619,20.067 h-12.926v16.718H226.445 M209.909,303.508l16.363,18.12v-36.165L209.909,303.508L209.909,303.508 M151.042,317.85h27.801 l13.023-14.342l-13.023-14.264h-27.801v8.001h27.095v11.801h-27.095V317.85L151.042,317.85 M239.468,298.83h13.633 c3.961,0,7.217-2.196,7.217-4.926c0-2.721-3.256-4.928-7.217-4.928h-13.633V298.83L239.468,298.83" />
												<path class="svg-amex-letter" d="M404.441,329.018h-23.496v-11.973h20.595c0,0,7.39,0.88,7.39-4.123 c0-4.679-11.17-4.316-11.17-4.316s-18.216,1.586-18.216-15.484c0-16.984,16.449-16.016,16.449-16.016h25.347v12.14h-20.41 c0,0-7.046-1.412-7.046,3.705c0,4.296,9.585,3.685,9.585,3.685s20.153-1.488,20.153,14.168 c0,16.793-12.984,18.282-17.585,18.282C405.044,329.084,404.441,329.018,404.441,329.018" />
												<polyline class="svg-amex-letter" points="333.794,329.104 333.794,277.191 374.962,277.191 374.962,289.244 347.073,289.244 347.073,297.245 374.264,297.245 374.264,309.046 347.073,309.046 347.073,317.85 374.962,317.85 374.962,329.104 333.794,329.104" />
												<path class="svg-amex-letter" d="M450.984,329.018h-23.495v-11.973h20.507c0,0,7.477,0.88,7.477-4.123 c0-4.679-11.169-4.316-11.169-4.316s-18.218,1.586-18.218-15.484c0-16.984,16.449-1.016,16.449-16.016h25.262v12.14h-20.334 c0,0-7.038-1.412-7.038,3.705c0,4.296,9.597,3.685,9.597,3.685s20.144-1.488,20.144,14.168 c0,16.793-12.983,18.282-17.584,18.282C451.586,329.084,450.984,329.018,450.984,329.018" />
											  </svg>
											</div>

										  </div>
									</div>
								</div>
							</div>
						</div>
                        </div>

                    </div>
                </div>

            </article>
        </section>
        <?php require('pages/footer.php'); ?>
            <script src="<?php echo IURL ?>assets/js/jquery.min.js"></script>
            <script src="<?php echo IURL ?>assets/js/jquery.validate.min.js"></script>
            <script src="<?php echo IURL ?>assets/js/bootstrap/bootstrap.min.js"></script>
            <script src="<?php echo IURL ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
            <script src="<?php echo IURL ?>assets/js/angular.min.js"></script>
            <script src="<?php echo IURL ?>assets/js/angular-sanitize.js"></script>
            <script src="<?php echo IURL ?>assets/app/HomeApp7893.js"></script>
            <script src="<?php echo IURL ?>assets/js/pagescripts.js"></script>
            <script type="text/javascript">
                $(document).ready(function(ev) {
                    $('#custom_carousel').on('slide.bs.carousel', function(evt) {
                        $('#custom_carousel .controls li.active').removeClass('active');
                        $('#custom_carousel .controls li:eq(' + $(evt.relatedTarget).index() + ')').addClass('active');
                    })
                });
                $(document).ready(function() {
                    $('.tab-section').hide();
                    $('#tabs a').click(function(event) {
                        $('#tabs a.current').removeClass('current');
                        $('.tab-section:visible').hide();
                        $(this.hash).show();
                        $(this).addClass('current');
                        event.preventDefault();
                    }).filter(':first').click();

                });
            </script>

</body>

</html>