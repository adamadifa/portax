# API SYNC LEDGER

API untuk sync/transfer data ledger dari aplikasi lain ke sistem ini.

## Base URL
```
http://your-domain/api/sync
```

---

## 📋 **ENDPOINTS**

### 1. Sync Single Ledger

**Endpoint:** `POST /api/sync/ledger`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "no_bukti": "LEDG2024120001",
    "tanggal": "2024-12-05",
    "pelanggan": "PT ABC",
    "kode_bank": "BNK01",
    "kode_akun": "110001",
    "keterangan": "Pembayaran invoice",
    "jumlah": 5000000,
    "debet_kredit": "D",
    "kode_peruntukan": "01",
    "keterangan_peruntukan": "Operasional"
}
```

**Success Response (201 - Created):**
```json
{
    "success": true,
    "message": "Data ledger berhasil disync",
    "data": {
        "no_bukti": "LEDG2024120001",
        "action": "created",
        "created_at": "2024-12-05 10:30:00"
    }
}
```

**Success Response (200 - Updated):**
```json
{
    "success": true,
    "message": "Data ledger berhasil diupdate",
    "data": {
        "no_bukti": "LEDG2024120001",
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
        "no_bukti": ["The no bukti field is required."],
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
        "path": "api/sync/ledgers",
        "requested_url": "http://your-domain/api/sync/ledgers",
        "cause": "Route tidak terdaftar atau endpoint salah",
        "suggestions": [
            "/api/sync/ledger",
            "/api/sync/ledger/batch",
            "/api/sync/ledger/check"
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
    "message": "Gagal sync data ledger",
    "error": "SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row..."
}
```

---

### 2. Hapus Ledger

**Endpoint:** `DELETE /api/sync/ledger`

**Request Body:**
```json
{
    "no_bukti": "LEDG2024120001"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Data ledger berhasil dihapus",
    "data": {
        "no_bukti": "LEDG2024120001",
        "deleted_at": "2024-12-05 11:30:00"
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "No bukti tidak ditemukan",
    "no_bukti": "LEDG2024120001"
}
```

---

### 3. Cek No Bukti (Duplikasi)

**Endpoint:** `POST /api/sync/ledger/check`

**Request Body:**
```json
{
    "no_bukti": "LEDG2024120001"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "exists": true,
    "no_bukti": "LEDG2024120001"
}
```

**Response jika tidak ada:**
```json
{
    "success": true,
    "exists": false,
    "no_bukti": "LEDG2024120001"
}
```

---

### 4. Hapus Batch (Multiple Ledger)

**Endpoint:** `DELETE /api/sync/ledger/batch`

**Request Body:**
```json
{
    "no_bukti": [
        "LEDG2024120001",
        "LEDG2024120002",
        "LEDG2024120003"
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
            "no_bukti": "LEDG2024120001",
            "status": "success",
            "message": "Berhasil dihapus"
        },
        {
            "no_bukti": "LEDG2024120002",
            "status": "success",
            "message": "Berhasil dihapus"
        },
        {
            "no_bukti": "LEDG2024120003",
            "status": "failed",
            "message": "No bukti tidak ditemukan"
        }
    ]
}
```

**Error Response (422 - Duplikasi):**
```json
{
    "success": false,
    "message": "Validasi gagal: Terdapat duplikasi No Bukti dalam request",
    "errors": {
        "duplicate_no_bukti": ["LEDG2024120001"]
    }
}
```

---

### 5. Sync Batch (Multiple Ledger)

**Endpoint:** `POST /api/sync/ledger/batch`

**Request Body:**
```json
{
    "data": [
        {
            "no_bukti": "LEDG2024120001",
            "tanggal": "2024-12-05",
            "pelanggan": "PT ABC",
            "kode_bank": "BNK01",
            "kode_akun": "110001",
            "keterangan": "Pembayaran invoice",
            "jumlah": 5000000,
            "debet_kredit": "D",
            "kode_peruntukan": "01",
            "keterangan_peruntukan": "Operasional"
        },
        {
            "no_bukti": "LEDG2024120002",
            "tanggal": "2024-12-05",
            "pelanggan": "PT XYZ",
            "kode_bank": "BNK02",
            "kode_akun": "110002",
            "keterangan": "Penerimaan pembayaran",
            "jumlah": 10000000,
            "debet_kredit": "K",
            "kode_peruntukan": "02",
            "keterangan_peruntukan": "Penjualan"
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
            "no_bukti": "LEDG2024120001",
            "status": "success",
            "message": "Berhasil disync",
            "action": "created"
        },
        {
            "no_bukti": "LEDG2024120002",
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
    "message": "Validasi gagal: Terdapat duplikasi No Bukti dalam request",
    "errors": {
        "duplicate_no_bukti": ["LEDG2024120001"]
    }
}
```

---

## 📝 **FIELD REQUIREMENTS**

### **Required Fields:**
- `no_bukti` - String, max 12 karakter (Key identifier)
- `tanggal` - Date (format: YYYY-MM-DD)
- `kode_bank` - String, max 5 karakter
- `kode_akun` - String, max 6 karakter (COA)
- `keterangan` - String, max 255 karakter
- `jumlah` - Integer (BigInteger)
- `debet_kredit` - String, 1 karakter (D/K)
  - **D** = Debet (Penerimaan)
  - **K** = Kredit (Pengeluaran)

### **Optional Fields:**
- `pelanggan` - String, max 255 karakter, nullable
- `kode_peruntukan` - String, max 2 karakter, nullable
- `keterangan_peruntukan` - String, max 255 karakter, nullable

---

## 🔐 **KEAMANAN**

### **Validasi Otomatis:**
1. ✅ No bukti sebagai key identifier (update jika sudah ada, insert jika belum)
2. ✅ Validasi duplikasi no_bukti dalam satu request (batch)
3. ✅ Semua foreign key divalidasi (kode_bank, kode_akun)
4. ✅ Format tanggal divalidasi
5. ✅ Debet/Kredit hanya menerima D atau K
6. ✅ Transaction rollback otomatis jika error

### **Update Logic:**
- Jika `no_bukti` sudah ada di database → **UPDATE** data yang ada
- Jika `no_bukti` belum ada → **INSERT** data baru
- Menggunakan DB Transaction untuk keamanan

---

## 💻 **CONTOH IMPLEMENTASI**

### **PHP/Laravel:**

#### **Sync Data:**
```php
use Illuminate\Support\Facades\Http;

// Single Sync
$response = Http::post('http://target-domain/api/sync/ledger', [
    'no_bukti' => 'LEDG2024120001',
    'tanggal' => '2024-12-05',
    'pelanggan' => 'PT ABC',
    'kode_bank' => 'BNK01',
    'kode_akun' => '110001',
    'keterangan' => 'Pembayaran invoice',
    'jumlah' => 5000000,
    'debet_kredit' => 'D',
    'kode_peruntukan' => '01',
    'keterangan_peruntukan' => 'Operasional'
]);

if ($response->successful()) {
    $data = $response->json();
    echo "Berhasil sync: " . $data['data']['no_bukti'];
    echo "Action: " . $data['data']['action']; // created atau updated
}
```

#### **Hapus Data:**
```php
// Single Delete
$response = Http::delete('http://target-domain/api/sync/ledger', [
    'no_bukti' => 'LEDG2024120001'
]);

// Batch Delete
$response = Http::delete('http://target-domain/api/sync/ledger/batch', [
    'no_bukti' => ['LEDG2024120001', 'LEDG2024120002']
]);
```

#### **Check Duplikasi:**
```php
$response = Http::post('http://target-domain/api/sync/ledger/check', [
    'no_bukti' => 'LEDG2024120001'
]);

$result = $response->json();
if ($result['exists']) {
    echo "No bukti sudah ada";
} else {
    echo "No bukti belum ada, bisa sync";
}
```

---

### **JavaScript/Axios:**

```javascript
const axios = require('axios');

// Sync Single
async function syncLedger(data) {
    try {
        const response = await axios.post('http://target-domain/api/sync/ledger', data);
        console.log('Success:', response.data);
        return response.data;
    } catch (error) {
        console.error('Error:', error.response.data);
        throw error;
    }
}

// Delete Single
async function deleteLedger(noBukti) {
    try {
        const response = await axios.delete('http://target-domain/api/sync/ledger', {
            data: { no_bukti: noBukti }
        });
        console.log('Deleted:', response.data);
    } catch (error) {
        console.error('Error:', error.response.data);
    }
}

// Check Duplikasi
async function checkLedger(noBukti) {
    try {
        const response = await axios.post('http://target-domain/api/sync/ledger/check', {
            no_bukti: noBukti
        });
        return response.data.exists;
    } catch (error) {
        console.error('Error:', error.response.data);
        return false;
    }
}

// Contoh penggunaan
const ledgerData = {
    no_bukti: 'LEDG2024120001',
    tanggal: '2024-12-05',
    pelanggan: 'PT ABC',
    kode_bank: 'BNK01',
    kode_akun: '110001',
    keterangan: 'Pembayaran invoice',
    jumlah: 5000000,
    debet_kredit: 'D',
    kode_peruntukan: '01',
    keterangan_peruntukan: 'Operasional'
};

// Check dulu sebelum sync
const exists = await checkLedger(ledgerData.no_bukti);
if (!exists) {
    await syncLedger(ledgerData);
} else {
    console.log('Data sudah ada, akan diupdate');
    await syncLedger(ledgerData); // Akan update otomatis
}
```

---

### **cURL (Testing):**

#### **Sync Data:**
```bash
curl -X POST http://your-domain/api/sync/ledger \
  -H "Content-Type: application/json" \
  -d '{
    "no_bukti": "LEDG2024120001",
    "tanggal": "2024-12-05",
    "pelanggan": "PT ABC",
    "kode_bank": "BNK01",
    "kode_akun": "110001",
    "keterangan": "Pembayaran invoice",
    "jumlah": 5000000,
    "debet_kredit": "D",
    "kode_peruntukan": "01",
    "keterangan_peruntukan": "Operasional"
  }'
```

#### **Hapus Data:**
```bash
curl -X DELETE http://your-domain/api/sync/ledger \
  -H "Content-Type: application/json" \
  -d '{
    "no_bukti": "LEDG2024120001"
  }'
```

#### **Check Duplikasi:**
```bash
curl -X POST http://your-domain/api/sync/ledger/check \
  -H "Content-Type: application/json" \
  -d '{
    "no_bukti": "LEDG2024120001"
  }'
```

#### **Sync Batch:**
```bash
curl -X POST http://your-domain/api/sync/ledger/batch \
  -H "Content-Type: application/json" \
  -d '{
    "data": [
      {
        "no_bukti": "LEDG2024120001",
        "tanggal": "2024-12-05",
        "kode_bank": "BNK01",
        "kode_akun": "110001",
        "keterangan": "Pembayaran invoice",
        "jumlah": 5000000,
        "debet_kredit": "D"
      },
      {
        "no_bukti": "LEDG2024120002",
        "tanggal": "2024-12-05",
        "kode_bank": "BNK02",
        "kode_akun": "110002",
        "keterangan": "Penerimaan pembayaran",
        "jumlah": 10000000,
        "debet_kredit": "K"
      }
    ]
  }'
