# Changelog n8n Connect

## 0.1.0
- Versi pertama plugin n8n Connect untuk Jeedom.
  Plugin ini memungkinkan Anda untuk mengontrol dan memantau alur kerja n8n Anda langsung dari antarmuka otomatisasi rumah Jeedom Anda. Ini menawarkan integrasi yang sederhana dan efektif untuk meluncurkan alur kerja, mengaktifkan/menonaktifkannya, dan memeriksa statusnya.

  Fitur termasuk:
  - Konfigurasi instance n8n: Hubungkan Jeedom Anda dengan mudah ke instance n8n Anda melalui URL dan kunci API-nya.
  - Manajemen alur kerja: Buat peralatan Jeedom untuk setiap alur kerja n8n yang ingin Anda kontrol.
  - Perintah tindakan:
    - Aktifkan/Nonaktifkan: Ubah status eksekusi alur kerja n8n Anda.
    - Luncurkan (melalui Webhook): Picu alur kerja n8n dengan mengirimkan permintaan ke URL webhook yang dikonfigurasi.
  - Perintah informasi:
    - Status: Dapatkan status (aktif/tidak aktif) alur kerja n8n Anda.
  - Notifikasi kesalahan alur kerja: Terima notifikasi di Jeedom ketika alur kerja n8n gagal.
  - Pemilihan yang disederhanakan: Pilih alur kerja n8n Anda melalui daftar dropdown atau masukkan ID-nya secara manual.
  - Pencatatan terperinci: Log yang tepat untuk memfasilitasi diagnosis jika terjadi masalah.

## 0.1.1
- Deskripsi Italia yang dikoreksi di info.json.
- Memperbaiki kesalahan sintaks JSON di info.json (koma ekstra).
