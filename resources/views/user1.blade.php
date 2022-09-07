<!DOCTYPE html>
<html html dir="rtl" lang="ar">
<head>
    <title>Laravel 8 Generate PDF From View</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        p{
            font-size: 18px;
            font-family:"Droid Arabic Naskh", Arial, Helvetica, sans-serif;

        }
        .image{
      margin-top: 35px;
      margin-right: 50%;
      width:200px;
      height: 200px;
      overflow:hidden;
      display:inline-block;
      background-color:white; /*not necessary, just to show the image box, can be added to img*/
    }
    .decisionh2{
        text-decoration: underline;
        font-family:"Droid Arabic Naskh", Arial, Helvetica, sans-serif;
    }
    </style>
</head>

<body>
    <div dir="rtl">
      <h2 class="decisionh2">القرار :</h2>
       <p> {{ $descision }} </p>
          <div class="image">
            <img src="{{ storage_path('app/public/img_signature/user'. $id .'.jpeg') }}">
          </div>
    </div>

</body>
</html>
