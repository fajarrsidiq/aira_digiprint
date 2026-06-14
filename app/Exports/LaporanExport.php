<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // Menambahkan baris total di akhir koleksi
        $totalTagihan = $this->data->sum('total_tagihan');
        $totalBayar = $this->data->sum('jumlah_bayar');
        $totalKurang = $this->data->sum(function($trx) {
            return max(0, $trx->total_tagihan - $trx->jumlah_bayar);
        });

        // Membuat baris total
        $totalRow = new \stdClass();
        $totalRow->is_total = true;
        $totalRow->total_tagihan = $totalTagihan;
        $totalRow->total_bayar = $totalBayar;
        $totalRow->total_kurang = $totalKurang;

        return $this->data->push($totalRow);
    }

    public function headings(): array
    {
        return ['No', 'Invoice', 'Pelanggan', 'Tanggal', 'Total', 'Bayar', 'Metode', 'Kurang'];
    }

    public function map($trx): array
    {
        // Jika ini adalah baris total
        if (isset($trx->is_total)) {
            return [
                'TOTAL', '', '', '', $trx->total_tagihan, $trx->total_bayar, '', $trx->total_kurang
            ];
        }

        return [
            $trx->id,
            $trx->no_invoice,
            $trx->pelanggan->username ?? '-',
            $trx->tanggal->format('d/m/Y'),
            $trx->total_tagihan,
            $trx->jumlah_bayar,
            $trx->pembayaran->nama_metode ?? '-',
            max(0, $trx->total_tagihan - $trx->jumlah_bayar),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->data->count();
        
        return [
            // Header
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D3D3D3']]],
            
            // Baris Total (Baris terakhir)
            $lastRow + 1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E0E0E0']]],

            // Border seluruh tabel
            'A1:H' . ($lastRow + 1) => [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Opsional: format angka menjadi rupiah bisa diatur di sini jika perlu
            },
        ];
    }
}