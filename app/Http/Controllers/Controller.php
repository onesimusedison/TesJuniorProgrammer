<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Status;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function ambilData()
    {
        $cekData = Produk::count();

        if ($cekData > 0) {
            return redirect()->back();
        }

        $Date = date('d');
        $Month = date('m');
        $Year = date('y');
        $Hour = date('H');

        $username = "tesprogrammer" . $Date . $Month . $Year . "C" . $Hour;

        $rawPassword = "bisacoding-$Date-$Month-$Year";

        $password = md5($rawPassword);

        $response = Http::asForm()
            ->post('https://recruitment.fastprint.co.id/tes/api_tes_programmer', [
                'username' => $username,
                'password' => $password,
            ]);


        // $statusText = 'Status Code: ' . $response->status();
        // $headerText = 'Header: ' . $response->header('Content-Type');
        // $cookiesText = 'Cookies: ' . json_encode($response->cookies());

        // return "{$statusText}<br>{$headerText}<br>{$cookiesText}";

        if ($response->successful()) {
            $dataProduk = $response->json()['data'];

            // return $dataProduk;

            foreach ($dataProduk as $data) {
                $kategori = Kategori::where('nama_kategori', $data['kategori'])->first();

                if (!$kategori) {
                    Kategori::insert([
                        'nama_kategori' => $data['kategori']
                    ]);
                }
            }

            foreach ($dataProduk as $data) {
                $status = Status::where('nama_status', $data['status'])->first();

                if (!$status) {
                    Status::insert([
                        'nama_status' => $data['status']
                    ]);
                }
            }
            foreach ($dataProduk as $data) {
                $id_kategori = Kategori::where('nama_kategori', $data['kategori'])->value('id_kategori');
                $id_status = Status::where('nama_status', $data['status'])->value('id_status');
                Produk::insert([
                    'id_produk' => $data['id_produk'],
                    'nama_produk' => $data['nama_produk'],
                    'harga' => (int)$data['harga'],
                    'kategori_id' => (int)$id_kategori,
                    'status_id' => (int)$id_status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            return redirect('/');
        } else {
            $errorResponse = $response->json();
            return 'Gagal login: ' . json_encode($errorResponse) . '<br>' .
                'Status Code: ' . $response->status() . '<br>' .
                'Content Type: ' . $response->header('Content-Type') . '<br>' .
                'Cookies: ' . json_encode($response->cookies()) . '<br>' .
                'username: ' . $username;
        }
    }

    public function data($status = null)
    {
        if ($status === null) {
            $produk = Produk::get();
        } else {
            $produk = Produk::where('status_id', $status)->get();
        }

        return view('home', compact('produk', 'status'));
    }


    public function tambah()
    {
        $kategori = Kategori::get();
        $status = Status::get();


        return view('tambah', compact('kategori', 'status'));
    }

    public function tambahSimpan(Request $request)
    {

        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|integer',
        ], [
            'nama_produk.required' => 'Inputan nama harus diisi.',
            'harga.required' => 'Inputan harga harus diisi.',
            'harga.integer' => 'Harga harus berupa inputan angka.'
        ]);

        Produk::insert([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'kategori_id' => $request->kategori,
            'status_id' => $request->status
        ]);

        return redirect('/')->with('success', 'Data produk berhasil disimpan.');
    }


    public function hapus($id_produk)
    {
        try {
            Produk::destroy($id_produk);
            return response()->json(['success' => 'Produk berhasil dihapus.']);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Gagal menghapus produk.']);
        }
    }

    public function edit($id_produk)
    {
        $produk = Produk::find($id_produk);
        $kategori = Kategori::get();
        $status = Status::get();
        return view('edit', compact(
            'produk',
            'kategori',
            'status'
        ));
    }

    public function editSimpan($id_produk, Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|integer',
        ], [
            'nama_produk.required' => 'Inputan nama harus diisi.',
            'harga.required' => 'Inputan harga harus diisi.',
            'harga.integer' => 'Harga harus berupa inputan angka.',
        ]);

        Produk::where('id_produk', $id_produk)->update([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'kategori_id' => $request->kategori,
            'status_id' => $request->status
        ]);

        return redirect('/')->with('success', 'Data produk berhasil diupdate.');
    }
}
