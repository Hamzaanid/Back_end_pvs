<!DOCTYPE html>
<html html dir="rtl" lang="ar">
<head>
    <title>Laravel 8 Generate PDF From View</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .space{
            height: 100px;
        }
    </style>
</head>

<body>
    <div dir="rtl">
      <h3>القرار :</h3> <p> {{ $descision }} </p>
    </div>

    <div class="space"></div>
    <img src="{{ storage_path('app/public/img_signature/user'. $id .'.jpeg') }}"
      style="width: 200px; height: 200px">


</body>
</html>
