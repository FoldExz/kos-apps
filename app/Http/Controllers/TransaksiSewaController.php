<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\PenyewaModel;
use App\Models\KamarModel;
use App\Models\TransaksiPembayaranModel;
use App\Models\TransaksiSewaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\ListItem;

class TransaksiSewaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Transaksi Sewa',
            'list' => ['Home', 'Transaksi Sewa']
        ];

        $page = (object)[
            'title' => 'Data Transaksi Sewa Kamar'
        ];

        $activeMenu = 'transaksi';

        return view('transaksi_sewa.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

// Tambahkan method ini di TransaksiSewaController.php

public function list(Request $request)
{
    // AUTO UPDATE STATUS YANG SUDAH EXPIRED
    $now = now();
    
    $expired = TransaksiSewaModel::where('status', 'aktif')
        ->where('tanggal_batas_sewa', '<', $now)
        ->get();

    // Debug - Uncomment untuk testing
    // dd([
    //     'current_time' => $now->toDateTimeString(),
    //     'expired_count' => $expired->count(),
    //     'expired_data' => $expired->toArray()
    // ]);

    foreach ($expired as $trx) {
        $trx->update(['status' => 'tidak_aktif']);
        KamarModel::where('id_kamar', $trx->id_kamar)
            ->update(['status' => 'tersedia']);
    }

    $data = TransaksiSewaModel::with(['penyewa', 'kamar'])
        ->select(
            'id_transaksi_sewa',
            'id_penyewa',
            'id_kamar',
            'lama_sewa',
            'tanggal_sewa',
            'tanggal_batas_sewa',
            'status'
        );

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('penyewa_nama', fn($row) => $row->penyewa->nama ?? '-')
        ->addColumn('kamar_nama', fn($row) => $row->kamar->no_kamar ?? '-')
        ->editColumn('tanggal_sewa', fn($row) => Carbon::parse($row->tanggal_sewa)->format('d-m-Y'))
        ->editColumn('tanggal_batas_sewa', fn($row) => Carbon::parse($row->tanggal_batas_sewa)->format('d-m-Y'))
        ->addColumn('status_badge', function ($row) {
            $color = $row->status == 'aktif' ? 'success' : 'danger';
            return '<span class="badge badge-' . $color . '">' . strtoupper($row->status) . '</span>';
        })
        ->addColumn('aksi', function ($row) {
            $urlDocx = url('/transaksi_sewa/' . $row->id_transaksi_sewa . '/generateDocx');

            return '
                <button class="btn btn-info btn-sm" onclick="window.location.href=\'' . $urlDocx . '\'">
                    Cetak
                </button>
                <button class="btn btn-warning btn-sm"
                    onclick="modalAction(`' . url('/transaksi_sewa/' . $row->id_transaksi_sewa . '/edit') . '`)">
                    Edit
                </button>
                <button class="btn btn-danger btn-sm"
                    onclick="modalAction(`' . url('/transaksi_sewa/' . $row->id_transaksi_sewa . '/confirm') . '`)">
                    Hapus
                </button>
            ';
        })
        ->rawColumns(['aksi', 'status_badge'])
        ->make(true);
}
    // public function list(Request $request)
    // {
    //     $data = TransaksiSewaModel::with(['penyewa', 'kamar'])
    //         ->select(
    //             'id_transaksi_sewa',
    //             'id_penyewa',
    //             'id_kamar',
    //             'lama_sewa',
    //             'tanggal_sewa',
    //             'tanggal_batas_sewa',
    //             'status'
    //         );

    //     return DataTables::of($data)
    //         ->addIndexColumn()

    //         ->addColumn('penyewa_nama', fn($row) =>
    //             $row->penyewa->nama ?? '-'
    //         )

    //         ->addColumn('kamar_nama', fn($row) =>
    //             $row->kamar->no_kamar ?? '-'
    //         )

    //         ->editColumn('tanggal_sewa', function ($row) {
    //             return Carbon::parse($row->tanggal_sewa)->format('d-m-Y');
    //         })

    //         ->editColumn('tanggal_batas_sewa', function ($row) {
    //             return Carbon::parse($row->tanggal_batas_sewa)->format('d-m-Y');
    //         })
    //         ->addColumn('status_badge', function ($row) {
    //             $color = $row->status == 'aktif' ? 'success' : 'danger';

    //             return '<span class="badge badge-' . $color . '">' . strtoupper($row->status) . '</span>';
    //         })

    //         ->addColumn('aksi', function ($row) {
    //             return '
    //                 <button class="btn btn-info btn-sm"
    //                     onclick="modalAction(`' . url('/transaksi_sewa/' . $row->id_transaksi_sewa . '/show') . '`)">
    //                     Detail
    //                 </button>

    //                 <button class="btn btn-warning btn-sm"
    //                     onclick="modalAction(`' . url('/transaksi_sewa/' . $row->id_transaksi_sewa . '/edit') . '`)">
    //                     Edit
    //                 </button>

    //                 <button class="btn btn-danger btn-sm"
    //                     onclick="modalAction(`' . url('/transaksi_sewa/' . $row->id_transaksi_sewa . '/confirm') . '`)">
    //                     Hapus
    //                 </button>
    //             ';
    //         })

    //         ->rawColumns(['aksi', 'status_badge'])
    //         ->make(true);
    // }


    public function show($id)
    {
        $transaksi = TransaksiSewaModel::with(['penyewa', 'kamar'])->findOrFail($id);
        return view('transaksi_sewa.show', compact('transaksi'));
    }


    public function confirm($id)
    {
        $transaksi = TransaksiSewaModel::findOrFail($id);
        return view('transaksi_sewa.confirm', compact('transaksi'));
    }


    public function delete(Request $request, $id)
    {
        if ($request->ajax()) {
            $transaksi = TransaksiSewaModel::find($id);

            if (!$transaksi) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data transaksi tidak ditemukan'
                ]);
            }

            // kembalikan kamar ke tersedia
            KamarModel::where('id_kamar', $transaksi->id_kamar)
                ->update(['status' => 'tersedia']);

            $transaksi->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data transaksi berhasil dihapus'
            ]);
        }

        return redirect()->route('transaksi.index');
    }


    public function create()
    {
        $penyewa = PenyewaModel::all();
        $kamar   = KamarModel::where('status', 'tersedia')->get();

        return view('transaksi_sewa.create', compact('penyewa', 'kamar'));
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_penyewa'   => 'required|exists:t_penyewa,id_penyewa',
            'id_kamar'     => 'required|exists:t_kamar,id_kamar',
            'lama_sewa'    => 'required|integer|min:1',
            'tanggal_sewa' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ], 422);
        }
        $lama = (int) $request->lama_sewa;

        $tanggal_batas = Carbon::parse($request->tanggal_sewa)
                                ->addMonths($lama);


        TransaksiSewaModel::create([
            'id_penyewa'         => $request->id_penyewa,
            'id_kamar'           => $request->id_kamar,
            'lama_sewa'          => $request->lama_sewa,
            'tanggal_sewa'       => $request->tanggal_sewa,
            'tanggal_batas_sewa' => $tanggal_batas,
            'status'             => 'aktif',
        ]);

        // kamar jadi disewa
        KamarModel::where('id_kamar', $request->id_kamar)
            ->update(['status' => 'disewa']);

        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil disimpan'
        ]);
    }



    public function edit($id)
    {
        $transaksi = TransaksiSewaModel::find($id);
        $penyewa   = PenyewaModel::all();

        // tampilkan kamar tersedia + kamar yang sedang dipakai
        $kamar = KamarModel::where('status', 'tersedia')
            ->orWhere('id_kamar', $transaksi->id_kamar)
            ->get();

        return view('transaksi_sewa.edit', compact('transaksi', 'penyewa', 'kamar'));
    }

