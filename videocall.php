<?php  
namespace MyApp;
require "core/init.php";
if(!isset($_SESSION['user_id'])) {
    redirect_to(url_for('register.php'));
}
if(isset($_GET['username']) && !empty($_GET['username'])) {
    $username = FormSanitizer::sanitizeFormUsername($_GET['username']);
    $profileData = $loadFromUser->getUserByUsername($username);
    if(!$profileData) {
        redirect_to(url_for('index.php'));
    }
    $pageTitle = 'Video Call with ' . $profileData->firstName . ' ' . $profileData->lastName;
    $profileId = $profileData->userID;
}
$loadFromUser->updateSession();
$userData = $loadFromUser->userData();
require "shared/header.php";
?>
<div class = "u-p-id" data-userid = "<?php echo $userData->userID; ?>" data-profileid = "<?php echo $profileId; ?>"></div>
<header class = "g-header">
    <div class = "site-logo">
        <a href="<?php echo url_for('index.php'); ?>">
            <svg width="130px" height="30px" fill="#fff" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 226 64" style="enable-background:new 0 0 226 64;" xml:space="preserve">
                <circle fill="#ff6600" cx="108.65" cy="42.82" r="8.35"/>
                <path d="M26.34,43.94l-5.1-18.9l-5.16,18.9c-0.4,1.43-0.72,2.46-0.96,3.09c-0.24,0.62-0.65,1.18-1.24,1.68
					c-0.59,0.49-1.37,0.74-2.34,0.74c-0.79,0-1.44-0.15-1.95-0.44c-0.51-0.29-0.92-0.71-1.24-1.25c-0.32-0.54-0.57-1.18-0.77-1.91
					c-0.2-0.74-0.38-1.42-0.54-2.05L1.8,22.56c-0.32-1.23-0.47-2.17-0.47-2.82c0-0.82,0.29-1.51,0.86-2.06
					c0.57-0.56,1.28-0.84,2.13-0.84c1.16,0,1.94,0.37,2.34,1.12c0.4,0.75,0.75,1.83,1.05,3.25l4.13,18.41l4.62-17.23
					c0.34-1.32,0.65-2.32,0.92-3.01c0.27-0.69,0.72-1.28,1.33-1.79c0.62-0.5,1.46-0.75,2.52-0.75c1.08,0,1.91,0.26,2.51,0.79
					c0.59,0.52,1.01,1.09,1.24,1.71c0.23,0.62,0.54,1.63,0.92,3.05l4.67,17.23L34.7,21.2c0.2-0.96,0.39-1.71,0.57-2.26
					c0.18-0.54,0.49-1.03,0.92-1.46c0.44-0.43,1.07-0.65,1.9-0.65c0.83,0,1.54,0.28,2.12,0.83c0.58,0.55,0.87,1.24,0.87,2.08
					c0,0.59-0.16,1.53-0.47,2.82l-5.25,21.23c-0.36,1.43-0.66,2.48-0.89,3.15c-0.24,0.67-0.64,1.25-1.2,1.75
					c-0.57,0.5-1.37,0.75-2.4,0.75c-0.98,0-1.76-0.24-2.34-0.73c-0.59-0.49-1-1.04-1.23-1.65C27.08,46.46,26.75,45.41,26.34,43.94z
					M59.48,38.95H48c0.01,1.33,0.28,2.51,0.81,3.53c0.52,1.02,1.22,1.79,2.09,2.3c0.87,0.52,1.82,0.77,2.87,0.77
					c0.7,0,1.34-0.08,1.92-0.25c0.58-0.16,1.14-0.42,1.69-0.77c0.54-0.35,1.05-0.73,1.51-1.13c0.46-0.4,1.05-0.95,1.79-1.63
					c0.3-0.26,0.73-0.39,1.29-0.39c0.6,0,1.09,0.16,1.46,0.49c0.37,0.33,0.56,0.8,0.56,1.4c0,0.53-0.21,1.15-0.62,1.86
					c-0.42,0.71-1.04,1.39-1.88,2.04c-0.84,0.65-1.89,1.19-3.16,1.62c-1.27,0.43-2.73,0.65-4.38,0.65c-3.77,0-6.7-1.08-8.8-3.23
					C43.05,44.07,42,41.15,42,37.46c0-1.73,0.26-3.34,0.77-4.83c0.52-1.48,1.27-2.76,2.26-3.82c0.99-1.06,2.21-1.87,3.66-2.44
					c1.45-0.57,3.05-0.85,4.82-0.85c2.29,0,4.26,0.48,5.9,1.45c1.64,0.97,2.87,2.22,3.69,3.75c0.82,1.53,1.23,3.1,1.23,4.69
					c0,1.48-0.42,2.43-1.27,2.87C62.21,38.73,61.02,38.95,59.48,38.95z M48,35.61h10.65c-0.14-2.01-0.68-3.51-1.62-4.51
					c-0.94-1-2.18-1.49-3.71-1.49c-1.46,0-2.66,0.51-3.6,1.52C48.77,32.14,48.2,33.64,48,35.61z M73.73,20.19v8.97
					c1.1-1.15,2.23-2.02,3.38-2.63c1.15-0.61,2.57-0.91,4.26-0.91c1.95,0,3.66,0.46,5.13,1.39c1.47,0.92,2.61,2.27,3.42,4.02
					c0.81,1.76,1.22,3.84,1.22,6.25c0,1.78-0.23,3.41-0.68,4.89s-1.11,2.77-1.97,3.86c-0.86,1.09-1.9,1.93-3.13,2.53
					c-1.23,0.6-2.58,0.89-4.05,0.89c-0.9,0-1.75-0.11-2.55-0.32c-0.8-0.22-1.47-0.5-2.03-0.85c-0.56-0.35-1.04-0.71-1.43-1.09
					c-0.39-0.37-0.91-0.93-1.56-1.68v0.58c0,1.1-0.27,1.94-0.8,2.51c-0.53,0.57-1.2,0.85-2.02,0.85c-0.83,0-1.49-0.28-1.99-0.85
					c-0.49-0.57-0.74-1.4-0.74-2.51V20.45c0-1.19,0.24-2.09,0.72-2.7c0.48-0.61,1.15-0.91,2.01-0.91c0.9,0,1.6,0.29,2.09,0.87
					C73.49,18.29,73.73,19.12,73.73,20.19z M74.01,37.64c0,2.34,0.53,4.13,1.6,5.39c1.07,1.26,2.47,1.88,4.2,1.88
					c1.48,0,2.75-0.64,3.82-1.92c1.07-1.28,1.6-3.12,1.6-5.52c0-1.55-0.22-2.88-0.67-4c-0.44-1.12-1.08-1.98-1.89-2.59
					c-0.82-0.61-1.77-0.91-2.86-0.91c-1.12,0-2.12,0.3-2.99,0.91c-0.87,0.61-1.56,1.49-2.06,2.65C74.26,34.67,74.01,36.04,74.01,37.64z"
					/>
                <path d="M148.88,35.42h-2.24v10.13c0,1.33-0.29,2.32-0.88,2.95c-0.59,0.63-1.35,0.95-2.3,0.95c-1.02,0-1.81-0.33-2.37-0.99
					c-0.56-0.66-0.84-1.63-0.84-2.9V21.29c0-1.38,0.31-2.37,0.92-2.99c0.62-0.62,1.61-0.92,2.99-0.92h10.39c1.43,0,2.66,0.06,3.68,0.18
					c1.02,0.12,1.94,0.37,2.75,0.74c0.99,0.42,1.86,1.01,2.62,1.79c0.76,0.77,1.34,1.67,1.73,2.7c0.39,1.03,0.59,2.11,0.59,3.26
					c0,2.35-0.66,4.23-1.99,5.63c-1.33,1.41-3.34,2.4-6.03,2.99c1.13,0.6,2.22,1.49,3.25,2.67c1.03,1.18,1.95,2.43,2.76,3.75
					c0.81,1.33,1.44,2.52,1.89,3.59c0.45,1.07,0.68,1.8,0.68,2.2c0,0.42-0.13,0.83-0.4,1.24c-0.27,0.41-0.63,0.73-1.09,0.97
					c-0.46,0.24-0.99,0.35-1.59,0.35c-0.72,0-1.32-0.17-1.81-0.51c-0.49-0.34-0.91-0.76-1.26-1.28c-0.35-0.52-0.83-1.28-1.43-2.28
					l-2.56-4.26c-0.92-1.56-1.74-2.75-2.46-3.57c-0.72-0.82-1.46-1.38-2.2-1.68C150.95,35.57,150.01,35.42,148.88,35.42z M152.53,22.17
					h-5.89v8.67h5.72c1.53,0,2.82-0.13,3.87-0.4c1.05-0.27,1.85-0.72,2.4-1.35c0.55-0.64,0.83-1.52,0.83-2.63
					c0-0.87-0.22-1.65-0.67-2.31c-0.44-0.67-1.06-1.16-1.85-1.49C156.2,22.33,154.73,22.17,152.53,22.17z M191.53,22.65h-6.97v22.9
					c0,1.32-0.29,2.3-0.88,2.94c-0.59,0.64-1.35,0.96-2.28,0.96c-0.95,0-1.72-0.32-2.31-0.97c-0.6-0.65-0.89-1.62-0.89-2.92v-22.9h-6.97
					c-1.09,0-1.9-0.24-2.43-0.72c-0.53-0.48-0.8-1.11-0.8-1.9c0-0.82,0.28-1.46,0.83-1.94c0.55-0.47,1.35-0.71,2.4-0.71h20.3
					c1.1,0,1.92,0.24,2.46,0.73c0.54,0.49,0.81,1.13,0.81,1.91c0,0.79-0.27,1.42-0.82,1.9C193.44,22.41,192.62,22.65,191.53,22.65z
					M223.36,38.95c0,1-0.25,2.09-0.74,3.26c-0.49,1.17-1.27,2.32-2.33,3.44c-1.06,1.13-2.42,2.04-4.06,2.74
					c-1.65,0.7-3.57,1.05-5.76,1.05c-1.66,0-3.18-0.16-4.54-0.47c-1.36-0.31-2.6-0.81-3.71-1.47c-1.11-0.67-2.13-1.54-3.06-2.63
					c-0.83-0.99-1.54-2.1-2.13-3.32c-0.59-1.23-1.03-2.53-1.32-3.92c-0.29-1.39-0.44-2.87-0.44-4.43c0-2.54,0.37-4.81,1.11-6.82
					c0.74-2.01,1.8-3.72,3.17-5.15c1.38-1.43,2.99-2.51,4.84-3.26c1.85-0.75,3.82-1.12,5.91-1.12c2.55,0,4.82,0.51,6.82,1.53
					c1.99,1.02,3.52,2.28,4.58,3.77c1.06,1.5,1.59,2.91,1.59,4.25c0,0.73-0.26,1.38-0.77,1.94c-0.52,0.56-1.14,0.84-1.87,0.84
					c-0.82,0-1.43-0.19-1.84-0.58c-0.41-0.39-0.86-1.05-1.37-2c-0.83-1.56-1.81-2.73-2.94-3.51c-1.13-0.77-2.51-1.16-4.16-1.16
					c-2.62,0-4.71,1-6.27,2.99c-1.56,1.99-2.33,4.83-2.33,8.5c0,2.45,0.34,4.49,1.03,6.12c0.69,1.63,1.66,2.84,2.92,3.65
					c1.26,0.8,2.74,1.2,4.43,1.2c1.84,0,3.39-0.45,4.66-1.37c1.27-0.91,2.23-2.25,2.87-4.01c0.27-0.83,0.61-1.51,1.01-2.03
					c0.4-0.52,1.05-0.79,1.94-0.79c0.76,0,1.41,0.27,1.96,0.8C223.09,37.5,223.36,38.16,223.36,38.95z"/>
                <circle fill="#ffcc00" cx="125.15" cy="31.14" r="8.35"/>
                <circle fill="#0089cc" cx="104.75" cy="30.95" r="8.35"/>
                <circle fill="#009939" cx="121.44" cy="42.82" r="8.35"/>
                <circle fill="#bf0000" cx="114.95" cy="23.53" r="8.35"/>
                <path fill="#fc0007" d="M116.81,31.14c0,0.18,0.02,0.35,0.03,0.52c3.7-0.85,6.46-4.16,6.46-8.12c0-0.18-0.02-0.35-0.03-0.52
	                C119.57,23.87,116.81,27.18,116.81,31.14z"/>
                <path fill="#1cd306" d="M117.91,35.27c1.44,2.51,4.14,4.21,7.24,4.21c1.27,0,2.46-0.29,3.53-0.79c-1.44-2.51-4.14-4.21-7.24-4.21
	                C120.18,34.48,118.98,34.76,117.91,35.27z"/>
                <path fill="#0f7504" d="M113.1,42.82c0,2.04,0.73,3.9,1.95,5.35c1.21-1.45,1.95-3.32,1.95-5.35c0-2.04-0.73-3.9-1.95-5.35
	                C113.83,38.92,113.1,40.78,113.1,42.82z"/>
                <path fill="#0c5e87" d="M101.45,38.61c1.01,0.44,2.13,0.68,3.3,0.68c3.07,0,5.75-1.67,7.2-4.14c-1.01-0.44-2.13-0.68-3.3-0.68
	                C105.57,34.48,102.9,36.14,101.45,38.61z"/>
                <path fill="#6b0001" d="M106.64,22.83c-0.02,0.23-0.04,0.47-0.04,0.7c0,3.96,2.76,7.26,6.46,8.12c0.02-0.23,0.04-0.47,0.04-0.7
	                C113.1,26.99,110.34,23.69,106.64,22.83z"/>
                <path fill="#ffffff" d="M107.42,41.79h-1.31c-1.15,0-2.09-0.93-2.09-2.08V27.89c0-1.15,0.94-2.08,2.09-2.08h16.37
	                c1.15,0,2.09,0.94,2.09,2.08V39.7c0,1.15-0.94,2.08-2.09,2.08h-5.58l-11.19,5.48L107.42,41.79z"/>
            </svg>
		</a>
    </div>
	<div class = "g-header-right">
		<span class = "user-status"><?php echo $userData->onlineStatus; ?></span>
		<div class = "user-wrapper">
			<img width = "40px" height = "40px" src = "<?php echo url_for($userData->profileImage); ?>" alt = "<?php echo $userData->firstName . ' ' . $userData->lastName; ?>">
			<span class = "username"><?php echo $userData->firstName . ' ' . $userData->lastName; ?></span>
		</div>
		<a href = "<?php echo url_for('logout.php'); ?>" class = "log-out-status">
		    <i class = "fa fa-sign-out"></i>
	    </a>
	</div>
