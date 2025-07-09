# n8n Connect untuk Jeedom

Plugin ini memungkinkan Anda untuk mengontrol dan memantau alur kerja **n8n** Anda langsung dari antarmuka otomatisasi rumah Jeedom Anda. Ini menawarkan integrasi yang sederhana dan efektif untuk meluncurkan alur kerja, mengaktifkan/menonaktifkannya, dan memeriksa statusnya.

## Fitur

*   **Konfigurasi instance n8n:** Hubungkan Jeedom Anda dengan mudah ke instance n8n Anda melalui URL dan kunci API-nya.
*   **Manajemen alur kerja:** Buat peralatan Jeedom untuk setiap alur kerja n8n yang ingin Anda kontrol.
*   **Perintah tindakan:**
    *   **Aktifkan/Nonaktifkan:** Ubah status eksekusi alur kerja n8n Anda.
    *   **Luncurkan (melalui Webhook):** Picu alur kerja n8n dengan mengirimkan permintaan ke URL webhook yang dikonfigurasi. Perintah ini hanya muncul jika webhook dikonfigurasi untuk peralatan.
*   **Perintah informasi:**
    *   **Status:** Dapatkan status (aktif/tidak aktif) alur kerja n8n Anda.
*   **Notifikasi kesalahan alur kerja:** Terima notifikasi di Jeedom ketika alur kerja n8n gagal, memungkinkan manajemen masalah proaktif.
*   **Pemilihan yang disederhanakan:** Pilih alur kerja n8n Anda melalui daftar dropdown atau masukkan ID-nya secara manual.
*   **Pencatatan terperinci:** Log yang tepat untuk memfasilitasi diagnosis jika terjadi masalah.

## Prasyarat

1.  Instance [n8n](https://n8n.io/) yang berfungsi dan dapat diakses dari Jeedom Anda.
2.  API REST n8n harus diaktifkan pada instance Anda.
3.  Kunci API n8n yang valid dengan izin yang diperlukan untuk mengelola alur kerja.
4.  Jeedom versi 4.2.0 atau lebih tinggi.
5.  Ekstensi PHP `cURL` harus diinstal dan diaktifkan pada sistem Jeedom Anda.

## Instalasi

1.  Instal plugin "n8n Connect" langsung dari Jeedom Market.
2.  Setelah instalasi, aktifkan plugin di **Plugin > Manajemen Plugin**.

## Konfigurasi

### 1. Konfigurasi Plugin Global

Akses konfigurasi plugin global melalui **Plugin > Manajemen Plugin > n8n Connect > Konfigurasi**.

*   **URL instance n8n:** Masukkan alamat lengkap instance n8n Anda (misalnya: `https://my.n8n.local` atau `http://192.168.1.100:5678`).
*   **Kunci API:** Masukkan kunci API n8n Anda, yang dibuat di n8n (**Pengaturan > API**).
*   Klik tombol **"Uji"** untuk memverifikasi koneksi ke instance n8n Anda.

### 2. Konfigurasi Peralatan (Alur Kerja)

Untuk setiap alur kerja n8n yang ingin Anda kontrol:

1.  Buka **Plugin > n8n Connect**.
2.  Klik **"Tambah"** untuk membuat peralatan baru.
3.  **Nama peralatan:** Berikan nama yang bermakna untuk peralatan Jeedom Anda (misalnya: "Alur Kerja Lampu Ruang Tamu").
4.  **Alur Kerja:**
    *   Klik tombol segarkan (<i class="fas fa-sync"></i>) untuk memuat daftar alur kerja n8n Anda yang tersedia.
    *   Pilih alur kerja yang diinginkan dari daftar dropdown.
    *   Jika daftar tidak memuat (misalnya, karena masalah koneksi API), bidang entri ID alur kerja manual akan muncul. Anda dapat menemukan ID alur kerja Anda di antarmuka n8n.
5.  **URL Webhook (Opsional):** Jika Anda ingin memicu alur kerja ini melalui perintah "Luncurkan", tempelkan URL webhook alur kerja n8n Anda di sini. URL ini disediakan oleh node "Webhook" alur kerja n8n Anda.
6.  Konfigurasi **Parameter umum** (Objek induk, Kategori, Aktifkan/Terlihat) sesuai kebutuhan Anda.
7.  Klik **"Simpan"**. Perintah "Aktifkan", "Nonaktifkan", dan "Status" akan dibuat secara otomatis. Perintah "Luncurkan" akan ditambahkan jika URL webhook telah disediakan.

## Perintah yang Tersedia

Setelah peralatan dikonfigurasi, perintah berikut akan tersedia:

*   **Aktifkan:** Mengaktifkan alur kerja yang sesuai di n8n.
*   **Nonaktifkan:** Menonaktifkan alur kerja yang sesuai di n8n.
*   **Luncurkan:** Mengirimkan permintaan HTTP POST ke URL webhook yang dikonfigurasi untuk alur kerja. Perintah ini hanya terlihat jika "URL Webhook" disediakan dalam konfigurasi peralatan.
*   **Status:** Perintah informasi biner yang menunjukkan apakah alur kerja aktif (1) atau tidak aktif (0) di n8n.

## Pemecahan Masalah

### Kesalahan HTTP 401 "tidak sah"

Kesalahan ini menunjukkan masalah autentikasi saat mencoba terhubung ke API n8n.

*   **Periksa konfigurasi Anda:** Pastikan **URL instance n8n** dan **Kunci API** dimasukkan dengan benar dalam konfigurasi plugin global.
*   **Uji koneksi:** Gunakan tombol **"Uji"** di halaman yang sama untuk memvalidasi kredensial Anda.
*   **Periksa n8n:**
    *   Pastikan API REST diaktifkan di n8n (**Pengaturan > API**).
    *   Verifikasi bahwa kunci API n8n Anda valid dan tidak kedaluwarsa, dan bahwa ia memiliki izin yang diperlukan.
    *   Pastikan instance n8n Anda dimulai dan dapat diakses dari Jeedom.
*   **Konektivitas jaringan:** Periksa firewall atau masalah jaringan yang mungkin mencegah Jeedom berkomunikasi dengan n8n.

### Pesan kesalahan umum

*   **"URL webhook hilang":** Perintah "Luncurkan" telah dieksekusi, tetapi tidak ada URL webhook yang dikonfigurasi untuk peralatan ini.
*   **"Kesalahan webhook: Webhook yang diminta ... tidak terdaftar":** Alur kerja tidak aktif di n8n, atau URL webhook salah. Pastikan alur kerja diaktifkan di n8n dan URL-nya tepat.
*   **"Waktu habis":** Jeedom tidak dapat menjangkau instance n8n Anda dalam waktu yang ditentukan. Pastikan n8n online dan dapat diakses.
*   **"Respons API n8n tidak valid":** API n8n mengembalikan respons yang tidak terduga.

### Log diagnostik

Untuk informasi lebih rinci, lihat log plugin:
1.  Buka **Alat > Log**.
2.  Pilih plugin **n8nconnect**.
3.  Cari pesan kesalahan terbaru untuk mengidentifikasi penyebab masalah.

## Notifikasi kesalahan n8n ke Jeedom

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