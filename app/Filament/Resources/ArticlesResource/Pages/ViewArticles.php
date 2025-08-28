<?php

namespace App\Filament\Resources\ArticlesResource\Pages;

use App\Filament\Resources\ArticlesResource;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewArticles extends ViewRecord
{
    protected static string $resource = ArticlesResource::class;

    public function getHeaderActions() : array {

        $rejectionReasons = [
            'no_main_title' => 'المقالة تفتقر إلى عنوان رئيسي.',
            'unclear_introduction' => 'المقدمة غير واضحة و لا توضح هدف المقالة و نطاقها البحثي بشكل كافٍ.',
            'weak_conclusion' => 'الخاتمة غير ملخصة للنتائج و لا تقدم استنتاجات واضحة.',
            'spelling_mistakes' => 'المقالة تحتوي على أخطاء إملائية,نحوية,لغوية.',
            'complex_concepts' => 'المفاهيم المعقدة لم تشرح بوضوح , صعبة الفهم على مستوى الطالب الجامعي.',
            'inaccurate_terminology' => 'استخدام المصطلحات العلمية غير دقيق و غير متسق.',
            'unorganized_article' => 'المقالة غير منظمة و تفتقر للعناوين الفرعية.',
            'illogical_flow' => 'تسلسل الأفكار غير منطقي لا يوجد ترابط واضح بين الفقرات',
            'no_fact_opinion_separation' => 'لا يوجد فصل واضح بين الحقائق والآراء و التحليلات الشخصية.',
            'low_added_value' => 'المقالة لا تقدم قيمة مضافة للطلاب الجامعيين المعلومات سطحية و لا توجد تحليلات أو رؤى نقدية.',
            'unreliable_source' => 'المقالة لا تصلح كمرجع لمشاريع الطلاب أو أبحاثهم بسبب نقص في الموثوقية.'
        ];

        return [

            //Accept article :

            Action::make('Accept')->label('قبول')->color('success')
                ->requiresConfirmation()
                ->modalDescription('هل أنت متأكد من قبول هذه المقالة؟')
                ->modalIcon('heroicon-s-check-circle')
                ->modalSubmitActionLabel('نعم')->modalCancelActionLabel('لا')
                ->action(function (Article $article) {
                    $article->update([
                        'status' => 'Accept',
                    ]);
                    Notification::make()
                        ->body('تم قبول المقال بنجاح')
                        ->success()->duration(5000)->send();
                }),

                //Reject article:

            Action::make('Reject')->label('رفض')->color('danger')
                ->requiresConfirmation()->slideOver()
                ->modalDescription('الرجاء تحديد أسباب الرفض.')
                ->modalSubmitActionLabel('تأكيد الرفض')->modalCancelActionLabel('إلغاء')
                ->form([
                    CheckboxList::make('rejection_reasons')
                        ->label('أسباب الرفض')
                        ->options($rejectionReasons)
                        ->required()
                ])
                ->action(function (array $data) use ($rejectionReasons) {
                    $selectedLabels = array_map(function ($key) use ($rejectionReasons) {
                        return $rejectionReasons[$key] ?? null;
                    }, $data['rejection_reasons']);

                    $article = $this->record;

                    $article->update([
                        'status' => 'Reject',
                        'reasonsOfReject' => json_encode(array_filter($selectedLabels),JSON_UNESCAPED_UNICODE),
                    ]);

                    Notification::make()
                        ->body('تم رفض المقال بنجاح.')
                        ->success()->duration(5000)->send();
                })
        ];      
    }
}
