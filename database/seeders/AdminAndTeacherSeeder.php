<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\StripeConnectService;

class AdminAndTeacherSeeder extends Seeder
{

    public function run(): void
    {


        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'admin',
            'password' => bcrypt('111111'),
        ]);

        $adminRole = Role::firstOrcreate(['name' => 'admin']);
        $adminPermission = [];
        foreach (config('permission.roles_permissions.admin') as $group => $actions) {
            foreach ($actions as $action) {
                $adminPermission[] = "$group.$action";
            }
        }
        foreach ($adminPermission as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            if (!$admin->hasPermissionTo($permission)) {
                $adminRole->givePermissionTo($permission);
            }
        }
        $admin->syncRoles('admin');
        // $admin->getRoleNames();
        $admin->syncPermissions($adminPermission);


        //Teacher
        $teachersData = [
            ['name' => 'مدحت الصوص', 'email' => 'medhat@example.com', 'academic_qualification' => 'دكتوراه في هندسة البرمجيات', 'years_of_experience' => 10, 'degree' => 'دكتور'],
            // ['name' => 'محمد الأحمد', 'email' => 'mohammad.ahmad@example.com', 'academic_qualification' => 'ماجستير في البرمجة', 'years_of_experience' => 8, 'degree' => 'دكتور'],
            // ['name' => 'عبد الرحمن اللحام', 'email' => 'abed.lahham@example.com', 'academic_qualification' => 'ماجستير في البرمجة', 'years_of_experience' => 8, 'degree' => 'دكتور'],
            // ['name' => 'عبد الله العمر', 'email' => 'abdullah.omar@example.com', 'academic_qualification' => 'دكتوراه رياضيات عددية', 'years_of_experience' => 9, 'degree' => 'دكتور'],
            // ['name' => 'إياد الخياط', 'email' => 'eyad.khayat@example.com', 'academic_qualification' => 'دكتوراه في الاتصالات', 'years_of_experience' => 10, 'degree' => 'دكتور'],
            // ['name' => 'عماد الدين محمد', 'email' => 'emad.mohammad@example.com', 'academic_qualification' => 'دكتوراه نظم رقمية', 'years_of_experience' => 7, 'degree' => 'دكتور'],
            // ['name' => 'روان قرعوني', 'email' => 'rawan.qaraoni@example.com', 'academic_qualification' => 'دكتوراه علوم الحاسوب', 'years_of_experience' => 6, 'degree' => 'دكتور'],
            // ['name' => 'أسمهان خضور', 'email' => 'asmakh@example.com', 'academic_qualification' => 'ماجستير رياضيات', 'years_of_experience' => 8, 'degree' => 'دكتورة'],
            // ['name' => 'فاطمة الخضر', 'email' => 'fatima.khuder@example.com', 'academic_qualification' => 'دكتوراه في الإلكترونيات', 'years_of_experience' => 10, 'degree' => 'دكتورة'],
            // ['name' => 'جورج كراز', 'email' => 'george.karaz@example.com', 'academic_qualification' => 'دكتوراه في الذكاء الصنعي', 'years_of_experience' => 11, 'degree' => 'دكتور'],
            // ['name' => 'آلاء الحمصي', 'email' => 'alaa.homsi@example.com', 'academic_qualification' => 'مدرسة لغة إنجليزية', 'years_of_experience' => 8, 'degree' => 'دكتورة'],
            // ['name' => 'رفيف السيد', 'email' => 'rafeef.sayed@example.com', 'academic_qualification' => 'مدرسة مهارات تواصل', 'years_of_experience' => 6, 'degree' => 'دكتورة'],
            // ['name' => 'نورس وظفة', 'email' => 'nawras.wazfa@example.com', 'academic_qualification' => 'دكتوراه في التسويق', 'years_of_experience' => 10, 'degree' => 'دكتور'],
            // ['name' => 'علي حماد', 'email' => 'ali.hamad@example.com', 'academic_qualification' => 'دكتوراه في بروتوكولات الاتصالات', 'years_of_experience' => 12, 'degree' => 'دكتور'],
            // ['name' => 'المثنى خضر', 'email' => 'mothanna.khuder@example.com', 'academic_qualification' => 'دكتوراه نظم تشغيل', 'years_of_experience' => 13, 'degree' => 'دكتور'],
            // ['name' => 'فراس ضعيف', 'email' => 'feras.daif@example.com', 'academic_qualification' => 'دكتوراه في الواقع الافتراضي', 'years_of_experience' => 11, 'degree' => 'دكتور'],
            // ['name' => 'عمر حمدون', 'email' => 'omar.hamdon@example.com', 'academic_qualification' => 'دكتوراه في نظم قواعد المعرفة', 'years_of_experience' => 10, 'degree' => 'دكتور'],
            // ['name' => 'عمار النحاس', 'email' => 'ammar.nahhas@example.com', 'academic_qualification' => 'دكتوراه منطق ترجيحي', 'years_of_experience' => 9, 'degree' => 'دكتور'],
            // ['name' => 'رياض سنيل', 'email' => 'riyad.sneel@example.com', 'academic_qualification' => 'دكتوراه تعلم تلقائي', 'years_of_experience' => 10, 'degree' => 'دكتور'],
            // ['name' => 'خالد عمر', 'email' => 'khaled.omar@example.com', 'academic_qualification' => 'دكتوراه استكشاف معرفة', 'years_of_experience' => 9, 'degree' => 'دكتور'],
            // ['name' => 'خولة العلي', 'email' => 'khawla.ali@example.com', 'academic_qualification' => 'دكتوراه نمذجة ومحاكاة', 'years_of_experience' => 10, 'degree' => 'دكتورة'],
            // ['name' => 'مياد جابر', 'email' => 'meyad.jaber@example.com', 'academic_qualification' => 'دكتوراه نظم زمن حقيقي', 'years_of_experience' => 11, 'degree' => 'دكتورة'],
            // ['name' => 'باسم قصيبة', 'email' => 'basem.kseiba@example.com', 'academic_qualification' => 'دكتوراه نظم موزعة', 'years_of_experience' => 10, 'degree' => 'دكتور'],
            // ['name' => 'أبي صندوق', 'email' => 'aby.sandouq@example.com', 'academic_qualification' => 'دكتوراه نظم بحث معلومات', 'years_of_experience' => 11, 'degree' => 'دكتور'],
            // ['name' => 'صابرين ونوس', 'email' => 'sabreen.wnos@example.com', 'academic_qualification' => 'معيدة مهارات تواصل', 'years_of_experience' => 4, 'degree' => 'دكتورة'],
            // ['name' => 'ماهر صارم', 'email' => 'maher.sarem@example.com', 'academic_qualification' => 'أستاذ في هندسة البرمجيات', 'years_of_experience' => 15, 'degree' => 'دكتورة'],
            // ['name' => 'شذا الخطيب', 'email' => 'shada.khateeb@example.com', 'academic_qualification' => 'ماجستير رياضيات', 'years_of_experience' => 5, 'degree' => 'أستاذة عملي'],
            // ['name' => 'مهند بكر', 'email' => 'muhannad.bakr@example.com', 'academic_qualification' => 'ماجستير تحليل', 'years_of_experience' => 7, 'degree' => 'أستاذ عملي'],
            // ['name' => 'ميساء العبودي', 'email' => 'maysaa.aboudi@example.com', 'academic_qualification' => 'معيدة في البرمجة العملية', 'years_of_experience' => 4, 'degree' => 'أستاذة عملي'],
            // ['name' => 'هبة كلو وانلي', 'email' => 'hiba.kallo@example.com', 'academic_qualification' => 'معيدة في البرمجة العملية', 'years_of_experience' => 3, 'degree' => 'أستاذةعملي'],
            // ['name' => 'نايف الصديق', 'email' => 'naif.sadeeq@example.com', 'academic_qualification' => 'معيد في البرمجة العملية', 'years_of_experience' => 5, 'degree' => 'أستاذ عملي'],
            // ['name' => 'كنان عبد الهادي', 'email' => 'kenan.abdelhadi@example.com', 'academic_qualification' => 'معيد في البرمجة العملية', 'years_of_experience' => 5, 'degree' => 'أستاذ عملي'],
            // ['name' => 'هيام نجم الدين', 'email' => 'hayam.najm@example.com', 'academic_qualification' => 'معيدة في التحليل', 'years_of_experience' => 6, 'degree' => 'أستاذةعملي'],
            // ['name' => 'رنا رنجوس', 'email' => 'rana.ranjous@example.com', 'academic_qualification' => 'معيدة في دارات كهربائية', 'years_of_experience' => 7, 'degree' => 'أستاذةعملي'],
            // ['name' => 'هدى العيسى', 'email' => 'huda.essa@example.com', 'academic_qualification' => 'معيدة في دارات كهربائية', 'years_of_experience' => 7, 'degree' => 'أستاذةعملي'],
            // ['name' => 'مؤيد عالولا', 'email' => 'moayyad.alloula@example.com', 'academic_qualification' => 'معيد في البرمجة العملية', 'years_of_experience' => 4, 'degree' => 'أستاذ عملي'],
            // ['name' => 'غالية صباغ', 'email' => 'ghalia.sabbagh@example.com', 'academic_qualification' => 'معيدة في البرمجة العملية', 'years_of_experience' => 4, 'degree' => 'أستاذةعملي'],
            // ['name' => 'آية الباير', 'email' => 'aya.bair@example.com', 'academic_qualification' => 'معيدة في الاتصالات', 'years_of_experience' => 5, 'degree' => 'أستاذةعملي'],
            // ['name' => 'جهاد دك الباب', 'email' => 'jihad.dakbab@example.com', 'academic_qualification' => 'دكتوراه في التحليل العددي', 'years_of_experience' => 15, 'degree' => 'أستاذ عملي'],
            // ['name' => 'عبير الكجك', 'email' => 'abeer.kj@example.com', 'academic_qualification' => 'ماجستير في التحليل العددي', 'years_of_experience' => 7, 'degree' => 'أستاذةعملي'],
            // ['name' => 'محمد وسيم البزرة', 'email' => 'wasim.bazra@example.com', 'academic_qualification' => 'معيد في مشروع المترجمات', 'years_of_experience' => 4, 'degree' => 'أستاذ عملي'],
            // ['name' => 'نور الحكيم', 'email' => 'nour.hakim@example.com', 'academic_qualification' => 'معيدة نظم وسائط متعددة', 'years_of_experience' => 5, 'degree' => 'أستاذة عملي'],
            // ['name' => 'آلاء الشماع', 'email' => 'alaa.shamaa@example.com', 'academic_qualification' => 'معيدة نظم وسائط متعددة', 'years_of_experience' => 4, 'degree' => 'أستاذة عملي'],
            // ['name' => 'كرم البعيني', 'email' => 'karam.baaini@example.com', 'academic_qualification' => 'ماجستير في هندسة البرمجيات', 'years_of_experience' => 8, 'degree' => 'أستاذ عملي'],
            // ['name' => 'ريناد نوفل', 'email' => 'rinad.noufal@example.com', 'academic_qualification' => 'معيدة في البرمجة التفرعية', 'years_of_experience' => 3, 'degree' => 'مأستاذ عملي'],
            // ['name' => 'عمار المصري', 'email' => 'ammar.masri@example.com', 'academic_qualification' => 'معيد في البرمجة التفرعية', 'years_of_experience' => 4, 'degree' => 'أستاذ عملي'],
            // ['name' => 'يوسف الرفاعي', 'email' => 'youssef.rafie@example.com', 'academic_qualification' => 'ماجستير في الشبكات', 'years_of_experience' => 9, 'degree' => 'أستاذ عملي'],
            // ['name' => 'حلا مرعي', 'email' => 'hala.marai@example.com', 'academic_qualification' => 'معيدة شبكات', 'years_of_experience' => 4, 'degree' => 'أستاذة عملي'],
            // ['name' => 'أماني الحلبي', 'email' => 'amani.halabi@example.com', 'academic_qualification' => 'معيدة نظم وسائط', 'years_of_experience' => 5, 'degree' => 'أستاذة عملي'],
            // ['name' => 'زكريا صافي', 'email' => 'zakaria.safi@example.com', 'academic_qualification' => 'معيد قواعد معرفة', 'years_of_experience' => 4, 'degree' => 'أستاذ عملي'],
            // ['name' => 'نوال الصفدي', 'email' => 'nawal.safadi@example.com', 'academic_qualification' => 'معيدة قواعد معرفة', 'years_of_experience' => 4, 'degree' => 'أستاذة عملي'],
            // ['name' => 'أناستاسيا الحميري', 'email' => 'anastasia.hamiri@example.com', 'academic_qualification' => 'معيدة قواعد معرفة', 'years_of_experience' => 4, 'degree' => 'أستاذة عملي'],
            // ['name' => 'خالد إسماعيل', 'email' => 'khaled.esmail@example.com', 'academic_qualification' => 'معيد واقع افتراضي', 'years_of_experience' => 4, 'degree' => 'أستاذ عملي'],
            // ['name' => 'مصطفى لطف', 'email' => 'mostafa.lotf@example.com', 'academic_qualification' => 'ماجستير نظم الزمن الحقيقي', 'years_of_experience' => 7, 'degree' => 'أستاذ عملي'],
            // ['name' => 'محسن أحمد', 'email' => 'mohsen.ahmad@example.com', 'academic_qualification' => 'ماجستير في المحاكاة', 'years_of_experience' => 7, 'degree' => 'أستاذ عملي'],
            // ['name' => 'مضر عباس', 'email' => 'modar.abbas@example.com', 'academic_qualification' => 'ماجستير أمن شبكات', 'years_of_experience' => 5, 'degree' => 'أستاذ عملي'],
            // ['name' => 'سليمى المحايري', 'email' => 'salima.mohairi@example.com', 'academic_qualification' => 'معيدة نظم بحث', 'years_of_experience' => 5, 'degree' => 'أستاذة عملي'],
            // ['name' => 'مروة الداية', 'email' => 'marwa.dayeh@example.com', 'academic_qualification' => 'معيدة نظم بحث', 'years_of_experience' => 5, 'degree' => 'أستاذة عملي'],
            // ['name' => 'آية معطي', 'email' => 'aya.motie@example.com', 'academic_qualification' => 'معيدة نظم موزعة', 'years_of_experience' => 5, 'degree' => 'أستاذة عملي'],
            // ['name' => 'أمير أبو الشعر', 'email' => 'ameer.aboshaar@example.com', 'academic_qualification' => 'معيد نظم موزعة', 'years_of_experience' => 5, 'degree' => 'أستاذ عملي'],
            // ['name' => 'رغدة البرشة', 'email' => 'raghda.barsha@example.com', 'academic_qualification' => 'معيدة نظم معلومات', 'years_of_experience' => 5, 'degree' => 'أستاذة عملي'],
            // ['name' => 'صبحي الأبوحمد', 'email' => 'sobhi.abouahmad@example.com', 'academic_qualification' => 'معيد نظم معلومات', 'years_of_experience' => 5, 'degree' => 'أستاذ عملي'],
            ['name' => 'آية شحادة', 'email' => 'aya.shahada@example.com', 'academic_qualification' => 'معيدة في مشروع المترجمات', 'years_of_experience' => 4, 'degree' => 'أستاذة عملي'],
        ];
