<!DOCTYPE html>
<html>
<head>
    <title>Comprobar URL Corta</title>
</head>
<body>
<h1>Comprobar URL Corta</h1>
<form action="/api/v1/short-urls" method="POST">
    @csrf
    <label for="shortUrl">URL Corta:</label>
    <input type="text" name="url" id="url">
    <button type="submit">Comprobar</button>
</form>
</body>
</html>
