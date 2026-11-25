# Panduan Angel (Leader + Backend 1): Dari Nol sampai Server Hidup

Ikuti urutannya. Setiap langkah punya **Tanda berhasil**. Kalau tanda itu belum muncul, jangan lanjut dulu, lihat bagian **Kalau error** di paling bawah.

**Target malam ini (±1,5 jam): Langkah 1 sampai 8.** Setelah itu Calista dan Ifa sudah bisa mulai kerja.
**Besok: Langkah 9 sampai 12.**

Terminal yang dipakai: terminal bawaan VS Code (`Ctrl + `` ` ``). Kalau perintah `cd` gagal karena beda drive, ketik dulu nama drive-nya, misalnya `D:`.

---

## Langkah 1. Cek semua software terpasang

Buka VS Code, lalu buka terminal (`Ctrl + `` ` ``). Ketik satu per satu:

```
git --version
code --version
```

Tanda berhasil: keluar nomor versi, bukan "not recognized".

Cek manual (tanpa terminal):
- XAMPP ada di `C:\xamppp` (kamu memakai tiga huruf p).
- Postman bisa dibuka.

Android Studio dan Flutter **tidak kamu perlukan** untuk peranmu. Lewati saja.

### Atur identitas Git (cukup sekali seumur laptop)

```
git config --global user.name "Nama Kamu"
git config --global user.email "emailgithubmu@gmail.com"
git config --global init.defaultBranch main
```

Ganti nama dan email dengan akun GitHub-mu. Cek hasilnya dengan `git config --global --list`.

---

## Langkah 2. Taruh folder proyek di tempat yang rapi

1. Ekstrak `medqueue-starter.zip` ke `C:\Projects\`, sehingga jadi `C:\Projects\medqueue\`.
   (Boleh di drive lain, tapi **jangan** di dalam `C:\xamppp\htdocs`. Folder ini adalah repo-mu, bukan folder server.)
2. Buka VS Code, **File → Open Folder**, pilih `C:\Projects\medqueue`.

Tanda berhasil: di panel kiri VS Code terlihat `app`, `backend`, `database`, `scripts`, dan file `PANDUAN_ANGEL.md`.

---

## Langkah 3. Buat repo di GitHub (lewat browser)

1. Buka https://github.com/new
2. **Repository name:** `medqueue`
3. Pilih **Private** (atau Public, lihat catatan di langkah 5).
4. **Jangan** centang "Add a README", ".gitignore", atau "license". Repo harus kosong, karena isi sudah ada di laptopmu.
5. Klik **Create repository**.
6. Salin URL yang muncul, bentuknya `https://github.com/USERNAME-KAMU/medqueue.git`.

Tanda berhasil: muncul halaman repo kosong dengan petunjuk "…or push an existing repository".

---

## Langkah 4. Dorong proyek ke GitHub (lewat terminal VS Code)

Pastikan terminal berada di folder `medqueue`. Cek dengan `pwd` (atau lihat prompt-nya). Kalau belum, ketik `cd C:\Projects\medqueue`.

```
git init -b main
git add .
git commit -m "chore: struktur awal repo, database, dan fondasi backend"
git remote add origin https://github.com/USERNAME-KAMU/medqueue.git
git push -u origin main
```

Ganti `USERNAME-KAMU` dengan username GitHub-mu.
Saat `git push` pertama, akan muncul jendela browser untuk login GitHub. Klik **Authorize**.

Lanjut, buat branch `dev`:

```
git checkout -b dev
git push -u origin dev
```

Tanda berhasil: di GitHub, dropdown branch menampilkan dua branch (`main` dan `dev`), dan semua folder terlihat.

---

## Langkah 5. Atur branch dan undang anggota (browser, halaman repo)

**a. Jadikan `dev` branch utama untuk PR**
**Settings → General → Default branch** → klik ikon tukar → pilih `dev` → **Update**.
Dengan begitu, Pull Request anggota otomatis menuju `dev`.

**b. Kunci `main`**
**Settings → Branches → Add branch ruleset** (atau "Add classic branch protection rule")
- Branch name pattern: `main`
- Centang **Require a pull request before merging**
- Simpan.

Catatan: GitHub gratis hanya menegakkan aturan ini di repo **Public**. Kalau repo Private dan kamu belum punya GitHub Pro, aturannya tidak berlaku. Pilihannya: jadikan repo Public (aman, karena tidak ada rahasia di dalamnya), atau cukup sepakati di grup bahwa tidak ada yang push langsung ke `main` atau `dev`. Mahasiswa bisa mendaftar GitHub Student Developer Pack untuk mendapat Pro gratis.

