<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailStokResource\Pages;
use App\Models\Barang;
use App\Models\DetailStok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DetailStokResource extends Resource
{
    protected static ?string $model = DetailStok::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $navigationLabel = 'Stok Masuk';

    protected static ?string $slug = 'stok-masuk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('barang_id')
                    ->label('Nama Barang')
                    ->options(Barang::all()->pluck('nama', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('no_seri')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barang.nama')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_seri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masuk Tanggal')
                    ->date()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-trash'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->paginated([25,50,100, 'all']);

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
            'index' => Pages\ListDetailStoks::route('/'),
            'create' => Pages\CreateDetailStok::route('/create'),
            'edit' => Pages\EditDetailStok::route('/{record}/edit'),
        ];
    }
}
