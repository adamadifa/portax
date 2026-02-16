# Mekanisme Pre-Delete Sync Penjualan

Dokumen ini menjelaskan alur penyesuaian di sisi client (Android/Desktop) untuk proses **Sync All (Batch)**. Sebelum melakukan pengiriman data batch (`syncBatch`), aplikasi client **DIWAJIBKAN** untuk memanggil endpoint `pre-delete` terlebih dahulu.

## 🎯 Tujuan
Endpoint ini berfungsi untuk membersihkan (menghapus) data penjualan di server yang sesuai dengan kriteria filter (Cabang, Salesman, Tanggal) sebelum data baru dari client dikirimkan. Hal ini bertujuan untuk:
1.  Mencegah duplikasi data.
2.  Memastikan data di server sinkron dengan kondisi terakhir di client (clean slate sync).
3.  Menghapus data yang mungkin sudah dihapus di client tapi masih ada di server (jika sync sebelumnya tidak tuntas).

---

## 🔄 Alur Sync All (Client Side)

Berikut adalah logika yang harus diimplementasikan di sisi client saat user menekan tombol **Sync All**:

1.  **Persiapkan Filter:** Tentukan data apa yang akan disync (misal: semua data sales A bulan ini).
2.  **Call Pre-Delete:** Panggil API `pre-delete` dengan filter yang sama.
3.  **Cek Response:**
    *   Jika **Sukses (200)**: Lanjutkan ke proses upload data (`syncBatch`).
    *   Jika **Gagal (422/500)**: **STOP!** Jangan lanjutkan sync. Tampilkan pesan error ke user.
4.  **Call Sync Batch:** Jika langkah 2 sukses, kirim data penjualan melalui API `syncBatch`.

### Diagram Alur
```mermaid
graph TD
    A[Start Sync All] --> B[Siapkan Filter Data\n(Salesman/Tanggal)]
    B --> C[Call POST /api/sync/penjualan/pre-delete]
    C -->|Error/Gagal| D[Tampilkan Error & Batal Sync]
    C -->|Success| E[Upload Data via\nPOST /api/sync/penjualan/batch]
    E --> F[Selesai]
```

---

## 📋 Detail Endpoint

**URL:**
`POST /api/sync/penjualan/pre-delete`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Parameter Body (JSON):**
Minimal **harus ada 1 parameter** dari daftar berikut agar tidak menghapus seluruh data secara tidak sengaja.

| Parameter | Tipe | Wajib? | Keterangan |
| :--- | :--- | :--- | :--- |
| `kode_cabang` | String | Opsional* | Filter berdasarkan kode cabang salesman. |
| `kode_salesman` | String | Opsional* | Filter untuk salesman tertentu. |
| `dari` | Date | Opsional* | Tanggal awal (Format: YYYY-MM-DD). |
| `sampai` | Date | Opsional* | Tanggal akhir (Format: YYYY-MM-DD). |

> (*) Minimal salah satu parameter di atas harus diisi.

---

## 💻 Contoh Implementasi Client

### Skenario: Sync data Salesman "BUDI" untuk bulan "Oktober 2024"

**Langkah 1: Request Pre-Delete**

Payload yang dikirim ke server:
```json
{
    "kode_cabang": "TSM",
    "kode_salesman": "SAL-BUDI",
    "dari": "2024-10-01",
    "sampai": "2024-10-31"
}
```

**Response Sukses (200 OK):**
```json
{
    "success": true,
    "message": "Pre-delete selesai. 15 faktur dihapus.",
    "deleted": {
        "header": 15,
        "detail": 45,
        "histori_bayar": 5
    },
    "filter": {
        "kode_cabang": "TSM",
        "kode_salesman": "SAL-BUDI",
        "dari": "2024-10-01",
        "sampai": "2024-10-31"
    }
}
```

**Response Jika Data Kosong (Tetap dianggap sukses):**
```json
{
    "success": true,
    "message": "Tidak ada data yang perlu dihapus",
    "deleted_count": 0
}
```

**Response Gagal Validasi (422 Unprocessable Entity):**
Terjadi jika tidak ada filter yang dikirim.
```json
{
    "success": false,
    "message": "Minimal harus ada 1 parameter filter (kode_cabang / kode_salesman / dari / sampai)"
}
```

### Contoh Code (JavaScript/Axios)

```javascript
async function performFullSync(salesmanId, startDate, endDate, salesDataList) {
    try {
        console.log("1. Memulai Pre-Delete...");

        // 1. Jalankan Pre-Delete
        const preDeleteResponse = await axios.post('/api/sync/penjualan/pre-delete', {
            kode_salesman: salesmanId,
            dari: startDate,
            sampai: endDate
        });

        if (!preDeleteResponse.data.success) {
            throw new Error("Gagal melakukan pembersihan data server: " + preDeleteResponse.data.message);
        }

        console.log("Pre-delete berhasil. Menghapus " + preDeleteResponse.data.deleted?.header + " data lama.");

        // 2. Jalankan Sync Batch (Upload Data Baru)
        console.log("2. Mengupload data baru...");
        const batchResponse = await axios.post('/api/sync/penjualan/batch', {
            data: salesDataList
        });

        if (batchResponse.data.success) {
            console.log("Sync Selesai! Data berhasil diperbarui.");
            return true;
        } else {
            throw new Error("Gagal upload batch.");
        }

    } catch (error) {
        console.error("Sync Error:", error);
        alert("Terjadi kesalahan saat sync: " + error.message);
        return false;
    }
}
```
