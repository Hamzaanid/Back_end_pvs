<!DOCTYPE html>
<html html dir="rtl" lang="ar">
<head>
    <title>Laravel 8 Generate PDF From View</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .space{
            height: 250px;
        }
        p{
            font-size: 26px;
        }

        .image{
            margin: 30px;
      width:300px;
      height:300px;
      overflow:hidden;
      display:block;
      background-color:white; /*not necessary, just to show the image box, can be added to img*/
    }
    </style>
</head>

<body>
    <div dir="rtl">
      <h1 style="text-decoration: underline;">القرار :</h1> <p> {{ $descision }} </p>
    </div>

    <div class="space"></div>

    <div class="image">
        <p>توقيع</p>
        <img src="{{ storage_path('app/public/img_signature/user'. $id .'.jpeg') }}"
            >
    </div>



</body>
</html>
