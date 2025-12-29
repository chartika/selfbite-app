<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"> <!-- mengatur encoding agar mendukung karakter khusus -->
<title>SelfBite - Self Reward App</title> <!-- judul halaman -->

<!-- library Chart.js untuk membuat grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/*mengatur tampilan keseluruhan halaman*/
body {
    margin: 0; /*menghilangkan margin bawaan browser*/
    height: 100vh; /*tinggi halaman 100% layar*/
    display: flex; /*menggunakan flexbox*/
    justify-content: center; /*posisi konten di tengah horizontal*/
    align-items: center; /*posisi konten di tengah vertikal*/
    background: #dad1ef; /*warna latar belakang*/
    font-family: Arial, sans-serif; /*jenis font*/
}

/*kartu utama aplikasi*/
.card {
    width: 460px; /*lebar kartu*/
    background: white; /*warna latar kartu*/
    padding: 25px; /*jarak isi ke tepi*/
    border-radius: 14px; /*sudut membulat*/
    box-shadow: 0 12px 25px rgba(0,0,0,0.15); /*bayangan*/
}

/*judul aplikasi*/
h2 {
    text-align: center;
    margin-bottom: 5px;
}

/*deskripsi aplikasi*/
p {
    text-align: center;
    color: #555;
    font-size: 15px;
}

/*baris input makanan*/
.food-row {
    display: flex; /*input sejajar*/
    gap: 8px; /*jarak antar input*/
    margin-bottom: 8px;
}

/*input nama dan harga*/
.food-row input {
    flex: 1; /*lebar seimbang*/
    padding: 8px;
}

/*tombol*/
button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    background: #6a63a5;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

/*efek hover tombol*/
button:hover {
    background: #4f4882;
}

/*box hasil perhitungan*/
.result {
    margin-top: 20px;
    background: #f0f5fa;
    padding: 15px;
    border-radius: 10px;
}
</style>

<script>
//fungsi untuk menambahkan input makanan baru secara dinamis
function tambahMakanan() {
    const div = document.createElement("div"); //membuat elemen div baru
    div.className = "food-row"; //memberi class agar sesuai CSS
    div.innerHTML = `
        <input type="text" name="nama[]" placeholder="Nama makanan" required>
        <input type="number" name="harga[]" placeholder="Harga" required>
    `;
    //menambahkan input ke dalam list makanan
    document.getElementById("listMakanan").appendChild(div);
}
</script>
</head>

<body>

<?php
//inisialisasi variabel
$total = 0; //total harga makanan
$iter = 0;  //waktu algoritma iteratif
$rek = 0;   //waktu algoritma rekursif
$data = null; //penanda apakah C++ berhasil dijalankan

//mengecek apakah form sudah disubmit
if (isset($_POST['harga'])) {
    $jumlahMenu = count($_POST['harga']); //jumlah makanan yang diinput user

    //menjumlahkan semua harga makanan
    foreach ($_POST['harga'] as $h) {
        $total += intval($h);
    }

    //menjalankan program C++ dan mengirim jumlah menu sebagai argumen
    $output = shell_exec("main.exe $jumlahMenu");

    //memastikan output dari C++ tidak kosong
    if ($output !== null && trim($output) !== "") {
        //memisahkan output C++ (iteratif dan rekursif)
        $data = preg_split('/\s+/', trim($output));
        $iter = $data[0] ?? 0; //waktu iteratif
        $rek  = $data[1] ?? 0; //waktu rekursif
    }
}
?>

<div class="card">
    <h2>🍰 SelfBite</h2>
    <p>Aplikasi Self-Reward Pembelian Makanan</p>

    <!-- form input makanan -->
    <form method="post">
        <div id="listMakanan">
            <!--input makanan pertama -->
            <div class="food-row">
                <input type="text" name="nama[]" placeholder="Nama makanan" required>
                <input type="number" name="harga[]" placeholder="Harga" required>
            </div>
        </div>

        <!-- tombol tambah input makanan -->
        <button type="button" onclick="tambahMakanan()">+ Tambah Makanan</button>
        <!-- tombol submit -->
        <button type="submit">Hitung Total</button>
    </form>

<?php if ($data !== null): ?>
    <!-- menampilkan hasil jika data dari C++ tersedia -->
    <div class="result">
        <b>Total Harga:</b> Rp <?= number_format($total) ?><br><br>

        <b>Running Time Algoritma</b><br>
        Iteratif : <?= $iter ?> µs<br>
        Rekursif : <?= $rek ?> µs

        <!-- canvas untuk grafik -->
        <canvas id="chart"></canvas>
    </div>
<?php endif; ?>
</div>

<?php if ($data !== null): ?>
<script>
//mengambil canvas grafik
const ctx = document.getElementById('chart').getContext('2d');

//membuat grafik batang menggunakan Chart.js
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Iteratif', 'Rekursif'], //label sumbu X
        datasets: [{
            label: 'Running Time (µs)', //keterangan grafik
            data: [<?= $iter ?>, <?= $rek ?>], //data dari C++
            backgroundColor: ['#76d0ee', '#76f5bf'] //warna batang
        }]
    },
    options: {
        responsive: true, //grafik menyesuaikan ukuran layar
        scales: {
            y: { beginAtZero: true } //sumbu Y mulai dari 0
        }
    }
});
</script>
<?php endif; ?>

</body>
</html>