//     public function update(Request $request, $id)
// {
//     // Cari transaksi
//     $transaksi = TransaksiSewaModel::find($id);
    
//     if (!$transaksi) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Data tidak ditemukan'
//         ], 404);
//     }

//     // Validasi
//     $validator = Validator::make($request->all(), [
//         'id_penyewa' => 'required|exists:t_penyewa,id_penyewa',
//         'id_kamar' => 'required|exists:t_kamar,id_kamar',
//         'tanggal_sewa' => 'required|date',
//         'lama_sewa' => 'required|integer|min:1'
//     ], [
//         'id_penyewa.required' => 'Penyewa harus dipilih',
//         'id_penyewa.exists' => 'Penyewa tidak valid',
//         'id_kamar.required' => 'Kamar harus dipilih',
//         'id_kamar.exists' => 'Kamar tidak valid',
//         'tanggal_sewa.required' => 'Tanggal sewa harus diisi',
//         'tanggal_sewa.date' => 'Format tanggal tidak valid',
//         'lama_sewa.required' => 'Lama sewa harus diisi',
//         'lama_sewa.integer' => 'Lama sewa harus berupa angka',
//         'lama_sewa.min' => 'Lama sewa minimal 1 bulan'
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Validasi gagal',
//             'msgField' => $validator->errors()
//         ]);
//     }

