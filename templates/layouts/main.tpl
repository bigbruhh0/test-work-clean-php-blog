<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{if isset($pageTitle)}{$pageTitle} - {/if}{$appName}</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    {include file='partials/header.tpl'}
    <main>
        {$content nofilter}
    </main>
    {include file='partials/footer.tpl'}
</body>
</html>
