#include <iostream>
#include <vector> //menyimpan data harga
#include <chrono> //mengukur waktu eksekusi

using namespace std;
using namespace chrono;

//fungsi untuk menghitung total harga secara iteratif
long long totalIteratif(const vector<int>& harga) {
    long long total = 0;
    for (int h : harga) total += h;
    return total;
}

//fungsi untuk menghitung total harga secara rekursif
long long totalRekursifHelper(const vector<int>& harga, int n) {
    if (n == 0) return 0;
    return harga[n-1] + totalRekursifHelper(harga, n-1);
}

//wrapper function untuk memulai rekursi
long long totalRekursif(const vector<int>& harga) {
    return totalRekursifHelper(harga, harga.size());
}

//argc = jumlah argumen
//argv = array dari argumen-argumen tersebut
int main(int argc, char* argv[]) {
    if (argc < 2) {
        cout << "0 0"; //default output
        return 0;
    }

    //mengambil nilai n dari argumen baris perintah
    int n = stoi(argv[1]);
    //jumlah pengulangan untuk pengukuran waktu
    const int ULANGI = 200000;

    //membuat vektor harga dengan n elemen, masing-masing bernilai 10000
    vector<int> harga(n, 10000);

    //mengukur waktu eksekusi metode iteratif
    auto s1 = high_resolution_clock::now();
    //menjalankan totalIteratif sebanyak ULANGI kali
    for (int i = 0; i < ULANGI; i++) totalIteratif(harga);
    //mencatat waktu selesai
    auto e1 = high_resolution_clock::now();

    //mengukur waktu eksekusi metode rekursif
    auto s2 = high_resolution_clock::now();
    //menjalankan totalRekursif sebanyak ULANGI kali
    for (int i = 0; i < ULANGI; i++) totalRekursif(harga);
    //mencatat waktu selesai
    auto e2 = high_resolution_clock::now();

    //menampilkan waktu eksekusi dalam mikrodetik
    cout << duration_cast<microseconds>(e1 - s1).count() << " ";
    cout << duration_cast<microseconds>(e2 - s2).count();

    return 0;
}