//     // Validasi: Cek kamar tersedia (kecuali kamar sendiri)
//     if ($request->id_kamar != $transaksi->id_kamar) {
//         $kamar = KamarModel::find($request->id_kamar);
        
//         if ($kamar->status == 'tidak_tersedia') {
//             return response()->json([
//                 'status' => false,
//                 'message' => 'Kamar yang dipilih sedang tidak tersedia',
//                 'msgField' => ['id_kamar' => ['Kamar ini sedang disewa oleh penyewa lain']]
//             ]);
//         }
//     }

//     // Simpan data lama
//     $kamarLama = $transaksi->id_kamar;
//     $statusLama = $transaksi->status;

//     // Hitung tanggal batas sewa baru
//     $tanggalSewa = Carbon::parse($request->tanggal_sewa);
//     $tanggalBatasSewa = $tanggalSewa->copy()->addMonths($request->lama_sewa);

//     // ⭐ LOGIKA AUTO STATUS BERDASARKAN TANGGAL BATAS SEWA
//     $statusBaru = ($tanggalBatasSewa > now()) ? 'aktif' : 'tidak_aktif';

//     // Update transaksi
//     $transaksi->update([
//         'id_penyewa' => $request->id_penyewa,
//         'id_kamar' => $request->id_kamar,
//         'tanggal_sewa' => $tanggalSewa,
//         'lama_sewa' => $request->lama_sewa,
//         'tanggal_batas_sewa' => $tanggalBatasSewa,
//         'status' => $statusBaru  // 
//     ]);

//     // ⭐ LOGIKA UPDATE STATUS KAMAR
    
//     // 1. Jika kamar berubah
//     if ($kamarLama != $request->id_kamar) {
//         // Kamar lama: kembalikan ke tersedia (jika transaksi lama aktif)
//         if ($statusLama == 'aktif') {
//             KamarModel::where('id_kamar', $kamarLama)
//                 ->update(['status' => 'tersedia']);
//         }
        
//         // Kamar baru: set tidak tersedia (jika transaksi baru aktif)
//         if ($statusBaru == 'aktif') {
//             KamarModel::where('id_kamar', $request->id_kamar)
//                 ->update(['status' => 'tidak_tersedia']);
//         }
//     }
//     // 2. Jika kamar sama tapi status berubah karena perpanjangan
//     else {
//         if ($statusLama == 'tidak_aktif' && $statusBaru == 'aktif') {
//             // ⭐ PERPANJANGAN: Dari tidak aktif ke aktif
//             KamarModel::where('id_kamar', $request->id_kamar)
//                 ->update(['status' => 'tidak_tersedia']);
//         } 
//         elseif ($statusLama == 'aktif' && $statusBaru == 'tidak_aktif') {
//             // Dari aktif ke tidak aktif (jarang terjadi saat edit manual)
//             KamarModel::where('id_kamar', $request->id_kamar)
//                 ->update(['status' => 'tersedia']);
//         }
//     }

//     // Pesan sukses yang informatif
//     $message = 'Data berhasil diupdate';
//     if ($statusLama == 'tidak_aktif' && $statusBaru == 'aktif') {
//         $message = 'Transaksi berhasil diperpanjang dan diaktifkan kembali';
//     }

