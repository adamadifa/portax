<table class="table table-bordered  table-striped table-hover" id="tabelproduk">
    <thead class="table-dark">
        <tr>
            <th>Kode</th>
            <th>Nama Produk</th>
            <th>Satuan</th>
            <th>Isi Pcs/Dus</th>
            <th>Isi Pack/Dus</th>
            <th>Isi Pcs/Pack</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produk as $d)
            <tr>
                <td>{{ $d->kode_produk }}</td>
                <td>{{ $d->nama_produk }}</td>
                <td>{{ $d->satuan }}</td>
                <td class="text-end">{{ $d->isi_pcs_dus }}</td>
                <td class="text-end">{{ $d->isi_pack_dus }}</td>
                <td class="text-end">{{ $d->isi_pcs_pack }}</td>
                <td>
                    <a href="#" class="pilihProduk" kode_produk="{{ $d->kode_produk }}" nama_produk="{{ $d->nama_produk }}"
                        isi_pcs_dus="{{ $d->isi_pcs_dus }}" isi_pcs_pack="{{ $d->isi_pcs_pack }}">
                        <i class="ti ti-external-link"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
