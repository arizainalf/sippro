<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokMasukResource\Pages;
use App\Filament\Resources\StokMasukResource\RelationManagers;
use App\Models\Barang;
use App\Models\StokMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StokMasukResource extends Resource
{
    protected static ?string $model = StokMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?string $navigationLabel = 'Stok Masuk';
    protected static ?string $slug = 'stok-masuk';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal Masuk')
                    ->default(now()),
                Forms\Components\Select::make('barang_id')
                    ->label('Produk')
                    ->options(Barang::all()->pluck('nama', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('stok')
                    ->label('Stok Masuk')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal'),
                Tables\Columns\TextColumn::make('barang.nama')
                    ->sortable()
                    ->label('Produk'),
                Tables\Columns\TextColumn::make('stok')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('keterangan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListStokMasuks::route('/'),
            // 'create' => Pages\CreateStokMasuk::route('/create'),
            // 'edit' => Pages\EditStokMasuk::route('/{record}/edit'),
        ];
    }
}