$teacherRole = Role::firstOrCreate(['name' => 'teacher']);
$teacherPermissions = [];

foreach (config('permission.roles_permissions.teacher') as $group => $actions) {
    foreach ($actions as $action) {
        $permissionName = "$group.$action";
        $teacherPermissions[] = $permissionName;
        $permission = Permission::firstOrCreate(['name' => $permissionName]);
        $teacherRole->givePermissionTo($permission);
    }
}

$stripeService = app(StripeConnectService::class);

foreach ($teachersData as $teacherData) {
    $teacher = User::firstOrCreate(
        ['email' => $teacherData['email']],
        [
            'name' => $teacherData['name'],
            'password' => bcrypt('111111'),
        ]
    );

    $teacher->teacher()->updateOrCreate(
        ['user_id' => $teacher->id],
        [
            'academic_qualification' => $teacherData['academic_qualification'],
            'years_of_experience' => $teacherData['years_of_experience'],
            'degree' => $teacherData['degree']
        ]
    );

    $teacher->syncRoles('teacher');
    $teacher->syncPermissions($teacherPermissions);

  
    if (!$teacher->teacher->stripe_account_id) {
        try {
            $accountId = $stripeService->createConnectedAccount($teacher);
            dump(" Created Stripe account for {$teacher->email}: $accountId");
        } catch (\Exception $e) {
            dump(" Stripe error for {$teacher->email}: " . $e->getMessage());
        }
    }
}

}
}
