<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

if(!isset($pageTitle)){
    $pageTitle = 'WebRTC || Register Page';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle; ?></title>
    <link rel="shortcut icon" href="<?php echo url_for('assets/favicon/webrtc.ico'); ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo url_for('assets/font-awesome/css/font-awesome.css'); ?>">
    <link rel="stylesheet" href="<?php echo url_for('assets/css/style.css'); ?>">
</head>
<body>
