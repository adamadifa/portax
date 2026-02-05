# API Sync Kas Kecil - Dokumentasi

## Base URL
```
https://your-domain.com/api
```

---

## 1. Sync Single Kas Kecil

**Endpoint:** `POST /sync-kaskecil`

Sync satu data kas kecil.

### Request Body
```json
{
  "id": 12345,
  "no_bukti": "KK24030001",
  "tanggal": "2026-02-05",
  "jumlah": 50000,
  "debet_kredit": "D",
  "kode_akun": "1-1401",
  "kode_cabang": "PST",
  "keterangan": "Pembelian ATK",
  "status_pajak": 0,
  "kode_peruntukan": "HRD",
  "cost_ratio": [
    "CR001",
    "CR002"
  ]
}
```

### Response Success (200/201)
```json
{
  "success": true,
  "message": "Data kas kecil berhasil disync",
  "data": {
    "id": 12345,
    "no_bukti": "KK24030001",
    "total_cost_ratio": 2,
    "action": "created",
    "created_at": "2026-02-05 13:20:00"
  }
}
```

---

## 2. Sync Batch Kas Kecil (REKOMENDASI untuk Sync All)

**Endpoint:** `POST /sync-kaskecil/batch`

Sync banyak data kas kecil sekaligus dalam 1 request. **Gunakan endpoint ini untuk fitur "Sync All" agar tidak kena error "Too Many Request".**

**Catatan Penting:** 
- Pastikan tidak ada duplikasi `id` dalam satu request batch.
- Batch size rekomendasi: 50-100 data per request.

### Request Body
```json
{
  "data": [
    {
      "id": 12345,
      "no_bukti": "KK24030001",
      "tanggal": "2026-02-05",
      "jumlah": 50000,
      "debet_kredit": "D",
      "kode_akun": "1-1401",
      "kode_cabang": "PST",
      "keterangan": "Pembelian ATK",
      "cost_ratio": ["CR001"]
    },
    {
      "id": 12346,
      "no_bukti": "KK24030002",
      "tanggal": "2026-02-05",
      "jumlah": 100000,
      "debet_kredit": "K",
      "kode_akun": "1-1402",
      "kode_cabang": "PST",
      "keterangan": "Reimburse Bensin",
      "cost_ratio": []
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
      "id": 12345,
      "no_bukti": "KK24030001",
      "status": "success",
      "message": "Berhasil disync",
      "action": "created",
      "cost_ratio_count": 1
    },
    {
      "id": 12346,
      "no_bukti": "KK24030002",
      "status": "success",
      "message": "Berhasil diupdate",
      "action": "updated",
      "cost_ratio_count": 0
    }
  ]
}
```

---

## 3. Check ID Kas Kecil

**Endpoint:** `POST /sync-kaskecil/check`

Cek apakah ID kas kecil sudah ada di server.

### Request Body
```json
{
  "id": 12345
}
```

### Response
```json
{
  "success": true,
  "exists": true,
  "id": 12345
}
```

---

## 4. Delete Single Kas Kecil

**Endpoint:** `POST /sync-kaskecil/delete`

### Request Body
```json
{
  "id": 12345
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Data kas kecil berhasil dihapus",
  "data": {
    "id": 12345,
    "no_bukti": "KK24030001",
    "deleted_cost_ratio_count": 2,
    "deleted_at": "2026-02-05 13:25:00"
  }
}
```

---

## 5. Delete Batch Kas Kecil

**Endpoint:** `POST /sync-kaskecil/delete-batch`

### Request Body
```json
{
  "id": [12345, 12346]
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
| `id` | integer | ✅ | - | ID unik dari client |
| `no_bukti` | string | ✅ | 12 | Nomor bukti kas kecil |
| `tanggal` | date | ✅ | - | Format: YYYY-MM-DD |
| `jumlah` | integer | ✅ | - | Nominal |
| `debet_kredit` | string | ✅ | 1 | 'D' (Debet) atau 'K' (Kredit) |
| `kode_akun` | string | ✅ | 6 | Kode akun akuntansi |
| `kode_cabang` | string | ✅ | 3 | Kode cabang (misal: PST, BDG) |
| `keterangan` | string | ❌ | 255 | - |
| `status_pajak` | integer | ❌ | - | Default: 0 |
| `kode_peruntukan` | string | ❌ | 3 | - |
| `cost_ratio` | array | ❌ | - | Array string kode cost ratio |