```

---

## 🎯 **FLOW SYNC DATA**

```
Aplikasi A                          Aplikasi B (Target)
    │                                      │
    │  1. Input ledger                    │
    ├──────────────────────►               │
    │                                      │
    │  2. Check no_bukti (optional)       │
    ├─────────POST /check──────────►      │
    │                                      │
    │  3. Response: exists = false         │
    │◄────────────────────────────────────┤
    │                                      │
    │  4. Sync ledger                     │
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
| 1 | `/api/sync/ledger` | **POST** | ➕ Sync 1 ledger (insert/update) |
| 2 | `/api/sync/ledger` | **DELETE** | 🗑️ Hapus 1 ledger |
| 3 | `/api/sync/ledger/batch` | **POST** | ➕ Sync banyak ledger |
| 4 | `/api/sync/ledger/batch` | **DELETE** | 🗑️ Hapus banyak ledger |
| 5 | `/api/sync/ledger/check` | **POST** | ✅ Cek duplikasi |

---

## ✅ **TESTING CHECKLIST:**

- [ ] Sync single ledger - success (created)
- [ ] Sync single ledger - success (updated)
- [ ] Sync single ledger - missing required fields (should fail)
- [ ] Sync single ledger - invalid debet_kredit (should fail)
- [ ] Sync batch - all success
- [ ] Sync batch - partial success
- [ ] Sync batch - duplicate no_bukti in request (should fail)
- [ ] Check no_bukti - exists
- [ ] Check no_bukti - not exists
- [ ] Delete single ledger - success
- [ ] Delete single ledger - not found (should fail)
- [ ] Delete batch - all success
- [ ] Delete batch - partial success
- [ ] Delete batch - duplicate no_bukti in request (should fail)

