<?php

namespace App\Filament\Resources\ArticlesResource\Pages;

use App\Filament\Resources\ArticlesResource;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewArticles extends ViewRecord
{
    protected static string $resource = ArticlesResource::class;

    public function getHeaderActions() : array {
        return [
                Action::make('Accept')->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure to accept this article')
                    ->modalIcon('heroicon-s-check-circle')
                    ->modalSubmitActionLabel('Yes')->modalCancelActionLabel('No')
                    ->action(function (Article $article) {
                        $article->update([
                            'status' => 'Accept',
                        ]);
                        Notification::make()
                            ->title('Success')
                            ->body('Artilce has been accepted successfully')
                            ->success()
                            ->duration(5000)
                            ->send();
                    }),
                
                Action::make('Reject')
                ->color('danger')->requiresConfirmation()
                ->slideOver()
               // ->form()
                ->action(function (Article $article) {
                    $article->update([
                        'status' => 'Reject',
                    ]);

                }),
        ];
           
    }

   
}
