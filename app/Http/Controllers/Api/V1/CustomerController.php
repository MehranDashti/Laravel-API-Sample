<?php

namespace App\Http\Controllers\Api\V1;

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
     * @OA\Get(
     *      path="/customer",
     *      operationId="getCustomerList",
     *      tags={"Customers"},
     *      summary="Get list of Customers",
     *      description="Returns list of customers",
     *      security={ {"bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="current_page", type="string"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="first_name", type="string"),
     *                      @OA\Property(property="last_name", type="string"),
     *                      @OA\Property(property="phone", type="string"),
     *                  ),
     *              ),
     *              @OA\Property(property="first_page_url", type="string"),
     *              @OA\Property(property="from", type="integer"),
     *              @OA\Property(property="last_page", type="integer"),
     *              @OA\Property(property="last_page_url", type="string"),
     *              @OA\Property(property="next_page_url", type="string"),
     *              @OA\Property(property="path", type="string"),
     *              @OA\Property(property="per_page", type="integer"),
     *              @OA\Property(property="prev_page_url", type="integer"),
     *              @OA\Property(property="to", type="integer"),
     *              @OA\Property(property="total", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     * )
     */
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
     * @OA\Post(
     *      path="/customer/import",
     *      operationId="importCustomers",
     *      tags={"Customers"},
     *      summary="Import Customers",
     *      security={ {"bearer": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/xml",
     *              @OA\Property(property="csv_file", type="string", format="file", description="csv file to upload"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="{number} new customer imported successfully.")
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="csv_file", type="string"),
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permission Denied",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Permission Denied")
     *          )
     *      ),
     * )
     */
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