</header>
<section class = "page-container">
	<aside>
		<div class = "page-container-top-aside">
			<div class = "nf-1 custom-style">
				<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
				width="205.000000pt" height="246.000000pt" viewBox="0 0 205.000000 246.000000"
				preserveAspectRatio="xMidYMid meet">

					<g transform="translate(0.000000,246.000000) scale(0.100000,-0.100000)"
					fill="#008066" stroke="none">
						<path d="M969 2331 c-17 -18 -29 -40 -29 -56 0 -14 -6 -27 -12 -29 -7 -2 -47
						-11 -90 -20 -241 -53 -461 -243 -551 -475 -54 -138 -57 -171 -57 -607 l0 -401
						-101 -117 c-82 -95 -100 -122 -97 -144 l3 -27 970 -3 c534 -1 980 0 993 3 44
						11 27 48 -77 171 l-100 117 -3 426 c-4 471 -5 474 -74 620 -81 173 -198 292
						-371 379 -67 34 -191 72 -235 72 -25 0 -28 4 -28 31 0 39 -48 89 -85 89 -16 0
						-38 -12 -56 -29z m226 -228 c168 -42 342 -181 422 -338 75 -146 75 -150 80
						-615 l5 -415 59 -80 60 -80 -395 -3 c-217 -1 -575 -1 -796 0 l-401 3 60 80 60
						80 3 415 c4 356 7 424 22 475 69 241 275 437 506 484 114 23 208 21 315 -6z"/>
						<path d="M605 318 c33 -153 205 -258 420 -258 136 0 252 39 330 111 50 46 78
						93 88 147 l7 32 -63 0 c-63 0 -63 0 -76 -35 -34 -95 -222 -154 -381 -121 -97
						21 -159 58 -185 113 l-20 42 -63 1 -64 0 7 -32z"/>
					</g>
				</svg>
				<div class = "nf-text">Notification</div>
			</div>
			<div class = "nf-2 custom-style active" style = "flex:2.2">
				<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
				width="20.000000pt" height="20.000000pt" viewBox="0 0 20.000000 20.000000"
				preserveAspectRatio="xMidYMid meet">

					<g transform="translate(0.000000,20.000000) scale(0.100000,-0.100000)"
					fill="#008066" stroke="none">
						<path d="M50 130 c0 -15 5 -20 18 -18 9 2 17 10 17 18 0 8 -8 16 -17 18 -13 2
						-18 -3 -18 -18z"/>
						<path d="M115 140 c-8 -14 3 -30 21 -30 8 0 14 9 14 20 0 21 -24 28 -35 10z"/>
						<path d="M28 79 c-32 -18 -19 -29 37 -29 56 0 70 11 36 30 -25 13 -51 12 -73
						-1z"/>
						<path d="M142 73 c3 -19 48 -32 48 -14 0 10 -31 31 -45 31 -4 0 -5 -8 -3 -17z"/>
					</g>
				</svg>
				<div class = "nf-text">Connected Peers</div>
			</div>
			<div class = "nf-3 custom-style">
				<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
				width="240.000000pt" height="240.000000pt" viewBox="0 0 240.000000 240.000000"
				preserveAspectRatio="xMidYMid meet">

					<g transform="translate(0.000000,240.000000) scale(0.100000,-0.100000)"
					fill="#008066" stroke="none">
						<path d="M761 2379 c-294 -63 -531 -247 -661 -514 -71 -144 -94 -247 -94 -415
						0 -168 23 -271 94 -415 122 -250 356 -439 625 -507 136 -34 334 -32 467 5 134
						37 282 113 361 186 l28 25 372 -372 372 -372 38 37 37 38 -372 372 -372 372
						25 28 c73 79 149 227 186 361 37 133 39 331 5 467 -30 119 -106 271 -183 368
						-122 153 -321 281 -509 327 -127 32 -299 35 -419 9z m424 -114 c112 -33 201
						-79 294 -154 136 -108 235 -256 287 -426 24 -80 27 -106 27 -235 0 -129 -3
						-155 -27 -235 -34 -113 -81 -202 -155 -294 -108 -136 -256 -235 -426 -287 -80
						-24 -106 -27 -235 -27 -129 0 -155 3 -235 27 -170 52 -318 151 -426 287 -74
						92 -121 181 -155 294 -24 80 -27 106 -27 235 0 129 3 155 27 235 34 113 81
						202 155 294 129 162 316 273 528 311 89 16 269 4 368 -25z"/>
					</g>
				</svg>
				<div class = "nf-text">Search</div>
			</div>
		</div>
		<div class = "g-users"></div>
		<div class = "g-search hidden">
			Search
		</div>
		<div class = "g-notify-message hidden">
			Notification
		</div>
	</aside>
	<main class = "main-content">
		<div class="call-wrap">
			<video id="local-video" autoplay playsinline></video>
			<div class="remote-video-wrap">
				<div class="call-hang-status">
					<div class="calling-status-wrap" id="conf-int">
						<div class="user-connected-img">
							<img src="<?php echo url_for($profileData->profileImage); ?>" alt="<?php echo $profileData->firstName . ' ' . $profileData->lastName; ?>">
						</div>
						<div class="user-status-text">
							<div class="user-calling-status">Make a Call with</div>
							<div class="user-connected-name"><?php echo $profileData->firstName . ' ' . $profileData->lastName; ?></div>
						</div>
						<div class="calling-action">
							<div class="video-call" data-profileid="<?php echo $profileData->userID; ?>" data-userid="<?php echo $userData->userID; ?>">
								<svg class="cam-icon" xmlns="http://www.w3.org/2000/svg" focusable="false" viewBox="0 0 24 24" class="Hdh4hc cIGbvc NMm5M"><path d="M18 10.48V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4.48l4 3.98v-11l-4 3.98zm-2-.79V18H4V6h12v3.69z"/></svg>
							</div>
							<div class="audio-call" data-profileid="<?php echo $profileData->userID; ?>" data-userid="<?php echo $userData->userID; ?>">
								<i class="fa fa-phone audio-icon-top"></i>
							</div>
						</div>
					</div>
					<div class="calling-status-wrap hidden-status" id="call-status">
						<!-- <div class="user-connected-img">
							<img src="<?php echo url_for($profileData->profileImage); ?>" alt="<?php echo $profileData->firstName . ' ' . $profileData->lastName; ?>">
						</div>
						<div class="user-status-text">
							<div class="user-calling-status">Calling </div>
							<div class="user-connected-name"><?php echo $profileData->firstName . ' ' . $profileData->lastName; ?></div>
						</div>
						<div class="calling-action">
							<div class="call-accept" data-profileid="<?php echo $profileData->userID; ?>" data-userid="<?php echo $userData->userID; ?>">
								<i class="fa fa-phone audio-icon"></i>
							</div>
							<div class="call-reject" data-profileid="<?php echo $profileData->userID; ?>" data-userid="<?php echo $userData->userID; ?>">
								<i class="fa fa-close close-icon"></i>
							</div>
						</div> -->
					</div>
				</div>
				<video id="remote-video" autoplay playsinline></video>
			</div>
		</div>
	</main>
</section>
<script src = "<?php echo url_for('assets/js/jquery.js'); ?>"></script>
<script src = "<?php echo url_for('assets/js/common.js'); ?>"></script>
<script>
    var conn = new WebSocket('ws://my-webrtc-video-chat.herokuapp.com/?token=<?php echo $userData->sessionID; ?>');
</script>
<script src = "<?php echo url_for('assets/js/client.js'); ?>"></script>
</body>
