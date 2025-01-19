<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Http\Resources\Api\BookingTransactionApiResource;
use App\Models\BookingTransaction;
use Illuminate\Http\Request;

class BookingTransactionController extends Controller
{
    public function store(StoreBookingTransactionRequest $request) {
        $validated = $request->validated();

        try {
            $validated['trx_id'] = BookingTransaction::generateUniqueTrxId();
            $validated['is_paid'] = false;
            
            if (request()->hasFile('proof')) {
                $validated['proof'] = request()->file('proof')->store('proofs', 'public');
            }

            $booking = BookingTransaction::create($validated);

            return new BookingTransactionApiResource($booking);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function check_booking(Request $request) {
        $validated = $request->validate([
            'trx_id' => 'required|string|exists:booking_transactions,trx_id',
            'phone_number' => 'required|string',
        ]);

        $bookingDetails = BookingTransaction::where('trx_id', $validated['trx_id'])
            ->where('phone_number', $validated['phone_number'])
            ->with([
                'place',
                'place.city',
                'field',
            ])
            ->first();

        if (!$bookingDetails) {
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }

        return new BookingTransactionApiResource($bookingDetails);
    }
}
