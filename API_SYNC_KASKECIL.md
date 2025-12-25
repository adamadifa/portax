# API SYNC KAS KECIL

API untuk sync/transfer data kas kecil dari aplikasi lain ke sistem ini.

## Base URL
```
http://your-domain/api/sync
```

---

## 📋 **ENDPOINTS**

### 1. Sync Single Kas Kecil

**Endpoint:** `POST /api/sync/kaskecil`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "no_bukti": "KK2024120001",
    "tanggal": "2024-12-05",
    "keterangan": "Pembelian ATK Kantor",
    "jumlah": 500000,
    "debet_kredit": "K",
    "status_pajak": 0,
    "kode_akun": "6-5101",
    "kode_cabang": "PST",
    "kode_peruntukan": "ATK",
    "cost_ratio": [
        "CR001",
        "CR002"
    ]
}
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "Data kas kecil berhasil disync",
    "data": {
        "id": 123,
        "no_bukti": "KK2024120001",
        "total_cost_ratio": 2,
        "created_at": "2024-12-05 10:30:00"
    }
}
```

**Error Response (422):**
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "id": ["The id field is required."],
        "debet_kredit": ["The debet kredit field must be D or K."]
    }
}
```

**Error Response (404 - Endpoint Tidak Ditemukan):**
```json
{
    "success": false,
    "message": "Endpoint tidak ditemukan (404)",
    "error": {
        "method": "POST",
        "path": "api/sync/kaskecils",
        "requested_url": "http://your-domain/api/sync/kaskecils",
        "cause": "Route tidak terdaftar atau endpoint salah",
        "suggestions": [
            "/api/sync/kaskecil",
            "/api/sync/kaskecil/batch",
            "/api/sync/kaskecil/check"
        ],
        "available_endpoints": {
            "POST /api/sync/penjualan": "Sync single penjualan",
            "POST /api/sync/penjualan/batch": "Sync batch penjualan",
            "POST /api/sync/penjualan/check": "Check no_faktur penjualan",
            "DELETE /api/sync/penjualan": "Delete single penjualan",
            "DELETE /api/sync/penjualan/batch": "Delete batch penjualan",
            "POST /api/sync/kaskecil": "Sync single kas kecil",
            "POST /api/sync/kaskecil/batch": "Sync batch kas kecil",
            "POST /api/sync/kaskecil/check": "Check id kas kecil",
            "DELETE /api/sync/kaskecil": "Delete single kas kecil",
            "DELETE /api/sync/kaskecil/batch": "Delete batch kas kecil",
            "POST /api/sync/ledger": "Sync single ledger",
            "POST /api/sync/ledger/batch": "Sync batch ledger",
            "POST /api/sync/ledger/check": "Check no_bukti ledger",
            "DELETE /api/sync/ledger": "Delete single ledger",
            "DELETE /api/sync/ledger/batch": "Delete batch ledger"
        }
    }
}
```

**Error Response (500 - Server Error):**
```json
{
    "success": false,
    "message": "Gagal sync data kas kecil",
    "error": "SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row..."
}
```

---

### 2. Hapus Kas Kecil

**Endpoint:** `DELETE /api/sync/kaskecil`

**Request Body:**
```json
{
    "no_bukti": "KK2024120001"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Data kas kecil berhasil dihapus",
    "data": {
        "no_bukti": "KK2024120001",
        "deleted_cost_ratio_count": 2,
        "deleted_at": "2024-12-05 11:30:00"
    }
}
```

---

### 3. Cek No Bukti (Duplikasi)

**Endpoint:** `POST /api/sync/kaskecil/check`

**Request Body:**
```json
{
    "no_bukti": "KK2024120001"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "exists": false,
    "no_bukti": "KK2024120001"
}
```

---

### 4. Hapus Batch (Multiple Kas Kecil)

**Endpoint:** `DELETE /api/sync/kaskecil/batch`

