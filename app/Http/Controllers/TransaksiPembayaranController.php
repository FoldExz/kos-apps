<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\TransaksiSewaModel;
use App\Models\TransaksiPembayaranModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class TransaksiPembayaranController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Transaksi Pembayaran',
            'list' => ['Home', 'Transaksi Pembayaran']
        ];

        $page = (object)[
            'title' => 'Data Transaksi Pembayaran Sewa Kamar'
        ];

        $activeMenu = 'pembayaran';

        return view('transaksi_pembayaran.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
{
    $data = TransaksiPembayaranModel::with(['transaksiSewa.penyewa', 'transaksiSewa.kamar'])
        ->select(
            'id_transaksi_pembayaran',
            'id_transaksi_sewa',
            'tanggal_bayar',
            'uraian',
            'nominal',
            'status'
        );

    return DataTables::of($data)
        ->addIndexColumn()

        ->addColumn('penyewa_nama', function ($row) {
            return $row->transaksiSewa->penyewa->nama ?? '-';
        })

        ->addColumn('kamar_no', function ($row) {
            return $row->transaksiSewa->kamar->no_kamar ?? '-';
        })

        ->editColumn('tanggal_bayar', function ($row) {
            return $row->tanggal_bayar
                ? Carbon::parse($row->tanggal_bayar)->format('d-m-Y H:i')
                : '-';
        })

        ->editColumn('nominal', fn($row) => 'Rp ' . number_format($row->nominal, 0, ',', '.'))

        ->addColumn('status_badge', function ($row) {
            $badges = [
                'bayar' => 'success',
                'tidak_bayar' => 'danger'
            ];
            $color = $badges[$row->status] ?? 'secondary';
            $text = str_replace('_', ' ', strtoupper($row->status));
            return '<span class="badge badge-' . $color . '">' . $text . '</span>';
        })

        // ->addColumn('aksi', function ($row) {
        //     $btnEdit = $row->status !== 'bayar'
        //         ? '<button class="btn btn-warning btn-sm" onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/edit') . '`)">Bayar</button>'
        //         : '';

        //     return '
        //         <button class="btn btn-info btn-sm" onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/show') . '`)">Detail</button>
        //         ' . $btnEdit . '
        //         <button class="btn btn-danger btn-sm" onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/confirm') . '`)">Hapus</button>
        //         <button class="btn btn-danger btn-sm" onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/confirm') . '`)">Edit</button>
        //     '
        //     ;
        // })

        // ->addColumn('aksi', function ($row) {
        //     $btnEdit = $row->status !== 'bayar'
        //         ? '<button class="btn btn-warning btn-sm" onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/edit') . '`)">Bayar</button>'
        //         : '';

        //     return '
        //         <button class="btn btn-info btn-sm" onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/show') . '`)">Detail</button>
        //         ' . $btnEdit . '

        //     '
        //     ;
        // })
        ->addColumn('aksi', function ($row) {

            $btnBayar = $row->status !== 'bayar'
                ? '<button class="btn btn-warning btn-sm"
                    onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/edit') . '`)">
                    Bayar
                </button>'
                : '';

            $btnCetak = $row->status === 'bayar'
                ? '<button class="btn btn-info btn-sm"
                    onclick="window.location.href=\'' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/generatePembayaran') . '\'">
                    Cetak
                </button>'
                : '';

            return $btnBayar . ' ' . $btnCetak;
        })
        ->rawColumns(['aksi', 'status_badge'])
        ->make(true);
}

    // public function list(Request $request)
    // {
    //     $data = TransaksiPembayaranModel::with(['sewa.penyewa', 'sewa.kamar'])
    //         ->select(
    //             'id_transaksi_pembayaran',
    //             'id_transaksi_sewa',
    //             'tanggal_bayar',
    //             'uraian',
    //             'nominal',
    //             'status'
    //         );

    //     return DataTables::of($data)
    //         ->addIndexColumn()
    //         ->addColumn('penyewa_nama', fn($row) => $row->transaksiSewa->penyewa->nama ?? '-')
    //         ->addColumn('kamar_no', fn($row) => $row->transaksiSewa->kamar->no_kamar ?? '-')
    //         ->editColumn('tanggal_bayar', function ($row) {
    //             return $row->tanggal_bayar 
    //                 ? Carbon::parse($row->tanggal_bayar)->format('d-m-Y H:i') 
    //                 : '-';
    //         })
    //         ->editColumn('nominal', fn($row) => 'Rp ' . number_format($row->nominal, 0, ',', '.'))
    //         ->addColumn('status_badge', function ($row) {
    //             $badges = [
    //                 'bayar' => 'success',
    //                 'belum_bayar' => 'warning',
    //                 'terlambat' => 'danger'
    //             ];
    //             $color = $badges[$row->status] ?? 'secondary';
    //             $text = str_replace('_', ' ', strtoupper($row->status));
    //             return '<span class="badge badge-' . $color . '">' . $text . '</span>';
    //         })
    //         ->addColumn('aksi', function ($row) {
    //             $btnEdit = $row->status !== 'bayar' 
    //                 ? '<button class="btn btn-warning btn-sm" onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/edit') . '`)">Bayar</button>' 
    //                 : '';

    //             return '
    //                 <button class="btn btn-info btn-sm" onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/show') . '`)">Detail</button>
    //                 ' . $btnEdit . '
    //                 <button class="btn btn-danger btn-sm" onclick="modalAction(`' . url('/transaksi_pembayaran/' . $row->id_transaksi_pembayaran . '/confirm') . '`)">Hapus</button>
    //             ';
    //         })
    //         ->rawColumns(['aksi', 'status_badge'])
    //         ->make(true);
    // }

    public function show($id)
    {
        $pembayaran = TransaksiPembayaranModel::with(['transaksiSewa.penyewa', 'transaksiSewa.kamar'])
            ->findOrFail($id);

        return view('transaksi_pembayaran.show', compact('pembayaran'));
    }

    public function create()
    {
        // Ambil hanya transaksi sewa yang aktif
        $transaksiSewa = TransaksiSewaModel::with(['penyewa', 'kamar'])
            ->where('status', 'aktif')
            ->get();

        return view('transaksi_pembayaran.create', compact('transaksiSewa'));
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id_transaksi_sewa' => 'required|exists:t_transaksi_sewa,id_transaksi_sewa',
    //         'nominal' => 'required|numeric|min:0',
    //     ], [
    //         'id_transaksi_sewa.required' => 'Transaksi sewa harus dipilih',
    //         'id_transaksi_sewa.exists' => 'Transaksi sewa tidak valid',
    //         'nominal.required' => 'Nominal harus diisi',
    //         'nominal.numeric' => 'Nominal harus berupa angka',
    //         'nominal.min' => 'Nominal minimal 0',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validasi gagal',
    //             'msgField' => $validator->errors()
    //         ], 422);
    //     }

    //     $sewa = TransaksiSewaModel::find($request->id_transaksi_sewa);

    //     // Cek status transaksi
    //     if ($sewa->status !== 'aktif') {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Transaksi sewa sudah tidak aktif!'
    //         ], 400);
    //     }

    //     $hargaPerBulan = $request->nominal;
    //     $lamaSewa = $sewa->lama_sewa;

    //     // Hitung jumlah pembayaran yang sudah ada
    //     $jumlahPembayaran = TransaksiPembayaranModel::where('id_transaksi_sewa', $sewa->id_transaksi_sewa)->count();

    //     // Jika ini pembayaran pertama, generate semua tagihan sekaligus
    //     if ($jumlahPembayaran == 0) {
    //         // Insert pembayaran pertama (sudah bayar)
    //         TransaksiPembayaranModel::create([
    //             'id_transaksi_sewa' => $sewa->id_transaksi_sewa,
    //             'tanggal_bayar' => now(),
    //             'uraian' => "Pembayaran bulan ke-1",
    //             'nominal' => $hargaPerBulan,
    //             'status' => 'bayar',
    //         ]);

    //         // Generate tagihan untuk bulan berikutnya (belum bayar)
    //         for ($bulan = 2; $bulan <= $lamaSewa; $bulan++) {
    //             TransaksiPembayaranModel::create([
    //                 'id_transaksi_sewa' => $sewa->id_transaksi_sewa,
    //                 'tanggal_bayar' => null,
    //                 'uraian' => "Tagihan bulan ke-$bulan",
    //                 'nominal' => $hargaPerBulan,
    //                 'status' => 'belum_bayar',
    //             ]);
    //         }

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Pembayaran pertama berhasil dan tagihan otomatis dibuat untuk ' . ($lamaSewa - 1) . ' bulan berikutnya'
    //         ]);
    //     }

    //     // Jika bukan pembayaran pertama, tidak perlu generate lagi
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Tagihan untuk transaksi ini sudah di-generate. Gunakan menu Edit/Bayar untuk melunasi tagihan.'
    //     ], 400);
    // }


// public function store(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'id_transaksi_sewa' => 'required|exists:t_transaksi_sewa,id_transaksi_sewa',
//         'nominal' => 'required|numeric|min:1',
//         'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => false,
//             'msgField' => $validator->errors(),
//             'message' => 'Validasi gagal'
//         ], 422);
//     }

//     // Ambil transaksi sewa + relasi kamar + tipe kamar
//     $sewa = TransaksiSewaModel::with(['kamar.tipeKamar'])->find($request->id_transaksi_sewa);
//     $hargaPerBulan = $sewa->kamar->tipeKamar->harga_perbulan;

//     // Jika pembayaran < harga 1 bulan → error
//     if ($request->nominal < $hargaPerBulan) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Nominal kurang dari biaya 1 bulan.'
//         ]);
//     }

//     // Upload file bukti bayar
//     $filePath = null;
//     if ($request->hasFile('bukti_bayar')) {
//         $file = $request->file('bukti_bayar');
//         $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
//         $file->storeAs('public/pembayaran', $filename);
//         $filePath = 'pembayaran/' . $filename;
//     }

//     // Cek jumlah pembayaran sudah ada berapa
//     $jumlahPembayaran = TransaksiPembayaranModel::where('id_transaksi_sewa', $sewa->id_transaksi_sewa)->count();

//     // ---------------------------------------------------
//     // 1️⃣ PEMBAYARAN PERTAMA → GENERATE SEMUA SISANYA
//     // ---------------------------------------------------
//     if ($jumlahPembayaran == 0) {

//         // Insert pembayaran pertama
//         TransaksiPembayaranModel::create([
//             'id_transaksi_sewa' => $sewa->id_transaksi_sewa,
//             'tanggal_bayar' => now(),
//             'uraian' => 'Pembayaran bulan ke-1',
//             'nominal' => $hargaPerBulan,
//             'bukti_bayar' => $filePath,
//             'status' => 'bayar',
//         ]);

//         // Generate sisa bulan
//         for ($i = 2; $i <= $sewa->lama_sewa; $i++) {
//             TransaksiPembayaranModel::create([
//                 'id_transaksi_sewa' => $sewa->id_transaksi_sewa,
//                 'tanggal_bayar' => null,
//                 'uraian' => "Tagihan bulan ke-$i",
//                 'nominal' => $hargaPerBulan,
//                 'bukti_bayar' => null,
//                 'status' => 'belum_bayar',
//             ]);
//         }

//         return response()->json([
//             'status' => true,
//             'message' => 'Pembayaran pertama berhasil! Tagihan bulan berikutnya telah dibuat otomatis.'
//         ]);
//     }

//     // ---------------------------------------------------
//     // 2️⃣ PEMBAYARAN SELANJUTNYA → tidak auto generate
//     // ---------------------------------------------------
//     return response()->json([
//         'status' => false,
//         'message' => 'Pembayaran sudah pernah dibuat. Gunakan menu Bayar untuk melunasi tagihan berikutnya.'
//     ]);
// }

public function edit($id)
{
    $pembayaran = TransaksiPembayaranModel::with(['transaksiSewa.penyewa', 'transaksiSewa.kamar'])
        ->findOrFail($id);

    if ($pembayaran->status === 'bayar') {
        return back()->with('error', 'Pembayaran ini sudah lunas!');
    }

    return view('transaksi_pembayaran.edit', compact('pembayaran'));
}


public function update(Request $request, $id)
{
    $pembayaran = TransaksiPembayaranModel::find($id);
    $sewa = TransaksiSewaModel::with(['kamar.tipeKamar'])->find($pembayaran->id_transaksi_sewa);
    $hargaPerBulan = $sewa->kamar->tipeKamar->harga_perbulan;

    if (!$pembayaran) {
        return response()->json([
            'status' => false,
            'message' => 'Data pembayaran tidak ditemukan'
        ], 404);
    }

    // Jika sudah lunas, tidak bisa diedit
    if ($pembayaran->status === 'bayar') {
        return response()->json([
            'status' => false,
            'message' => 'Pembayaran ini sudah lunas!'
        ], 400);
    }

    // Ambil transaksi sewa
    $sewa = TransaksiSewaModel::with(['kamar.tipeKamar'])
        ->find($pembayaran->id_transaksi_sewa);

    if (!$sewa) {
        return response()->json([
            'status' => false,
            'message' => 'Transaksi sewa tidak ditemukan'
        ], 404);
    }

    // Sewa harus aktif
    if ($sewa->status !== 'aktif') {
        return response()->json([
            'status' => false,
            'message' => 'Transaksi sewa sudah tidak aktif!'
        ], 400);
    }

    // Validasi input
    $validator = Validator::make($request->all(), [
        'nominal' => 'required|numeric|min:1',
        'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);
    // Nominal harus tepat harga per bulan
    if ($request->nominal != $hargaPerBulan) {
        return response()->json([
            'status' => false,
            'message' => 'Nominal harus tepat Rp ' . number_format($hargaPerBulan, 0, ',', '.')
        ], 400);
    }

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal',
            'msgField' => $validator->errors()
        ], 422);
    }

    $hargaPerBulan = $sewa->kamar->tipeKamar->harga_perbulan;

    // Nominal harus minimal 1 bulan
    if ($request->nominal < $hargaPerBulan) {
        return response()->json([
            'status' => false,
            'message' => 'Nominal kurang dari biaya 1 bulan (Rp ' . number_format($hargaPerBulan, 0, ',', '.') . ')'
        ], 400);
    }

    // Upload bukti bayar bila ada
    $filePath = $pembayaran->bukti_bayar;
    if ($request->hasFile('bukti_bayar')) {
        $file = $request->file('bukti_bayar');
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/pembayaran', $filename);
        $filePath = 'pembayaran/' . $filename;
    }

    // Update pembayaran → jadi lunas
    $pembayaran->update([
        'tanggal_bayar' => now(),
        'nominal' => $request->nominal,
        'bukti_bayar' => $filePath,
        'status' => 'bayar',
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Tagihan berhasil dibayar'
    ]);
}

    public function confirm($id)
    {
        $pembayaran = TransaksiPembayaranModel::findOrFail($id);
        return view('transaksi_pembayaran.confirm', compact('pembayaran'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax()) {
            $pembayaran = TransaksiPembayaranModel::find($id);

            if (!$pembayaran) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data pembayaran tidak ditemukan'
                ]);
            }

            // Cek apakah pembayaran sudah lunas
            if ($pembayaran->status === 'bayar') {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak dapat menghapus pembayaran yang sudah lunas!'
                ], 400);
            }

            $pembayaran->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data pembayaran berhasil dihapus'
            ]);
        }

        return redirect()->route('transaksi_pembayaran.index');
    }

