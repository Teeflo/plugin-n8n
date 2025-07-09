# Konfigurasi dan Pemecahan Masalah Plugin n8n Connect

Dokumen ini menyediakan panduan terperinci untuk mengkonfigurasi plugin n8n Connect di Jeedom, serta solusi untuk masalah umum yang mungkin Anda temui.

## Daftar Isi

1.  [Prasyarat](#1-prasyarat)
2.  [Instalasi Plugin](#2-instalasi-plugin)
3.  [Konfigurasi Plugin Global](#3-konfigurasi-plugin-global)
    *   [Mengakses Konfigurasi](#mengakses-konfigurasi)
    *   [Parameter Koneksi n8n](#parameter-koneksi-n8n)
    *   [Uji Koneksi](#uji-koneksi)
4.  [Konfigurasi Peralatan (Alur Kerja)](#4-konfigurasi-peralatan-alur-kerja)
    *   [Membuat Peralatan Baru](#membuat-peralatan-baru)
    *   [Parameter Peralatan Umum](#parameter-peralatan-umum)
    *   [Parameter Alur Kerja Spesifik](#parameter-alur-kerja-spesifik)
    *   [Menyimpan Peralatan](#menyimpan-peralatan)
5.  [Perintah yang Tersedia](#5-perintah-yang-tersedia)
    *   [Perintah Tindakan](#perintah-tindakan)
    *   [Perintah Informasi](#perintah-informasi)
6.  [Pemecahan Masalah dan Kesalahan Umum](#6-pemecahan-masalah-dan-kesalahan-umum)
    *   [Kesalahan HTTP 401 "tidak sah"](#kesalahan-http-401-tidak-sah)
    *   ["URL webhook hilang"](#url-webhook-hilang)
    *   ["Kesalahan webhook: Webhook yang diminta ... tidak terdaftar"](#kesalahan-webhook-webhook-yang-diminta--tidak-terdaftar)
    *   ["Waktu habis"](#waktu-habis)
    *   ["Respons API n8n tidak valid"](#respons-api-n8n-tidak-valid)
    *   [Log Diagnostik](#log-diagnostik)
7.  [Dukungan](#7-dukungan)

---

## 1. Prasyarat

Sebelum memulai konfigurasi, pastikan elemen-elemen berikut sudah tersedia:

*   **Instance n8n:** Instance n8n yang berfungsi dan dapat diakses dari instalasi Jeedom Anda. Ini bisa berupa instance lokal, di jaringan pribadi, atau instance cloud.
*   **API REST n8n diaktifkan:** API REST harus diaktifkan di pengaturan instance n8n Anda. Anda biasanya akan menemukannya di bawah `Pengaturan > API`.
*   **Kunci API n8n:** Kunci API yang valid yang dibuat di n8n. Kunci ini harus memiliki izin yang diperlukan untuk:
    *   Mencantumkan alur kerja.
    *   Mengaktifkan/menonaktifkan alur kerja.
    *   (Opsional) Mengeksekusi alur kerja melalui API jika Anda menggunakan metode ini (meskipun plugin lebih memilih webhook untuk peluncuran).
*   **Jeedom:** Instalasi Jeedom versi 4.2.0 atau lebih tinggi.
*   **Ekstensi PHP cURL:** Ekstensi PHP `cURL` sangat penting bagi plugin untuk berkomunikasi dengan API n8n. Pastikan sudah terinstal dan diaktifkan di sistem Jeedom Anda.

## 2. Instalasi Plugin

1.  **Melalui Jeedom Market:** Akses antarmuka Jeedom Anda, lalu buka `Plugin > Manajemen Plugin > Market`. Cari "n8n Connect" dan instal.
2.  **Aktivasi:** Setelah instalasi selesai, plugin akan muncul di daftar plugin Anda. Klik tombol `Aktifkan` (biasanya ikon centang hijau) untuk membuatnya beroperasi.

## 3. Konfigurasi Plugin Global

Langkah ini membangun koneksi antara Jeedom Anda dan instance n8n Anda.

### Mengakses Konfigurasi

*   Di Jeedom, navigasikan ke `Plugin > Manajemen Plugin`.
*   Temukan "n8n Connect" di daftar dan klik ikonnya (biasanya kunci pas) atau tombol `Konfigurasi`.

### Parameter Koneksi n8n

Pada halaman konfigurasi, Anda akan menemukan bidang-bidang berikut:

*   **URL instance n8n:**
    *   Masukkan alamat lengkap instance n8n Anda.
    *   **Contoh:**
        *   `https://my.n8n.local` (untuk instance dengan SSL/TLS)
        *   `http://192.168.1.100:5678` (untuk instance lokal tanpa SSL/TLS, dengan port default)
    *   Pastikan URL dapat diakses dari server Jeedom.
*   **Kunci API:**
    *   Salin dan tempel kunci API yang Anda buat di instance n8n Anda (di bawah `Pengaturan > API`).
    *   **Peringatan:** Jangan pernah membagikan kunci ini. Ini memberikan akses ke instance n8n Anda.

### Uji Koneksi

*   Setelah memasukkan URL dan Kunci API, klik tombol **"Uji"**.
*   Jeedom akan mencoba terhubung ke instance n8n Anda dan mengambil daftar alur kerja untuk memverifikasi validitas informasi yang diberikan.
*   Pesan sukses atau kesalahan akan ditampilkan, menunjukkan apakah koneksi berhasil dibuat.

## 4. Konfigurasi Peralatan (Alur Kerja)

Setiap peralatan Jeedom mewakili alur kerja n8n spesifik yang ingin Anda kontrol.

### Membuat Peralatan Baru

1.  Di Jeedom, buka `Plugin > n8n Connect`.
2.  Klik tombol **"Tambah"** untuk membuat peralatan baru.

### Parameter Peralatan Umum

*   **Nama peralatan:** Berikan nama yang jelas dan deskriptif untuk peralatan Jeedom Anda (misalnya, "Alur Kerja Penyiraman Taman", "Alur Kerja Notifikasi").
*   **Objek induk:** Kaitkan peralatan dengan objek Jeedom yang ada (misalnya, "Taman", "Rumah").
*   **Kategori:** Tetapkan satu atau lebih kategori ke peralatan (misalnya, "Cahaya", "Keamanan").
*   **Opsi:**
    *   **Aktifkan:** Centang kotak ini untuk mengaktifkan peralatan di Jeedom.
    *   **Terlihat:** Centang kotak ini untuk membuat peralatan terlihat di Dasbor Jeedom.

### Parameter Alur Kerja Spesifik

*   **Alur Kerja:**
    *   Klik tombol segarkan (<i class="fas fa-sync"></i>) di samping bidang untuk memuat daftar semua alur kerja yang tersedia di instance n8n Anda.
    *   Pilih alur kerja n8n yang diinginkan yang harus dikontrol oleh peralatan ini dari daftar dropdown.
    *   **Kasus kesalahan:** Jika daftar tidak memuat (misalnya, karena masalah koneksi API atau jika tidak ada alur kerja yang ditemukan), bidang entri ID alur kerja manual akan muncul. Anda dapat menemukan ID alur kerja Anda di URL editor n8n (misalnya, `https://instance.n8n.anda/workflow/ID_ALUR_KERJA_ANDA`).
*   **URL Webhook (Opsional):**
    *   Jika Anda ingin dapat memicu alur kerja n8n ini melalui perintah "Luncurkan" dari Jeedom, Anda harus memasukkan URL webhook-nya.
    *   URL ini dihasilkan oleh node "Webhook" alur kerja n8n Anda. Salin URL lengkapnya (misalnya, `https://instance.n8n.anda/webhook/jalur-unik-anda`).
    *   **Penting:** Jika bidang ini kosong, perintah "Luncurkan" tidak akan tersedia untuk peralatan ini.
*   **Penyegaran otomatis:** (Jika tersedia) Memungkinkan Anda untuk menentukan frekuensi Jeedom harus menyegarkan status alur kerja (aktif/tidak aktif) dari n8n. Gunakan pembantu cron untuk menentukan jadwal.

### Menyimpan Peralatan

*   Setelah semua parameter dikonfigurasi, klik tombol **"Simpan"** di bagian atas halaman.
*   Jeedom akan menyimpan peralatan dan secara otomatis membuat perintah terkait (Aktifkan, Nonaktifkan, Status, dan Luncurkan jika webhook dikonfigurasi).

## 5. Perintah yang Tersedia

Setelah peralatan dikonfigurasi, perintah berikut akan dapat diakses:

### Perintah Tindakan

*   **Aktifkan:** Mengirimkan permintaan ke n8n untuk mengaktifkan alur kerja yang terkait dengan peralatan ini. Alur kerja akan mulai dieksekusi sesuai dengan konfigurasinya (misalnya, pada pemicu).
*   **Nonaktifkan:** Mengirimkan permintaan ke n8n untuk menonaktifkan alur kerja. Alur kerja akan berhenti dieksekusi dan tidak akan lagi merespons pemicunya.
*   **Luncurkan:** (Terlihat hanya jika "URL Webhook" dikonfigurasi untuk peralatan). Mengirimkan permintaan HTTP `POST` ke URL webhook yang ditentukan. Ini akan memicu eksekusi alur kerja n8n seolah-olah webhook telah dipanggil secara eksternal.

### Perintah Informasi

*   **Status:** Perintah informasi biner (`0` atau `1`) yang menunjukkan status alur kerja saat ini di n8n:
    *   `1` (Aktif): Alur kerja diaktifkan dan siap dieksekusi.
    *   `0` (Tidak Aktif): Alur kerja dinonaktifkan.
    *   Informasi ini diperbarui selama penyegaran otomatis atau setelah tindakan aktifkan/nonaktifkan.

## 6. Pemecahan Masalah dan Kesalahan Umum

Berikut adalah masalah yang paling sering ditemui dan cara mengatasinya.

### Kesalahan HTTP 401 "tidak sah"

**Deskripsi:** Kesalahan ini menunjukkan masalah autentikasi saat mencoba terhubung ke API n8n.

**Kemungkinan penyebab:**
*   Kunci API hilang, salah, atau kedaluwarsa.
*   API REST tidak diaktifkan di n8n.
*   URL instance n8n salah atau tidak dapat diakses.
*   Masalah izin kunci API.

**Solusi:**
1.  **Periksa konfigurasi plugin global Anda:** Pastikan **URL instance n8n** dan **Kunci API** dimasukkan dengan benar di `Plugin > Manajemen Plugin > n8n Connect > Konfigurasi`.
2.  **Uji koneksi:** Gunakan tombol **"Uji"** di halaman yang sama untuk memvalidasi kredensial Anda dan aksesibilitas instance.
3.  **Periksa n8n:**
    *   Di instance n8n Anda, buka `Pengaturan > API` dan pastikan API REST diaktifkan.
    *   Verifikasi bahwa kunci API yang Anda gunakan memang yang dibuat di sini, bahwa itu belum kedaluwarsa, dan bahwa ia memiliki izin yang diperlukan (setidaknya `workflows.read`, `workflows.write`, `workflows.activate`, `workflows.deactivate`).
    *   Pastikan instance n8n Anda dimulai dan berjalan dengan benar.
4.  **Konektivitas jaringan:** Periksa firewall atau masalah perutean jaringan yang mungkin mencegah Jeedom berkomunikasi dengan n8n pada port yang ditentukan.

### "URL webhook hilang"

**Deskripsi:** Pesan ini muncul ketika Anda mencoba mengeksekusi perintah "Luncurkan" untuk peralatan, tetapi bidang "URL Webhook" kosong dalam konfigurasinya.

**Solusi:**
*   Edit peralatan yang terpengaruh (`Plugin > n8n Connect`, klik pada peralatan).
*   Dalam parameter spesifik, masukkan URL webhook lengkap alur kerja n8n Anda di bidang **"URL Webhook"**.
*   Simpan peralatan. Perintah "Luncurkan" sekarang akan berfungsi.

### "Kesalahan webhook: Webhook yang diminta ... tidak terdaftar"

**Deskripsi:** n8n menunjukkan bahwa ia tidak dapat menemukan webhook yang sesuai dengan URL atau bahwa alur kerja tidak aktif.

**Kemungkinan penyebab:**
*   Alur kerja tidak diaktifkan di n8n. Webhook produksi hanya berfungsi jika alur kerja aktif.
*   URL webhook yang dimasukkan di Jeedom salah (kesalahan ketik, ID webhook salah, dll.).
*   Node "Webhook" di alur kerja n8n Anda tidak dikonfigurasi untuk menerima permintaan `POST` (meskipun ini adalah perilaku default).

**Solusi:**
1.  **Aktifkan alur kerja di n8n:** Buka alur kerja Anda di n8n dan pastikan tombol `Aktif` (kanan atas editor) diatur ke `ON`.
2.  **Periksa URL webhook:** Salin URL webhook langsung dari node "Webhook" alur kerja n8n Anda dan tempelkan lagi ke bidang "URL Webhook" peralatan Jeedom untuk menghindari kesalahan.
3.  **Metode HTTP:** Plugin mengirimkan permintaan `POST`. Pastikan node "Webhook" Anda di n8n dikonfigurasi untuk menerima permintaan `POST` (ini adalah default untuk webhook produksi).

### "Waktu habis"

**Deskripsi:** Jeedom tidak menerima respons dari n8n dalam waktu yang ditentukan (30 detik secara default).

**Kemungkinan penyebab:**
*   Instance n8n Anda dihentikan atau tidak responsif.
*   Masalah konektivitas jaringan antara Jeedom dan n8n (firewall, router, dll.).
*   Instance n8n kelebihan beban atau sangat lambat untuk merespons.

**Solusi:**
1.  **Periksa status n8n:** Pastikan instance n8n Anda berjalan dan dapat diakses melalui browser atau `ping` dari server Jeedom.
2.  **Periksa konektivitas:** Uji koneksi jaringan antara Jeedom Anda dan n8n. Misalnya, dari terminal Jeedom Anda, coba `curl -v URL_N8N_ANDA`.
3.  **Kinerja n8n:** Jika n8n kelebihan beban, pertimbangkan untuk mengoptimalkan alur kerja Anda atau meningkatkan sumber daya yang dialokasikan ke instance n8n Anda.

### Log Diagnostik

Untuk informasi lebih lanjut tentang kesalahan, lihat log plugin n8n Connect:

1.  Di Jeedom, buka `Alat > Log`.
2.  Di daftar dropdown, pilih `n8nconnect`.
3.  Log menampilkan komunikasi antara Jeedom dan n8n, termasuk permintaan yang dikirim dan respons yang diterima, yang sangat penting untuk pemecahan masalah.

## Notifikasi kesalahan alur kerja

Untuk menerima notifikasi kesalahan alur kerja n8n langsung di Jeedom, Anda dapat mengkonfigurasi "Alur Kerja Kesalahan" global di n8n yang akan mengirimkan permintaan HTTP ke Jeedom.

### Konfigurasi di n8n

1.  **Buat alur kerja baru** di n8n (atau gunakan alur kerja yang ada yang didedikasikan untuk kesalahan).
2.  Tambahkan node **"Webhook"** sebagai pemicu. Konfigurasikan untuk mendengarkan permintaan `POST`.
3.  Tambahkan node **"Permintaan HTTP"** setelah node "Webhook".
    *   **Metode:** `POST`
    *   **URL:** `http://IP_JEEDOM_ANDA/plugins/n8nconnect/core/ajax/n8nconnect.ajax.php?action=receiveErrorNotification`
        *   Ganti `IP_JEEDOM_ANDA` dengan alamat IP atau nama domain instalasi Jeedom Anda.
    *   **Tipe Konten Body:** `JSON`
    *   **Body JSON:** Anda dapat mengirim data JSON yang relevan. Misalnya, untuk mengirim informasi kesalahan dari alur kerja yang gagal, Anda dapat menggunakan ekspresi seperti:
        ```json
        {
          "workflowName": "{{ $json.workflow.name }}",
          "workflowId": "{{ $json.workflow.id }}",
          "executionId": "{{ $json.id }}",
          "error": "{{ $json.error.message }}",
          "stackTrace": "{{ $json.error.stack }}"
        }
        ```
        Variabel-variabel ini (`$json.workflow.name`, dll.) tersedia dalam konteks alur kerja kesalahan n8n.
4.  **Aktifkan alur kerja ini** di n8n.
5.  **Konfigurasi alur kerja ini sebagai "Alur Kerja Kesalahan" global:**
    *   Di n8n, buka **Pengaturan > Penanganan Kesalahan Alur Kerja**.
    *   Pilih alur kerja yang baru saja Anda buat dari daftar dropdown "Alur Kerja Kesalahan".

### Pemrosesan di Jeedom

Plugin n8n Connect akan menerima notifikasi ini dan mencatatnya di log plugin (`Alat > Log > n8nconnect`). Anda kemudian dapat menggunakan skenario Jeedom untuk menganalisis log ini dan memicu tindakan (notifikasi, peringatan, dll.) berdasarkan konten pesan kesalahan.

## 7. Dukungan

Jika Anda mengalami masalah yang terus-menerus setelah mengikuti panduan ini, harap kumpulkan informasi berikut sebelum meminta bantuan:

*   Versi Jeedom yang tepat (terlihat di `Pengaturan > Sistem > Konfigurasi > Umum`).
*   Versi instance n8n Anda.
*   Pesan kesalahan lengkap dan tepat, disalin langsung dari log Jeedom `n8nconnect`.
*   Tangkapan layar halaman konfigurasi plugin global (sembunyikan kunci API Anda).
*   Tangkapan layar halaman konfigurasi peralatan Jeedom yang bersangkutan.
*   Deskripsi terperinci tentang langkah-langkah untuk mereproduksi masalah.