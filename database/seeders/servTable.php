<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class servTable extends Seeder
{

    //plaint
    public $sourceplaint=["مشتكي", "محامي" ,"وشاية", "اختصاص", "وزارة العدل"," مؤسسة سجنية" , "الوكيل" ,"العام لدى محكمة النقض", "انابة"];

    public $typeplaint = ["[3101] شكاية عادية","[3102] شكاية إهمال الاسرة","[3103] شكاية العنف ضد الأطفال",
   "[3104]	شكاية العنف ضد النساء",
    "[3106]	شكاية شيك بدون مؤونة",
    "[3107]	شكاية المعتقلين"
];

    public $traiteds = ["في طور الدراسة","تمت الدراسة ","تم تأكيد الدراسة","الإحالة على التحقيق","تأكيد التحقيق","ملف تحقيق"];

    public $typepvs=[ "محضر عادي [3201]","محضر تلبسي [3202]","محضر العنف ضد النساء [3216]","محضر عادي الجرائم المالية[3222]"," محضر تلبسي الجرائم المالية [3223]"] ;
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