//     public function store(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'id_transaksi_sewa' => 'required|exists:t_transaksi_sewa,id_transaksi_sewa',
//         'nominal' => 'required|numeric|min:1',
//         'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => false,
//             'msgField' => $validator->errors(),
//             'message' => 'Validasi gagal'
//         ], 422);
//     }

//     // 1. Ambil data sewa dengan relasi kamar + tipe kamar
//     $sewa = TransaksiSewaModel::with(['kamar.tipeKamar'])
//         ->find($request->id_transaksi_sewa);

//     if (!$sewa) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Data transaksi sewa tidak ditemukan'
//         ], 404);
//     }

//     // 2. Cek apakah status sewa aktif
//     if ($sewa->status !== 'aktif') {
//         return response()->json([
//             'status' => false,
//             'message' => 'Transaksi sewa tidak dalam status aktif'
//         ], 400);
//     }

//     $hargaPerBulan = $sewa->kamar->tipeKamar->harga_perbulan;

//     // Validasi nominal minimum
//     if ($request->nominal < $hargaPerBulan) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Nominal pembayaran kurang dari biaya 1 bulan (Rp ' . number_format($hargaPerBulan, 0, ',', '.') . ')'
//         ], 400);
//     }

//     // Upload file bukti bayar
//     $filePath = null;
//     if ($request->hasFile('bukti_bayar')) {
//         $file = $request->file('bukti_bayar');
//         $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
//         $file->storeAs('public/pembayaran', $filename);
//         $filePath = 'pembayaran/' . $filename;
//     }