---

## 🎯 **USE CASE**

**Scenario 1: Pencatatan Transaksi Bank**
```
Input di App A → Validasi → Sync ke App B → 
Tercatat di ledger
```

**Scenario 2: Koreksi Data**
```
Data salah → DELETE via API → Input ulang → Sync lagi
atau
Sync langsung dengan no_bukti yang sama → Otomatis update
```

**Scenario 3: Batch End of Day**
```
Kumpulkan transaksi seharian → Sync batch malam hari →
Semua tercatat sekaligus
```

**Scenario 4: Update Data**
```
Data sudah ada → Sync dengan no_bukti yang sama →
Otomatis update data yang ada
```

---

## 🔑 **KEY DIFFERENCES DENGAN KAS KECIL:**

| Aspek | Kas Kecil | Ledger |
|-------|-----------|--------|
| **Key Identifier** | `id` (integer) | `no_bukti` (string, max 12) |
| **Primary Key** | Auto increment `id` | `no_bukti` (char 12) |
| **Field Khusus** | `kode_cabang`, `status_pajak` | `kode_bank`, `pelanggan`, `kode_peruntukan` |
| **Update Logic** | Update jika `id` sudah ada | Update jika `no_bukti` sudah ada |

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
        "path": "api/sync/ledgers",
        "requested_url": "http://your-domain/api/sync/ledgers",
        "cause": "Route tidak terdaftar atau endpoint salah",
        "suggestions": [
            "/api/sync/ledger",
            "/api/sync/ledger/batch",
            "/api/sync/ledger/check"
        ],
        "available_endpoints": {
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

API Ledger siap digunakan dengan fitur lengkap:
- ✅ **Create/Update** (POST sync - otomatis detect)
- ✅ **Delete** (DELETE)
- ✅ **Check** duplicate
- ✅ **Batch** operations
- ✅ **Validasi duplikasi** dalam request
- ✅ **Error handling** yang informatif

Dokumentasi lengkap tersedia! 📄

