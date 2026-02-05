# API Sync Ledger - Dokumentasi

## Base URL
```
https://your-domain.com/api
```

---

## 1. Sync Single Ledger

**Endpoint:** `POST /sync-ledger`

Sync satu data ledger.

### Request Body
```json
{
  "no_bukti": "BKM24030001",
  "tanggal": "2026-02-05",
  "pelanggan": "Toko Maju Jaya",
  "kode_bank": "BCA",
  "kode_akun": "1-1401",
  "keterangan": "Penerimaan Piutang",
  "jumlah": 1500000,
  "debet_kredit": "D",
  "kode_peruntukan": "ADM",
  "keterangan_peruntukan": "Biaya Admin"
}
```

### Response Success (200/201)
```json
{
  "success": true,
  "message": "Data ledger berhasil disync",
  "data": {
    "no_bukti": "BKM24030001",
    "action": "created",
    "created_at": "2026-02-05 14:10:00"
  }
}
```

---

## 2. Sync Batch Ledger (REKOMENDASI untuk Sync All)

**Endpoint:** `POST /sync-ledger/batch`

Sync banyak data ledger sekaligus dalam 1 request. **Gunakan endpoint ini untuk fitur "Sync All" agar tidak kena error "Too Many Request".**

**Catatan Penting:**
- `no_bukti` adalah primary key yang digunakan untuk mengecek update/insert.
- Pastikan `kode_bank` dan `kode_akun` sudah terdaftar di master data server.
- Batch size rekomendasi: 50-100 data per request.

### Request Body
```json
{
  "data": [
    {
      "no_bukti": "BKM24030001",
      "tanggal": "2026-02-05",
      "pelanggan": "Toko Maju Jaya",
      "kode_bank": "BCA",
      "kode_akun": "1-1401",
      "keterangan": "Penerimaan Piutang",
      "jumlah": 1500000,
      "debet_kredit": "D"
    },
    {
      "no_bukti": "BKK24030002",
      "tanggal": "2026-02-05",
      "pelanggan": null,
      "kode_bank": "KAS",
      "kode_akun": "5-5100",
      "keterangan": "Biaya Operasional",
      "jumlah": 50000,
      "debet_kredit": "K"
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
      "no_bukti": "BKM24030001",
      "status": "success",
      "message": "Berhasil disync",
      "action": "created"
    },
    {
      "no_bukti": "BKK24030002",
      "status": "success",
      "message": "Berhasil diupdate",
      "action": "updated"
    }
  ]
}
```

---

## 3. Check No Bukti Ledger

**Endpoint:** `POST /sync-ledger/check`

Cek apakah No Bukti ledger sudah ada di server.

### Request Body
```json
{
  "no_bukti": "BKM24030001"
}
```

### Response
```json
{
  "success": true,
  "exists": true,
  "no_bukti": "BKM24030001"
}
```

---

## 4. Delete Single Ledger

**Endpoint:** `POST /sync-ledger/delete`

### Request Body
```json
{
  "no_bukti": "BKM24030001"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Data ledger berhasil dihapus",
  "data": {
    "no_bukti": "BKM24030001",
    "deleted_at": "2026-02-05 14:15:00"
  }
}
```

---

## 5. Delete Batch Ledger

**Endpoint:** `POST /sync-ledger/delete-batch`

### Request Body
```json
{
  "no_bukti": ["BKM24030001", "BKK24030002"]
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Hapus batch selesai. Sukses: 2, Gagal: 0",
  "summary": {
    "total": 2,
    "success": 2,
    "failed": 0
  },
  "results": [...]
}
```

---

## Field Reference

| Field | Type | Required | Max Length | Keterangan |
|-------|------|----------|------------|------------|
| `no_bukti` | string | ✅ | 12 | Primary Key / Nomor Bukti Transaksi |
| `tanggal` | date | ✅ | - | Format: YYYY-MM-DD |
| `pelanggan` | string | ❌ | 255 | Nama pelanggan/vendor |
| `kode_bank` | string | ✅ | 5 | Kode Bank (Wajib ada di Master Bank) |
| `kode_akun` | string | ✅ | 6 | Kode Akun COA (Wajib ada di Master COA) |
| `keterangan` | string | ✅ | 255 | Deskripsi transaksi |
| `jumlah` | integer | ✅ | - | Nominal transaksi |
| `debet_kredit` | string | ✅ | 1 | 'D' (Debet) atau 'K' (Kredit) |
| `kode_peruntukan` | string | ❌ | 2 | Kode alokasi dana |
| `keterangan_peruntukan`| string | ❌ | 255 | Keterangan tambahan alokasi |
