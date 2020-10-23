<?php

namespace App\Http\Controllers\Api\v1;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportCsvRequest;
use App\Imports\CustomersImport;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Customer[]
     */
    public function index()
    {
        return Customer::paginate(20);
    }
    /**
     * Import new customers by csv file.
     *
     * @param ImportCsvRequest $request
     * @return Response
     */
    public function import(ImportCsvRequest $request)
    {
        try {
            $customerImport = new CustomersImport();
            Excel::import($customerImport, $request->file('csv_file'));

            $response = [
                'message' => "{$customerImport->recordsNumber()} new customer imported successfully.",
            ];
            $status = 200;
        } catch (ValidationException $e) {
            $response = [
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ];
            $status = $e->status;
        }

        return response($response, $status);
    }
}