//     // 3 & 4. Ambil tanggal sewa dan tanggal batas sewa
//     $tanggalSewa = Carbon::parse($sewa->tanggal_sewa);
//     $tanggalBatasSewa = Carbon::parse($sewa->tanggal_batas_sewa);

//     // 5. Hitung rentang bulan dari tanggal sewa sampai batas sewa
//     $rentangBulan = $tanggalSewa->diffInMonths($tanggalBatasSewa) + 1;

//     // Cek jumlah pembayaran yang sudah ada
//     $jumlahPembayaran = TransaksiPembayaranModel::where('id_transaksi_sewa', $sewa->id_transaksi_sewa)->count();

//     // ---------------------------------------------------
//     // PEMBAYARAN PERTAMA
//     // ---------------------------------------------------
//     if ($jumlahPembayaran == 0) {
        
//         $tanggalBayar = now();
        
//         // 7. Cek apakah tanggal bayar masuk dalam rentang sewa
//         if ($tanggalBayar->lt($tanggalSewa) || $tanggalBayar->gt($tanggalBatasSewa)) {
//             return response()->json([
//                 'status' => false,
//                 'message' => 'Tanggal pembayaran tidak dalam rentang periode sewa'
//             ], 400);
//         }

//         // 8. Hitung berapa bulan yang dibayar berdasarkan nominal
//         $bulanDibayar = floor($request->nominal / $hargaPerBulan);
        
