<?php 

namespace MyApp;

require "core/init.php";
if(isset($_SESSION['user_id'])) {
    redirect_to(url_for('index.php'));
}
if(is_request_post()) {
    if(isset($_POST['submitButton'])) {
        $fname = FormSanitizer::sanitizeFormString($_POST['firstName']);
        $lname = FormSanitizer::sanitizeFormString($_POST['lastName']);
        $un = FormSanitizer::sanitizeFormUsername($_POST['username']);
        $em = FormSanitizer::sanitizeFormEmail($_POST['email']);
        $pwd = FormSanitizer::sanitizeFormString($_POST['password']);

        $wasSuccessful = $account->register($fname, $lname, $un, $em, $pwd);
        if($wasSuccessful) {
            session_regenerate_id();
            $_SESSION['user_id'] = $wasSuccessful;
            redirect_to(url_for('index.php'));
        }
    }
}
?>

<?php require "shared/header.php"; ?>
<div class = "signInContainer">
        <div class = "column">
            <section class = "header">
                <img src = "assets/images/webrtclockup.png" alt = "Site Logo">
                <h3>Register</h3>
                <span>to continue to WebRTC</span>
            </section>
            <form action = "<?php echo h($_SERVER['PHP_SELF']); ?>" method = "POST">
                <?php echo $account->getError(Constant::$firstNameCharacters); ?>
                <input type="text" name="firstName" placeholder="FirstName....." value = "<?php getInputValue('firstName'); ?>" required>
                <?php echo $account->getError(Constant::$lastNameCharacters); ?>
                <input type="text" name="lastName" placeholder="lastName....." value = "<?php getInputValue('lastName'); ?>" required>
                <?php echo $account->getError(Constant::$UsernameCharacters); ?>
                <?php echo $account->getError(Constant::$UsernameTaken); ?>
                <input type="text" name="username" placeholder="Username....." value = "<?php getInputValue('username'); ?>" required>
                <?php echo $account->getError(Constant::$EmailTaken); ?>
                <?php echo $account->getError(Constant::$EmailInvalid); ?>
                <input type="email" name="email" placeholder="Email....." value = "<?php getInputValue('email'); ?>" required>
                <?php echo $account->getError(Constant::$PasswordCharacters); ?>
                <?php echo $account->getError(Constant::$PasswordNotAlphaNumeric); ?>
                <input type="password" name="password" placeholder="Password....." required>
                <input type="submit" name="submitButton" value="Register">
            </form>
            <a href = "login.php" class = "logInMessage">Already have an account?LogIn Here</a>
        </div>
    </div>
</body>
</html>
