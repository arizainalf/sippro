<?php

namespace App\Filament\Resources\BarangResource\RelationManagers;

use Filament\Forms;
use App\Models\Stok;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use App\Models\DetailStok;
use App\Models\StokKeluar;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
// Ganti ke Tables\Actions\Action

class DetailStoksRelationManager extends RelationManager
{
    protected static string $relationship = 'detailStoks';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('no_seri')
            ->columns([
                Tables\Columns\TextColumn::make('no_seri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Masuk')
                ->date()
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('generate')
                    ->icon('heroicon-o-document-text')
                    ->label('Generate')
                    ->form([
                        Select::make('barang_id')
                            ->label('Pilih Barang')
                            ->options(Barang::all()->pluck('nama', 'id'))
                            ->required()
                            ->searchable(),
                        TextInput::make('no_seri')
                            ->label('No Seri Pertama')
                            ->required(),
                        TextInput::make('stok')
                            ->label('Jumlah')
                            ->required()
                            ->numeric(),
                    ])
                    ->action(function (array $data) {
                        $barangId = $data['barang_id'];
                        $jumlah = $data['stok'];
                        $noSeri = $data['no_seri'];

                        $stok = Barang::where('id', $barangId)->first();

                        for ($i = 0; $i < $jumlah; $i++) {
                            $currentNoSeri = (int) $noSeri + $i;
                            DetailStok::create([
                                'barang_id' => $barangId,
                                'no_seri' => (string) $currentNoSeri,
                            ]);
                        }

                        $stok->update([
                            'stok' => $stok->stok + $jumlah,
                        ]);

                        Notification::make()
                            ->title('Stok Berhasil Ditambahkan')
                            ->body('Stok berhasil ditambahkan untuk barang yang dipilih.')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('info'),
            ])
            ->actions([
                Action::make('keluar')
                    ->label('Keluar')
                    ->action(function (DetailStok $record) {
                        $noSeri = $record->no_seri;
                        $nama = $record->barang->nama;
                        $stok = Barang::where('id', $record->barang_id)->first();
                        $stokKeluar = StokKeluar::create([
                            'no_seri' => $noSeri,
                            'barang_id' => $record->barang_id,
                        ]);
                        $stok->update([
                            'stok' => $stok->stok - 1,
                        ]);
                        $record->delete();
                        Notification::make()
                            ->title('Berhasil')
                            ->body($nama . ' dengan no ' . $noSeri . ' dikeluarkan dari stok.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Action::make('keluar_bulk')
                        ->label('Keluar')
                        ->action(function (array $records) {
                            foreach ($records as $recordId) {
                                $record = DetailStok::find($recordId);
                                $noSeri = $record->no_seri;
                                $nama = $record->barang->nama;
                                $stok = Barang::where('id', $record->barang_id)->first();
            
                                // Create StokKeluar entry
                                StokKeluar::create([
                                    'no_seri' => $noSeri,
                                    'barang_id' => $record->barang_id,
                                ]);
            
                                // Update the barang's stok
                                $stok->update([
                                    'stok' => $stok->stok - 1,
                                ]);
            
                                // Delete the record from DetailStok
                                $record->delete();
                            }
            
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Barang berhasil dikeluarkan dari stok.')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->color('warning'),
                ]),
            ])
            
            ->paginated([100, 'all']);
    }
}