//         // Pastikan tidak melebihi total lama sewa
//         if ($bulanDibayar > $rentangBulan) {
//             $bulanDibayar = $rentangBulan;
//         }

//         DB::beginTransaction();
//         try {
//             // 6. Insert pembayaran untuk bulan yang dibayar
//             for ($i = 1; $i <= $bulanDibayar; $i++) {
//                 $tanggalJatuhTempo = $tanggalSewa->copy()->addMonths($i - 1);
                
//                 TransaksiPembayaranModel::create([
//                     'id_transaksi_sewa' => $sewa->id_transaksi_sewa,
//                     'tanggal_bayar' => $i == 1 ? $tanggalBayar : null,
//                     'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
//                     'uraian' => "Pembayaran bulan ke-$i",
//                     'nominal' => $hargaPerBulan,
//                     'bukti_bayar' => $i == 1 ? $filePath : null,
//                     'status' => $i == 1 ? 'bayar' : 'tidak_bayar',
//                 ]);
//             }

//             // 9. Auto generate tagihan untuk bulan selanjutnya (jika ada sisa)
//             if ($bulanDibayar < $rentangBulan) {
//                 for ($i = $bulanDibayar + 1; $i <= $rentangBulan; $i++) {
//                     $tanggalJatuhTempo = $tanggalSewa->copy()->addMonths($i - 1);
                    