**Request Body:**
```json
{
    "no_bukti": [
        "KK2024120001",
        "KK2024120002",
        "KK2024120003"
    ]
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Hapus batch selesai. Sukses: 2, Gagal: 1",
    "summary": {
        "total": 3,
        "success": 2,
        "failed": 1
    },
    "results": [
        {
            "no_bukti": "KK2024120001",
            "status": "success",
            "message": "Berhasil dihapus",
            "deleted_cost_ratio_count": 2
        },
        {
            "no_bukti": "KK2024120002",
            "status": "success",
            "message": "Berhasil dihapus",
            "deleted_cost_ratio_count": 1
        },
        {
            "no_bukti": "KK2024120003",
            "status": "failed",
            "message": "No bukti tidak ditemukan"
        }
    ]
}
```

---

### 5. Sync Batch (Multiple Kas Kecil)

**Endpoint:** `POST /api/sync/kaskecil/batch`

**Request Body:**
```json
{
    "data": [
        {
            "no_bukti": "KK2024120001",
            "tanggal": "2024-12-05",
            "keterangan": "Pembelian ATK",
            "jumlah": 500000,
            "debet_kredit": "K",
            "status_pajak": 0,
            "kode_akun": "6-5101",
            "kode_cabang": "PST",
            "kode_peruntukan": "ATK",
            "cost_ratio": ["CR001"]
        },
        {
            "no_bukti": "KK2024120002",
            "tanggal": "2024-12-05",
            "keterangan": "Bayar Listrik",
            "jumlah": 1000000,
            "debet_kredit": "K",
            "status_pajak": 0,
            "kode_akun": "6-5201",
            "kode_cabang": "PST",
            "kode_peruntukan": "LIS",
            "cost_ratio": ["CR002"]
        }
    ]
}
```

**Success Response (200):**
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
            "no_bukti": "KK2024120001",
            "status": "success",
            "message": "Berhasil disync",
            "cost_ratio_count": 1
        },
        {
            "no_bukti": "KK2024120002",
            "status": "success",
            "message": "Berhasil disync",
            "cost_ratio_count": 1
        }
    ]
}
```

---

## 📝 **FIELD REQUIREMENTS**

### **Required Fields (Header):**
- `no_bukti` - String, max 12 karakter, harus unique
- `tanggal` - Date (format: YYYY-MM-DD)
- `jumlah` - Integer
- `debet_kredit` - String, 1 karakter (D/K)
  - **D** = Debet (Penerimaan)
  - **K** = Kredit (Pengeluaran)
- `kode_akun` - String, max 6 karakter
- `kode_cabang` - String, max 3 karakter

### **Optional Fields (Header):**
- `keterangan` - String, max 255 karakter, nullable
- `status_pajak` - Integer, default: 0
- `kode_peruntukan` - String, max 3 karakter, nullable

### **Optional Fields (Cost Ratio):**
- `cost_ratio` - Array of strings (kode_cr), max 10 karakter per item
- Digunakan untuk tracking biaya per cost center/project

---

## 🔐 **KEAMANAN**

### **Validasi Otomatis:**
1. ✅ No bukti harus unique (tidak boleh duplikat)
2. ✅ Semua foreign key divalidasi
3. ✅ Format tanggal divalidasi
4. ✅ Debet/Kredit hanya menerima D atau K
5. ✅ Transaction rollback otomatis jika error

### **Cascade Delete:**
- Saat hapus kas kecil, cost ratio otomatis terhapus
- Menggunakan DB Transaction untuk keamanan

---

## 💻 **CONTOH IMPLEMENTASI**

### **PHP/Laravel:**

#### **Sync Data:**
```php
use Illuminate\Support\Facades\Http;

// Single Sync
$response = Http::post('http://target-domain/api/sync/kaskecil', [
    'no_bukti' => 'KK2024120001',
    'tanggal' => '2024-12-05',
    'keterangan' => 'Pembelian ATK',
    'jumlah' => 500000,
    'debet_kredit' => 'K',
    'status_pajak' => 0,
    'kode_akun' => '6-5101',
    'kode_cabang' => 'PST',
    'kode_peruntukan' => 'ATK',
    'cost_ratio' => ['CR001', 'CR002']
]);

if ($response->successful()) {
    $data = $response->json();
    echo "Berhasil sync: " . $data['data']['no_bukti'];
    echo "Total cost ratio: " . $data['data']['total_cost_ratio'];
}
```

#### **Hapus Data:**
```php
// Single Delete
$response = Http::delete('http://target-domain/api/sync/kaskecil', [
    'no_bukti' => 'KK2024120001'
]);

