<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokResource\Pages;
use App\Models\Barang;
use App\Models\Stok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StokResource extends Resource
{
    protected static ?string $model = Stok::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Stok';

    protected static ?string $slug = 'stok';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Stok')
                    ->schema([
                        Forms\Components\Repeater::make('stoks')
                        ->label('Produk')
                            ->schema([
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\DatePicker::make('tanggal')
                                            ->required()
                                            ->label('Tanggal')
                                            ->default(now())
                                            ->columnSpan(1),

                                        Forms\Components\Select::make('barang_id')
                                            ->options(Barang::all()->pluck('nama', 'id'))
                                            ->required()
                                            ->searchable()
                                            ->label('Barang')
                                            ->columnSpan(1),

                                        Forms\Components\Select::make('tipe')
                                            ->options([
                                                'masuk' => 'Masuk',
                                                'keluar' => 'Keluar',
                                            ])
                                            ->required()
                                            ->label('Tipe Transaksi')
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('stok')
                                            ->required()
                                            ->numeric()
                                            ->label('Jumlah Stok')
                                            ->columnSpan(1),

                                        Forms\Components\Textarea::make('keterangan')
                                            ->required()
                                            ->label('Keterangan')
                                            ->columnSpan(4),
                                    ]),
                            ])
                            ->createItemButtonLabel('Tambah Stok')
                            ->deleteAction(
                                fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                            )
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                $state['barang_id']
                                    ? Barang::find($state['barang_id'])?->nama . ' - ' . ($state['tipe'] ?? '')
                                    : null
                            )
                            ->minItems(1)
                            ->maxItems(20)
                            ->columns(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('D, d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('barang.nama')
                    ->label('Produk')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->label('Jenis')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('stok')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->label('Tipe')
                    ->options([
                        'masuk' => 'Masuk',
                        'keluar' => 'Keluar',
                    ]),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->paginated([50, 100, 'all']);
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
            'index' => Pages\ListStoks::route('/'),
            'create' => Pages\CreateStok::route('/create'),
        ];
    }
}
