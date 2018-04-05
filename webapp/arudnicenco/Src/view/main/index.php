<body class="text-center">

<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">

    <main role="main" class="inner cover">
        <h1 class="cover-heading">Hi, <?php echo $_SESSION['username'] ?? ''?></h1>
        <p class="lead">
            <a href="/logout" class="btn btn-lg btn-secondary">Log out</a>
        </p>
    </main>
</div>