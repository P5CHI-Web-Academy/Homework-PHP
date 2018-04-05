<html lang="en">
<head>
    <meta charset="utf-8">

    <title>MyApp</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/signin.css" rel="stylesheet">
</head>

<body class="text-center">
<form class="form-signin" method="POST" action="/login">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="text" id="inputEmail" name="username" class="form-control"
           placeholder="Vlad6847" autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" name="password"
           class="form-control" placeholder="12345">

    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in
    </button>

    <br/>
    {{ error }}

</form>
</body>
</html>
