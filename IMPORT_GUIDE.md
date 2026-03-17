# 📋 Panduan Import Excel - User & Bisnis

## ✅ Status: SIAP DIGUNAKAN

Kedua fitur import sudah **diperbaiki dan aman** untuk digunakan!

---

## 🎯 Workflow Import

### **Langkah 1: Import User (Mahasiswa) Terlebih Dahulu**
File Excel user harus memiliki kolom-kolom ini:

#### **Kolom Wajib:**
- `name` - Nama lengkap mahasiswa ✅
- `email` - Email mahasiswa (harus unique) ✅

#### **Kolom Opsional (akan diisi jika ada):**
- `nis` - Nomor Induk Siswa
- `nisn` - NISN
- `prodi` / `jurusan` / `major` - Program studi
- `angkatan` / `student_year` - Tahun angkatan
- `tanggal_lahir` / `birth_date` - Tanggal lahir
- `tempat_lahir` / `birth_city` - Tempat lahir
- `agama` / `religion` - Agama
- `phone` / `telp` / `phone_number` - Telepon
- `hp` / `mobile` / `mobile_number` - HP/Mobile
- `wa` / `whatsapp` - WhatsApp
- `ipk` / `cgpa` - IPK/CGPA
- `is_graduate` - Status kelulusan (yes/no)

Plus banyak field lain untuk data orangtua, pendidikan, dll.

---

### **Langkah 2: Import Bisnis**
Setelah user ter-import, baru import bisnis. File Excel bisnis harus memiliki:

#### **Kolom Wajib:**
- `nama` / `name` / `business_name` - Nama bisnis ✅
- `email` - Email pemilik (untuk matching dengan user) ✅

#### **Kolom Opsional:**
- `business_type` / `business_line` / `kategori` / `jenis_bisnis` - Jenis/kategori bisnis
- `deskripsi` / `description` - Deskripsi bisnis
- `alamat` / `address` - Alamat bisnis
- `established_date` / `tanggal_berdiri` - Tanggal berdiri
- `employee_count` - Jumlah karyawan
- `revenue_range` - Range pendapatan
- `business_mode` - Mode bisnis (product/service/both)

**Kolom Contact (Auto-import & Auto-create Contact Type):**
- `phone` / `telepon` - Telepon bisnis
- `mobile` / `hp` - HP/Mobile bisnis
- `whatsapp` / `wa` - WhatsApp bisnis
- `email_bisnis` / `business_email` - Email bisnis
- `facebook` - Facebook bisnis
- `instagram` - Instagram bisnis
- `twitter` - Twitter bisnis
- `tiktok` - TikTok bisnis
- `linkedin` - LinkedIn bisnis
- `line` - LINE bisnis
- `telegram` - Telegram bisnis
- `website` / `web` - Website bisnis

Dan lain-lain

---

## 🔒 Keamanan & Validasi

### **UsersImport Safety Features:**
1. ✅ **Duplicate Prevention**: Email harus unique, user yang sudah ada akan di-skip
2. ✅ **Password Default**: Jika tidak ada password di Excel, otomatis set `password123`
3. ✅ **Email Verification**: Otomatis set email_verified_at saat import
4. ✅ **Field Validation**: 
   - `name` wajib diisi
   - `email` wajib, harus format email yang valid, dan unique
   - `role` harus salah satu dari: student, alumni, admin
5. ✅ **Database Field Matching**: Field names sudah disesuaikan dengan database schema (NIS, Student_Year, Major, Is_Graduate, CGPA)

### **BusinessesImport Safety Features:**
1. ✅ **Duplicate Prevention**: Bisnis dengan nama sama akan di-skip
2. ✅ **Owner Matching**: 
   - Prioritas 1: Match by email (paling akurat)
   - Prioritas 2: Match by nama (partial match)
   - Prioritas 3: Match by field 'owner' (jika ada)
3. ✅ **Skip Jika Tidak Ada Owner**: Jika tidak ketemu user yang sesuai, bisnis akan di-skip dan di-log
4. ✅ **Auto-create Business Type**: Jika tipe bisnis belum ada, otomatis dibuat ✨
5. ✅ **Auto-create Contact Type**: Jika tipe contact belum ada (phone, email, whatsapp, dll), otomatis dibuat ✨
6. ✅ **Auto-import Contacts**: Contacts dari Excel otomatis di-import dan di-link ke bisnis
7. ✅ **Validation**: Field nama dan email di-validasi
8. ✅ **Logging**: Semua proses di-log untuk tracking

---

## 🎯 Cara Assign Bisnis ke User yang Tepat

BusinessesImport menggunakan strategi matching **berdasarkan EMAIL atau NAMA**:

1. **Email Exact Match** (Paling Akurat):
   ```
   Excel Bisnis: email = "john@example.com"
   Excel User: email = "john@example.com"
   ✅ MATCH!
   ```

