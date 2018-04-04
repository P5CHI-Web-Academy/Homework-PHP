<?php require 'partials/header.view.php'; ?>

<?php require 'partials/nav.view.php'; ?>

<?php if (isset($message)) {
    echo $message;
} ?>

<form action="register" method="post">

    <span>Register form</span>

    <label for="username">Username</label>
    <input type="text" name="username" id="username" required/>

    <label for="">Password</label>
    <input type="text" name="password" id="password" required/>

    <input type="submit" value="submit" />
</form>

<?php require 'partials/footer.view.php'; ?>
