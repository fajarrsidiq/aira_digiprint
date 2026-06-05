<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $transaksi->no_invoice }}</title>
</head>
<body style="font-family: 'Courier', 'Courier New', monospace; font-size: 11px; color: #000000; line-height: 1.2; margin: 0; padding: 0;">

    <div style="width: 100%; padding: 10px; background-color: #ffffff;">
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
            <tr>
                <td style="width: 55%; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 70px; padding-right: 10px; vertical-align: top;">
                                @if(file_exists(public_path('images/logo-aira.png')))
                                    <img src="{{ public_path('images/logo-aira.png') }}" style="width: 65px; height: 65px; object-fit: contain;">
                                @endif
                            </td>
                            <td style="vertical-align: top;">
                                <div style="font-weight: bold; text-transform: uppercase; font-size: 13px;">CV. AIRA ADVERTISING</div>
                                <div style="font-size: 10px; margin-top: 2px;">Telp. 0813 2005 3678 | Email.</div>
                                <div style="font-weight: bold; font-size: 10px;">aira.adv55@gmail.com</div>
                                <div style="font-size: 10px; margin-top: 3px; color: #333333;">
                                    Jln. Otista II No. 3 Pamoyanan,<br>Cianjur - Jawa Barat
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                
                <td style="width: 45%; padding-left: 25px; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <tr>
                            <td style="width: 45px; color: #555555; vertical-align: top;">No.</td>
                            <td style="font-weight: bold; vertical-align: top;">: {{ $transaksi->no_invoice }}</td>
                        </tr>
                        <tr>
                            <td style="color: #555555; vertical-align: top;">Yth,</td>
                            <td style="text-transform: uppercase; font-weight: bold; vertical-align: top;">: {{ $transaksi->pelanggan->username ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color: #555555; vertical-align: top;">Telp.</td>
                            <td style="vertical-align: top;">: {{ $transaksi->pelanggan->no_telpon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color: #555555; vertical-align: top;">Kota</td>
                            <td style="text-transform: uppercase; vertical-align: top;">: {{ $transaksi->pelanggan->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-style: italic; text-align: right; font-size: 10px; padding-top: 15px; color: #444444; vertical-align: top;">
                                {{ $transaksi->tanggal ? \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d M Y | H:i') : '-' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 5px;">
            <thead>
                <tr style="border-bottom: 3px double #000000; font-weight: bold; text-transform: uppercase; font-size: 11px;">
                    <th style="padding-top: 4px; padding-bottom: 4px; text-align: center; width: 5%;">NO</th>
                    <th style="padding-top: 4px; padding-bottom: 4px; text-align: left; width: 45%; padding-left: 5px;">NAMA PESANAN</th>
                    <th style="padding-top: 4px; padding-bottom: 4px; text-align: center; width: 15%;">UKURAN</th>
                    <th style="padding-top: 4px; padding-bottom: 4px; text-align: right; width: 13%;">HARGA</th>
                    <th style="padding-top: 4px; padding-bottom: 4px; text-align: center; width: 7%;">QTY</th>
                    <th style="padding-top: 4px; padding-bottom: 4px; text-align: right; width: 15%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi->details ?? [] as $index => $detail)
                <tr style="border-bottom: 1px solid #000000; font-size: 11px;">
                    <td style="padding-top: 4px; padding-bottom: 4px; text-align: center; vertical-align: top;">{{ $index + 1 }}</td>
                    <td style="padding-top: 4px; padding-bottom: 4px; text-align: left; text-transform: uppercase; font-weight: bold; padding-left: 5px; vertical-align: top;">{{ $detail->produk->nama_produk ?? '-' }}</td>
                    <td style="padding-top: 4px; padding-bottom: 4px; text-align: center; text-transform: uppercase; vertical-align: top;">{{ $detail->keterangan_ukuran ?? '-' }}</td>
                    <td style="padding-top: 4px; padding-bottom: 4px; text-align: right; vertical-align: top;">Rp {{ number_format($detail->harga_satuan ?? $detail->produk->harga ?? 0, 0, ',', '.') }}</td>
                    <td style="padding-top: 4px; padding-bottom: 4px; text-align: center; vertical-align: top;">{{ $detail->qty }}</td>
                    <td style="padding-top: 4px; padding-bottom: 4px; text-align: right; font-weight: bold; vertical-align: top;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding-top: 8px; padding-bottom: 8px; text-align: center; font-style: italic; color: #666666; vertical-align: top;">Tidak ada item transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
            <tr>
                <td style="width: 52%; vertical-align: top;">
                    <div style="font-style: italic; margin-bottom: 25px; line-height: 1.4;">
                        <span style="font-weight: bold; font-style: normal;">Terbilang :</span> 
                        @php
                            function konversiTerbilang($angka) {
                                $angka = abs($angka);
                                $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
                                $terbilang = "";
                                
                                if ($angka < 12) {
                                    $terbilang = " " . $baca[$angka];
                                } else if ($angka < 20) {
                                    $terbilang = konversiTerbilang($angka - 10) . " belas";
                                } else if ($angka < 100) {
                                    $terbilang = konversiTerbilang(floor($angka / 10)) . " puluh" . konversiTerbilang($angka % 10);
                                } else if ($angka < 200) {
                                    $terbilang = " seratus" . konversiTerbilang($angka - 100);
                                } else if ($angka < 1000) {
                                    $terbilang = konversiTerbilang(floor($angka / 100)) . " ratus" . konversiTerbilang($angka % 100);
                                } else if ($angka < 2000) {
                                    $terbilang = " seribu" . konversiTerbilang($angka - 1000);
                                } else if ($angka < 1000000) {
                                    $terbilang = konversiTerbilang(floor($angka / 1000)) . " ribu" . konversiTerbilang($angka % 1000);
                                } else if ($angka < 1000000000) {
                                    $terbilang = konversiTerbilang(floor($angka / 1000000)) . " juta" . konversiTerbilang($angka % 1000000);
                                } else if ($angka < 1000000000000) {
                                    $terbilang = konversiTerbilang(floor($angka / 1000000000)) . " milyar" . konversiTerbilang(fmod($angka, 1000000000));
                                }
                                return $terbilang;
                            }

                            $total = $transaksi->total_tagihan ?? 0;
                            if($total == 0) {
                                echo "Nol Rupiah";
                            } else {
                                echo ucwords(trim(konversiTerbilang($total))) . " Rupiah";
                            }
                        @endphp
                    </div>
                    
                    <table style="width: 100%; border-collapse: collapse; text-align: center;">
                        <tr>
                            <td style="width: 50%; padding-bottom: 45px; color: #333333; vertical-align: top;">Pelanggan,</td>
                            <td style="width: 50%; padding-bottom: 45px; color: #333333; vertical-align: top;">Hormat Kami,</td>
                        </tr>
                        <tr style="font-weight: bold; text-transform: uppercase;">
                            <td style="vertical-align: top;">( {{ $transaksi->pelanggan->username ?? '...................' }} )</td>
                            <td style="vertical-align: top;">( Admin )</td>
                        </tr>
                    </table>
                </td>

                <td style="width: 48%; padding-left: 5px; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <tr>
                            <td style="text-align: left; color: #444444; width: 40%; vertical-align: top;">SUBTOTAL</td>
                            <td style="text-align: right; width: 15%; vertical-align: top;">Rp</td>
                            <td style="text-align: right; font-weight: bold; width: 45%; vertical-align: top;">{{ number_format(($transaksi->details ?? collect())->sum('subtotal'), 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; color: #444444; vertical-align: top;">DISKON</td>
                            <td style="text-align: right; vertical-align: top;">Rp</td>
                            <td style="text-align: right; font-weight: bold; vertical-align: top;">{{ number_format($transaksi->diskon ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="font-weight: bold;">
                            <td style="text-align: left; border-top: 1px solid #000000; padding-top: 2px; vertical-align: top;">GRANDTOTAL</td>
                            <td style="text-align: right; border-top: 1px solid #000000; padding-top: 2px; vertical-align: top;">Rp</td>
                            <td style="text-align: right; border-top: 1px solid #000000; padding-top: 2px; vertical-align: top;">{{ number_format($transaksi->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                        
                        @php 
                            $sisaKekurangan = $transaksi->total_tagihan - $transaksi->jumlah_bayar;
                            $labelBayar = ($sisaKekurangan <= 0) ? 'LUNAS' : 'DP';
                            $namaMetode = strtoupper($transaksi->pembayaran->nama_metode ?? 'TUNAI');
                        @endphp

                        <tr style="font-weight: bold;">
                            <td style="text-align: left; text-transform: uppercase; vertical-align: top;">{{ $labelBayar }} ({{ $namaMetode }})</td>
                            <td style="text-align: right; vertical-align: top;">Rp</td>
                            <td style="text-align: right; vertical-align: top;">{{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                        
                        <tr style="font-weight: bold;">
                            <td style="text-align: left; border-top: 1px solid #000000; padding-top: 2px; vertical-align: top;">{{ $sisaKekurangan <= 0 ? 'KEMBALI' : 'KURANG' }}</td>
                            <td style="text-align: right; border-top: 1px solid #000000; padding-top: 2px; vertical-align: top;">Rp</td>
                            <td style="text-align: right; border-top: 1px solid #000000; padding-top: 2px; vertical-align: top;">{{ number_format(abs($sisaKekurangan), 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; border-top: 1px solid #000000; padding-top: 5px; font-size: 10px; line-height: 1.4;">
            <tr>
                <td style="vertical-align: top;">
                    <div style="font-weight: bold; margin-top: 5px; margin-bottom: 3px;">Perhatian :</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 10px; line-height: 1.4;">
                        <tr>
                            <td style="width: 15px; vertical-align: top;">1.</td>
                            <td style="vertical-align: top;">Setiap Order Harus Menyertakan DP (Min. 50%).</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">2.</td>
                            <td style="vertical-align: top;">Kami tidak bertanggung jawab jika terdapat kesalahan dalam hasil cetak, apabila konsumen sudah menyetujui dan menyatakan OK untuk hasil settingannya.</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">3.</td>
                            <td style="vertical-align: top;">Hilangnya barang Anda yang belum diambil dalam waktu 1 bulan bukan tanggung jawab kami.</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
</body>
</html>