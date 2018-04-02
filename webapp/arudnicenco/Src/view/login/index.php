<form class="form-signin" method="POST" action="/login">
    <h1 class="signin">Please sign in</h1>
    <label for="inputEmail">Email address</label>
    <input type="text" name="username"/>
    <label for="inputPassword">Password</label>
    <input type="password" id="inputPassword" name="passw"/>

    <button class="butt" type="submit">Sign in</button>
<?php
if (isset($_SESSION['error'])) {
    echo '<div class="alert_danger" role="alert">'.$_SESSION['error'].'</div>';

    unset($_SESSION['error']);
}
?>
</form>
