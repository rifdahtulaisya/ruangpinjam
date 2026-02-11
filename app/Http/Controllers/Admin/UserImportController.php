<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.datapeminjam.import');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'File import wajib diupload',
            'file.mimes' => 'File harus berformat Excel (xlsx, xls) atau CSV',
            'file.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            $header = array_shift($rows);

            $successCount = 0;
            $failedCount = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena index mulai dari 0 dan ada header

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Mapping kolom: name, username, email, password, status
                $name = $row[0] ?? null;
                $username = $row[1] ?? null;
                $email = !empty(trim($row[2] ?? '')) ? trim($row[2]) : null;
                $password = $row[3] ?? null;
                $status = !empty($row[4]) ? strtolower(trim($row[4])) : 'active';

                // Validasi data
                $rowValidator = Validator::make([
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'status' => $status,
                ], [
                    'name' => 'required|string|max:255',
                    'username' => 'required|string|max:255|unique:users,username',
                    'email' => 'nullable|email|max:255|unique:users,email',
                    'password' => 'required|string|min:6',
                    'status' => 'in:active,inactive',
                ]);

                if ($rowValidator->fails()) {
                    $failedCount++;
                    $errors[] = [
                        'row' => $rowNumber,
                        'data' => [
                            'name' => $name,
                            'username' => $username,
                            'email' => $email,
                        ],
                        'errors' => $rowValidator->errors()->all()
                    ];
                    continue;
                }

                try {
                    User::create([
                        'name' => $name,
                        'username' => $username,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'status' => $status,
                        'role' => 'user', // atau sesuaikan dengan kebutuhan
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = [
                        'row' => $rowNumber,
                        'data' => [
                            'name' => $name,
                            'username' => $username,
                            'email' => $email,
                        ],
                        'errors' => [$e->getMessage()]
                    ];
                }
            }

            // Simpan log import (opsional)
            // UserImport::create([...]);

            $message = "Import selesai! Berhasil: {$successCount}, Gagal: {$failedCount}";

            if ($failedCount > 0) {
                return back()
                    ->with('warning', $message)
                    ->with('import_errors', $errors);
            }

            return redirect()
                ->route('admin.datapeminjam.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Password');
        $sheet->setCellValue('E1', 'Status');

        // Styling header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Contoh data
        $sheet->setCellValue('A2', 'John Doe');
        $sheet->setCellValue('B2', 'johndoe');
        $sheet->setCellValue('C2', 'john@example.com');
        $sheet->setCellValue('D2', 'password123');
        $sheet->setCellValue('E2', 'active');

        $sheet->setCellValue('A3', 'Jane Smith');
        $sheet->setCellValue('B3', 'janesmith');
        $sheet->setCellValue('C3', ''); // Email kosong (opsional)
        $sheet->setCellValue('D3', 'password456');
        $sheet->setCellValue('E3', 'active');

        // Auto size columns
        foreach(range('A','E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add notes
        $sheet->setCellValue('A5', 'Catatan:');
        $sheet->setCellValue('A6', '- Email bersifat opsional (boleh kosong)');
        $sheet->setCellValue('A7', '- Status: active atau inactive (default: active)');
        $sheet->setCellValue('A8', '- Password minimal 6 karakter');
        $sheet->getStyle('A5:A8')->getFont()->setItalic(true)->setSize(9);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $filename = 'template_import_peminjam_' . date('Y-m-d') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}