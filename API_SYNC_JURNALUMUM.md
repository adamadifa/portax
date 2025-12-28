# API SYNC JURNAL UMUM

API untuk sync/transfer data jurnal umum dari aplikasi lain ke sistem ini.

## Base URL
```
http://your-domain/api/sync
```

---

## 📋 **ENDPOINTS**

### 1. Sync Single Jurnal Umum

**Endpoint:** `POST /api/sync/jurnalumum`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "kode_ju": "JL2412001",
    "tanggal": "2024-12-05",
    "keterangan": "Pencatatan transaksi penjualan",
    "jumlah": 5000000,
    "debet_kredit": "D",
    "kode_akun": "1-1401",
    "kode_dept": "AKT",
    "kode_peruntukan": "PC",
    "id_user": 1,
    "kode_cabang": "PST"
}
```

**Success Response (201 - Created):**
```json
{
    "success": true,
    "message": "Data jurnal umum berhasil disync",
    "data": {
        "kode_ju": "JL2412001",
        "action": "created",
        "created_at": "2024-12-05 10:30:00"
    }
}
```

**Success Response (200 - Updated):**
```json
{
    "success": true,
    "message": "Data jurnal umum berhasil diupdate",
    "data": {
        "kode_ju": "JL2412001",
        "action": "updated",
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
        "kode_ju": ["The kode ju field is required."],
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
        "path": "api/sync/jurnalumums",
        "requested_url": "http://your-domain/api/sync/jurnalumums",
        "cause": "Route tidak terdaftar atau endpoint salah",
        "suggestions": [
            "/api/sync/jurnalumum",
            "/api/sync/jurnalumum/batch",
            "/api/sync/jurnalumum/check"
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
            "DELETE /api/sync/ledger/batch": "Delete batch ledger",
            "POST /api/sync/jurnalumum": "Sync single jurnal umum",
            "POST /api/sync/jurnalumum/batch": "Sync batch jurnal umum",
            "POST /api/sync/jurnalumum/check": "Check kode_ju jurnal umum",
            "DELETE /api/sync/jurnalumum": "Delete single jurnal umum",
            "DELETE /api/sync/jurnalumum/batch": "Delete batch jurnal umum"
        }
    }
}
```

**Error Response (500 - Server Error):**
```json
{
    "success": false,
    "message": "Gagal sync data jurnal umum",
    "error": "SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row..."
}
```

---

### 2. Hapus Jurnal Umum

**Endpoint:** `DELETE /api/sync/jurnalumum`

**Request Body:**
```json
{
    "kode_ju": "JL2412001"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Data jurnal umum berhasil dihapus",
    "data": {
        "kode_ju": "JL2412001",
        "deleted_at": "2024-12-05 11:30:00"
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "Kode JU tidak ditemukan",
    "kode_ju": "JL2412001"
}
```

---

### 3. Cek Kode JU (Duplikasi)

**Endpoint:** `POST /api/sync/jurnalumum/check`

**Request Body:**
```json
{
    "kode_ju": "JL2412001"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "exists": true,
    "kode_ju": "JL2412001"
}
```

**Response jika tidak ada:**
```json
{
    "success": true,
    "exists": false,
    "kode_ju": "JL2412001"
}
```

---

### 4. Hapus Batch (Multiple Jurnal Umum)

**Endpoint:** `DELETE /api/sync/jurnalumum/batch`

**Request Body:**
```json
{
    "kode_ju": [
        "JL2412001",
        "JL2412002",
        "JL2412003"
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
            "kode_ju": "JL2412001",
            "status": "success",
            "message": "Berhasil dihapus"
        },
        {
            "kode_ju": "JL2412002",
            "status": "success",
            "message": "Berhasil dihapus"
        },
        {
            "kode_ju": "JL2412003",
            "status": "failed",
            "message": "Kode JU tidak ditemukan"
        }
    ]
}
```

**Error Response (422 - Duplikasi):**
```json
{
    "success": false,
    "message": "Validasi gagal: Terdapat duplikasi Kode JU dalam request",
    "errors": {
        "duplicate_kode_ju": ["JL2412001"]
    }
}
```

---

### 5. Sync Batch (Multiple Jurnal Umum)

**Endpoint:** `POST /api/sync/jurnalumum/batch`

**Request Body:**
```json
{
    "data": [
        {
            "kode_ju": "JL2412001",
            "tanggal": "2024-12-05",
            "keterangan": "Pencatatan transaksi penjualan",
            "jumlah": 5000000,
            "debet_kredit": "D",
            "kode_akun": "1-1401",
            "kode_dept": "AKT",
            "kode_peruntukan": "PC",
            "id_user": 1,
            "kode_cabang": "PST"
        },
        {
            "kode_ju": "JL2412002",
            "tanggal": "2024-12-05",
            "keterangan": "Pencatatan transaksi pembelian",
            "jumlah": 3000000,
            "debet_kredit": "K",
            "kode_akun": "6-5101",
            "kode_dept": "AKT",
            "kode_peruntukan": "OP",
            "id_user": 1,
            "kode_cabang": "PST"
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
            "kode_ju": "JL2412001",
            "status": "success",
            "message": "Berhasil disync",
            "action": "created"
        },
        {
            "kode_ju": "JL2412002",
            "status": "success",
            "message": "Berhasil diupdate",
            "action": "updated"
        }
    ]
}
```

**Error Response (422 - Duplikasi dalam request):**
```json
{
    "success": false,
    "message": "Validasi gagal: Terdapat duplikasi Kode JU dalam request",
    "errors": {
        "duplicate_kode_ju": ["JL2412001"]
    }
}
```

---

## 📝 **FIELD REQUIREMENTS**

### **Required Fields:**
- `kode_ju` - String, max 9 karakter (Key identifier, format: JL + YYMM + 3 digit)
- `tanggal` - Date (format: YYYY-MM-DD)
- `keterangan` - String, max 255 karakter
- `jumlah` - Integer (BigInteger)
- `debet_kredit` - String, 1 karakter (D/K)
  - **D** = Debet (Penerimaan)
  - **K** = Kredit (Pengeluaran)
- `kode_akun` - String, max 6 karakter (COA)
- `kode_dept` - String, max 3 karakter (Departemen)
- `kode_peruntukan` - String, max 2 karakter
- `id_user` - Integer (User ID)

### **Optional Fields:**
- `kode_cabang` - String, max 3 karakter, nullable

---

## 🔐 **KEAMANAN**

### **Validasi Otomatis:**
1. ✅ Kode JU sebagai key identifier (update jika sudah ada, insert jika belum)
2. ✅ Validasi duplikasi kode_ju dalam satu request (batch)
3. ✅ Semua foreign key divalidasi (kode_akun, kode_dept)
4. ✅ Format tanggal divalidasi
5. ✅ Debet/Kredit hanya menerima D atau K
6. ✅ Transaction rollback otomatis jika error

### **Update Logic:**
- Jika `kode_ju` sudah ada di database → **UPDATE** data yang ada
- Jika `kode_ju` belum ada → **INSERT** data baru
- Menggunakan DB Transaction untuk keamanan

---

## 💻 **CONTOH IMPLEMENTASI**

### **PHP/Laravel:**

#### **Sync Data:**
```php
use Illuminate\Support\Facades\Http;

