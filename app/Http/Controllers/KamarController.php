<?php

namespace App\Http\Controllers;

use App\Models\KamarModel;
use App\Models\TipeKamarModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KamarController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Kamar',
            'list'  => ['Home', 'Kamar']
        ];

        $page = (object) [
            'title' => 'Data Kamar Kos'
        ];

        $activeMenu = 'kamar';

        return view('kamar.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $data = KamarModel::select(
                't_kamar.id_kamar',
                't_kamar.no_kamar',
                't_kamar.status',
                't_kamar.id_tipe_kamar',
                't_tipe_kamar.jenis_tipe_kamar as tipe_kamar'
            )
            ->join('t_tipe_kamar', 't_tipe_kamar.id_tipe_kamar', '=', 't_kamar.id_tipe_kamar');


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($row) {
                if ($row->status == 'tersedia') {
                    return '<span class="badge badge-success">Tersedia</span>';
                } else {
                    return '<span class="badge badge-danger">Disewa</span>';  
                }
            })
            ->addColumn('aksi', function ($row) {
                // $btn  = '<button onclick="modalAction(\'' . url('/kamar/' . $row->id_kamar . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn = '<button onclick="modalAction(\'' . url('/kamar/' . $row->id_kamar . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kamar/' . $row->id_kamar . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status_badge'])
            ->toJson();
    }

    public function create()
    {
        $tipe_kamar = TipeKamarModel::all(); // untuk dropdown tipe kamar

        return view('kamar.create', compact('tipe_kamar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tipe_kamar' => 'required|exists:t_tipe_kamar,id_tipe_kamar',
            'no_kamar' => 'required|string|max:2|min:1|unique:t_kamar,no_kamar',
            'status'        => 'required|in:tersedia,disewa',
        ]);

        KamarModel::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Data kamar berhasil disimpan.'
        ]);
    }

    public function show($id)
    {
        $kamar = KamarModel::join('t_tipe_kamar', 't_tipe_kamar.id_tipe_kamar', '=', 't_kamar.id_tipe_kamar')
            ->select('t_kamar.*', 't_tipe_kamar.jenis_tipe_kamar')
            ->where('id_kamar', $id)
            ->first();

        if (!$kamar) {
            abort(404);
        }

        return view('kamar.show', compact('kamar'));
    }

    public function edit($id)
    {
        $kamar = KamarModel::find($id);
        $tipe = TipeKamarModel::all();

        if (!$kamar) {
            abort(404);
        }

        return view('kamar.edit', compact('kamar', 'tipe'));
    }

    public function update(Request $request, $id)
    {
        $kamar = KamarModel::find($id);

        if (!$kamar) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        $validated = $request->validate([
            'id_tipe_kamar' => 'required|exists:t_tipe_kamar,id_tipe_kamar',
            'no_kamar' => 'required|string|max:2|unique:t_kamar,no_kamar,' . $id . ',id_kamar',
            'status'        => 'required|in:tersedia,disewa',
        ]);

        $kamar->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Data kamar berhasil diperbarui.'
        ]);
    }

    public function confirm($id)
    {
        $kamar = KamarModel::find($id);

        if (!$kamar) {
            abort(404);
        }

        return view('kamar.confirm', compact('kamar'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax()) {
            $kamar = KamarModel::find($id);

            if (!$kamar) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan.'
                ]);
            }

            $kamar->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        }

        return redirect()->route('kamar.index');
    }
}