2. **Name Partial Match**:
   ```
   Excel Bisnis: nama = "John Doe"
   Excel User: name = "John Doe Santoso"
   ✅ MATCH! (partial match)
   ```

3. **Owner Field** (fallback):
   ```
   Excel Bisnis: owner = "john@example.com"
   ✅ Akan cari user dengan email atau nama tersebut
   ```

**PENTING**: Pastikan email di Excel User dan Excel Bisnis **SAMA** agar bisnis ter-assign dengan benar!

---

## 📊 Field Mapping Summary

### User Import - Database Fields (PascalCase):
- `NIS` ← `nis` (Excel)
- `Student_Year` ← `student_year` / `angkatan` (Excel)
- `Major` ← `major` / `prodi` / `jurusan` (Excel)
- `Is_Graduate` ← `is_graduate` (Excel)
- `CGPA` ← `cgpa` / `ipk` (Excel)

### Business Import - Matching Logic:
- **Owner**: Email match → Name match → Owner field
- **Business Type**: `business_type` / `business_line` / `kategori` / `jenis_bisnis`
- **Description**: `description` / `deskripsi`
- **Address**: `address` / `alamat`

---

## ⚠️ Hal yang Perlu Diperhatikan

### **SEBELUM Import User:**
1. Pastikan kolom `name` dan `email` terisi dengan benar
2. Email harus unique (tidak boleh duplikat)
3. Email harus format yang valid

### **SEBELUM Import Bisnis:**
1. **WAJIB** import User dulu!
2. Pastikan email di Excel Bisnis **SAMA PERSIS** dengan email di Excel User
3. Jika matching by name, pastikan nama di bisnis mirip dengan nama user
4. Bisnis yang tidak ketemu ownernya akan di-skip (check logs)

---

## 📝 Contoh Format Excel

### User Excel Example:
```
name            | email              | nis      | prodi    | angkatan
John Doe        | john@example.com   | 12345    | TI       | 2020
Jane Smith      | jane@example.com   | 12346    | SI       | 2021
```

### Business Excel Example:
```
nama                | email              | business_line | deskripsi         | phone        | whatsapp       | instagram
Toko Baju John      | john@example.com   | Fashion       | Jual baju online  | 081234567890 | 081234567890   | @tokobaju
Warung Jane         | jane@example.com   | F&B           | Warung makan      | 087654321098 | 087654321098   | @warungjane
```

**Hasil**: 
- ✅ "Toko Baju John" akan ter-assign ke John Doe (email match)
- ✅ "Warung Jane" akan ter-assign ke Jane Smith (email match)
- ✅ Business Type "Fashion" dan "F&B" otomatis dibuat jika belum ada
- ✅ Contacts (Phone, WhatsApp, Instagram) otomatis di-import
- ✅ Contact Types otomatis dibuat jika belum ada

---

## 🚀 Cara Penggunaan

1. **Import User**: Upload Excel user melalui fitur import user
2. **Check**: Verifikasi user berhasil masuk ke database
3. **Import Bisnis**: Upload Excel bisnis melalui fitur import bisnis
4. **Check Logs**: Lihat log untuk memastikan bisnis ter-assign dengan benar

---

## 🔍 Troubleshooting

### "Bisnis tidak ter-assign ke user yang benar"
- ✅ Check: Apakah email di Excel Bisnis sama dengan email di Excel User?
- ✅ Check: Apakah user sudah ter-import terlebih dahulu?
- ✅ Check logs untuk melihat matching process

### "User tidak ter-import"
- ✅ Check: Apakah email unique? (tidak duplikat)
- ✅ Check: Apakah format email valid?
- ✅ Check: Apakah kolom `name` dan `email` terisi?

### "Field tidak terisi"
- ✅ Check: Apakah nama kolom di Excel sesuai dengan mapping di atas?
- ✅ Import mendukung nama kolom Indonesia dan English (misal: `prodi` / `major`)

---

## 📌 Summary

✅ **UsersImport.php**: Field names sudah match dengan database, validation aman  
✅ **BusinessesImport.php**: Matching logic sudah benar (email → name → owner)  
✅ **Auto-create Business Type**: Bisnis Type otomatis dibuat jika belum ada ✨  
✅ **Auto-create Contact Type**: Contact Type otomatis dibuat jika belum ada ✨  
✅ **Auto-import Contacts**: Semua contacts dari Excel otomatis di-import dan di-link ke bisnis ✨  
✅ **Safety**: Duplicate prevention, validation, dan logging lengkap  
✅ **Ready**: Kedua import sudah siap dan aman untuk digunakan!

**Next Step**: Upload Excel user terlebih dahulu, lalu upload Excel bisnis. Bisnis akan otomatis ter-assign ke user yang tepat berdasarkan email matching, dan semua contacts akan otomatis ter-import! 🎉
