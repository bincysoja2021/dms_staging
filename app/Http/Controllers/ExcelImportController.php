<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceDate;
use App\Imports\InvoiceImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function index()
    {
        return view('admin.excel_import');
    }
    public function import(Request $request)
    {
        // Increase the execution time limit
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try 
        {
            Excel::import(new InvoiceImport, $request->file('file')->store('files'));
            return redirect()->route('excel_import')->with('message', 'Invoices imported successfully!');
        } 
        catch (\Exception $e) 
        {
            return redirect()->route('excel_import')->with('error', 'Error importing invoices: ' . $e->getMessage());
        }
    }

    
}
