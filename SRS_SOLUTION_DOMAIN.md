# SOLUTION DOMAIN
## REQUIREMENT ELICITATION — LIST OF REQUIREMENTS
### Functional & Non-Functional Requirements

---

## Narasi Solution Domain

Berdasarkan permasalahan yang teridentifikasi dalam Problem Domain, sistem **XENA (Xena Efficient Network Assistant)** dirancang sebagai solusi berbasis web untuk mengatasi seluruh kendala dalam pengelolaan tiket keluhan pelanggan. Sistem ini mengimplementasikan arsitektur *Role-Based Access Control* (RBAC) dengan tiga peran pengguna — Admin, Team Leader, dan Agent — yang masing-masing memiliki hak akses dan fungsionalitas berbeda sesuai tanggung jawabnya.

Solusi yang ditawarkan mencakup dua kelompok kebutuhan: **Functional Requirements** yang menggambarkan fungsi-fungsi konkret yang harus dimiliki sistem sebagai jawaban atas permasalahan operasional, serta **Non-Functional Requirements** yang memastikan sistem berjalan secara andal, aman, dan mudah digunakan dalam lingkungan produksi.

---

## Tabel 1 — Functional Requirements

| Req ID | Req Description | Ambiguity | Incomplete | Inconsistent |
|--------|----------------|-----------|------------|--------------|
| F-01 | Sistem menyediakan mekanisme autentikasi login menggunakan email/username dan password, dengan validasi identitas pengguna sebelum diberikan akses ke aplikasi. *(Merujuk: P1)* | | | |
| F-02 | Sistem mengarahkan pengguna secara otomatis ke dashboard yang sesuai berdasarkan role masing-masing (Admin, Team Leader, Agent) setelah login berhasil. *(Merujuk: P1)* | | | |
| F-03 | Sistem menyediakan fitur logout yang mengakhiri sesi pengguna secara aman dan menghapus token autentikasi aktif. *(Merujuk: P1)* | | | |
| F-04 | Sistem menyediakan fitur reset password mandiri melalui verifikasi email, sehingga pengguna dapat memulihkan akses tanpa bantuan pihak lain. *(Merujuk: P1)* | | Tidak dijelaskan apa yang terjadi jika email pengguna belum terverifikasi atau tidak terdaftar di sistem. | |
| F-05 | Admin dapat melihat daftar seluruh pengguna terdaftar beserta role, status aktif/nonaktif, dan informasi penugasannya, serta dapat melakukan pencarian dan filter berdasarkan kriteria tertentu. *(Merujuk: P2)* | "Kriteria tertentu" tidak mendefinisikan secara spesifik field apa saja yang dapat dijadikan filter. | | |
| F-06 | Admin dapat menambahkan pengguna baru ke sistem dengan menentukan role (Admin, Team Leader, atau Agent) dan data profil yang diperlukan melalui formulir pendaftaran terstandar. *(Merujuk: P2)* | | | |
| F-07 | Admin dapat mengubah role pengguna yang sudah terdaftar serta mengaktifkan atau menonaktifkan akun pengguna tanpa menghapus data mereka dari sistem. *(Merujuk: P2)* | | Tidak dijelaskan apakah Admin dapat mengubah role akunnya sendiri, dan apa dampaknya jika hal tersebut dilakukan. | |
| F-08 | Admin dapat menghapus pengguna secara permanen dari sistem apabila diperlukan. *(Merujuk: P2)* | | Tidak dijelaskan apa yang terjadi pada tiket yang masih di-assign ke pengguna yang dihapus. | |
| F-09 | Admin memiliki dashboard yang menampilkan overview statistik sistem secara real-time, meliputi total pengguna, jumlah tiket per status, activity log, dan grafik performa. *(Merujuk: P3)* | "Real-time" tidak mendefinisikan interval pembaruan data — apakah setiap detik, menit, atau hanya saat refresh halaman. | | |
| F-10 | Team Leader memiliki dashboard yang menampilkan ringkasan statistik tiket tim, jumlah tiket assigned/unassigned, dan performa agent, disertai grafik tren tiket. *(Merujuk: P3)* | | | |
| F-11 | Agent memiliki dashboard yang menampilkan daftar tiket yang di-assign kepadanya, statistik tiket pribadi (open, on progress, solved, closed), serta status sesi kerja aktif. *(Merujuk: P3)* | | | |
| F-12 | Team Leader dapat melihat seluruh daftar tiket dalam sistem — baik yang sudah maupun belum di-assign — beserta detail lengkap dan riwayat aktivitas setiap tiket. *(Merujuk: P4)* | | | |
| F-13 | Team Leader dapat memperbarui informasi dan status tiket, serta melakukan pencarian dan filter tiket berdasarkan status, prioritas, regional, witel, dan rentang tanggal. *(Merujuk: P4)* | | | |
| F-14 | Team Leader dapat melihat daftar tiket yang belum di-assign dan mendistribusikannya kepada agent yang tersedia, dengan informasi status ketersediaan (online/offline) masing-masing agent. *(Merujuk: P5)* | Tidak jelas apakah "agent yang tersedia" hanya berdasarkan status online, atau juga mempertimbangkan jumlah tiket aktif yang sedang ditangani (workload). | | |
| F-15 | Team Leader dapat melakukan reassign tiket dari satu agent ke agent lain apabila diperlukan penyesuaian distribusi beban kerja. *(Merujuk: P5)* | | Tidak dijelaskan apakah agent asal mendapatkan notifikasi ketika tiketnya di-reassign ke agent lain. | |
| F-16 | Sistem mengirimkan notifikasi otomatis kepada agent yang bersangkutan setiap kali tiket baru di-assign atau di-reassign kepadanya. *(Merujuk: P5)* | | Tidak dijelaskan mekanisme fallback jika pengiriman notifikasi gagal (misal: antrian penuh atau server tidak merespons). | |
| F-17 | Agent dapat melihat daftar tiket yang menjadi tanggung jawabnya secara terpusat, beserta detail lengkap setiap tiket, dan dapat melakukan pencarian serta filter berdasarkan status atau prioritas. *(Merujuk: P6)* | | | |
| F-18 | Agent dapat memperbarui progress penanganan tiket melalui formulir terstandar yang mencakup field resume, klasifikasi, topik, detail topik, nomor service center, status eskalasi, dan deskripsi penanganan. *(Merujuk: P6)* | | Tidak dijelaskan field mana yang wajib diisi (mandatory) dan mana yang bersifat opsional dalam formulir pembaruan tiket. | |
| F-19 | Agent dapat memperbarui status tiket menjadi On Progress, Pending, Solved, atau Closed, dan perubahan status tersebut langsung terefleksi secara real-time ke seluruh pengguna yang berwenang. *(Merujuk: P6)* | | Tidak dijelaskan aturan transisi status — apakah semua perpindahan status diperbolehkan atau ada alur tertentu yang harus diikuti (misal: tidak bisa langsung dari Open ke Closed). | |
| F-20 | Sistem mencatat secara otomatis seluruh riwayat perubahan tiket dalam bentuk activity log, termasuk identitas pengguna yang melakukan perubahan, field yang diubah, dan waktu kejadiannya. *(Merujuk: P7)* | | | |
| F-21 | Agent dapat mengubah status ketersediaannya menjadi Online atau Offline, di mana perpindahan ke status Online secara otomatis mencatat waktu mulai shift kerja. *(Merujuk: P8)* | | Tidak dijelaskan apa yang terjadi pada tiket yang masih aktif ketika agent mengubah statusnya menjadi Offline di tengah shift kerja. | |
| F-22 | Agent dapat mengaktifkan mode AUX (auxiliary) dengan memilih kategori — Break, Meeting, Training, atau Other — dan mengakhirinya untuk kembali ke status available, dengan seluruh waktu AUX tercatat otomatis oleh sistem. *(Merujuk: P8)* | Tidak jelas apakah agent masih dapat memperbarui atau mengakses tiket ketika sedang berada dalam mode AUX aktif. | | |
| F-23 | Agent dapat mengakhiri shift kerja (End Shift) dan sistem secara otomatis mencatat waktu akhir shift serta menghitung total jam kerja, jam AUX, jam available, dan jam produktif hari tersebut. *(Merujuk: P8)* | | | Tidak konsisten — jika agent lupa menekan End Shift atau tiba-tiba offline tanpa mengakhiri sesi, tidak jelas apakah sistem secara otomatis menutup shift atau membiarkan sesi terbuka tanpa batas waktu. |
| F-24 | Admin dapat melihat laporan tiket dari seluruh sistem secara terpusat dengan kemampuan filter berdasarkan pengguna, rentang tanggal, status tiket, prioritas, dan regional/witel. *(Merujuk: P9)* | | | |
| F-25 | Admin dapat mengunduh laporan performa seluruh pengguna, laporan seluruh tiket, maupun laporan tiket per pengguna (assigned/solved/inbox) dalam format file Excel (.xlsx) yang terstruktur dan siap pakai. *(Merujuk: P9)* | "Terstruktur dan siap pakai" tidak mendefinisikan secara spesifik kolom apa saja yang harus ada dalam file Excel yang dihasilkan. | | |
| F-26 | Sistem menyediakan notifikasi real-time kepada pengguna terkait aktivitas penting seperti assignment tiket, perubahan status, dan eskalasi, disertai indikator jumlah notifikasi yang belum dibaca. *(Merujuk: P10)* | "Real-time" tidak mendefinisikan toleransi keterlambatan pengiriman notifikasi yang masih dapat diterima pengguna. | | |
| F-27 | Pengguna dapat menandai notifikasi sebagai sudah dibaca (mark as read) untuk memisahkan notifikasi yang telah ditindaklanjuti dari yang belum. *(Merujuk: P10)* | | | |
| F-28 | Setiap pengguna dapat memperbarui informasi profil pribadinya — nama, email, username, nomor telepon — serta mengganti password akun secara mandiri melalui halaman profil. *(Merujuk: P11)* | | Tidak dijelaskan aturan validasi yang berlaku, seperti keunikan username/email, panjang minimum password, dan format nomor telepon yang diterima. | |
| F-29 | Admin dapat mengakses halaman pengaturan sistem yang menampilkan informasi konfigurasi aplikasi dan memungkinkan pengaktifan atau penonaktifan fitur tertentu seperti notifikasi dan auto-assign. *(Merujuk: P12)* | "Fitur tertentu" tidak mendefinisikan secara lengkap daftar fitur apa saja yang dapat dikonfigurasi oleh Admin. | | |
| F-30 | **[REQ-TAMBAHAN]** Sistem secara paksa menolak permintaan "End Shift" dari Agen jika masih memegang tiket "On Progress", mengharuskannya diselesaikan atau di-*handover*. | Menutupi ambiguitas transisi tiket aktif (merujuk F-21). | | |
| F-31 | **[REQ-TAMBAHAN]** Sistem memisahkan wewenang agen di mana status maksimal adalah "Solved". Perubahan ke "Closed" diesksekusi eksklusif oleh sistem sesudah tervalidasi 1x24 jam dari pihak pelanggan. | Menutupi ambiguitas kepemilikan wewenang resolusi akhir. | | |
| F-32 | **[REQ-TAMBAHAN]** Sistem otomatis menutup sisa sesi shift terbuka milik agen di jam malam atau tenggat tertentu jika lolos deteksi ("*auto-logout cronjob*") sehingga mencegah sesi menggantung tak terbatas. | | | Mencegah inkonsistensi kealpaan penghentian waktu kerja (merujuk F-23). |
| F-33 | **[REQ-TAMBAHAN]** Sistem menyediakan automasi *Auto-Routing* beban kerja untuk membagi tiket masuk ("Unassigned") prioritas ke Agen terluang yang berstatus "Online" dengan beban metrik (*workload*) paling rendah. | Menutupi ambiguitas sebaran distribusi prioritas ketersediaan agen secara spesifik (merujuk F-14). | | |
| F-34 | **[REQ-TAMBAHAN]** Sistem memblokir modifikasi Target Handling Time (THT) dari pengguna dan menggantinya sebagai kalkulasi mutlak (angka baku) berdasarkan tingkatan kelas Priority. | | | Menutupi inkonsistensi kebebasan metrik penilaian durasi yang dapat diubah manual (merujuk F-18). |
| F-35 | **[REQ-TAMBAHAN]** Sistem memberlakukan validasi mekanisme urutan paksa *State-Machine* agar tiket dilarang melompat langsung dari Open > Closed, agen akan diblokir tanpa melengkapi prosedur formulir *Resume* terlebih dahulu. | | | Mencegah inkonsistensi akibat aturan transisi status bebas lintas yang sebelumnya cacat celah (merujuk F-19). |
| F-36 | **[REQ-TAMBAHAN]** Fitur Reset Password (*Recovery*) wajib menampilkan *error modal* tegas apabila input email target tidak eksis pada *database* untuk mendiferensiasi *invalid email*. | | Menutupi rumpang panduan ketika pemulihan tidak memiliki respons jelas (merujuk F-04). | |
| F-37 | **[REQ-TAMBAHAN]** Semua operasi perombakan atau modifikasi konfigurasi global oleh Administrator memancarkan log pencatatan khusus atau tidak bisa menyertakan akun miliknya untuk intervensi *Role* demi *safety guard*. | | Mengatasi poin tidak tuntas untuk eskalasi kekacauan dari hierarki administrator sendiri (merujuk F-07). | |