//     return response()->json([
//         'status' => true,
//         'message' => $message
//     ]);
// }


    // public function update(Request $request, $id)
    // {
    //     $transaksi = TransaksiSewaModel::find($id);

    //     if (!$transaksi) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Data transaksi tidak ditemukan'
    //         ]);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'id_penyewa'   => 'required|exists:t_penyewa,id_penyewa',
    //         'id_kamar'     => 'required|exists:t_kamar,id_kamar',
    //         'lama_sewa'    => 'required|integer|min:1',
    //         'tanggal_sewa' => 'required|date',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validasi gagal',
    //             'msgField' => $validator->errors()
    //         ], 422);
    //     }

    //     // Jika kamar berubah
    //     if ($transaksi->id_kamar != $request->id_kamar) {

    //         // kamar lama kembali tersedia
    //         KamarModel::where('id_kamar', $transaksi->id_kamar)
    //             ->update(['status' => 'tersedia']);

    //         // kamar baru jadi disewa
    //         KamarModel::where('id_kamar', $request->id_kamar)
    //             ->update(['status' => 'disewa']);
    //     }

    //     // hitung ulang tanggal batas sewa
    //     $tanggal_batas = Carbon::parse($request->tanggal_sewa)
    //                         ->addMonths($request->lama_sewa);

    //     $transaksi->update([
    //         'id_penyewa'         => $request->id_penyewa,
    //         'id_kamar'           => $request->id_kamar,
    //         'lama_sewa'          => $request->lama_sewa,
    //         'tanggal_sewa'       => $request->tanggal_sewa,
    //         'tanggal_batas_sewa' => $tanggal_batas,
    //         'status'             => $request->status ?? $transaksi->status,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Transaksi berhasil diperbarui'
    //     ]);
    // }


    public function update(Request $request, $id)
{
    $transaksi = TransaksiSewaModel::find($id);

    if (!$transaksi) {
        return response()->json([
            'status' => false,
            'message' => 'Data transaksi tidak ditemukan'
        ]);
    }

    $validator = Validator::make($request->all(), [
        'id_penyewa'   => 'required|exists:t_penyewa,id_penyewa',
        'id_kamar'     => 'required|exists:t_kamar,id_kamar',
        'lama_sewa'    => 'required|integer|min:1',
        'tanggal_sewa' => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal',
            'msgField' => $validator->errors()
        ], 422);
    }

    // Hitung ulang tanggal batas sewa
    $tanggal_sewa  = Carbon::parse($request->tanggal_sewa);
    $lamaSewa      = (int) $request->lama_sewa;
    $tanggal_batas = $tanggal_sewa->copy()->addMonths($lamaSewa);

    $now = now();
    if ($transaksi->status === 'tidak_aktif' && $tanggal_batas > $now) {
        $statusBaru = 'aktif';

        // kamar otomatis disewa kembali
        KamarModel::where('id_kamar', $request->id_kamar)
            ->update(['status' => 'disewa']);
    } else {
        // Normal rule
        $statusBaru = ($tanggal_batas < $now) ? 'tidak_aktif' : 'aktif';
    }

    // Jika kamar berubah
    if ($transaksi->id_kamar != $request->id_kamar) {

        // kamar lama kembali tersedia
        KamarModel::where('id_kamar', $transaksi->id_kamar)
            ->update(['status' => 'tersedia']);

        // kamar baru disewa
        KamarModel::where('id_kamar', $request->id_kamar)
            ->update(['status' => 'disewa']);
    }

    // Update transaksi
    $transaksi->update([
        'id_penyewa'         => $request->id_penyewa,
        'id_kamar'           => $request->id_kamar,
        'lama_sewa'          => $lamaSewa,
        'tanggal_sewa'       => $tanggal_sewa,
        'tanggal_batas_sewa' => $tanggal_batas,
        'status'             => $statusBaru,
    ]);


//   AUTO GENERATE TAGIHAN TAMBAHAN JIKA LAMA SEWA  DITAMBAH

    $jumlahPembayaran = TransaksiPembayaranModel::where('id_transaksi_sewa', $transaksi->id_transaksi_sewa)->count();

    if ($lamaSewa > $jumlahPembayaran) {

        // $selisih = $lamaSewa - $jumlahPembayaran;

        $hargaPerBulan = $transaksi->kamar->tipeKamar->harga_perbulan;
        $tanggalSewa = Carbon::parse($transaksi->tanggal_sewa);

        for ($i = $jumlahPembayaran + 1; $i <= $lamaSewa; $i++) {

            $tanggalJatuhTempo = $tanggalSewa->copy()->addMonths($i - 1);

            TransaksiPembayaranModel::create([
                'id_transaksi_sewa' => $transaksi->id_transaksi_sewa,
                'tanggal_bayar' => null,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                'uraian' => "Tagihan bulan ke-$i",
                'nominal' => $hargaPerBulan,
                'status' => 'tidak_bayar',
            ]);
        }
    }
    return response()->json([
        'status' => true,
        'message' => 'Transaksi berhasil diperbarui'
    ]);
}