// Single Sync
$response = Http::post('http://target-domain/api/sync/jurnalumum', [
    'kode_ju' => 'JL2412001',
    'tanggal' => '2024-12-05',
    'keterangan' => 'Pencatatan transaksi penjualan',
    'jumlah' => 5000000,
    'debet_kredit' => 'D',
    'kode_akun' => '1-1401',
    'kode_dept' => 'AKT',
    'kode_peruntukan' => 'PC',
    'id_user' => 1,
    'kode_cabang' => 'PST'
]);

if ($response->successful()) {
    $data = $response->json();
    echo "Berhasil sync: " . $data['data']['kode_ju'];
    echo "Action: " . $data['data']['action']; // created atau updated
}
```

#### **Hapus Data:**
```php
// Single Delete
$response = Http::delete('http://target-domain/api/sync/jurnalumum', [
    'kode_ju' => 'JL2412001'
]);

// Batch Delete
$response = Http::delete('http://target-domain/api/sync/jurnalumum/batch', [
    'kode_ju' => ['JL2412001', 'JL2412002']
]);
```

#### **Check Duplikasi:**
```php
$response = Http::post('http://target-domain/api/sync/jurnalumum/check', [
    'kode_ju' => 'JL2412001'
]);

$result = $response->json();
if ($result['exists']) {
    echo "Kode JU sudah ada";
} else {
    echo "Kode JU belum ada, bisa sync";
}
```

---

### **JavaScript/Axios:**

```javascript
const axios = require('axios');

