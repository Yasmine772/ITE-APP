<?php

namespace Database\Seeders;

use App\Models\Advice;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use Illuminate\Support\Facades\DB;

class AdviceSeeder extends Seeder
{
    public function run(): void
  {
    //     $faker = Faker::create();

    //     $teacherIds = Teacher::pluck('id')->toArray();
    //     $subjectIds = Subject::pluck('id')->toArray();

    //     for ($i = 0; $i < 20; $i++) {
    //         Advice::create([
    //             'content' => $faker->sentence(12),
    //             'teacher_id' => $faker->randomElement($teacherIds),
    //             'subject_id' => $faker->randomElement($subjectIds),
    //         ]);
    //     }

        $adviceData = [
            [
                'subject_id' => 1,
                'content' => 'الرياضيات 1 هي أساس مهم. ركز على فهم النظريات وتطبيقها في حل المسائل. لا تكتفِ بالحفظ، بل حاول فهم المنطق خلف كل قاعدة.',
                'teacher_id' => 1,
            ],
            [
                'subject_id' => 2,
                'content' => 'الرياضيات 2 تبني على ما تعلمته في الرياضيات 1. قم بمراجعة أساسيات الجزء الأول باستمرار. حل التمارين المتنوعة يساعدك على استيعاب المفاهيم بشكل أعمق.',
                'teacher_id' => 2,
            ],
            [
                'subject_id' => 3,
                'content' => 'الرياضيات تتطلب تطبيقًا عمليًا أكبر للمفاهيم الرياضية. لا تتردد في استخدام الأدوات البرمجية لفهم المشاكل وحلها.',
                'teacher_id' => 1,
            ],
            [
                'subject_id' => 4,
                'content' => 'الرياضيات تركز على مواضيع متقدمة. حضّر للدروس مسبقًا وحاول حل الأمثلة المتقدمة. تعاون مع زملائك في المجموعات.',
                'teacher_id' => 1,
            ],
            [
                'subject_id' => 5,
                'content' => 'البرمجة 1 هي بوابتك لعالم البرمجة. ابدأ بكتابة أكواد بسيطة بنفسك. لا تخف من الأخطاء، فهي جزء من عملية التعلم. قم بإنشاء مشاريع صغيرة لتطبيق المفاهيم النظرية.',
                'teacher_id' => 1,
            ],
            [
                'subject_id' => 6,
                'content' => 'البرمجة 2 تتطلب تطبيقًا عمليًا مكثفًا. حاول حل تحديات برمجية عبر الإنترنت، وقم بتوسيع مشاريعك الصغيرة. التعاون مع الزملاء يمكن أن يسرّع من عملية التعلم.',
                'teacher_id' => 2,
            ],
            [
                'subject_id' => 7,
                'content' => 'في مادة أنظمة التشغيل، ركز على فهم كيفية عمل مكونات النظام الأساسية مثل إدارة الذاكرة والعمليات. استخدم رسومات توضيحية لتلخيص المفاهيم المعقدة.',
                'teacher_id' => 1,
            ],
            [
                'subject_id' => 8,
                'content' => 'هياكل البيانات أساسية في أي تخصص برمجي. حاول فهم كل هيكل بيانات ومتى يتم استخدامه. ارسم المخططات لتوضيح العلاقة بينها قبل كتابة الكود.',
                'teacher_id' => 2,
            ],
            [
                'subject_id' => 9,
                'content' => 'فهم تصميم قواعد البيانات وعلاقاتها أمر بالغ الأهمية. تدرب على كتابة استعلامات SQL معقدة. حاول تصميم قاعدة بيانات لمشروع صغير خاص بك.',
                'teacher_id' => 2,
            ],
            [
                'subject_id' => 10,
                'content' => 'تعد قواعد البيانات العمود الفقري للعديد من التطبيقات. تأكد من فهمك لمفاهيم النمذجة (normalization) والوصول الأمثل للبيانات.',
                'teacher_id' => 7,
            ],
            [
                'subject_id' => 11,
                'content' => 'الشبكات الحاسوبية مادة تعتمد على فهم الطبقات والبروتوكولات. حاول ربط المفاهيم النظرية بتجارب واقعية. استخدم برامج محاكاة الشبكات لتجربة السيناريوهات المختلفة.',
                'teacher_id' => 1,
            ],
            [
                'subject_id' => 12,
                'content' => 'الذكاء الاصطناعي مجال واسع. ابدأ بفهم الخوارزميات الأساسية مثل تعلم الآلة والشبكات العصبية. واطلع على أحدث الأبحاث والتطورات في المجال.',
                'teacher_id' => 1,
            ],
            [
                'subject_id' => 13,
                'content' => 'مشروع التخرج هو تتويج لجهودك. اختر موضوعًا يثير اهتمامك. قم بتقسيم المشروع إلى مهام صغيرة وواقعية. حافظ على التواصل المستمر مع مشرفك وفريقك.',
                'teacher_id' => 2,
            ],
            [
                'subject_id' => 14,
                'content' => 'ابدأ العمل على مشروع التخرج مبكراً. حدد أهدافك بوضوح وتأكد من أن لديك خطة زمنية واقعية.',
                'teacher_id' => 2,
            ],
        ];

        foreach ($adviceData as $advice) {
            DB::table('advice')->insert([
                'content' => $advice['content'],
                'teacher_id' => $advice['teacher_id'],
                'subject_id' => $advice['subject_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
