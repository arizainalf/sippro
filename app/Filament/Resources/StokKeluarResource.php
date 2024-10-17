<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokKeluarResource\Pages;
use App\Filament\Resources\StokKeluarResource\RelationManagers;
use App\Models\StokKeluar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StokKeluarResource extends Resource
{
    protected static ?string $model = StokKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-x-mark';

    protected static ?string $navigationLabel = 'Stok Keluar';

    protected static ?string $slug = 'stok-keluar';

    protected static ?int $navigationSort = 3;


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
                    ->label('Tanggal Keluar')
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStokKeluars::route('/'),
            'create' => Pages\CreateStokKeluar::route('/create'),
            'edit' => Pages\EditStokKeluar::route('/{record}/edit'),
        ];
    }
}
