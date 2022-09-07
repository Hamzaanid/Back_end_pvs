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
        font-weight: bold;
        font-size: 26px;
        text-decoration: underline;
        font-family:"Droid Arabic Naskh", Arial, Helvetica, sans-serif;
    }


    </style>
</head>

<body>
    <div dir="rtl">
         <p class="decisionh2"> القرار/الإجراء : </p>
         <p dir="ltr"> <span dir="rtl">{{$text}} </span>  : {{ $NumpvsOuplaint }} </p>

       <p> بتاريخ : {{ $date }}
         <br />
         <br />
         {{ $descision }} </p>
          <div class="image">
            <img src="{{ storage_path('app/public/img_signature/user'. $id .'.jpeg') }}">
          </div>
    </div>

</body>
</html>