// Sync Single
async function syncJurnalumum(data) {
    try {
        const response = await axios.post('http://target-domain/api/sync/jurnalumum', data);
        console.log('Success:', response.data);
        return response.data;
    } catch (error) {
        console.error('Error:', error.response.data);
        throw error;
    }
}

// Delete Single
async function deleteJurnalumum(kodeJu) {
    try {
        const response = await axios.delete('http://target-domain/api/sync/jurnalumum', {
            data: { kode_ju: kodeJu }
        });
        console.log('Deleted:', response.data);
    } catch (error) {
        console.error('Error:', error.response.data);
    }
}

// Check Duplikasi
async function checkJurnalumum(kodeJu) {
    try {
        const response = await axios.post('http://target-domain/api/sync/jurnalumum/check', {
            kode_ju: kodeJu
        });
        return response.data.exists;
    } catch (error) {
        console.error('Error:', error.response.data);
        return false;
    }
}

// Contoh penggunaan
const jurnalumumData = {
    kode_ju: 'JL2412001',
    tanggal: '2024-12-05',
    keterangan: 'Pencatatan transaksi penjualan',
    jumlah: 5000000,
    debet_kredit: 'D',
    kode_akun: '1-1401',
    kode_dept: 'AKT',
    kode_peruntukan: 'PC',
    id_user: 1,
    kode_cabang: 'PST'
};

// Check dulu sebelum sync
const exists = await checkJurnalumum(jurnalumumData.kode_ju);
if (!exists) {
    await syncJurnalumum(jurnalumumData);
} else {
    console.log('Data sudah ada, akan diupdate');
    await syncJurnalumum(jurnalumumData); // Akan update otomatis
}
```

---

### **cURL (Testing):**

#### **Sync Data:**
```bash
curl -X POST http://your-domain/api/sync/jurnalumum \
  -H "Content-Type: application/json" \
  -d '{
    "kode_ju": "JL2412001",
    "tanggal": "2024-12-05",
    "keterangan": "Pencatatan transaksi penjualan",
    "jumlah": 5000000,
    "debet_kredit": "D",
    "kode_akun": "1-1401",
    "kode_dept": "AKT",
    "kode_peruntukan": "PC",
    "id_user": 1,
    "kode_cabang": "PST"
  }'
```

#### **Hapus Data:**
```bash
curl -X DELETE http://your-domain/api/sync/jurnalumum \
  -H "Content-Type: application/json" \
  -d '{
    "kode_ju": "JL2412001"
  }'
```

#### **Check Duplikasi:**
```bash
curl -X POST http://your-domain/api/sync/jurnalumum/check \
  -H "Content-Type: application/json" \
  -d '{
    "kode_ju": "JL2412001"
  }'
```

#### **Sync Batch:**
```bash
curl -X POST http://your-domain/api/sync/jurnalumum/batch \
  -H "Content-Type: application/json" \
  -d '{
    "data": [
      {
        "kode_ju": "JL2412001",
        "tanggal": "2024-12-05",
        "keterangan": "Pencatatan transaksi penjualan",
        "jumlah": 5000000,
        "debet_kredit": "D",
        "kode_akun": "1-1401",
        "kode_dept": "AKT",
        "kode_peruntukan": "PC",
        "id_user": 1,
        "kode_cabang": "PST"
      },
      {
        "kode_ju": "JL2412002",
        "tanggal": "2024-12-05",
        "keterangan": "Pencatatan transaksi pembelian",
        "jumlah": 3000000,
        "debet_kredit": "K",
        "kode_akun": "6-5101",
        "kode_dept": "AKT",
        "kode_peruntukan": "OP",
        "id_user": 1
      }
    ]
  }'
