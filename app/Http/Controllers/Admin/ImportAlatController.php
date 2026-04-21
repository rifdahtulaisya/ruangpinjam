<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlatImport;

class ImportAlatController extends Controller
{
    /**
     * Show the import form
     */
    public function showImportForm()
    {
        return view('admin.dataalat.import');
    }

    /**
     * Handle the import process
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new AlatImport, $request->file('file'));

            return redirect()->route('admin.dataalat.index')
                ->with('success', 'Data alat berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        $template = [
            ['nama_alat', 'kategori', 'kondisi', 'stok'],
        ];

        $callback = function () use ($template) {
            $file = fopen('php://output', 'w');
            foreach ($template as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_alat.csv"',
        ]);
    }
}