//                     TransaksiPembayaranModel::create([
//                         'id_transaksi_sewa' => $sewa->id_transaksi_sewa,
//                         'tanggal_bayar' => null,
//                         'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
//                         'uraian' => "Tagihan bulan ke-$i",
//                         'nominal' => $hargaPerBulan,
//                         'bukti_bayar' => null,
//                         'status' => 'tidak_bayar',
//                     ]);
//                 }
//             }

//             DB::commit();

//             $message = "Pembayaran berhasil untuk $bulanDibayar bulan.";
//             if ($bulanDibayar < $rentangBulan) {
//                 $sisaBulan = $rentangBulan - $bulanDibayar;
//                 $message .= " Tagihan untuk $sisaBulan bulan berikutnya telah dibuat otomatis.";
//             } else {
//                 $message .= " Semua tagihan telah lunas.";
//             }

//             return response()->json([
//                 'status' => true,
//                 'message' => $message,
//                 'data' => [
//                     'bulan_dibayar' => $bulanDibayar,
//                     'total_bulan' => $rentangBulan,
//                     'sisa_tagihan' => $rentangBulan - $bulanDibayar
//                 ]
//             ]);

//         } catch (\Exception $e) {
//             DB::rollBack();
//             return response()->json([
//                 'status' => false,
//                 'message' => 'Gagal menyimpan pembayaran: ' . $e->getMessage()
//             ], 500);
//         }
//     }
//     return response()->json([
//         'status' => false,
//         'message' => 'Pembayaran sudah pernah dibuat. Gunakan menu Bayar untuk melunasi tagihan berikutnya.'
//     ]);
// }

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id_transaksi_sewa' => 'required|exists:t_transaksi_sewa,id_transaksi_sewa',
        'nominal' => 'required|numeric|min:1',
        'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'msgField' => $validator->errors(),
            'message' => 'Validasi gagal'
        ], 422);
    }

    $sewa = TransaksiSewaModel::with(['kamar.tipekamar'])->find($request->id_transaksi_sewa);

    if (!$sewa) {
        return response()->json(['status' => false, 'message' => 'Data sewa tidak ditemukan'], 404);
    }

    if ($sewa->status !== 'aktif') {
        return response()->json(['status' => false, 'message' => 'Transaksi sewa tidak aktif'], 400);
    }

    $hargaPerBulan = $sewa->kamar->tipekamar->harga_perbulan ?? 0;

    if ($request->nominal < $hargaPerBulan) {
        return response()->json([
            'status' => false,
            'message' => 'Nominal minimal adalah Rp ' . number_format($hargaPerBulan, 0, ',', '.')
        ], 400);
    }

    // Upload file
    $filePath = null;
    if ($request->hasFile('bukti_bayar')) {
        $file = $request->file('bukti_bayar');
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/pembayaran', $filename);
        $filePath = 'pembayaran/' . $filename;
    }

    $tanggalBayar = now();
    $tanggalBatasSewa = Carbon::parse($sewa->tanggal_batas_sewa)->endOfDay();

    if ($tanggalBayar->gt($tanggalBatasSewa)) {
        return response()->json(['status' => false, 'message' => 'Masa sewa sudah berakhir'], 400);
    }

    // Hitung dia bayar buat berapa bulan (misal bayar 2 juta untuk kamar 1 juta, berarti 2 bulan)
    $bulanDibayar = floor($request->nominal / $hargaPerBulan);

    // Langsung insert 1 baris aja sesuai pembayaran saat ini
    try {
        TransaksiPembayaranModel::create([
            'id_transaksi_sewa' => $sewa->id_transaksi_sewa,
            'tanggal_bayar' => $tanggalBayar,
            'uraian' => "Pembayaran sewa $bulanDibayar bulan",
            'nominal' => $request->nominal,
            'bukti_bayar' => $filePath,
            'status' => 'bayar',
        ]);

        return response()->json(['status' => true, 'message' => 'Pembayaran berhasil disimpan!']);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
    }
}
// public function generateDocx($id)
// {
//     $pembayaran = TransaksiPembayaranModel::with([
//         'transaksiSewa.penyewa',
//         'transaksiSewa.kamar.tipekamar'
//     ])->find($id);

