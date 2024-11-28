<?php

namespace App\Filament\Resources\BarangResource\RelationManagers;

use App\Models\Stok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StokRelationManager extends RelationManager
{
    protected static string $relationship = 'stoks';

    protected static ?string $title = 'Stok Keluar Masuk';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\DatePicker::make('tanggal')
                            ->required()
                            ->label('Tanggal')
                            ->default(now()),
                        Forms\Components\Select::make('tipe')
                            ->options([
                                'masuk' => 'Masuk',
                                'keluar' => 'Keluar',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('stok')
                            ->numeric()
                            ->required()
                            ->label('Stok'),
                            ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('jenis')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('D, d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->label('Jenis')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Masuk' => 'success',
                        'Keluar' => 'danger',
                    })
                    ->getStateUsing(fn(Stok $record): string => $record->tipe === 'masuk' ? 'Masuk' : 'Keluar'),
                Tables\Columns\TextColumn::make('stok')
                    ->numeric()
                    ->label('Stok')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
