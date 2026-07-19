# Demo Ticket Traffic Simulator untuk XENA

## Tujuan

Dokumen ini menjelaskan bagaimana menjalankan simulator tiket demo agar aplikasi XENA dapat menampilkan aliran tiket real-time secara otomatis.

Tujuan utama:
- Membuat tiket baru secara otomatis setiap beberapa detik.
- Memastikan tiket masuk ke dua jalur yang berbeda sesuai logika routing yang sudah ada:
  - auto-assign ke agent (round-robin) untuk urgency 1–2
  - masuk loker Team Leader untuk urgency 3–5
- Memungkinkan demo dashboard agent dan team leader terlihat hidup tanpa refresh.

---

## Cara kerja

Simulasi ini memanfaatkan logika routing yang sudah ada di aplikasi:

- [app/Observers/TicketObserver.php](../app/Observers/TicketObserver.php): setiap `Ticket::create(...)` memicu observer `created()`, yang lalu memanggil `TicketRoutingService::routeTicket()`.
- [app/Services/TicketRoutingService.php](../app/Services/TicketRoutingService.php): routing akan:
  - menentukan urgency dari `reportedpriority`
  - memilih auto-assign ke agent bila urgency 1–2
  - mengirim tiket ke loker Team Leader bila urgency 3–5
  - bila tidak ada agent online, tiket urgency rendah akan jatuh ke `QUEUED`

Dengan demikian, command simulator hanya perlu membuat tiket baru dengan data acak; routing otomatis akan berjalan sendiri.

---

## Command simulator

Command tersedia melalui:

```bash
php artisan tickets:simulate
```

### Opsi yang tersedia

```bash
php artisan tickets:simulate --interval=10 --max=0 --high=0.4
```

Penjelasan opsi:
- `--interval=10` : jeda detik antar tiket (contoh: 10 atau 30)
- `--max=0` : batas jumlah tiket. Nilai `0` berarti tak terbatas sampai Ctrl+C
- `--high=0.4` : proporsi tiket urgency tinggi (3–5) yang masuk ke loker Team Leader; sisanya akan cenderung ke agent

### Contoh pemakaian

```bash
php artisan tickets:simulate --interval=10 --high=0.4
php artisan tickets:simulate --interval=30 --max=10
```

---

## Data acak yang dibangkitkan

Generator data acak dipusatkan sehingga command simulator dan command inject lama memakai sumber yang sama.

Atribut acak yang dibentuk meliputi:
- `namacust`
- `jenisTicket`
- `topic`
- `reportedpriority`
- `source_system`
- `channel`
- `pool_id`
- `noSC`
- `contact`
- `eksalasiVia`
- `detailticket`
- dan atribut lain yang relevan untuk demo

### Mapping urgency yang dipakai

Berdasarkan logika yang sudah ada di routing service:

- `Low Emergency` → urgency `1`
- `Emergency` → urgency `2`
- `Super Emergency` / `SE` → urgency `3`
- `HVC` / `High Value` → urgency `4`
- `VVIP` / `Management` → urgency `5`

Karena routing service menggunakan `reportedpriority` untuk menghitung urgency, generator harus menghasilkan nilai prioritas yang sesuai.

---

## Demo lokal

Untuk menjalankan demo secara penuh:

### Terminal A — websocket realtime
```bash
php artisan reverb:start
```

### Terminal B — queue worker (jika broadcast diproses lewat queue)
```bash
php artisan queue:work
```

### Terminal C — simulator tiket
```bash
php artisan tickets:simulate --interval=10 --high=0.4
```

Buka dashboard:
- dashboard agent
- dashboard Team Leader

Tiket akan masuk secara real-time tanpa refresh. Sebagian akan auto-assign ke agent, sebagian akan masuk loker Team Leader.

---

## Penanda tiket simulasi

Agar tiket demo mudah dibersihkan, disarankan menambahkan penanda seperti:
- `is_simulated` = `1`
- atau prefix pada `resume` / `namacust` seperti `SIM-`

Jika dibutuhkan, dapat dibuat command pembersih terpisah, misalnya:

```bash
php artisan tickets:cleanup-simulated
```

---

## Catatan penting

- Jangan mengubah logika routing yang sudah ada.
- Jangan mengubah skema database atau nama route.
- Simulator harus membuat tiket melalui `Ticket::create(...)` saja; jangan mengisi `assigned_to_user_id` manual.
- Command ini adalah CLI dan tidak boleh dipakai di request HTTP.

---

## Stop simulator

Untuk menghentikan simulator:
- tekan `Ctrl+C`, atau
- tunggu sampai `--max` tercapai

Simulator dirancang agar berhenti dengan bersih dan tidak meninggalkan proses yang menggantung.