//     if (!$pembayaran) {
//         return back()->with('error', 'Data pembayaran tidak ditemukan.');
//     }

//     $sewa    = $pembayaran->transaksiSewa;
//     $penyewa = $sewa->penyewa;
//     $kamar   = $sewa->kamar;
//     $tipe    = $sewa->kamar->tipekamar;
 

//     $phpWord = new PhpWord();
//     $section = $phpWord->addSection([
//         'marginLeft' => 1000,
//         'marginRight' => 1000,
//         'marginTop' => 800,
//         'marginBottom' => 800,
//     ]);

//     // Judul
//     $section->addText(
//         'BUKTI PEMBAYARAN',
//         ['bold' => true, 'size' => 16],
//         ['alignment' => 'center']
//     );
//     $section->addTextBreak(1);

//     // Tanggal pembayaran
//     $section->addText("Tanggal Pembayaran: " . Carbon::parse($pembayaran->tanggal_bayar)->format('d-m-Y'));
//     $section->addTextBreak(1);

//     $section->addText("DATA PENYEWA", ['bold' => true, 'size' => 12]);
//     $table = $section->addTable();

//     $table->addRow();
//     $table->addCell(3000)->addText("Nama Penyewa");
//     $table->addCell(8000)->addText(": " . ($penyewa->nama ?? '-'));

