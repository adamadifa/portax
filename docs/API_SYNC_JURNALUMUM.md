# API Sync Jurnal Umum - Dokumentasi

## Base URL
```
https://your-domain.com/api
```

---

## 1. Sync Single Jurnal Umum

**Endpoint:** `POST /sync-jurnalumum`

Sync satu data jurnal umum.

### Request Body
```json
{
  "kode_ju": "JU2403001",
  "tanggal": "2026-02-05",
  "keterangan": "Biaya Operasional",
  "jumlah": 500000,
  "debet_kredit": "D",
  "kode_akun": "5-5100",
  "kode_dept": "FIN",
  "kode_peruntukan": "ADM",
  "kode_cabang": "PST"
}
```

### Response Success (200/201)
```json
{
  "success": true,
  "message": "Data jurnal umum berhasil disync",
  "data": {
    "kode_ju": "JU2403001",
    "action": "created",
    "created_at": "2026-02-05 14:45:00"
  }
}
```

---

## 2. Sync Batch Jurnal Umum (REKOMENDASI untuk Sync All)

**Endpoint:** `POST /sync-jurnalumum/batch`

Sync banyak data jurnal umum sekaligus dalam 1 request. **Gunakan endpoint ini untuk fitur "Sync All" agar tidak kena error "Too Many Request".**

**Catatan Penting:**
- `kode_ju` adalah primary key.
- Pastikan `kode_akun` dan `kode_dept` sudah terdaftar di master data server.
- Batch size rekomendasi: 50-100 data per request.
- Jangan ada duplikasi `kode_ju` dalam satu request.

### Request Body
```json
{
  "data": [
    {
      "kode_ju": "JU2403001",
      "tanggal": "2026-02-05",
      "keterangan": "Biaya Operasional",
      "jumlah": 500000,
      "debet_kredit": "D",
      "kode_akun": "5-5100",
      "kode_dept": "FIN",
      "kode_peruntukan": "ADM",
      "kode_cabang": "PST",
      "id_user": 1
    },
    {
      "kode_ju": "JU2403002",
      "tanggal": "2026-02-05",
      "keterangan": "Hutang",
      "jumlah": 500000,
      "debet_kredit": "K",
      "kode_akun": "2-2100",
      "kode_dept": "FIN",
      "kode_peruntukan": "ADM",
      "kode_cabang": "PST",
      "id_user": 1
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
      "kode_ju": "JU2403001",
      "status": "success",
      "message": "Berhasil disync",
      "action": "created"
    },
    {
      "kode_ju": "JU2403002",
      "status": "success",
      "message": "Berhasil diupdate",
      "action": "updated"
    }
  ]
}
```

---

## 3. Check Kode JU

**Endpoint:** `POST /sync-jurnalumum/check`

Cek apakah Kode Ju sudah ada di server.

### Request Body
```json
{
  "kode_ju": "JU2403001"
}
```

### Response
```json
{
  "success": true,
  "exists": true,
  "kode_ju": "JU2403001"
}
```

---

## 4. Delete Single Jurnal Umum

**Endpoint:** `POST /sync-jurnalumum/delete`

### Request Body
```json
{
  "kode_ju": "JU2403001"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Data jurnal umum berhasil dihapus",
  "data": {
    "kode_ju": "JU2403001",
    "deleted_at": "2026-02-05 14:50:00"
  }
}
```

---

## 5. Delete Batch Jurnal Umum

**Endpoint:** `POST /sync-jurnalumum/delete-batch`

### Request Body
```json
{
  "kode_ju": ["JU2403001", "JU2403002"]
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
| `kode_ju` | string | ✅ | 9 | Primary Key / No Bukti JU |
| `tanggal` | date | ✅ | - | Format: YYYY-MM-DD |
| `keterangan` | string | ✅ | 255 | Deskripsi transaksi |
| `jumlah` | integer | ✅ | - | Nominal transaksi |
| `debet_kredit` | string | ✅ | 1 | 'D' (Debet) atau 'K' (Kredit) |
| `kode_akun` | string | ✅ | 6 | Wajib ada di Master COA |
| `kode_dept` | string | ✅ | 3 | Wajib ada di Master Departemen |
| `kode_peruntukan` | string | ✅ | 3 | Kode Alokasi |
| `kode_cabang` | string | ❌ | 3 | Kode Cabang (PST, BDG, dll) |
| `id_user` | integer | ✅ | - | ID User penginput |
