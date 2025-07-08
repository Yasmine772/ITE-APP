<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RulesForArticlesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rules_for_articles')->insert(
        [
            //  التحقق من الأساسيات 
            [
                'rule_section' => 'التحقق من الأساسيات',
                'rejection_reason' => '؟المقالة تفتقر إلى عنوان رئيسي.'
            ],
            [
                'rule_section' => 'التحقق من الأساسيات',
                'rejection_reason' => '؟المقدمة غير واضحة و لا توضح هدف المقالة و نطاقها البحثي بشكل كافٍ.'
            ],
            [
                'rule_section' => 'التحقق من الأساسيات',
                'rejection_reason' => '؟الخاتمة غير ملخصة للنتائج و لا تقدم استنتاجات واضحة.'
            ],

            // جودة المحتوى واللغة الأكاديمية 
            [
                'rule_section' => 'جودة المحتوى واللغة الأكاديمية',
                'rejection_reason' => '؟المقالة تحتوي على أخطاء إملائية,نحوية,لغوية.'
            ],
            [
                'rule_section' => 'جودة المحتوى واللغة الأكاديمية',
                'rejection_reason' => '؟المفاهيم المعقدة لم تشرح بوضوح , صعبة الفهم على مستوى الطالب الجامعي.'
            ],
            [
                'rule_section' => 'جودة المحتوى واللغة الأكاديمية',
                'rejection_reason' => '؟استخدام المصطلحات العلمية غير دقيق و غير متسق.'
            ],

            // التنظيم والأسلوب الأكاديمي 
            [
                'rule_section' => 'التنظيم والأسلوب الأكاديمي',
                'rejection_reason' => '؟المقالة غير منظمة و تفتقر للعناوين الفرعية.'
            ],
            [
                'rule_section' => 'التنظيم والأسلوب الأكاديمي',
                'rejection_reason' => '؟تسلسل الأفكار غير منطقي لا يوجد ترابط واضح بين الفقرات .'
            ],
            [
                'rule_section' => 'التنظيم والأسلوب الأكاديمي',
                'rejection_reason' => '؟لا يوجد فصل واضح بين الحقائق والآراء و التحليلات الشخصية.'
            ],

            //  الفائدة للطلاب الجامعيين 
            [
                'rule_section' => 'الفائدة للطلاب الجامعيين',
                'rejection_reason' => '؟المقالة لا تقدم قيمة مضافة للطلاب الجامعيين المعلومات سطحية و لا توجد تحليلات أو رؤى نقدية.'
            ],
            [
                'rule_section' => 'الفائدة للطلاب الجامعيين',
                'rejection_reason' => '؟المقالة لا تصلح كمرجع لمشاريع الطلاب أو أبحاثهم بسبب نقص في الموثوقية.'
            ],
        ]);
    }
}
