<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Web app</title>

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>

<?php if (isset($_GET['status'])) : ?>
    <div class="alert alert-danger">
        <h3 align="center">Неверные данные</h3>
    </div>
<?php endif; ?>

<form action="/login" method="post" class="navbar-form">
    <div class="form-group input-group">
        <div class="form-group input-group">
            <span class="input-group-addon">Username</span>
            <input name="login" type="text" class="form-control" placeholder="test">
        </div>
        <br>
        <br>
        <div class="form-group input-group">
            <span class="input-group-addon">Password</span>
            <input name="password" type="password" class="form-control" placeholder="passwd">
        </div>
        <br>
        <br>
        <input type="submit" name="submit" value="submit" class="btn btn-info form-controller">
</form>
</div>

</body>