public function generateDocx($id)
{
$transaksi = TransaksiSewaModel::with(['penyewa', 'kamar.tipekamar'])->find($id);


    if (!$transaksi) {
        return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
    }

    $penyewa = $transaksi->penyewa;
    $kamar   = $transaksi->kamar;
$tipekamar = $transaksi->kamar->tipekamar;
$harga = $tipekamar->harga_perbulan ?? 0;
    $phpWord = new PhpWord();

    // Set margin agar rapi
    $section = $phpWord->addSection([
        'marginLeft'   => 1200,
        'marginRight'  => 1200,
        'marginTop'    => 800,
        'marginBottom' => 800,
    ]);

    // Style paragraf utama (justify, spacing rapi)
    $paragraph = ['alignment' => 'both', 'spaceAfter' => 200];

    // JUDUL
    $section->addText(
        'PERJANJIAN SEWA KAMAR KOS',
        ['bold' => true, 'size' => 16],
        ['alignment' => 'center']
    );
    $section->addTextBreak(1);

    // BAGIAN HEAD
    $section->addText("Yang bertanda tangan di bawah ini:", ['size' => 12], $paragraph);

    $section->addText("PIHAK PERTAMA (Pemilik Kos)", ['bold' => true], $paragraph);
    $section->addText("Nama: MAS'ODI", [], $paragraph);
    $section->addText("Alamat: WONOREJO SELATAN V/9", [], $paragraph);
    $section->addTextBreak(1);

    $section->addText("PIHAK KEDUA (Penyewa Kos)", ['bold' => true], $paragraph);
    $section->addText("Nama: {$penyewa->nama}", [], $paragraph);
    // $section->addText("No. Identitas: " . ($penyewa->nik ?? '-'), [], $paragraph);
    $section->addText("Jenis Kelamin: " . ($penyewa->jenis_kelamin ?? '-'), [], $paragraph);
    $section->addText("Pekerjaan: " . ($penyewa->pekerjaan ?? '-'), [], $paragraph);
    $section->addText("Alamat: " . ($penyewa->alamat ?? '-'), [], $paragraph);
    $section->addTextBreak(1);

    // LIST KETENTUAN
    $section->addText(
        "Pihak Pertama setuju menyewakan kamar kos kepada Pihak Kedua dengan ketentuan berikut:",
        [],
        $paragraph
    );

    $listStyle = ['listType' => ListItem::TYPE_NUMBER];


    $section->addListItem("Nomor Kamar: {$kamar->no_kamar}", 0, [], $listStyle);
    $section->addListItem("Masa sewa: {$transaksi->lama_sewa} bulan (Mulai: {$transaksi->tanggal_sewa} s/d {$transaksi->tanggal_batas_sewa})", 0, [], $listStyle);
    $section->addListItem("Biaya Sewa: Rp " . number_format($harga, 0, ',', '.') . " /bulan", 0, [],$listStyle );
    $section->addListItem("Penyewa wajib menyerahkan fotokopi kartu identitas kepada pemilik kos.", 0, [], $listStyle);
    $section->addListItem("Penyewa kamar kos diminta secara menyeluruh mengetahui, memahami dan menaati peraturan dan tata tertib rumah kos yang telah ditetapkan olek pemilik. Serta wajib mematuhi peraturan perundang-undangan (termasuk namun tidak terbatas pada: larangan penyalahgunaan narkotika, minuman keras, kekerasan/kejahatan seksual, terorisme, dst.).", 0, [], $listStyle);
    $section->addListItem("Kehilangan/kerusakan barang-barang fasilitas kamar kos yang disebabkan oleh penyewa kamar kos baik secara sengaja ataupun tidak sengaja akan dikenakan denda atau penyewa diwajibkan untuk mengganti barang yang dirusakan.", 0, [], $listStyle);
    $section->addListItem("Barang pribadi sepenuhnya tanggung jawab penyewa.", 0, [], $listStyle);
    $section->addListItem("Tamu dilarang menginap tanpa izin pemilik kos.", 0, [], $listStyle);
    $section->addListItem("Para pihak telah memahami dan menyetujui perjanjian ini.", 0, [], $listStyle);

    $section->addTextBreak(1);

    // PENUTUP
    $section->addText(
        "Demikian perjanjian ini dibuat pada tanggal " . date('d-m-Y') . " tanpa paksaan dari pihak manapun.",
        [],
        $paragraph
    );

    // Jarak sedikit
    $section->addTextBreak(1);

    // Tabel tanda tangan
    $table = $section->addTable([
        'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        'cellMargin' => 80,
        'borderSize' => 0,
        'borderColor' => 'ffffff' 

    ]);

    // Row 1: Judul pihak
    $table->addRow();
    $table->addCell(3500)->addText("PIHAK PERTAMA", ['bold' => true], ['alignment' => 'center']);
    $table->addCell(3500)->addText("PIHAK KEDUA", ['bold' => true], ['alignment' => 'center']);

    // Row 2: Kosong untuk tanda tangan
    $table->addRow();
    $table->addCell(3500)->addText("\n\n\n\n\n", [], ['alignment' => 'center']);
    $table->addCell(3500)->addText("\n\n\n\n\n", [], ['alignment' => 'center']);

    // Row 3: Nama di bawah
    $table->addRow();
    $table->addCell(3500)->addText("(MAS'ODI)", [], ['alignment' => 'center']);
    $table->addCell(3500)->addText("({$penyewa->nama})", [], ['alignment' => 'center']);

    // SIMPAN
    $fileName = "Perjanjian_Sewa_{$penyewa->nama}.docx";
    $filePath = storage_path("app/public/{$fileName}");

    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}


}