// Batch Delete
$response = Http::delete('http://target-domain/api/sync/kaskecil/batch', [
    'no_bukti' => ['KK2024120001', 'KK2024120002']
]);
```

---

### **JavaScript/Axios:**

```javascript
const axios = require('axios');

// Sync Single
async function syncKaskecil(data) {
    try {
        const response = await axios.post('http://target-domain/api/sync/kaskecil', data);
        console.log('Success:', response.data);
        return response.data;
    } catch (error) {
        console.error('Error:', error.response.data);
        throw error;
    }
}

// Delete Single
async function deleteKaskecil(noBukti) {
    try {
        const response = await axios.delete('http://target-domain/api/sync/kaskecil', {
            data: { no_bukti: noBukti }
        });
        console.log('Deleted:', response.data);
    } catch (error) {
        console.error('Error:', error.response.data);
    }
}

// Contoh penggunaan
const kaskecilData = {
    no_bukti: 'KK2024120001',
    tanggal: '2024-12-05',
    keterangan: 'Pembelian ATK',
    jumlah: 500000,
    debet_kredit: 'K',
    kode_akun: '6-5101',
    kode_cabang: 'PST',
    kode_peruntukan: 'ATK',
    cost_ratio: ['CR001', 'CR002']
};

syncKaskecil(kaskecilData);
```

---

### **cURL (Testing):**

#### **Sync Data:**
```bash
curl -X POST http://your-domain/api/sync/kaskecil \
  -H "Content-Type: application/json" \
  -d '{
    "no_bukti": "KK2024120001",
    "tanggal": "2024-12-05",
    "keterangan": "Pembelian ATK",
    "jumlah": 500000,
    "debet_kredit": "K",
    "kode_akun": "6-5101",
    "kode_cabang": "PST",
    "cost_ratio": ["CR001", "CR002"]
  }'
```

#### **Hapus Data:**
```bash
curl -X DELETE http://your-domain/api/sync/kaskecil \
  -H "Content-Type: application/json" \
  -d '{
    "no_bukti": "KK2024120001"
  }'
```

---

## 🎯 **FLOW SYNC DATA**

```
Aplikasi A                          Aplikasi B (Target)
    │                                      │
    │  1. Input kas kecil                 │
    ├──────────────────────►               │
    │                                      │
    │  2. Check no_bukti                  │
    ├─────────POST /check──────────►      │
    │                                      │
    │  3. Response: exists = false        │
    │◄────────────────────────────────────┤
    │                                      │
    │  4. Sync kas kecil + cost ratio     │
    ├─────────POST /sync────────────►     │
    │                                      │
    │  5. Insert ke database              │
    │                       ┌──────────────┤
    │                       │ Transaction  │
    │                       │ - Insert header
    │                       │ - Insert cost ratio
    │                       │ - Commit     │
    │                       └──────────────┤
    │                                      │
    │  6. Response: Success               │
    │◄────────────────────────────────────┤
    │                                      │