**c. Undang 4 anggota**
**Settings → Collaborators → Add people** → ketik username GitHub Calista, Ifa, Zella, Fizrida → pilih role Write.
Minta mereka membuka email dan menekan **Accept invitation**.

Tanda berhasil: di halaman Collaborators, keempat nama muncul (status Pending sampai mereka menerima).

---

## Langkah 6. Nyalakan XAMPP dan buat database

### 6a. Nyalakan server
1. Buka `C:\xamppp\xampp-control.exe` dengan **klik kanan → Run as administrator**.
2. Klik **Start** pada **Apache** dan **MySQL**.

Tanda berhasil: kedua tulisan modul berwarna hijau, ada angka PID dan Port (Apache 80, 443; MySQL 3306).

Tes: buka browser, ketik `http://localhost`. Harus muncul halaman dashboard XAMPP.

### 6b. Impor database
1. Buka `http://localhost/phpmyadmin`.
2. Klik tab **Import** (bagian atas).
3. **Choose File** → pilih `C:\Projects\medqueue\database\medqueue.sql`.
4. Gulir ke bawah, klik **Go**.

Tanda berhasil: di panel kiri muncul database **medqueue** dengan **6 tabel**: `users`, `doctors`, `schedules`, `patients`, `appointments`, `queues`.
Cek isi: klik tabel `users` lalu **Browse**. Harus ada 2 baris (`admin` dan `pasien1`). Tabel `doctors` berisi 3 dokter dan `schedules` berisi 6 jadwal.

Akun uji bawaan:

| Username | Password | Role |
|---|---|---|
| admin | admin123 | admin |
| pasien1 | pasien123 | patient |

Ganti password admin sebelum demo.

---

## Langkah 7. Deploy backend dan tes

Backend harus disalin ke `C:\xamppp\htdocs\medqueue_api` agar Apache bisa melayaninya. Skripnya sudah disediakan.

1. Di File Explorer, buka `C:\Projects\medqueue\scripts\`.
2. **Klik dua kali** `deploy_backend.bat`.
3. Tunggu sampai tulisan "Selesai", tekan tombol apa saja.

Tes di browser:
- `http://localhost/medqueue_api/ping.php` → harus muncul JSON dengan `"db":"connected"`.
- `http://localhost/medqueue_api/doctors/read.php` → harus muncul 3 dokter dalam JSON.

Tanda berhasil: kedua alamat menampilkan JSON dengan `"success": true`.

Ulangi `deploy_backend.bat` **setiap kali** ada perubahan kode backend atau merge baru.

---

## Langkah 8. Umumkan "siap" ke grup

Kirim pesan ke grup (silakan sesuaikan):

```
Update dari Angel:
- Repo siap: https://github.com/USERNAME-KAMU/medqueue (cek email, terima undangan)
- Branch kerja: dev. Semua kode lewat Pull Request ke dev, jangan push langsung.
- Database: import database/medqueue.sql ke phpMyAdmin (khusus Calista, untuk uji lokal).
- Akun uji: admin / admin123 dan pasien1 / pasien123.
- Format respons semua endpoint: {"success": true/false, "data": ..., "message": ""}
- Login mengembalikan "token". Semua request setelah login wajib mengirim header:
    X-Auth-Token: <token>
- IP server bersama menyusul sebentar lagi.
```

Untuk Calista, tambahan: "Kamu bisa langsung `git clone`, import SQL, lalu pakai `helpers/auth.php` (`requireLogin()`, `requireAdmin()`, `requirePatient()`, `getPatientForUser()`)."

Tanda berhasil: Calista dan Ifa membalas bahwa clone berhasil.

---

## Langkah 9. Siapkan server bersama (Mode B)

Tujuan: HP semua anggota bisa membuka API di laptopmu lewat WiFi.

### 9a. Cari IP laptop
Buka terminal, ketik:

```
ipconfig
```

Cari bagian **Wireless LAN adapter Wi-Fi**, lalu baca baris **IPv4 Address**, misalnya `192.168.1.10`. Itulah `IP-SERVER`.

### 9b. Set jaringan ke Private
**Settings → Network & internet → Wi-Fi → (nama WiFi-mu) → Network profile type → Private**.

### 9c. Buka pintu Firewall untuk Apache
Buka **PowerShell sebagai Administrator** (klik kanan tombol Start → Terminal (Admin)), lalu ketik:

```
New-NetFirewallRule -DisplayName "MedQueue Apache 80" -Direction Inbound -Protocol TCP -LocalPort 80 -Action Allow -Profile Private
```

Jangan membuka port 3306 (MySQL). Cukup Apache.

