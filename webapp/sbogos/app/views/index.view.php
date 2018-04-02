<?php require 'partials/header.view.php'; ?>
<?php require 'partials/nav.view.php'; ?>

<h1>Welcome, <?php print $user->name; ?></h1>

<form action="/logout" method="post">
    <input type="submit" value="logout"/>
</form>

<?php require 'partials/footer.view.php'; ?>

