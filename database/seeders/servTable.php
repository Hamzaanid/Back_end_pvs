<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class servTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // datapartie

    public $provinces = ['province1','province2','province3'];
    public $pays = ['pay1','pay2','pays3'];
    public $ville_de_naisses = ['ville1','ville2','ville3'];
    public $situation_famils =['statuion1','statuion2','statuion3'];
    public $nationalites = ['nation1','nation2','nation3'];
    public $genres = ['genre1','genre2','genre3'];
    public $personne_morales = ['pers1','avocat','pers3'];
    public $type_carte_idents = ['national','passport','autre'];

    //plaint

    //public $sourceplaint = ['src1','src2','src3'] ;
    public $sourceplaint=["مشتكي", "محامي" ,"وشاية", "اختصاص", "وزارة العدل"," مؤسسة سجنية" , "الوكيل" ,"العام لدى محكمة النقض", "انابة"];
   // public $typeplaint = ['typep1','typep2','typep3'] ;
    public $typeplaint = ["[3101] شكاية عادية","[3102] شكاية إهمال الاسرة","[3103] شكاية العنف ضد الأطفال",
   "[3104]	شكاية العنف ضد النساء",
    "[3106]	شكاية شيك بدون مؤونة",
    "[3107]	شكاية المعتقلين"
];

    //pvs
   // public $typepvs = ["typepvs1","typepvs2","typepvs3"];
   // public $type_police_judics = ["typrpolice1","typrpolice2","typrpolice3"];
    //public $type_source_pvs = ["typesrcpvs1","typrpolice2","typrpolice3"];
    public $traiteds = ["pas","en cours","traite","autre traite"];
    public $typepvs=[ "محضر عادي [3201]","محضر تلبسي [3202]","محضر العنف ضد النساء [3216]","محضر عادي الجرائم المالية[3222]"," محضر تلبسي الجرائم المالية [3223]"] ;
    public $type_police_judics=["الامن الوطني" , "الدرك الملكي" , "كتاب الضبط الموظفون", "المكلفون بمهمة", "باقي", "محرري المحاضر" ,"السلطة المحلية","الضباط السامون", "وكلاء الملك" ,"هيئة المحامون"];
    public $type_source_pvs=[ "محلي","انابة","اختصاص"];

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

       /* $this->dbInsert('provinces',$this->provinces);
        $this->dbInsert('pays',$this->pays);
        $this->dbInsert('ville_de_naisses',$this->ville_de_naisses);
        $this->dbInsert('situation_famils',$this->situation_famils);
        $this->dbInsert('nationalites',$this->nationalites);
        $this->dbInsert('genres',$this->genres);
        $this->dbInsert('personne_morales',$this->personne_morales);
        $this->dbInsert('type_carte_idents',$this->type_carte_idents);*/
        $this->dbInsert('traiteds',$this->traiteds);
    }
}