### 9d. Tes dari HP
Sambungkan HP ke WiFi yang **sama** dengan laptop, lalu buka di browser HP:

```
http://IP-SERVER/medqueue_api/ping.php
```

Tanda berhasil: HP menampilkan JSON `"db":"connected"`.

### 9e. Umumkan IP
Kirim ke grup: `Server bersama hidup di http://IP-SERVER/medqueue_api`. Frontend memakai alamat ini lewat `--dart-define=BASE_URL=http://IP-SERVER/medqueue_api`.

Tips penting:
- Kalau HP tidak bisa membuka padahal sudah satu WiFi, kemungkinan WiFi kampus memblokir antarperangkat (client isolation). Solusinya pakai hotspot HP salah satu anggota, atau Mobile Hotspot Windows (Settings → Network → Mobile hotspot). Kalau pakai Mobile Hotspot Windows, IP laptop biasanya `192.168.137.1`.
- IP bisa berubah kalau laptop pindah WiFi atau restart router. Kalau berubah, umumkan ulang.
- Laptop harus menyala, tidak sleep, dan XAMPP tetap hijau selama tim menguji.

---

## Langkah 10. Postman: buat collection dan uji

### 10a. Collection dan variabel
1. Postman → **New → Collection** → nama: `MedQueue API`.
2. Tab **Variables** pada collection, tambahkan:

| Variable | Initial value | Current value |
|---|---|---|
| baseUrl | http://localhost/medqueue_api | http://localhost/medqueue_api |
| token | (kosong) | (kosong) |

3. Tab **Authorization** pada collection: Type **API Key**, Key `X-Auth-Token`, Value `{{token}}`, Add to **Header**. Klik **Save** (`Ctrl+S`).
4. Buat folder di dalam collection: `Auth`, `Doctors`, `Schedules`, `Appointments`, `Queues`, `Patients`.

### 10b. Request yang dibuat

**Health check** (taruh di luar folder)
- `GET {{baseUrl}}/ping.php`

**Auth → Login Admin**
- `POST {{baseUrl}}/auth/login.php`
- Tab **Body** → **raw** → pilih **JSON**:
```json
{ "username": "admin", "password": "admin123" }
```
- Tab **Scripts** (atau **Tests** di Postman versi lama), isi agar token tersimpan otomatis:
```javascript
const r = pm.response.json();
if (r.success) { pm.collectionVariables.set("token", r.data.token); }
```

**Auth → Login Pasien**
- `POST {{baseUrl}}/auth/login.php`, body:
```json
{ "username": "pasien1", "password": "pasien123" }
```
- Script yang sama seperti di atas.

**Auth → Register**
- `POST {{baseUrl}}/auth/register.php`, body:
```json
{ "name": "Budi Tes", "username": "budi01", "password": "rahasia123", "phone": "08123456789" }
```

**Doctors → Read**
- `GET {{baseUrl}}/doctors/read.php`

### 10c. Hasil yang harus kamu lihat

| Uji | Hasil benar |
|---|---|
| Login admin (benar) | `success: true`, ada `token`, `user.role` = `admin` |
| Login password salah | `success: false`, status 401 |
| Register username baru | `success: true`; cek baris baru di tabel `users` dan `patients` |
| Register username yang sama lagi | `success: false`, status 409 |
| Doctors read | 3 dokter |

### 10d. Export ke repo
Klik titik tiga di collection → **Export** → **Collection v2.1** → simpan sebagai
`C:\Projects\medqueue\backend\postman\MedQueue_API.postman_collection.json`.
Ulangi setiap ada endpoint baru.

---

## Langkah 11. Commit dan push pekerjaan hari ini (alur harian yang benar)

Mulai dari sini, **jangan kerja langsung di `dev`**. Pakai branch fitur:

```
git checkout dev
git pull
git checkout -b feature/backend-auth
git add .
git commit -m "feat: collection Postman dan tes auth"
git push -u origin feature/backend-auth
```

Lalu buka GitHub, klik **Compare & pull request**, pastikan tujuannya `dev`, klik **Create pull request**, lalu **Merge** (kamu leader, kamu yang merge). Setelah merge:

```
git checkout dev
git pull
```

lalu jalankan `deploy_backend.bat` lagi.

Alur harian leader, ringkas:
1. `git checkout dev` lalu `git pull`
2. Review Pull Request anggota (Files changed → Review changes)
3. Merge ke `dev`
4. `git pull` lalu `deploy_backend.bat`
5. Tes ulang `ping.php` dan alur utama

---

## Langkah 12. Urutan pekerjaanmu berikutnya

