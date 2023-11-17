<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Blog;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BlogResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BlogResource\RelationManagers;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // untuk generate form html nya                
                TextInput::make('title')->columnSpan('full')->required(),
                // toggle radio
                // label untuk set label
                // column anggap seperti grid / col di tailwind or bootstrap
                Toggle::make('is_active')->columnSpan('full')->label('Active'),
                // alternatif dari tinymce
                RichEditor::make('content')->columnSpan('full')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // apa aja sih yang mau ditampilkan
                // bisa juga cosplay seperti datatable "sortable, searchable". namun tidak perlu plugin tambahan.
                // limit untuk membatasi string
                TextColumn::make('id')->sortable(),
                TextColumn::make('title')->searchable()->sortable(),
                // karena rencana ingin ada toggle jadi harus memakai "ToggleColumn"
                ToggleColumn::make('is_active')->searchable(),
                TextColumn::make('content')->limit(20)->markdown()->sortable()->searchable(),
                TextColumn::make('created_at')->searchable()->sortable(),
            ])
            ->filters([
                // kalau ada filter, misal munculkan data yg status nya 1
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            // jika data kosong dan ingin memunculkan info "Create a blog to get started."
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
