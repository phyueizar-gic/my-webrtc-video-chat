<?php
require "../init.php";
if(is_request_post()) {
    if(isset($_POST['userid']) && !empty($_POST['userid'])) {
        $userid = h($_POST['userid']);
        $otherid = h($_POST['otherid']);

        if($userid == $loadFromUser->userID) {
            $users = $loadFromUser->getConnectedPeers();
            foreach($users as $user) {
                $activeClass = ((!empty($otherid) == $user->userID) ? 'activeClass' : '');
                echo '<a href = "' . url_for($user->username . '/videoChat') . '" class = "user-connected ' . $activeClass . '" data-profileid = "' . $user->userID . '">
				<div class = "u-connected-wrapper">
					<img width = "40px" height = "40px" style = "border-radius:50%;" src = "' . url_for($user->profileImage) . '" alt = "' . $user->firstName . ' ' . $user->lastName . '">
				</div>
				<span class = "u-connected-name">' . $user->firstName . ' ' . $user->lastName . '</span>
				<div class = "u-icons">
					<svg class = "cam-icon-connected video-call" xmlns="http://www.w3.org/2000/svg" focusable="false" width="24" height="24" viewBox="0 0 24 24" class="Hdh4hc cIGbvc NMm5M"><path d="M18 10.48V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4.48l4 3.98v-11l-4 3.98zm-2-.79V18H4V6h12v3.69z"/></svg>
					<i class = "fa fa-phone audio-icon audio-call"></i>
				</div>
			</div>';
            }
        }
    }
}
