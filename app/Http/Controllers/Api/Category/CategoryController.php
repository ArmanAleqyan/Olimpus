<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SportType;
use App\Models\City;
class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/get_sports_type",
     *     operationId="get_sports_type",
     *     tags={"Sports Management"},
     *     summary="Get a list of sports types with field counts",
     *     @OA\Response(
     *         response=200,
     *         description="List of sports types retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object", ref="Sport Data")),
     *         ),
     *     ),
     * )
     */
    public function get_sports_type(Request $request){
        $get = SportType::Orderby('id', 'desc')->withcount('filds')->get();


        return response()->json([
           'status' => true,
           'data' => $get
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/get_city",
     *     operationId="getCity",
     *     tags={"City Management"},
     *     summary="Get a list of cities",
     *     @OA\Response(
     *         response=200,
     *         description="List of cities retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object", ref="City Data")),
     *         ),
     *     ),
     * )
     */

    public function get_city(){
        $get = City::orderby('name', 'asc')->get();


        return response()->json([
           'status' => true,
           'data' => $get
        ],200);
    }


}
