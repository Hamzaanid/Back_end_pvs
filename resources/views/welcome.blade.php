<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pvs</title>
    </head>
    <body> 
        <div> Gestion des pvs </div>
        <div id="app">
            <app></app>
        </div>

    </body>
    <script src="{{ mix('js/app.js') }}"></script>
</html>
