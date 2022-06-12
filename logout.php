<?php 
require "core/init.php";
if(isset($_SESSION['user_id'])) {
    log_out_user();
    redirect_to(url_for('login.php'));
}
