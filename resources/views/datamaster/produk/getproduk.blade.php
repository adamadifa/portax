<table class="table table-bordered table-striped table-hover" id="tabelproduk">
    <thead class="table-dark">
        <tr>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Satuan</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produk as $d)
            <tr>
                <td>{{ $d->kode_produk }}</td>
                <td>{{ $d->nama_produk }}</td>
                <td>{{ $d->satuan }}</td>
                <td>{{ $d->nama_kategori_produk }}</td>
                <td>
                    <a href="#" class="pilihProduk" 
                       kode_produk="{{ $d->kode_produk }}" 
                       nama_produk="{{ $d->nama_produk }}" 
                       satuan="{{ $d->satuan }}" 
                       kategori="{{ $d->nama_kategori_produk }}"
                       isi_pcs_dus="{{ $d->isi_pcs_dus }}"
                       isi_pcs_pack="{{ $d->isi_pcs_pack }}"
                       kode_kategori_diskon="{{ $d->kode_kategori_diskon }}">
                        <i class="ti ti-plus"></i> Pilih
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
