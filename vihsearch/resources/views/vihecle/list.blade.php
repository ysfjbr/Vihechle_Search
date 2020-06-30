
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div id="app"> 
    </div>
    <ul>
        @foreach ($vihecles as $vihecle)
        <li>{{$vihecle->year}}</li>
        @endforeach
    </ul>
    
</body>
<script type="text/javascript" src="js/app.js"></script>
</html>