| Urutan | Pekerjaan | Tanda selesai |
|---|---|---|
| 1 | CRUD dokter: `doctors/create.php`, `update.php`, `delete.php` (admin saja; dokter yang punya riwayat tidak boleh dihapus) | Admin bisa tambah/ubah dokter, delete dokter berriwayat ditolak dengan pesan |
| 2 | CRUD jadwal: `schedules/create.php`, `update.php`, `delete.php` | Admin bisa tambah jadwal dan kuota |
| 3 | `auth/update_profile.php` dan `auth/change_password.php` | Nama, HP, dan password bisa diubah |
| 4 | Uji keamanan role: panggil semua endpoint admin dengan akun pasien | Semua ditolak (403) |
| 5 | Review PR, merge, deploy, jalankan alur gabungan | Tidak ada layar atau endpoint yang rusak |
| 6 | Menjelang akhir: merge `dev` ke `main`, uji alur demo penuh | Demo berjalan dari awal sampai akhir |

Kalau mau, minta aku buatkan kode untuk urutan 1 sampai 3 dengan pola yang sama persis seperti `login.php` dan `register.php`.

---

## Catatan keputusan teknis (supaya tim sepakat)

Dokumen konsep belum menjelaskan cara server mengenali siapa yang login. Starter ini memakai **token bertanda tangan**:
- `login.php` mengembalikan `data.token`.
- Flutter menyimpannya (di `shared_preferences`) dan mengirimnya di header `X-Auth-Token` pada setiap request.
- Server membaca user dan role dari database, jadi role tidak bisa dipalsukan dari aplikasi.
- Tidak ada kolom tambahan di tabel `users`, jadi 6 tabel tetap sesuai dokumen.

Contoh respons login yang perlu diketahui Ifa:

```json
{
  "success": true,
  "data": {
    "token": "....",
    "user": { "id": 2, "name": "Pasien Uji", "username": "pasien1", "role": "patient" },
    "patient_id": 1,
    "phone": "081234567890"
  },
  "message": "Login berhasil."
}
```

Sebelum demo, ganti nilai `AUTH_SECRET` di `backend/helpers/auth.php` dengan teks acak panjang milikmu.

---

## Kalau error

| Gejala | Penyebab umum | Solusi |
|---|---|---|
| Apache tidak mau Start (port 80 dipakai) | IIS, Skype, atau program lain memakai port 80 | Di XAMPP klik **Netstat** untuk melihat siapa pemakainya, hentikan program itu (untuk IIS: Services → World Wide Web Publishing Service → Stop), lalu Start lagi |
| MySQL tidak mau Start | Port 3306 dipakai MySQL lain | Hentikan service MySQL lain di Services, atau restart laptop lalu coba lagi |
| `git` tidak dikenali | Git belum terpasang atau VS Code belum dibuka ulang | Pasang Git for Windows, tutup lalu buka lagi VS Code |
| `git push` ditolak (403 atau authentication failed) | Login GitHub salah akun | Windows → Credential Manager → hapus kredensial `github.com`, push lagi |
| `git push` ditolak "fetch first" | Repo GitHub tidak kosong (kamu mencentang README) | Buat repo baru yang kosong, atau `git pull origin main --allow-unrelated-histories` lalu push |
| phpMyAdmin Import gagal | File SQL salah pilih | Pastikan `database\medqueue.sql`, bukan file lain |
| `ping.php` bilang "Database tidak terhubung" | MySQL belum Start, atau database belum diimpor | Start MySQL di XAMPP, ulangi impor (Langkah 6b) |
| `localhost/medqueue_api` 404 | Belum deploy | Jalankan `deploy_backend.bat` |
| `deploy_backend.bat` bilang folder htdocs tidak ditemukan | Path XAMPP berbeda | Buka file `.bat` dengan VS Code, ubah `XAMPP_DIR` |
| HP tidak bisa membuka IP server | Firewall, jaringan Public, atau WiFi memblokir antarperangkat | Ulangi 9b dan 9c, coba hotspot HP |
| Postman: "Belum login" (401) | Token kosong | Jalankan request Login dulu, cek variabel `token` terisi |
| Postman: "Could not get response" | Apache mati atau baseUrl salah | Cek XAMPP hijau dan nilai `baseUrl` |

---

## Checklist akhir malam ini

- [ ] Repo `medqueue` ada di GitHub, branch `main` dan `dev`
- [ ] 4 anggota sudah diundang
- [ ] Database `medqueue` punya 6 tabel dan seed
- [ ] `ping.php` dan `doctors/read.php` jalan di localhost
- [ ] Login admin dan login pasien berhasil di Postman
- [ ] Pengumuman "siap" sudah dikirim ke grup
- [ ] (Boleh besok) IP server diumumkan dan dites dari HP
