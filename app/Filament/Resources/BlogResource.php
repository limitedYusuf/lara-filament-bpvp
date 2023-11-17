<?php

namespace App\Filament\Resources;

use App\Models\Blog;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use App\Filament\Resources\BlogResource\Pages;

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
                // input file dengan kondisi image only & tersedia image editor & pastikan private
                FileUpload::make('images')->disk('public')->columnSpan('full')->multiple()->image()->imageEditor()->visibility('private'),
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
                // tampilkan image dalam bentuk bulat dan private (url sementara)
                ImageColumn::make('images')->circular()->stacked()->visibility('private'),
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
