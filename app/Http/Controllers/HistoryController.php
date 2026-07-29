<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    // Fungsi untuk menampilkan halaman Dashboard beserta data riwayatnya
    public function index()
    {
        // Mengambil semua riwayat milik user yang sedang login, diurutkan dari yang terbaru
        $histories = History::where('user_id', Auth::id())->latest()->get();
        
        return view('dashboard.index', compact('histories'));
    }
public function store(Request $request) {
        $request->validate([ 'action_type' => 'required|in:encode,decode']);

        $filePath = null;
        // Jika ada pengiriman data gambar (khusus saat Encode)
        if ($request->has('image_base64') && $request->image_base64) {
            // Pisahkan format base64 dengan data aslinya
            $image_parts = explode(";base64,", $request->image_base64);
            $image_base64 = base64_decode($image_parts[1]);
        
            // Buat nama file unik dan simpan ke storage/app/public/encodes
            $fileName = uniqid('stegano_') . '.png';
            $path = 'encodes/' . $fileName;
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, $image_base64);
            
            $filePath = '/storage/' . $path; // URL untuk diakses browser
        }

        History::create([
            'user_id' => Auth::id(),
            'action_type' => $request->action_type,
            'file_name' => $request->file_name,
            'file_path' => $filePath,
            'message_length' => $request->message_length,
            'has_password' => $request->has_password,
            'xor_key' => $request->xor_key, // Simpan XOR Key
            'notes' => $request->notes, // Simpan Catatan

        ]);

        return response()->json(['status' => 'success', 'message' => 'Riwayat & Akurasi berhasil dicatat!']);
    }
public function destroy($id) {
        $history = History::where('user_id', auth()->id())->findOrFail($id);
        $history->delete();

        return redirect()->back()->with('success', 'Riwayat aktivitas berhasil dihapus!');
    }
}