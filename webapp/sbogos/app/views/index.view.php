<?php require 'partials/header.view.php'; ?>
<?php require 'partials/nav.view.php'; ?>

<h1>Welcome, <?php print $user->name; ?></h1>
<?php if (!is_null($profile)) : ?>
<a href="<?= $profile ?>" target="_blank">GitHub Profile</a>
<?php endif; ?>

<form action="/logout" method="post">
    <input type="submit" value="logout"/>
</form>

<?php require 'partials/footer.view.php'; ?>