```

---

## 🎯 **FLOW SYNC DATA**

```
Aplikasi A                          Aplikasi B (Target)
    │                                      │
    │  1. Input jurnal umum               │
    ├──────────────────────►               │
    │                                      │
    │  2. Check kode_ju (optional)        │
    ├─────────POST /check──────────►      │
    │                                      │
    │  3. Response: exists = false         │
    │◄────────────────────────────────────┤
    │                                      │
    │  4. Sync jurnal umum                │
    ├─────────POST /sync────────────►     │
    │                                      │
    │  5. Insert/Update ke database       │
    │                       ┌──────────────┤
    │                       │ Transaction  │
    │                       │ - Check exists
    │                       │ - Insert/Update header
    │                       │ - Commit     │
    │                       └──────────────┤
    │                                      │
    │  6. Response: Success (created/updated)
    │◄────────────────────────────────────┤
    │                                      │
```

---

## 📊 **ENDPOINT SUMMARY:**

| No | Endpoint | Method | Fungsi |
|----|----------|--------|--------|
| 1 | `/api/sync/jurnalumum` | **POST** | ➕ Sync 1 jurnal umum (insert/update) |
| 2 | `/api/sync/jurnalumum` | **DELETE** | 🗑️ Hapus 1 jurnal umum |
| 3 | `/api/sync/jurnalumum/batch` | **POST** | ➕ Sync banyak jurnal umum |
| 4 | `/api/sync/jurnalumum/batch` | **DELETE** | 🗑️ Hapus banyak jurnal umum |
| 5 | `/api/sync/jurnalumum/check` | **POST** | ✅ Cek duplikasi |

---

## ✅ **TESTING CHECKLIST:**

- [ ] Sync single jurnal umum - success (created)
- [ ] Sync single jurnal umum - success (updated)
- [ ] Sync single jurnal umum - missing required fields (should fail)
- [ ] Sync single jurnal umum - invalid debet_kredit (should fail)
- [ ] Sync batch - all success
- [ ] Sync batch - partial success
- [ ] Sync batch - duplicate kode_ju in request (should fail)
- [ ] Check kode_ju - exists
- [ ] Check kode_ju - not exists
- [ ] Delete single jurnal umum - success
- [ ] Delete single jurnal umum - not found (should fail)
- [ ] Delete batch - all success
- [ ] Delete batch - partial success
- [ ] Delete batch - duplicate kode_ju in request (should fail)

---

## 🎯 **USE CASE**

**Scenario 1: Pencatatan Transaksi Akuntansi**
```
Input di App A → Validasi → Sync ke App B → 
Tercatat di jurnal umum
```

**Scenario 2: Koreksi Data**
```
Data salah → DELETE via API → Input ulang → Sync lagi
atau
Sync langsung dengan kode_ju yang sama → Otomatis update
```

**Scenario 3: Batch End of Day**
```
Kumpulkan transaksi seharian → Sync batch malam hari →
Semua tercatat sekaligus
```

**Scenario 4: Update Data**
```
Data sudah ada → Sync dengan kode_ju yang sama →
Otomatis update data yang ada
```

---

## 🔑 **KEY DIFFERENCES DENGAN MODUL LAIN:**

| Aspek | Kas Kecil | Ledger | Jurnal Umum |
|-------|-----------|--------|-------------|
| **Key Identifier** | `id` (integer) | `no_bukti` (string, max 12) | `kode_ju` (string, max 9) |
| **Primary Key** | Auto increment `id` | `no_bukti` (char 12) | `kode_ju` (char 9) |
| **Field Khusus** | `kode_cabang`, `status_pajak` | `kode_bank`, `pelanggan` | `kode_dept`, `kode_peruntukan` |
| **Cost Ratio** | ✅ Support | ❌ Tidak ada | ❌ Tidak ada |
| **Update Logic** | Update jika `id` sudah ada | Update jika `no_bukti` sudah ada | Update jika `kode_ju` sudah ada |

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
        "path": "api/sync/jurnalumums",
        "requested_url": "http://your-domain/api/sync/jurnalumums",
        "cause": "Route tidak terdaftar atau endpoint salah",
        "suggestions": [
            "/api/sync/jurnalumum",
            "/api/sync/jurnalumum/batch",
            "/api/sync/jurnalumum/check"
        ],
        "available_endpoints": {
            "POST /api/sync/jurnalumum": "Sync single jurnal umum",
            "POST /api/sync/jurnalumum/batch": "Sync batch jurnal umum",
            "POST /api/sync/jurnalumum/check": "Check kode_ju jurnal umum",
            "DELETE /api/sync/jurnalumum": "Delete single jurnal umum",
            "DELETE /api/sync/jurnalumum/batch": "Delete batch jurnal umum"
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

### **Error 405 - Method Not Allowed**

Terjadi ketika HTTP method yang digunakan tidak sesuai dengan endpoint yang dipanggil. Misalnya menggunakan GET pada endpoint yang hanya menerima POST, atau menggunakan POST pada endpoint yang hanya menerima DELETE.

**Contoh Error:**
```json
{
    "success": false,
    "message": "Method tidak diizinkan (405)",
    "error": {
        "method_used": "GET",
        "path": "api/sync/jurnalumum",
        "requested_url": "http://your-domain/api/sync/jurnalumum",
        "cause": "Method 'GET' tidak diizinkan untuk endpoint ini",
        "allowed_methods": ["POST", "DELETE"],
        "suggestion": "Gunakan salah satu method berikut untuk endpoint ini:",
        "suggested_endpoints": {
            "POST /api/sync/jurnalumum": "Sync single jurnal umum",
            "DELETE /api/sync/jurnalumum": "Delete single jurnal umum"
        },
        "available_endpoints": {
            "POST /api/sync/jurnalumum": "Sync single jurnal umum",
            "POST /api/sync/jurnalumum/batch": "Sync batch jurnal umum",
            "POST /api/sync/jurnalumum/check": "Check kode_ju jurnal umum",
            "DELETE /api/sync/jurnalumum": "Delete single jurnal umum",
            "DELETE /api/sync/jurnalumum/batch": "Delete batch jurnal umum"
        },
        "detail": "Endpoint 'api/sync/jurnalumum' tidak menerima method 'GET'. Method yang diizinkan: POST, DELETE"
    }
}
```

**Informasi yang Diberikan:**
- ✅ **Method yang digunakan** (GET, POST, DELETE, dll)
- ✅ **Path** yang diminta
- ✅ **Full URL** yang diakses
- ✅ **Penyebab** error (Method tidak diizinkan)
- ✅ **Method yang diizinkan** untuk endpoint tersebut
- ✅ **Suggested endpoints** - endpoint yang sesuai dengan path
- ✅ **Daftar semua endpoint** yang tersedia
- ✅ **Detail** penjelasan lengkap error

**Tips:**
- Periksa method HTTP yang digunakan (POST vs DELETE vs GET)
- Lihat `allowed_methods` untuk mengetahui method yang benar
- Gunakan `suggested_endpoints` untuk menemukan endpoint yang sesuai
- Lihat `available_endpoints` untuk daftar lengkap endpoint

### **Error 422 - Validasi Gagal**

Terjadi ketika data yang dikirim tidak memenuhi validasi:
- Field required tidak diisi
- Format data salah
- Tipe data tidak sesuai
- Duplikasi dalam satu request (batch)
- Foreign key tidak ditemukan (kode_akun, kode_dept)

**Contoh Error:**
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "kode_ju": ["The kode ju field is required."],
        "debet_kredit": ["The debet kredit field must be D or K."],
        "kode_akun": ["Kode akun tidak ditemukan di database"]
    }
}
```

### **Error 500 - Server Error**

Terjadi ketika ada error di server:
- Database constraint violation
- Foreign key tidak ditemukan
- Error lainnya di server

**Contoh Error:**
```json
{
    "success": false,
    "message": "Gagal sync data jurnal umum",
    "error": "Database error: SQLSTATE[23000]: Integrity constraint violation...",
    "details": "Stack trace detail (hanya muncul jika app.debug = true)"
}
```

**Response akan menyertakan:**
- `message`: Pesan error umum
- `error`: Detail error dari server (untuk debugging)
- `details`: Stack trace detail (hanya muncul jika `APP_DEBUG=true` di `.env`)

---

## 🚀 **READY TO USE!**

API Jurnal Umum siap digunakan dengan fitur lengkap:
- ✅ **Create/Update** (POST sync - otomatis detect)
- ✅ **Delete** (DELETE)
- ✅ **Check** duplicate
- ✅ **Batch** operations
- ✅ **Validasi duplikasi** dalam request
- ✅ **Error handling** yang informatif

Dokumentasi lengkap tersedia! 📄

