# API Sync Penjualan - Dokumentasi

## Base URL
```
https://your-domain.com/api
```

---

## 1. Sync Single Penjualan

**Endpoint:** `POST /sync-penjualan`

Sync satu data penjualan.

### Request Body
```json
{
  "no_faktur": "FKT24030001",
  "tanggal": "2026-02-05",
  "kode_pelanggan": "PLG001",
  "kode_salesman": "SLS001",
  "jenis_transaksi": "T",
  "jenis_bayar": "TN",
  "status": "0",
  "kode_akun": "1-1401",
  "kode_akun_potongan": "4-2201",
  "kode_akun_penyesuaian": "4-2202",
  "potongan_aida": 0,
  "potongan_swan": 0,
  "potongan_stick": 0,
  "potongan_sp": 0,
  "potongan_sambal": 0,
  "potongan": 0,
  "potis_aida": 0,
  "potis_swan": 0,
  "potis_stick": 0,
  "potongan_istimewa": 0,
  "peny_aida": 0,
  "peny_swan": 0,
  "peny_stick": 0,
  "penyesuaian": 0,
  "ppn": 0,
  "jatuh_tempo": "2026-03-05",
  "routing": null,
  "signature": null,
  "tanggal_pelunasan": null,
  "print": 0,
  "keterangan": null,
  "status_batal": "0",
  "lock_print": "0",
  "salesman": {
    "kode_salesman": "SLS001",
    "nama_salesman": "John Doe",
    "alamat": "Jl. Contoh No. 123"
  },
  "pelanggan": {
    "kode_pelanggan": "PLG001",
    "nama_pelanggan": "Toko ABC",
    "alamat": "Jl. Pelanggan No. 456"
  },
  "detail": [
    {
      "kode_harga": "H001",
      "harga_dus": 100000,
      "harga_pack": 10000,
      "harga_pcs": 1000,
      "jumlah": 10,
      "subtotal": 1000000,
      "status_promosi": "0"
    }
  ],
  "historibayar": [
    {
      "no_bukti": "BKT24030001",
      "tanggal": "2026-02-05",
      "kode_salesman": "SLS001",
      "jenis_bayar": "TN",
      "jumlah": 500000,
      "voucher": "0",
      "jenis_voucher": "0",
      "kode_lhp": null,
      "kode_akun": "1-1401",
      "keterangan": null
    }
  ]
}
```

### Response Success (201)
```json
{
  "success": true,
  "message": "Data penjualan berhasil disync",
  "data": {
    "no_faktur": "FKT24030001",
    "total_detail": 1,
    "created_at": "2026-02-05 07:20:00"
  }
}
```

---

## 2. Sync Batch Penjualan (REKOMENDASI untuk Sync All)

**Endpoint:** `POST /sync-penjualan/batch`

Sync banyak data penjualan sekaligus dalam 1 request. **Gunakan endpoint ini untuk fitur "Sync All" agar tidak kena error "Too Many Request".**

### Request Body
```json
{
  "data": [
    {
      "no_faktur": "FKT24030001",
      "tanggal": "2026-02-05",
      "kode_pelanggan": "PLG001",
      "kode_salesman": "SLS001",
      "jenis_transaksi": "T",
      "jenis_bayar": "TN",
      "status": "0",
      "detail": [
        {
          "kode_harga": "H001",
          "harga_dus": 100000,
          "harga_pack": 10000,
          "harga_pcs": 1000,
          "jumlah": 10,
          "subtotal": 1000000,
          "status_promosi": "0"
        }
      ],
      "historibayar": []
    },
    {
      "no_faktur": "FKT24030002",
      "tanggal": "2026-02-05",
      "kode_pelanggan": "PLG002",
      "kode_salesman": "SLS001",
      "jenis_transaksi": "K",
      "jenis_bayar": "TN",
      "status": "0",
      "detail": [
        {
          "kode_harga": "H002",
          "harga_dus": 200000,
          "harga_pack": 20000,
          "harga_pcs": 2000,
          "jumlah": 5,
          "subtotal": 1000000,
          "status_promosi": "0"
        }
      ],
      "historibayar": []
    }
  ]
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Sync batch selesai. Sukses: 2, Gagal: 0",
  "summary": {
    "total": 2,
    "success": 2,
    "failed": 0
  },
  "results": [
    {
      "no_faktur": "FKT24030001",
      "status": "success",
      "message": "Berhasil disync"
    },
    {
      "no_faktur": "FKT24030002",
      "status": "success",
      "message": "Berhasil disync"
    }
  ]
}
```

---

## 3. Check Faktur

**Endpoint:** `POST /sync-penjualan/check`

Cek apakah no_faktur sudah ada di server.

### Request Body
```json
{
  "no_faktur": "FKT24030001"
}
```

### Response
```json
{
  "success": true,
  "exists": true,
  "no_faktur": "FKT24030001"
}
```

---

## 4. Delete Single Penjualan

**Endpoint:** `POST /sync-penjualan/delete`

