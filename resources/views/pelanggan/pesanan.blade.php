@extends('layouts.app')
@section('title', 'Input Pesanan')
@section('content')
<div class="w-full p-2 md:p-4 min-h-screen">
    <form method="POST"  action="{{ route('pelanggan.pesanan.store') }}" enctype="multipart/form-data" id="transaksiForm">
        @csrf
        <div class="flex flex-col gap-6 w-full">
            <div class="bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden w-full">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-300">
                    <strong class="text-sm font-semibold text-gray-700 tracking-wider">INPUT PESANAN</strong>
                </div>
                <div class="p-5 flex flex-col gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-600">Invoice</label>
                                <input type="text" name="no_invoice" class="w-full text-sm px-3 py-2 bg-gray-200 border border-gray-300 rounded text-gray-700 font-medium focus:outline-none" id="invoiceNumber" readonly value="{{ $invoiceOtomatis ?? 'INV-'.date('dmy') }}">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-600">Produk<span class="text-red-500">*</span></label>
                                <select id="produkSelect" class="w-full text-sm px-3 py-2 bg-white border border-gray-300 rounded text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($produks as $p)
                                    <option value="{{ $p->id_produk }}" data-harga="{{ $p->harga }}" data-nama="{{ $p->nama_produk }}" data-ukuran="{{ $p->ukuran_default }}">
                                        {{ $p->nama_produk }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-600">Ukuran<span class="text-red-500">*</span></label>
                                <div id="ukuranFixed" style="display: none;">
                                    <input type="text" id="ukuranFixedValue" class="w-full text-sm px-3 py-2 bg-gray-200 border border-gray-300 rounded text-gray-700 focus:outline-none" readonly>
                                </div>
                                <div id="ukuranCustom" style="display: none;">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 flex items-center bg-white border border-gray-300 rounded overflow-hidden">
                                            <input type="number" id="panjangInput" class="w-full text-sm px-3 py-2 focus:outline-none" placeholder="Panjang" step="0.01">
                                        </div>
                                        <span class="text-sm font-semibold text-gray-400">x</span>
                                        <div class="flex-1 flex items-center bg-white border border-gray-300 rounded overflow-hidden">
                                            <input type="number" id="lebarInput" class="w-full text-sm px-3 py-2 focus:outline-none" placeholder="Lebar" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                <div id="ukuranEmpty">
                                    <input type="text" class="w-full text-sm px-3 py-2 bg-gray-100 border border-gray-300 rounded text-gray-400 italic focus:outline-none" placeholder="Pilih produk terlebih dahulu" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-600">Harga</label>
                                <input type="text" id="hargaDisplay" class="w-full text-sm px-3 py-2 bg-gray-200 border border-gray-300 rounded text-gray-700 font-bold focus:outline-none" readonly value="Rp 0">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-600">Qty<span class="text-red-500">*</span></label>
                                <input type="number" id="qtyInput" class="w-full text-sm px-3 py-2 bg-white border border-gray-300 rounded text-gray-700 focus:outline-none focus:border-blue-500" value="1" min="1">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-600">Upload Desain</label>
                                <input type="file" id="desainInput" class="w-full text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded bg-white text-gray-500 focus:outline-none" accept="image/*,application/pdf">
                                <small class="text-xs text-gray-400 font-medium mt-1 block truncate" id="namaFileDesain"></small>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" id="batalItemBtn" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold text-xs px-5 py-2.5 rounded shadow-sm uppercase tracking-wider">Batal</button>
                        <button type="button" id="tambahItemBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-5 py-2.5 rounded shadow-sm uppercase tracking-wider">Simpan</button>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden w-full">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-300">
                    <strong class="text-sm font-semibold text-gray-700 tracking-wider">DETAIL TRANSAKSI</strong>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 font-semibold border-b border-gray-300">
                                <th class="p-3 text-center w-[5%]">No</th>
                                <th class="p-3 w-[30%]">Nama Produk</th>
                                <th class="p-3 text-center w-[12%]">Ukuran</th>
                                <th class="p-3 text-center w-[13%]">Desain</th>
                                <th class="p-3 text-right w-[15%]">Harga</th>
                                <th class="p-3 text-center w-[10%]">Qty</th>
                                <th class="p-3 text-right w-[15%]">Total</th>
                                <th class="p-3 text-center w-[10%]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody" class="text-gray-600 divide-y divide-gray-200">
                            <tr>
                                <td colspan="8" class="text-center py-10 text-gray-400 italic">Belum ada item</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 font-bold text-gray-700 border-t border-gray-300">
                                <td colspan="6" class="p-3 text-right">Subtotal</td>
                                <td colspan="2" class="p-3 text-left pl-6 text-blue-600 font-black text-sm" id="subtotalDisplay">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden w-full">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-300">
                    <strong class="text-sm font-semibold text-gray-700 tracking-wider">PEMBAYARAN TRANSAKSI</strong>
                </div>
                <div class="p-5 flex flex-col gap-4">
                    <div class="mb-2 p-3 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
                        <p>Silakan melakukan pembayaran via <strong>Transfer</strong> ke:</p>
                        @foreach($pembayarans as $pm)
                            @if(strtolower($pm->nama_metode) == 'transfer')
                                <p class="font-bold mt-1">{{ $pm->no_rekening ?? 'Nomor Rekening Belum Diatur' }}</p>
                            @endif
                        @endforeach
                    </div>

                    <input type="hidden" id="cartInput" name="cart">
                    <input type="hidden" name="id_pembayaran" value="{{ $pembayarans->where('nama_metode', 'Transfer')->first()->id_jenis_pembayaran ?? '' }}">
                    <input type="hidden" name="status_pesanan" value="Menunggu Konfirmasi">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                        <div class="flex flex-col gap-4">
                            <div class="grid grid-cols-3 items-start gap-2">
                                <label class="text-xs font-semibold text-gray-600 pt-1">Catatan</label>
                                <div class="col-span-2">
                                    <textarea name="catatan" rows="3" class="w-full text-sm px-3 py-1.5 bg-white border border-gray-300 rounded text-gray-700 focus:border-blue-500 focus:outline-none" placeholder="Masukkan catatan tambahan di sini..."></textarea>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-3 items-center gap-2">
                                <label class="text-xs font-semibold text-gray-600">Tanggal</label>
                                <div class="col-span-2">
                                    <input type="text" value="{{ date('d-m-Y') }}" class="w-full text-sm px-3 py-1.5 bg-gray-100 border border-gray-300 rounded text-gray-700" readonly>
                                    <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-3 items-center gap-2">
                                <label class="text-xs font-semibold text-gray-600">Jenis Bayar</label>
                                <div class="col-span-2">
                                    <input type="text" value="Transfer" class="w-full text-sm px-3 py-1.5 bg-gray-100 border border-gray-300 rounded text-gray-700 font-medium" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="grid grid-cols-3 items-center gap-2">
                                <label class="text-xs font-semibold text-gray-600">Diskon</label>
                                <div class="col-span-2">
                                    <input type="text"  class="w-full text-sm px-3 py-1.5 bg-gray-100 border border-gray-300 rounded text-gray-400 italic" readonly id="diskonInput">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 items-center gap-2">
                                <label class="text-xs font-semibold text-gray-600">Total Tagihan</label>
                                <div class="col-span-2">
                                    <input type="text" id="totalTagihanDisplay" class="w-full text-sm px-3 py-1.5 bg-gray-200 border border-gray-300 rounded text-gray-800 font-bold" readonly>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 items-center gap-2">
                                <label class="text-xs font-semibold text-gray-600">Nominal Transfer</label>
                                <div class="col-span-2">
                                    <input type="number" name="jumlah_bayar" class="w-full text-sm px-3 py-1.5 bg-white border border-gray-300 rounded text-gray-700 focus:border-blue-500 focus:outline-none" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 items-center gap-2">
                                <label class="text-xs font-semibold text-gray-600">Bukti Bayar</label>
                                <div class="col-span-2">
                                    <input type="file" name="bukti_bayar" class="w-full text-sm file:mr-3 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-gray-100 border border-gray-300 rounded bg-white" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-gray-200">
                        <a href="#" class="bg-gray-500 hover:bg-gray-600 text-white font-bold text-xs px-6 py-2.5 rounded shadow transition uppercase tracking-wider">Batal</a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold text-xs px-6 py-2.5 rounded shadow transition uppercase tracking-wider">Proses Transaksi</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- Global State ---
    let cart = [];
    let currentFile = null;
    let currentProduk = null;

    // SweetAlert2 Buttons Styling
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2 text-xs uppercase tracking-wider",
            cancelButton: "bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded text-xs uppercase tracking-wider"
        },
        buttonsStyling: false
    });

    // Helper: Validasi ukuran custom
    function isCustomSize(ukuran) {
        return ukuran && (ukuran.toLowerCase().includes('x') || ukuran.toLowerCase().includes('pxl'));
    }

    // --- Fungsi Global untuk Input ---
    window.updateQty = function(idx, val) {
        let qty = parseInt(val) || 1;
        if (qty < 1) qty = 1;
        // Hitung ulang total: ambil harga satuan asli dengan membagi total lama dengan qty lama
        let hargaSatuan = cart[idx].total / cart[idx].qty;
        cart[idx].qty = qty;
        cart[idx].total = hargaSatuan * qty;
        updateCartDisplay();
    };

    // Hapus baris item tertentu dari keranjang dengan alert
    window.hapusItem = function(idx, nama) {
        swalWithBootstrapButtons.fire({
            title: "Apakah Anda yakin?",
            text: `Item "${nama}" akan dikeluarkan dari daftar belanja transaksi!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                cart.splice(idx, 1);
                updateCartDisplay();

                Swal.fire({
                    title: "Terhapus!",
                    text: "Item berhasil dikeluarkan.",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    };

    // --- Render Tabel (Dengan Logika Diskon Qty) ---
    function updateCartDisplay() {
        let html = '';
        let subtotal = 0;
        let totalQty = 0; // Variabel penampung jumlah barang
        
        cart.forEach((item, idx) => {
            subtotal += item.total;
            totalQty += parseInt(item.qty); // Menghitung total qty

            // --- LOGIKA PREVIEW DESAIN ---
            let desainPreview = '<span class="text-gray-400 italic">Tidak ada</span>';

            if (item.file_desain_base64) {
                // Cek tipe file dari item.file_desain_type (Pastikan Anda sudah menyimpan tipe file saat push ke cart)
                // Jika tidak ada tipe, kita bisa cek berdasarkan ekstensi nama file
                let isImage = item.file_desain_name.match(/\.(jpg|jpeg|png|gif|webp)$/i);

                if (isImage) {
                    // Tampilkan sebagai Gambar
                    desainPreview = `
                        <div class="flex flex-col items-center gap-1">
                            <img src="data:image/*;base64,${item.file_desain_base64}" class="w-12 h-12 object-cover rounded border border-gray-300 shadow-sm mx-auto">
                            <span class="text-[10px] text-gray-500 max-w-[80px] truncate block" title="${item.file_desain_name}">
                                ${item.file_desain_name}
                            </span>
                        </div>`;
                } else {
                    // Tampilkan sebagai Ikon File (untuk PDF/CDR/lainnya)
                    desainPreview = `
                        <div class="flex flex-col items-center gap-1 text-blue-600">
                            <span class="text-xl">📄</span>
                            <span class="text-[10px] text-gray-500 max-w-[80px] truncate block" title="${item.file_desain_name}">
                                ${item.file_desain_name}
                            </span>
                        </div>`;
                }
            }
            
            html += `<tr>
                <td class="p-3 text-center">${idx + 1}</td>
                <td class="p-3">${item.nama_produk}</td>
                <td class="p-3 text-center">${item.ukuran}</td>
                <td class="p-3 text-center">${desainPreview}</td>
                <td class="p-3 text-right">Rp ${(item.total / item.qty).toLocaleString('id-ID')}</td>
                <td class="p-3 text-center">
                    <input type="number" min="1" value="${item.qty}" 
                        class="w-16 border rounded text-center p-1 text-sm" 
                        onchange="updateQty(${idx}, this.value)">
                </td>
                <td class="p-3 text-right">Rp ${item.total.toLocaleString('id-ID')}</td>
                <td class="p-3 text-center">
                    <button type="button" class="text-red-500 font-bold" onclick="hapusItem(${idx}, '${item.nama_produk}')">Hapus</button>
                </td>
            </tr>`;
        });

        if(cart.length === 0) html = '<tr><td colspan="8" class="text-center py-10 text-gray-400 italic">Belum ada item</td></tr>';
        
        document.getElementById('cartTableBody').innerHTML = html;
        document.getElementById('subtotalDisplay').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

        // --- Logika Diskon Berdasarkan Total Qty ---
        let diskonPersen = 0;
        if (totalQty >= 100) {
            diskonPersen = 10;
        } else if (totalQty >= 50) {
            diskonPersen = 5;
        }

        let nilaiDiskon = (subtotal * diskonPersen) / 100;
        let totalAkhir = subtotal - nilaiDiskon;
        // ------------------------------------------

        // Update Field di Form (Pastikan ID ini ada di HTML Anda)
        if(document.getElementById('diskonInput')) {
            document.getElementById('diskonInput').value = nilaiDiskon;
        }
        document.getElementById('totalTagihanDisplay').value = 'Rp ' + totalAkhir.toLocaleString('id-ID');
        document.getElementById('cartInput').value = JSON.stringify(cart);
    }

    // --- Logika Form & Input ---
    function resetInputForm() {
        document.getElementById('produkSelect').value = '';
        if (document.getElementById('panjangInput')) document.getElementById('panjangInput').value = '';
        if (document.getElementById('lebarInput')) document.getElementById('lebarInput').value = '';
        document.getElementById('qtyInput').value = '1';
        document.getElementById('desainInput').value = '';
        document.getElementById('namaFileDesain').innerHTML = '';
        document.getElementById('hargaDisplay').value = 'Rp 0';
        document.getElementById('ukuranFixed').style.display = 'none';
        document.getElementById('ukuranCustom').style.display = 'none';
        document.getElementById('ukuranEmpty').style.display = 'block';
        currentFile = null;
        currentProduk = null;
    }

    function updateUkuranForm() {
        let select = document.getElementById('produkSelect');
        let selectedOption = select.options[select.selectedIndex];
        if (!select.value) { resetInputForm(); return; }
        currentProduk = {
            id: select.value,
            nama: selectedOption.dataset.nama,
            ukuran_default: selectedOption.dataset.ukuran,
            harga_dasar: parseFloat(selectedOption.dataset.harga)
        };
        document.getElementById('ukuranEmpty').style.display = 'none';
        if (isCustomSize(currentProduk.ukuran_default)) {
            document.getElementById('ukuranCustom').style.display = 'block';
            document.getElementById('ukuranFixed').style.display = 'none';
        } else {
            document.getElementById('ukuranFixed').style.display = 'block';
            document.getElementById('ukuranCustom').style.display = 'none';
            document.getElementById('ukuranFixedValue').value = currentProduk.ukuran_default;
        }
        hitungHarga();
    }

    function hitungHarga() {
        if (!currentProduk) return 0;
        let harga = currentProduk.harga_dasar;
        if (isCustomSize(currentProduk.ukuran_default)) {
            let p = parseFloat(document.getElementById('panjangInput').value) || 0;
            let l = parseFloat(document.getElementById('lebarInput').value) || 0;
            harga = currentProduk.harga_dasar * (p > 0 ? p : 1) * (l > 0 ? l : 1);
        }
        document.getElementById('hargaDisplay').value = 'Rp ' + harga.toLocaleString('id-ID');
        return harga;
    }

    document.getElementById('tambahItemBtn').onclick = async function() {
        if (!currentProduk) return alert('Pilih produk dulu!');
        let qty = parseInt(document.getElementById('qtyInput').value) || 1;
        let hargaSatuan = hitungHarga();
        let totalPerItem = hargaSatuan * qty;
        let ukuran = isCustomSize(currentProduk.ukuran_default) 
                     ? document.getElementById('panjangInput').value + 'x' + document.getElementById('lebarInput').value
                     : currentProduk.ukuran_default;

        let fileBase64 = null;
        if (currentFile) {
            fileBase64 = await new Promise(r => {
                let fr = new FileReader();
                fr.onload = () => r(fr.result.split(',')[1]);
                fr.readAsDataURL(currentFile);
            });
        }
        cart.push({
            produk_id: currentProduk.id,
            nama_produk: currentProduk.nama,
            ukuran: ukuran,
            qty: qty,
            total: totalPerItem,
            file_desain_base64: fileBase64,
            file_desain_name: currentFile ? currentFile.name : null
        });
        updateCartDisplay();
        resetInputForm();
    };

    document.getElementById('produkSelect').addEventListener('change', updateUkuranForm);
    document.getElementById('panjangInput').addEventListener('input', hitungHarga);
    document.getElementById('lebarInput').addEventListener('input', hitungHarga);
    document.getElementById('batalItemBtn').addEventListener('click', resetInputForm);
    document.getElementById('desainInput').onchange = (e) => {
        currentFile = e.target.files[0];
        document.getElementById('namaFileDesain').innerText = currentFile ? currentFile.name : '';
    };
</script>
@endsection