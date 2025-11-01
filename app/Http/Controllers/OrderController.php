<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    /**
     * @OA\Post(
     *   path="/orders/{id}/pay",
     *   summary="Pay for an order",
     *   description="Marks the order as 'paid' and updates the user's points accordingly. If the total price is greater than or equal to 100, a 10-point bonus will be added.",
     *   tags={"Orders"},
     *   operationId="payOrder",
     *   security={{"bearer_token": {}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="The ID of the order to be paid",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Order successfully paid and user points updated",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="message", type="string", example="Payment successful."),
     *       @OA\Property(property="order", type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="user_id", type="integer", example=1),
     *         @OA\Property(property="total_price", type="number", format="float", example=120),
     *         @OA\Property(property="status", type="string", example="paid")
     *       ),
     *       @OA\Property(property="user_points", type="number", format="float", example=180)
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=422,
     *     description="Invalid request (e.g. order not in pending state)",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="message", type="string", example="This order cannot be paid because it is not pending.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=500,
     *     description="Internal server error",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="message", type="string", example="Payment failed."),
     *       @OA\Property(property="error", type="string", example="Database transaction failed.")
     *     )
     *   )
     * )
     */

    public function payOrder(Request $request, Order $order)
    {

        // -------------------------------------------------
        // No need to manually validate order_id here.
        // Laravel Route Model Binding automatically checks
        // if the Order exists in the database.
        // If it doesn't exist, Laravel will return a 404 response.
        //
        // Comment by Ali Hasan
        // -------------------------------------------------
        
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'This order cannot be paid because it is not pending.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $order->status = 'paid';
            $order->save();

            $user = $order->user;
            $points_to_add = $order->total_price;

            if ($order->total_price >= 100) {
                $points_to_add += 10;
            }

            $user->points += $points_to_add;
            $user->save();

            DB::commit();

            return response()->json([
                'order' => new OrderResource($order->load('user')),
                'new_points' => $user->points,
                'message' => 'Payment successful.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Payment failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
