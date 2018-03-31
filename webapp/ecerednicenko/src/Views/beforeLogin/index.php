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
<div>
    <form action="/login" method="post" class="navbar-form">
        Login: <input class="form-control" type="text" name="login"><br/>
        Password: <input class="form-control" type="password" name="password"><br/>
        <input type="submit" name="submit" value="submit" class="btn btn-submit">
    </form>
</div>
</body>
