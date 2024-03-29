<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class servTable extends Seeder
{

    //plaint
    public $typeplaint = [
        "[3101] شكاية عادية",
        "[3102] شكاية إهمال الاسرة",
        "[3103] شكاية العنف ضد الأطفال",
        "[3104]	شكاية العنف ضد النساء",
        "[3105]	شكاية الرجوع الى بيت الزوجية (ف 53 م أ)",
        "[3106]	شكاية شيك بدون مؤونة",
        "[3107]	شكاية المعتقلين",
        "[3108]	شكاية الجالية المغربية",
        "[3109]	شكاية ضد المحامون",
        "[3110]	شكاية إستثنائية",
        "[3111]	شكاية ضد العدول",
        "[3112]	شكاية ضد الموثقين",
        "[3113]	شكاية ضد الخبراء",
        "[3114]	شكاية ضد المفوضين القضائيين",
        "[3115]	شكاية ضد ضباط الشرطة و من لهم الإمتياز",
        "[3116]	شكاية ضد التراجمة",
        "[3117]	شكاية العنف ضد الزوج",
        "[3118]	شكاية ضد المحكمين",
        "[3119]	شكاية خرق قانون الإنتخابات",
        "[3120]	شكاية مباشرة أمام قاضي التحقيق",
        "[3121]	شكاية مباشرة أمام المحكمة",
        "[3122]	شكاية قضاء الأسرة  (ف 54 م أ)",
        "[3123]	شكاية الجرائم المالية",
        "[3124]	شكاية الإرهاب",
        "[3125]	شكاية البيئة",
        "[3126]	شكاية خلية حقوق الانسان"
        ];
        public $sourceplaint=["مشتكي", "محامي" ,"وشاية", "اختصاص", "وزارة العدل"," مؤسسة سجنية" , "الوكيل" ,"العام لدى محكمة النقض", "انابة"];
        public $traiteds = ["في طور الدراسة","تمت الدراسة ","تم تأكيد الدراسة","الإحالة على التحقيق","تأكيد التحقيق","ملف تحقيق"];

    public $typepvs=["[3201] محضر عادي","[3202]	محضر تلبسي",
    "[3203]	محضر تلبسي جنحي سير","[3204] محضر تلبسي حوادث السير",
    "[3205]	محضر حوادث السير","[3206] محضر جنح السير",
    "[3207]	محضر مخالفات السير","[3208]	محضر قمع الغش",
    "[3209]	محضر الغش في الآسعار","[3210] محضر مخالفات الغابة",
    "[3211]	محضر بناء بدون رخصة","[3212] محضر محاربة التهريب",
    "[3213]	محضر محاربة المخدرات","[3214] محضر قضايا الأسرة",
    "[3215]	محضر تلبسي أحداث","[3216] محضر العنف ضد النساء",
    "[3217]	محضر العنف ضد القاصرين","[3218]	محضر الإكراه البدني",
    "[3219]	محضر البحث عن متغيب","[3220] محضر تنفيد العقوبة الحبسية",
    "[3221]	محضر ضد ضباط الشرطة و من لهم الإمتياز","[3222] محضر عادي الجرائم المالية",
    "[3223]	محضر تلبسي الجرائم المالية","[3224]	محضر عادي ارهاب",
    "[3225]	محضر تلبسي ارهاب","[3226] محضر مخالفات التعمير",
    "[3227]	محضر عادي أحداث","[3228] محضر البيئة",
    "[3229]	محضر المقالع","[3230] محضر العنف ضد النساء تلبس",
    "[3234]	محضر العنف ضد القاصرين تلبس","[3235] محضر حقوق الإنسان",
    "[3236]	محضر حقوق الطفل","[3237] محضر حماية الأحداث ضحايا العنف",
    "[3238]	محضر الأحداث في وضعية صعبة","[3239]	محضر مخالفات السير (الرادار الثابت)",
    "[3240]	محضر جنح السير  (الرادار الثابت)","[3241] محضر مخالفة قانون الانتخابات"
    ] ;
    public $type_police_judics=["الامن الوطني" , "الدرك الملكي" , "كتاب الضبط الموظفون", "المكلفون بمهمة", "باقي", "محرري المحاضر" ,"السلطة المحلية","الضباط السامون", "وكلاء الملك" ,"هيئة المحامون"];
    public $type_source_pvs=[ "محلي","انابة","اختصاص"];


    public $type_dossiers = ['التحقيق رشداء','التحقيق أحداث'];

    public function dbInsert($table,$values){
        foreach($values as $elt){
           DB::table($table)->insert([
               'nom'=>$elt
           ]);
          }
      }

    public function run()
    {
        $this->dbInsert('typepvs',$this->typepvs);
        $this->dbInsert('type_police_judics',$this->type_police_judics);
        $this->dbInsert('type_source_pvs',$this->type_source_pvs);

        $this->dbInsert('source_plaints',$this->sourceplaint);
        $this->dbInsert('type_plaints',$this->typeplaint);

        $this->dbInsert('traiteds',$this->traiteds);
        $this->dbInsert('type_dossiers',$this->type_dossiers);
    }
}

?>