//     $table->addRow();
//     $table->addCell(3000)->addText("Nomor HP");
//     $table->addCell(8000)->addText(": " . ($penyewa->no_hp ?? '-'));

//     $table->addRow();
//     $table->addCell(3000)->addText("Kamar");
//     $table->addCell(8000)->addText(": " . ($kamar->no_kamar ?? '-'));

//     $table->addRow();
//     $table->addCell(3000)->addText("Tipe Kamar");
//     $table->addCell(8000)->addText(": " . ($tipe->jenis_tipe_kamar ?? '-'));

//     $section->addTextBreak(1);


//     $section->addText("DETAIL PEMBAYARAN", ['bold' => true, 'size' => 12]);
//     $table2 = $section->addTable();

//     $table2->addRow();
//     $table2->addCell(3000)->addText("Nominal Dibayar");
//     $table2->addCell(8000)->addText(": Rp " . number_format($pembayaran->nominal, 0, ',', '.'));

//     $table2->addRow();
//     $table2->addCell(3000)->addText("Status");
//     $table2->addCell(8000)->addText(": " . strtoupper($pembayaran->status));

//     $table2->addRow();
//     $table2->addCell(3000)->addText("Uraian");
//     $table2->addCell(8000)->addText(": " . ($pembayaran->uraian ?? '-'));

//     $section->addTextBreak(1);

//     //ringkasan
//     $section->addText("RINGKASAN", ['bold' => true, 'size' => 12]);

//     $section->addText(
//         "Telah diterima pembayaran sewa kamar sebesar Rp " .
//         number_format($pembayaran->nominal, 0, ',', '.')
//     );

//     $section->addText("Atas nama : " . ($penyewa->nama ?? '-'));
//     $section->addText("Untuk kamar : " . ($kamar->no_kamar ?? '-'));
//     $section->addTextBreak(3);

//     $section->addText("Pengelola Kos,", []);
//     $section->addTextBreak(4);
//     $section->addText("__________________________", []);
//     $section->addText("Tanda Tangan", []);

//     // File name
//     $filename = 'Bukti_Pembayaran_' . $pembayaran->id_transaksi_pembayaran . '.docx';
//     $path = storage_path('app/public/' . $filename);