---

## Tabel 2 — Non-Functional Requirements

| Req ID | Req Description | Ambiguity | Incomplete | Inconsistent |
|--------|----------------|-----------|------------|--------------|
| NF-01 | **Usability** — Antarmuka aplikasi harus intuitif dan mudah digunakan oleh pengguna dengan latar belakang teknis yang beragam, dengan navigasi yang konsisten dan label yang jelas pada setiap fitur. | "Intuitif dan mudah digunakan" bersifat subjektif dan tidak memiliki metrik pengukuran yang terukur (misal: task completion rate atau error rate). | | |
| NF-02 | **Usability** — Tampilan aplikasi harus responsif dan dapat diakses dengan baik melalui berbagai perangkat dan ukuran layar, termasuk desktop, tablet, dan smartphone. | | | |
| NF-03 | **Usability** — Setiap aksi penting seperti assignment tiket, perubahan status, dan penghapusan pengguna harus disertai konfirmasi atau feedback visual yang jelas kepada pengguna. | | | |
| NF-04 | **Reliability** — Sistem harus mampu beroperasi secara stabil dengan tingkat ketersediaan (*uptime*) minimal 99% selama jam operasional layanan customer service berlangsung. | | Tidak dijelaskan periode pengukuran uptime (harian, bulanan, atau tahunan) maupun tindakan yang diambil jika target tidak tercapai. | |
| NF-05 | **Reliability** — Seluruh data tiket, aktivitas pengguna, dan log perubahan harus tersimpan secara konsisten di database tanpa risiko kehilangan data akibat kegagalan sistem. | | | |
| NF-06 | **Reliability** — Sistem harus mampu menangani kegagalan koneksi sementara tanpa menyebabkan kehilangan data atau kerusakan status yang sedang diproses. | "Kegagalan koneksi sementara" tidak mendefinisikan batas durasi gangguan yang masih dapat ditoleransi oleh sistem. | | |
| NF-07 | **Performance** — Halaman utama dan dasbor masing-masing role harus dapat dimuat dalam waktu kurang dari 3 detik pada kondisi jaringan normal. | "Kondisi jaringan normal" tidak mendefinisikan parameter teknis seperti kecepatan bandwidth minimum yang menjadi acuan pengukuran. | | |
| NF-08 | **Performance** — Sistem harus mampu menangani penggunaan bersamaan (*concurrent users*) oleh seluruh agent, team leader, dan admin tanpa degradasi performa yang signifikan. | "Degradasi performa yang signifikan" tidak memiliki ambang batas yang terukur. | Tidak disebutkan jumlah maksimum concurrent users yang menjadi target kapasitas sistem. | |
| NF-09 | **Performance** — Proses ekspor laporan ke format Excel harus dapat diselesaikan dalam waktu yang wajar meskipun data yang diekspor mencakup ribuan baris tiket. | "Waktu yang wajar" tidak mendefinisikan batas waktu maksimum yang dapat diterima pengguna. | | |
| NF-10 | **Security** — Setiap endpoint dalam sistem harus dilindungi oleh middleware autentikasi dan otorisasi berbasis role, sehingga pengguna hanya dapat mengakses fitur yang sesuai dengan perannya. | | | |
| NF-11 | **Security** — Password pengguna harus disimpan dalam bentuk hash menggunakan algoritma yang aman (bcrypt) dan tidak pernah disimpan atau ditransmisikan dalam bentuk plaintext. | | | |
| NF-12 | **Security** — Sistem harus memiliki proteksi terhadap serangan umum seperti SQL Injection, Cross-Site Scripting (XSS), dan Cross-Site Request Forgery (CSRF). | | | |
| NF-13 | **Supportability** — Kode sumber aplikasi harus mengikuti standar penulisan kode yang konsisten (PSR-12 untuk PHP) sehingga mudah dipelihara dan dikembangkan oleh tim lain di masa mendatang. | | | |
| NF-14 | **Supportability** — Sistem harus dilengkapi dengan mekanisme pencatatan log error (*error logging*) yang memudahkan proses identifikasi dan perbaikan masalah teknis secara cepat. | | | |
| NF-15 | **Supportability** — Sistem harus dapat dijalankan di berbagai jenis web server (Apache/Nginx) dan mendukung konfigurasi database yang fleksibel (SQLite untuk development, MySQL untuk production). | | | |
| NF-16 | **[REQ-TAMBAHAN] Usability** — Pengukuran objektivitas perancangan (intuitivitas) divalidasi dengan pencapaian metrik kelulusan skor *System Usability Scale* bernilai minimal lebih dari 75. | Meruntuhkan penilaian subjektif antarmuka yang membingungkan. (merujuk NF-01). | | |
| NF-17 | **[REQ-TAMBAHAN] Reliability** — Batas toleransi durasi interupsi server komunikasi (titik tunda *kegagalan sementara*) pada *WebSocket* dimaksimalkan mentolerir pemutusan koneksi hingga durasi jeda 30 detik sebelum sesi ditendang keluar oleh sistem. | Mengisi tafsir durasi abu-abu toleransi gangguan koneksi sementara tanpa merusak draf entri (merujuk NF-06). | | |
| NF-18 | **[REQ-TAMBAHAN] Performance** — Standar acuan patokan pengujian rendering 3 detik diukur secara absolut mengacu kepada kecepatan unduh minimal jangkauan sinyal internet *1 Mbps*. | Menghapus tafsir abu-abu terkait batas parameter "Kondisi jaringan normal" (merujuk NF-07). | | |
| NF-19 | **[REQ-TAMBAHAN] Reliability** — Tingkat batas kesuksesan operasional sistem hitungan ketersediaan *Uptime 99%* mutlak diakumulasikan dan diuji coba setiap siklus penghujung bulan dalam satuan jam. | | Mengisi kekosongan penentuan siklus hitungan keberhasilan *uptime* evaluasi performa aplikasi (merujuk NF-04). | |
| NF-20 | **[REQ-TAMBAHAN] Performance** — Jaminan batas toleransi minim beban sistem dan pengujian server ditekankan sanggup menahan tekanan operasi tanpa *limit delay* hingga batas *threshold* **500 pengguna bersamaan** (*concurrent users*). | | Mengatasi rumpang sasaran infrastruktur jumlah maksimum kapibilitas puncak penggunaan (merujuk NF-08). | |
| NF-21 | **[REQ-TAMBAHAN] Performance** — Batasan waktu tunggu (*wait time*) yang dapat diterima pengguna maksimal selesai dalam tempo *15 hingga 30 Detik* lamanya untuk pemrosesan file rekapitulasi data Excel (.xlsx) per 10.000 rentetan baris pencatatan. | Menutupi bahasa samar batas komplain yang hanya ditafsirkan pada diksi "*waktu yang wajar*" (merujuk NF-09). | | |

