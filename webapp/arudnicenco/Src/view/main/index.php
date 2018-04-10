<html lang="en">
<head>
    <meta charset="utf-8">

    <title>MyApp</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/cover.css" rel="stylesheet">
</head>

<body class="text-center">

<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
    <main role="main" class="inner cover">
        <h1 class="cover-heading">Hello {{ username }}</h1>
        <p>Here is your <a class="alert-link" target="_blank"
                           href="{{ GitLink }}">Git profile</a></p>
        <p class="lead">
            <a href="/logout" class="btn btn-lg btn-secondary">Log out</a>
        </p>
    </main>
</div>
</body>
</html>