//     $phpWord->save($path, 'Word2007');

//     return response()->download($path)->deleteFileAfterSend(true);
// }

public function generatePembayaran($id)
{
    $pembayaran = TransaksiPembayaranModel::with([
        'transaksiSewa.penyewa',
        'transaksiSewa.kamar.tipekamar'
    ])->find($id);

    if (!$pembayaran) {
        return back()->with('error', 'Data pembayaran tidak ditemukan.');
    }

    $sewa    = $pembayaran->transaksiSewa;
    $penyewa = $sewa->penyewa;
    $kamar   = $sewa->kamar;
    $tipe    = $kamar->tipekamar;

    $phpWord = new PhpWord();
    $section = $phpWord->addSection([
        'marginLeft' => 1000,
        'marginRight' => 1000,
        'marginTop' => 800,
        'marginBottom' => 800,
    ]);

    // Judul
    $section->addText(
        'BUKTI PEMBAYARAN',
        ['bold' => true, 'size' => 16],
        ['alignment' => 'center']
    );
    $section->addTextBreak(1);

    // Tanggal pembayaran
    $section->addText("Tanggal Pembayaran: " . Carbon::parse($pembayaran->tanggal_bayar)->format('d-m-Y'));
    $section->addTextBreak(1);

    // Data penyewa
    $section->addText("DATA PENYEWA", ['bold' => true, 'size' => 12]);
    $table = $section->addTable();

    $table->addRow();
    $table->addCell(3000)->addText("Nama Penyewa");
    $table->addCell(8000)->addText(": " . ($penyewa->nama ?? '-'));

    $table->addRow();
    $table->addCell(3000)->addText("Nomor HP");
    $table->addCell(8000)->addText(": " . ($penyewa->no_hp ?? '-'));

    $table->addRow();
    $table->addCell(3000)->addText("Kamar");
    $table->addCell(8000)->addText(": " . ($kamar->no_kamar ?? '-'));

    $table->addRow();
    $table->addCell(3000)->addText("Tipe Kamar");
    $table->addCell(8000)->addText(": " . ($tipe->jenis_tipe_kamar ?? '-'));

    $section->addTextBreak(1);

    //detail pembayaran
    $section->addText("DETAIL PEMBAYARAN", ['bold' => true, 'size' => 12]);
    $table2 = $section->addTable();

    $table2->addRow();
    $table2->addCell(3000)->addText("Nominal Dibayar");
    $table2->addCell(8000)->addText(": Rp " . number_format($pembayaran->nominal, 0, ',', '.'));

    $table2->addRow();
    $table2->addCell(3000)->addText("Status");
    $table2->addCell(8000)->addText(": " . strtoupper($pembayaran->status));

    $table2->addRow();
    $table2->addCell(3000)->addText("Uraian");
    $table2->addCell(8000)->addText(": " . ($pembayaran->uraian ?? '-'));

    $section->addTextBreak(1);

    //ringkasan
    $section->addText("RINGKASAN", ['bold' => true, 'size' => 12]);

    $section->addText(
        "Telah diterima pembayaran sewa kamar sebesar Rp " .
        number_format($pembayaran->nominal, 0, ',', '.')
    );

    $section->addText("Atas nama : " . ($penyewa->nama ?? '-'));
    $section->addText("Untuk kamar : " . ($kamar->no_kamar ?? '-'));
    $section->addTextBreak(3);

    $section->addText("Pengelola Kos,", []);
    $section->addTextBreak(4);

    $section->addText("__________________________", []);
    $section->addText("Tanda Tangan", []);


    // Nama file mengikuti pola perjanjian (rapi)
    $nameClean = str_replace(' ', '_', $penyewa->nama);
    $fileName = "Bukti_Pembayaran_{$nameClean}_{$pembayaran->id_transaksi_pembayaran}.docx";

    $filePath = storage_path("app/public/{$fileName}");

    // Save using same format as perjanjian sewa
    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);

    
}

}