---

## Tabel 3 — Inverse Requirements
**(Hal-Hal yang Tidak Boleh Terjadi Selama Penggunaan Sistem)**

| Req ID | Req Description |
|--------|----------------|
| INV-01 | Pengguna yang belum melakukan login **tidak boleh** dapat mengakses halaman mana pun di dalam sistem, termasuk halaman dashboard, tiket, laporan, maupun pengaturan. |
| INV-02 | Agent **tidak boleh** dapat mengakses halaman atau fitur yang hanya diperuntukkan bagi Team Leader atau Admin, seperti halaman manajemen pengguna, assign tiket, atau laporan sistem. |
| INV-03 | Team Leader **tidak boleh** dapat mengakses fitur manajemen pengguna dan pengaturan sistem yang merupakan hak eksklusif Admin, meskipun keduanya memiliki akses ke fitur pengelolaan tiket. |
| INV-04 | Sistem **tidak boleh** mengizinkan dua pengguna mendaftar atau terdaftar dengan alamat email atau username yang sama secara bersamaan dalam satu instance sistem. |
| INV-05 | Agent **tidak boleh** dapat melihat, mengakses, atau memperbarui tiket yang tidak di-assign kepadanya, baik tiket milik agent lain maupun tiket yang belum di-assign sama sekali. |
| INV-06 | Sistem **tidak boleh** mengizinkan Agent memperbarui status tiket menjadi Closed secara langsung tanpa melewati status Solved terlebih dahulu sebagai bagian dari alur penanganan yang sesuai prosedur. |
| INV-07 | Riwayat aktivitas (activity log) pada tiket **tidak boleh** dapat diubah, diedit, atau dihapus oleh pengguna mana pun, termasuk Admin, guna menjaga integritas jejak audit. |
| INV-08 | Sistem **tidak boleh** memperkenankan seorang agent untuk memulai sesi AUX ketika statusnya sedang Offline atau ketika shift belum dimulai, karena AUX hanya relevan dalam kondisi aktif bekerja. |
| INV-09 | Data tiket yang telah berstatus Closed **tidak boleh** dapat diperbarui atau diubah kembali oleh Agent, kecuali ada mekanisme pembukaan kembali (reopen) yang diotorisasi oleh Team Leader. |
| INV-10 | Sistem **tidak boleh** menampilkan password pengguna dalam bentuk teks biasa (plaintext) di antarmuka mana pun, termasuk pada halaman profil, formulir, maupun respons API. |
| INV-11 | Pengguna dengan akun nonaktif **tidak boleh** dapat masuk ke sistem meskipun memasukkan email dan password yang benar, hingga akun tersebut diaktifkan kembali oleh Admin. |
| INV-12 | Team Leader **tidak boleh** dapat meng-assign tiket kepada agent yang statusnya sedang Offline, karena penugasan kepada agent yang tidak tersedia berisiko menyebabkan tiket tidak tertangani tepat waktu. |
| INV-13 | Sistem **tidak boleh** mengizinkan ekspor laporan tanpa ada setidaknya satu filter atau parameter yang dipilih, guna mencegah unduhan data dalam skala penuh yang tidak terstruktur dan dapat membebani server. |
| INV-14 | Pengguna **tidak boleh** dapat menghapus atau mengubah notifikasi yang telah dikirimkan kepada pengguna lain, karena notifikasi bersifat individual dan hanya dapat dikelola oleh penerimanya masing-masing. |
| INV-15 | Sistem **tidak boleh** mengizinkan Agent memulai shift baru ketika shift sebelumnya masih tercatat sebagai aktif (belum diakhiri), untuk mencegah tumpang tindih data sesi kerja yang memengaruhi akurasi laporan produktivitas. |

