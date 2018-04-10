<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MyApp</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/cover.css" rel="stylesheet">
</head>

<body class="text-center">

<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">

    <main role="main" class="inner cover">
        <h1 class="cover-heading">Hello {{ username }}</h1>
        <p>Here is your <a class="alert-link" target="_blank"
                    href="{{ gitLink }}">git profile</a></p>
        <h4>Repository list</h4>
        <table class="table-bordered">
            <tr>
                <th><a href="/?orderBy=name">Name</a></th>
                <th><a href="/?orderBy=updated_at">Updated date</a></th>
                <th><a href="/?orderBy=html_url">Link</a></th>
                <th><a href="/?orderBy=commitCount">Commits</a></th>
            </tr>
            {% for repo in gitRepoInfo %}
            <tr>
                <td>{{ repo['name'] }}</td>
                <td>{{ repo['updated_at'] }}</td>
                <td><a href="{{ repo['html_url'] }}" target="_blank">{{ repo['html_url'] }}</a></td>
                <td>{{ repo['commitCount'] }}</td>
            </tr>
            {% endfor %}
        </table>
        <br /><br />
        <p class="lead">
            <a href="/logout" class="btn btn-lg btn-secondary">Log out</a>
        </p>
    </main>
</div>


</body>
</html>