### Request Body
```json
{
  "no_faktur": "FKT24030001"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Data penjualan berhasil dihapus",
  "data": {
    "no_faktur": "FKT24030001",
    "deleted_detail_count": 1,
    "deleted_at": "2026-02-05 07:25:00"
  }
}
```

---

## 5. Delete Batch Penjualan

**Endpoint:** `POST /sync-penjualan/delete-batch`

### Request Body
```json
{
  "no_faktur": ["FKT24030001", "FKT24030002", "FKT24030003"]
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Hapus batch selesai. Sukses: 3, Gagal: 0",
  "summary": {
    "total": 3,
    "success": 3,
    "failed": 0
  },
  "results": [...]
}
```

---

---

## 6. Reset No Faktur New

**Endpoint:** `POST /sync-penjualan/reset-no-fak-new`

Reset (generate ulang) `no_fak_new` untuk periode tertentu. Berguna jika urutan no_fak_new berantakan atau perlu di-resequence. Tanda (-) pada `no_fak_new` akan dihapus dan diganti dengan urutan baru yang kontinu berdasarkan prefix cabang/salesman/tahun.

### Request Body
```json
{
  "periode": "2024-04",
  "kode_cabang": "PST",  // Optional. Jika kosong, semua cabang.
  "kode_salesman": "SLS001" // Optional. Jika kosong, semua salesman di cabang tersebut.
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Reset no_fak_new berhasil",
  "updated_count": 150,
  "periode": "2024-04"
}
```

---

## Field Reference

### Header Penjualan

| Field | Type | Required | Max Length | Keterangan |
|-------|------|----------|------------|------------|
| `no_faktur` | string | ✅ | 13 | Nomor faktur unik |
| `tanggal` | date | ✅ | - | Format: YYYY-MM-DD |
| `kode_pelanggan` | string | ✅ | 13 | - |
| `kode_salesman` | string | ✅ | 7 | - |
| `jenis_transaksi` | string | ✅ | 1 | T=Tunai, K=Kredit |
| `jenis_bayar` | string | ✅ | 2 | - |
| `status` | string | ✅ | 1 | 0=Belum lunas, 1=Lunas |
| `kode_akun` | string | ❌ | 6 | Default: 1-1401 |
| `potongan_*` | integer | ❌ | - | Default: 0 |
| `peny_*` | integer | ❌ | - | Default: 0 |
| `ppn` | integer | ❌ | - | Default: 0 |
| `jatuh_tempo` | date | ❌ | - | Format: YYYY-MM-DD |
| `print` | integer | ❌ | - | Default: 0 |
| `status_batal` | string | ❌ | 1 | Default: 0 |
| `lock_print` | string | ❌ | 1 | Default: 0 |

### Detail Penjualan

| Field | Type | Required | Max Length | Keterangan |
|-------|------|----------|------------|------------|
| `kode_harga` | string | ✅ | 7 | - |
| `harga_dus` | integer | ✅ | - | - |
| `harga_pack` | integer | ✅ | - | - |
| `harga_pcs` | integer | ✅ | - | - |
| `jumlah` | integer | ✅ | - | - |
| `subtotal` | integer | ✅ | - | - |
| `status_promosi` | string | ❌ | 1 | Default: 0 |

### Histori Bayar

| Field | Type | Required | Max Length | Keterangan |
|-------|------|----------|------------|------------|
| `no_bukti` | string | ✅ | 20 | Nomor bukti unik |
| `tanggal` | date | ✅ | - | Format: YYYY-MM-DD |
| `kode_salesman` | string | ❌ | 7 | Default: kode_salesman faktur |
| `jenis_bayar` | string | ✅ | 2 | - |
| `jumlah` | integer | ✅ | - | - |
| `voucher` | string | ❌ | - | Default: 0 |
| `jenis_voucher` | string | ❌ | - | Default: 0 |
| `kode_lhp` | string | ❌ | - | - |
| `kode_akun` | string | ❌ | - | Default: 1-1401 |
| `keterangan` | string | ❌ | - | - |

---

## Tips Implementasi Sync All

### ❌ Cara Lama (Menyebabkan Too Many Request)
```javascript
// JANGAN LAKUKAN INI
for (const item of dataList) {
  await fetch('/api/sync-penjualan', {
    method: 'POST',
    body: JSON.stringify(item)
  });
}
```

### ✅ Cara Baru (Menggunakan Batch)
```javascript
// LAKUKAN INI
const BATCH_SIZE = 50; // Max 50-100 per batch

async function syncAll(dataList) {
  const results = [];
  
  for (let i = 0; i < dataList.length; i += BATCH_SIZE) {
    const batch = dataList.slice(i, i + BATCH_SIZE);
    
    const response = await fetch('/api/sync-penjualan/batch', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ data: batch })
    });
    
    const result = await response.json();
    results.push(result);
  }
  
  return results;
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "no_faktur": ["The no faktur field is required."]
  }
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "No faktur tidak ditemukan",
  "no_faktur": "FKT24030001"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Gagal sync data penjualan",
  "error": "Error message details"
}
```