---

## Tabel 4 — Design & Implementation Constraint

<table>
  <thead>
    <tr style="background-color: #ffff00; color: #000;">
      <th colspan="2" style="text-align: center; padding: 10px;"><em><b>Design & Implementation Constraint</b></em></th>
    </tr>
    <tr style="background-color: #ffff00; color: #000;">
      <th style="width: 10%; text-align: left;"><em><u>Req. ID</u></em></th>
      <th style="width: 90%; text-align: left;"><em><u>Req. Description</u></em></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Req 001</td>
      <td>Aplikasi XENA hanya berbentuk aplikasi <em>Web-Based</em> (mengutamakan tampilan layar peramban PC/Mobile), tidak disediakan khusus ke distribusi berkas aplikasi mentah <em>Mobile Native</em> (berbasis Android .apk maupun iOS).</td>
    </tr>
    <tr>
      <td>Req 002</td>
      <td>Sistem dioperasikan menggunakan bahasa pemrograman skrip berbasis PHP 8, pemrograman logika interaktif JavaScript, format JSON, dan struktur tampilan HTML.</td>
    </tr>
    <tr>
      <td>Req 003</td>
      <td><b>Sistem harus dikembangkan di atas arsitektur perancah utilitas Laravel Development Framework (mewajibkan dukungan versi v12).</b></td>
    </tr>
    <tr>
      <td>Req 004</td>
      <td>Gaya arsitektur visual sistem mewajibkan penggunaan pustaka <i>(Frontend Library)</i> berbasis abstraksi deklaratif dari Tailwind CSS dan interaktivitas ringan dari Alpine.js.</td>
    </tr>
    <tr>
      <td>Req 005</td>
      <td>Database relasional sentral produksi sistem wajib menggunakan eksekusi <i>storage engine</i> MySQL versi 8.0 ke atas.</td>
    </tr>
    <tr>
      <td>Req 006</td>
      <td><b>Infrastruktur lalu lintas sistem siaran waktu nyata (notifikasi/status) didelegasikan sepenuhnya menggunakan protokol <i>WebSocket Server</i> (merujuk pada Laravel Reverb / antarmuka Pusher).</b></td>
    </tr>
    <tr>
      <td>Req 007</td>
      <td>Sistem operasional pelaporan menuntut secara mutlak koneksi stabil dengan <i>Dependencies API</i> ekspor khusus pembaca file biner Excel <i>(Package Laravel Maatwebsite Excel)</i>.</td>
    </tr>
  </tbody>
</table>

---

*Dokumen ini merupakan bagian dari Software Requirements Specification (SRS) — Aplikasi XENA: Sistem Distribusi Tiket Keluhan Berbasis Web.*
