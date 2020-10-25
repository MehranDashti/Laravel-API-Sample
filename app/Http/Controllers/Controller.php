<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * @OA\Info(
     *      version="1.0",
     *      title="Laravel Api Sample",
     *      description="This project includes JWT authtentication, Import & Fetch customers",
     *      @OA\Contact(
     *          email="vahidrastgoo7@gmail.com"
     *      ),
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="API Server"
     * )
     *
     * @OA\Tag(
     *     name="Customers",
     *     description="API Endpoints of Customers"
     * )
     * @OA\Tag(
     *     name="Auth",
     *     description="Authentication Endpoints"
     * )
     */
}