```

---

## 📊 **ENDPOINT SUMMARY:**

| No | Endpoint | Method | Fungsi |
|----|----------|--------|--------|
| 1 | `/api/sync/kaskecil` | **POST** | ➕ Sync 1 kas kecil |
| 2 | `/api/sync/kaskecil` | **DELETE** | 🗑️ Hapus 1 kas kecil |
| 3 | `/api/sync/kaskecil/batch` | **POST** | ➕ Sync banyak kas kecil |
| 4 | `/api/sync/kaskecil/batch` | **DELETE** | 🗑️ Hapus banyak kas kecil |
| 5 | `/api/sync/kaskecil/check` | **POST** | ✅ Cek duplikasi |

---

## ✅ **TESTING CHECKLIST:**

- [ ] Sync single kas kecil - success
- [ ] Sync single kas kecil - duplicate (should fail)
- [ ] Sync with missing required fields (should fail)
- [ ] Sync with cost ratio
- [ ] Sync without cost ratio
- [ ] Sync batch - all success
- [ ] Sync batch - partial success
- [ ] Check no_bukti - exists
- [ ] Check no_bukti - not exists
- [ ] Delete single kas kecil - success
- [ ] Delete single kas kecil - not found (should fail)
- [ ] Delete batch - all success
- [ ] Delete batch - partial success

---

## 🎯 **USE CASE**

**Scenario 1: Pencatatan Pengeluaran**
```
Input di App A → Validasi → Sync ke App B → 
Tercatat di kas kecil + Cost Ratio
```

**Scenario 2: Koreksi Data**
```
Data salah → DELETE via API → Input ulang → Sync lagi
```

**Scenario 3: Batch End of Day**
```
Kumpulkan transaksi seharian → Sync batch malam hari →
Semua tercatat sekaligus
```

---

## ⚠️ **ERROR HANDLING**

### **Error 404 - Endpoint Tidak Ditemukan**

Jika endpoint yang dipanggil tidak terdaftar, API akan mengembalikan response 404 yang informatif:

**Contoh Error:**
```json
{
    "success": false,
    "message": "Endpoint tidak ditemukan (404)",
    "error": {
        "method": "POST",
        "path": "api/sync/kaskecils",
        "requested_url": "http://your-domain/api/sync/kaskecils",
        "cause": "Route tidak terdaftar atau endpoint salah",
        "suggestions": [
            "/api/sync/kaskecil",
            "/api/sync/kaskecil/batch",
            "/api/sync/kaskecil/check"
        ],
        "available_endpoints": {
            "POST /api/sync/penjualan": "Sync single penjualan",
            "POST /api/sync/penjualan/batch": "Sync batch penjualan",
            "POST /api/sync/penjualan/check": "Check no_faktur penjualan",
            "DELETE /api/sync/penjualan": "Delete single penjualan",
            "DELETE /api/sync/penjualan/batch": "Delete batch penjualan",
            "POST /api/sync/kaskecil": "Sync single kas kecil",
            "POST /api/sync/kaskecil/batch": "Sync batch kas kecil",
            "POST /api/sync/kaskecil/check": "Check id kas kecil",
            "DELETE /api/sync/kaskecil": "Delete single kas kecil",
            "DELETE /api/sync/kaskecil/batch": "Delete batch kas kecil",
            "POST /api/sync/ledger": "Sync single ledger",
            "POST /api/sync/ledger/batch": "Sync batch ledger",
            "POST /api/sync/ledger/check": "Check no_bukti ledger",
            "DELETE /api/sync/ledger": "Delete single ledger",
            "DELETE /api/sync/ledger/batch": "Delete batch ledger"
        }
    }
}
```

**Informasi yang Diberikan:**
- ✅ **Method** yang digunakan (POST, DELETE, dll)
- ✅ **Path** yang diminta
- ✅ **Full URL** yang diakses
- ✅ **Penyebab** error (Route tidak terdaftar)
- ✅ **Suggestions** - endpoint yang mirip (jika ada)
- ✅ **Daftar semua endpoint** yang tersedia

**Tips:**
- Periksa apakah endpoint yang dipanggil sudah benar (perhatikan typo)
- Periksa method HTTP yang digunakan (POST vs DELETE)
- Gunakan `suggestions` untuk menemukan endpoint yang benar
- Lihat `available_endpoints` untuk daftar lengkap endpoint

### **Error 422 - Validasi Gagal**

Terjadi ketika data yang dikirim tidak memenuhi validasi:
- Field required tidak diisi
- Format data salah
- Tipe data tidak sesuai
- Duplikasi dalam satu request (batch)

### **Error 500 - Server Error**

Terjadi ketika ada error di server:
- Database constraint violation
- Foreign key tidak ditemukan
- Error lainnya di server

**Response akan menyertakan:**
- `message`: Pesan error umum
- `error`: Detail error dari server (untuk debugging)

---

## 🚀 **READY TO USE!**

API Kas Kecil siap digunakan dengan fitur lengkap:
- ✅ **Create/Update** (POST sync - otomatis detect)
- ✅ **Delete** (DELETE)
- ✅ **Check** duplicate
- ✅ **Batch** operations
- ✅ **Cost Ratio** support
- ✅ **Error handling** yang informatif

Dokumentasi lengkap tersedia! 📄








