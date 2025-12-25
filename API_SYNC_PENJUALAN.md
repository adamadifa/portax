# API SYNC PENJUALAN

API untuk sync/transfer data penjualan dari aplikasi lain ke sistem ini.

## Base URL
```
http://your-domain/api/sync
```

---

## 📋 **ENDPOINTS**

### 1. Sync Single Penjualan

**Endpoint:** `POST /api/sync/penjualan`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "no_faktur": "2024120001",
    "tanggal": "2024-12-05",
    "kode_pelanggan": "PEL0001",
    "kode_salesman": "SAL001",
    "kode_akun": "1-1401",
    "kode_akun_potongan": "4-2201",
    "kode_akun_penyesuaian": "4-2202",
    "potongan_aida": 0,
    "potongan_swan": 0,
    "potongan_stick": 0,
    "potongan_sp": 0,
    "potongan_sambal": 0,
    "potongan": 5000,
    "potis_aida": 0,
    "potis_swan": 0,
    "potis_stick": 0,
    "potongan_istimewa": 0,
    "peny_aida": 0,
    "peny_swan": 0,
    "peny_stick": 0,
    "penyesuaian": 0,
    "ppn": 11000,
    "jenis_transaksi": "K",
    "jenis_bayar": "TP",
    "jatuh_tempo": "2024-12-12",
    "status": "0",
    "routing": null,
    "signature": null,
    "tanggal_pelunasan": null,
    "print": 0,
    "id_user": 1,
    "keterangan": "Sync from App A",
    "status_batal": "0",
    "lock_print": "0",
    "detail": [
        {
            "kode_harga": "HRG001",
            "harga_dus": 100000,
            "harga_pack": 50000,
            "harga_pcs": 5000,
            "jumlah": 10,
            "subtotal": 100000,
            "status_promosi": "0"
        },
        {
            "kode_harga": "HRG002",
            "harga_dus": 150000,
            "harga_pack": 75000,
            "harga_pcs": 7500,
            "jumlah": 5,
            "subtotal": 75000,
            "status_promosi": "0"
        }
    ]
}
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "Data penjualan berhasil disync",
    "data": {
        "no_faktur": "2024120001",
        "total_detail": 2,
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
        "no_faktur": ["The no faktur field is required."],
        "detail": ["The detail field is required."]
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
        "path": "api/sync/penjualans",
        "requested_url": "http://your-domain/api/sync/penjualans",
        "cause": "Route tidak terdaftar atau endpoint salah",
        "suggestions": [
            "/api/sync/penjualan",
            "/api/sync/penjualan/batch",
            "/api/sync/penjualan/check"
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
    "message": "Gagal sync data penjualan",
    "error": "SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row..."
}
```

---

### 2. Hapus Penjualan

**Endpoint:** `DELETE /api/sync/penjualan`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "no_faktur": "2024120001"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Data penjualan berhasil dihapus",
    "data": {
        "no_faktur": "2024120001",
        "deleted_detail_count": 2,
        "deleted_at": "2024-12-05 11:30:00"
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "No faktur tidak ditemukan",
    "no_faktur": "2024120001"
}
```

**Error Response (500):**
```json
{
    "success": false,
    "message": "Gagal menghapus data penjualan",
    "error": "Detail error message"
}
```

---

### 3. Cek No Faktur (Duplikasi)

**Endpoint:** `POST /api/sync/penjualan/check`

**Request Body:**
```json
{
    "no_faktur": "2024120001"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "exists": false,
    "no_faktur": "2024120001"
}
```

**Keterangan:**
- `exists: false` = No faktur belum ada (aman untuk disync)
- `exists: true` = No faktur sudah ada (duplikat)

---

### 4. Hapus Batch (Multiple Penjualan)

**Endpoint:** `DELETE /api/sync/penjualan/batch`

**Request Body:**
```json
{
    "no_faktur": [
        "2024120001",
        "2024120002",
        "2024120003"
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
            "no_faktur": "2024120001",
            "status": "success",
            "message": "Berhasil dihapus",
            "deleted_detail_count": 2
        },
        {
            "no_faktur": "2024120002",
            "status": "success",
            "message": "Berhasil dihapus",
            "deleted_detail_count": 3
        },
        {
            "no_faktur": "2024120003",
            "status": "failed",
            "message": "No faktur tidak ditemukan"
        }
    ]
}
```

---

### 5. Sync Batch (Multiple Penjualan)

**Endpoint:** `POST /api/sync/penjualan/batch`

**Request Body:**
```json
{
    "data": [
        {
            "no_faktur": "2024120001",
            "tanggal": "2024-12-05",
            "kode_pelanggan": "PEL0001",
            "kode_salesman": "SAL001",
            "jenis_transaksi": "K",
            "jenis_bayar": "TP",
            "status": "0",
            "id_user": 1,
            "potongan": 0,
            "ppn": 0,
            "detail": [
                {
                    "kode_harga": "HRG001",
                    "harga_dus": 100000,
                    "harga_pack": 50000,
                    "harga_pcs": 5000,
                    "jumlah": 10,
                    "subtotal": 100000,
                    "status_promosi": "0"
                }
            ]
        },
        {
            "no_faktur": "2024120002",
            "tanggal": "2024-12-05",
            "kode_pelanggan": "PEL0002",
            "kode_salesman": "SAL001",
            "jenis_transaksi": "T",
            "jenis_bayar": "TN",
            "status": "0",
            "id_user": 1,
            "potongan": 5000,
            "ppn": 11000,
            "detail": [
                {
                    "kode_harga": "HRG002",
                    "harga_dus": 150000,
                    "harga_pack": 75000,
                    "harga_pcs": 7500,
                    "jumlah": 5,
                    "subtotal": 75000,
                    "status_promosi": "0"
                }
            ]
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
            "no_faktur": "2024120001",
            "status": "success",
            "message": "Berhasil disync"
        },
        {
            "no_faktur": "2024120002",
            "status": "success",
            "message": "Berhasil disync"
        }
    ]
}
```

---

## 📝 **FIELD REQUIREMENTS**

### **Required Fields (Header):**
- `no_faktur` - String, max 13 karakter, harus unique
- `tanggal` - Date (format: YYYY-MM-DD)
- `kode_pelanggan` - String, max 13 karakter
- `kode_salesman` - String, max 7 karakter
- `jenis_transaksi` - String, 1 karakter (T/K)
- `jenis_bayar` - String, max 2 karakter
- `status` - String, 1 karakter
- `id_user` - Integer

### **Optional Fields (Header) - Dengan Default Value:**
- `kode_akun` - Default: '1-1401'
- `kode_akun_potongan` - Default: '4-2201'
- `kode_akun_penyesuaian` - Default: '4-2202'
- `potongan_aida` - Default: 0
- `potongan_swan` - Default: 0
- `potongan_stick` - Default: 0
- `potongan_sp` - Default: 0
- `potongan_sambal` - Default: 0
- `potongan` - Default: 0
- `potis_aida` - Default: 0
- `potis_swan` - Default: 0
- `potis_stick` - Default: 0
- `potongan_istimewa` - Default: 0
- `peny_aida` - Default: 0
- `peny_swan` - Default: 0
- `peny_stick` - Default: 0
- `penyesuaian` - Default: 0
- `ppn` - Default: 0
- `jatuh_tempo` - Nullable
- `routing` - Nullable
- `signature` - Nullable
- `tanggal_pelunasan` - Nullable
- `print` - Default: 0
- `keterangan` - Nullable
- `status_batal` - Default: '0'
- `lock_print` - Default: '0'

### **Required Fields (Detail):**
- `detail` - Array, minimal 1 item
- `detail.*.kode_harga` - String, max 7 karakter
- `detail.*.harga_dus` - Integer
- `detail.*.harga_pack` - Integer
- `detail.*.harga_pcs` - Integer
- `detail.*.jumlah` - Integer
- `detail.*.subtotal` - Integer

### **Optional Fields (Detail):**
- `detail.*.status_promosi` - Default: '0'

---

## 🔐 **KEAMANAN**

### **Validasi Otomatis:**
1. ✅ No faktur harus unique (tidak boleh duplikat)
2. ✅ Semua foreign key divalidasi
3. ✅ Format tanggal divalidasi
4. ✅ Tipe data divalidasi
5. ✅ Transaction rollback otomatis jika error

### **Best Practice:**
1. Selalu cek no_faktur terlebih dahulu menggunakan endpoint `/check`
2. Gunakan endpoint `/batch` untuk sync multiple data sekaligus
3. Handle error response dengan baik di aplikasi pengirim
4. Log setiap sync request untuk audit trail

---

## 💻 **CONTOH IMPLEMENTASI**

### **PHP/Laravel (Aplikasi Pengirim):**

#### **Sync Data:**
```php
use Illuminate\Support\Facades\Http;

// Single Sync
$response = Http::post('http://target-domain/api/sync/penjualan', [
    'no_faktur' => '2024120001',
    'tanggal' => '2024-12-05',
    'kode_pelanggan' => 'PEL0001',
    'kode_salesman' => 'SAL001',
    'jenis_transaksi' => 'K',
    'jenis_bayar' => 'TP',
    'status' => '0',
    'id_user' => 1,
    'potongan' => 0,
    'ppn' => 0,
    'detail' => [
        [
            'kode_harga' => 'HRG001',
            'harga_dus' => 100000,
            'harga_pack' => 50000,
            'harga_pcs' => 5000,
            'jumlah' => 10,
            'subtotal' => 100000,
            'status_promosi' => '0'
        ]
    ]
]);

if ($response->successful()) {
    $data = $response->json();
    echo "Berhasil sync: " . $data['data']['no_faktur'];
} else {
    echo "Gagal sync: " . $response->json()['message'];
}
```

#### **Hapus Data:**
```php
use Illuminate\Support\Facades\Http;

// Single Delete
$response = Http::delete('http://target-domain/api/sync/penjualan', [
    'no_faktur' => '2024120001'
]);

if ($response->successful()) {
    $data = $response->json();
    echo "Berhasil hapus: " . $data['data']['no_faktur'];
    echo "Total detail terhapus: " . $data['data']['deleted_detail_count'];
} else {
    echo "Gagal hapus: " . $response->json()['message'];
}

// Batch Delete
$response = Http::delete('http://target-domain/api/sync/penjualan/batch', [
    'no_faktur' => ['2024120001', '2024120002', '2024120003']
]);

if ($response->successful()) {
    $data = $response->json();
    echo "Sukses: " . $data['summary']['success'];
    echo "Gagal: " . $data['summary']['failed'];
}
```

### **JavaScript/Axios (Aplikasi Pengirim):**

#### **Sync Data:**
```javascript
const axios = require('axios');

async function syncPenjualan(data) {
    try {
        const response = await axios.post('http://target-domain/api/sync/penjualan', data);
        console.log('Success:', response.data);
        return response.data;
    } catch (error) {
        console.error('Error:', error.response.data);
        throw error;
    }
}

// Contoh penggunaan
const penjualanData = {
    no_faktur: '2024120001',
    tanggal: '2024-12-05',
    kode_pelanggan: 'PEL0001',
    kode_salesman: 'SAL001',
    jenis_transaksi: 'K',
    jenis_bayar: 'TP',
    status: '0',
    id_user: 1,
    potongan: 0,
    ppn: 0,
    detail: [
        {
            kode_harga: 'HRG001',
            harga_dus: 100000,
            harga_pack: 50000,
            harga_pcs: 5000,
            jumlah: 10,
            subtotal: 100000,
            status_promosi: '0'
        }
    ]
};

syncPenjualan(penjualanData);
```

#### **Hapus Data:**
```javascript
const axios = require('axios');

// Single Delete
async function deletePenjualan(noFaktur) {
    try {
        const response = await axios.delete('http://target-domain/api/sync/penjualan', {
            data: { no_faktur: noFaktur }
        });
        console.log('Deleted:', response.data);
        return response.data;
    } catch (error) {
        console.error('Error:', error.response.data);
        throw error;
    }
}

// Batch Delete
async function deletePenjualanBatch(noFakturArray) {
    try {
        const response = await axios.delete('http://target-domain/api/sync/penjualan/batch', {
            data: { no_faktur: noFakturArray }
        });
        console.log('Batch Delete Result:', response.data);
        return response.data;
    } catch (error) {
        console.error('Error:', error.response.data);
        throw error;
    }
}

// Contoh penggunaan
deletePenjualan('2024120001');
deletePenjualanBatch(['2024120001', '2024120002']);
```

### **cURL (Testing):**

#### **Sync Data:**
```bash
curl -X POST http://your-domain/api/sync/penjualan \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "no_faktur": "2024120001",
    "tanggal": "2024-12-05",
    "kode_pelanggan": "PEL0001",
    "kode_salesman": "SAL001",
    "jenis_transaksi": "K",
    "jenis_bayar": "TP",
    "status": "0",
    "id_user": 1,
    "potongan": 0,
    "ppn": 0,
    "detail": [
        {
            "kode_harga": "HRG001",
            "harga_dus": 100000,
            "harga_pack": 50000,
            "harga_pcs": 5000,
            "jumlah": 10,
            "subtotal": 100000,
            "status_promosi": "0"
        }
    ]
}'
```

#### **Hapus Data:**
```bash
# Single Delete
curl -X DELETE http://your-domain/api/sync/penjualan \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "no_faktur": "2024120001"
  }'

# Batch Delete
curl -X DELETE http://your-domain/api/sync/penjualan/batch \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "no_faktur": ["2024120001", "2024120002", "2024120003"]
  }'
```

---

## 🎯 **FLOW SYNC DATA**

```
Aplikasi A                          Aplikasi B (Target)
    │                                      │
    │  1. Ambil data penjualan            │
    ├──────────────────────►               │
    │                                      │
    │  2. Check no_faktur                 │
    ├─────────POST /check──────────►      │
    │                                      │
    │  3. Response: exists = false        │
    │◄────────────────────────────────────┤
    │                                      │
    │  4. Sync penjualan + detail         │
    ├─────────POST /sync────────────►     │
    │                                      │
    │  5. Insert ke database              │
    │                       ┌──────────────┤
    │                       │ Transaction  │
    │                       │ - Insert header
    │                       │ - Insert detail
    │                       │ - Commit     │
    │                       └──────────────┤
    │                                      │
    │  6. Response: Success               │
    │◄────────────────────────────────────┤
    │                                      │
    │  7. Update status di App A          │
    └──────────────────────►               │
```

---

## ✅ **TESTING**

### Test dengan Postman:
1. Import collection dari dokumentasi ini
2. Set base URL di environment
3. Test endpoint satu per satu
4. Verifikasi data di database target

### Test Checklist:
- [ ] Sync single penjualan - success
- [ ] Sync single penjualan - duplicate (should fail)
- [ ] Sync with missing required fields (should fail)
- [ ] Sync batch - all success
- [ ] Sync batch - partial success
- [ ] Check no_faktur - exists
- [ ] Check no_faktur - not exists
- [ ] Delete single penjualan - success
- [ ] Delete single penjualan - not found (should fail)
- [ ] Delete batch - all success
- [ ] Delete batch - partial success

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
        "path": "api/sync/penjualans",
        "requested_url": "http://your-domain/api/sync/penjualans",
        "cause": "Route tidak terdaftar atau endpoint salah",
        "suggestions": [
            "/api/sync/penjualan",
            "/api/sync/penjualan/batch",
            "/api/sync/penjualan/check"
        ],
        "available_endpoints": {
            "POST /api/sync/penjualan": "Sync single penjualan",
            "POST /api/sync/penjualan/batch": "Sync batch penjualan",
            "POST /api/sync/penjualan/check": "Check no_faktur penjualan",
            "DELETE /api/sync/penjualan": "Delete single penjualan",
            "DELETE /api/sync/penjualan/batch": "Delete batch penjualan"
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

### **Error 404 - Data Tidak Ditemukan**

Terjadi ketika mencoba menghapus data yang tidak ada:
```json
{
    "success": false,
    "message": "No faktur tidak ditemukan",
    "no_faktur": "2024120001"
}
```

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

## 📞 **SUPPORT**

Jika ada masalah atau pertanyaan, hubungi tim development.


