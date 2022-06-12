<?php 

namespace MyApp;

require "core/init.php";
if(isset($_SESSION['user_id'])) {
    redirect_to(url_for('index.php'));
}
if(is_request_post()) {
    if(isset($_POST['loginButton'])) {
        $un = FormSanitizer::sanitizeFormUsername($_POST['username']);
        $pwd = FormSanitizer::sanitizeFormString($_POST['password']);

        $wasSuccessful = $account->login($un, $pwd);
        if($wasSuccessful) {
            session_regenerate_id();
            $_SESSION['user_id'] = $wasSuccessful;
            redirect_to(url_for('index.php'));
        }
    }
}
$pageTitle = 'WebRTC || LogIn Page';
require "shared/header.php";
?>
<div class = "signInContainer">
        <div class = "column">
            <section class = "header">
                <img src = "assets/images/webrtclockup.png" alt = "Site Logo">
                <h3>LogIn</h3>
                <span>to continue to WebRTC</span>
            </section>
            <form action = "<?php echo h($_SERVER['PHP_SELF']); ?>" method = "POST">
            <?php echo $account->getError(Constant::$loginFailed); ?>
                <input type="text" name="username" placeholder="Username or Email....." value = "<?php getInputValue('username'); ?>" required>
                <input type="password" name="password" placeholder="Password....." required>
                <input type="submit" name="loginButton" value="LogIn">
            </form>
            <a href = "register.php" class = "logInMessage">Need an account?Register Here</a>
        </div>
    </div>
</body>
</html>
