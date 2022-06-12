<?php
require "../init.php";
if(is_request_post()) {
    if(isset($_POST['receiver']) && !empty($_POST['receiver'])) {
        $receiver = h($_POST['receiver']);

        $calleeData = $loadFromUser->userData($receiver);
        echo json_encode(array(
            "sender" => $loadFromUser->userID,
            "receiver" => $calleeData->userID,
            "name" => $calleeData->firstName . ' ' . $calleeData->lastName,
            "profileImage" => $calleeData->profileImage
        ));
    }
}
