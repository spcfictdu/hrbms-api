<?php

namespace App\Repositories\Transaction\Folio;

use App\Repositories\BaseRepository;
use App\Models\Transaction\{
    Transaction,
    Folio
};
use App\Models\Amenity\BookingAddon;

class UpdateFolioRepository extends BaseRepository
{
    public function execute($request){
        if (isset($request->referenceNumber)) {
            $transaction = Transaction::where('reference_number', $request->referenceNumber)->first();
            $folio = Folio::where('transaction_id', $transaction->id)
                ->where('item', 'ROOM')
                ->first();
        } elseif (isset($request->bookingAddonId)) {
            $folio = Folio::where('booking_addon_id', $request->bookingAddonId)
                ->first();
        }

        if (!$folio) {
            return $this->error('Folio record not found');
        }

        $bookingAddon = BookingAddon::where('id', $request->bookingAddonId)->first();

        if ($folio->item === 'ROOM') {
            $grandTotal = $transaction->room_total;
        } elseif ($folio->item === 'ADDON') {
            $grandTotal = $bookingAddon->total_price;
        }

        try {
            if ($request->folioType === 'INDIVIDUAL') {
                $folio->update([
                    'type' => 'INDIVIDUAL',
                    'folio_a_charge' => 1.00,
                    'folio_a_amount' => $grandTotal,
                    'folio_b_name' => null,
                    'folio_b_charge' => 0,
                    'folio_b_amount' => 0,
                    'folio_c_name' => null,
                    'folio_c_charge' => 0,
                    'folio_c_amount' => 0,
                    'folio_d_name' => null,
                    'folio_d_charge' => 0,
                    'folio_d_amount' => 0,
                ]);
            } elseif ($request->folioType === 'SPONSORED') {
                $folio->update([
                    'type' => $request->folioType ?? $folio->type,
                    'folio_a_charge' => 1 - ($request->folioB['charge'] ?? $folio->folio_b_charge ?? 0) - ($request->folioC['charge'] ?? $folio->folio_c_charge ?? 0) - ($request->folioD['charge'] ?? $folio->folio_d_charge ?? 0),
                    'folio_a_amount' => $grandTotal - ($request->folioB['amount'] ?? $folio->folio_b_amount ?? 0) - ($request->folioC['amount'] ?? $folio->folio_c_amount ?? 0) - ($request->folioD['amount'] ?? $folio->folio_d_amount ?? 0),
                    'folio_b_name' => $request->folioB['name'] ?? $folio->folio_b_name,
                    'folio_b_charge' => $request->folioB['charge'] ?? $folio->folio_b_charge ?? 0,
                    'folio_b_amount' => $request->folioB['amount'] ?? $folio->folio_b_amount ?? 0,
                    'folio_c_name' => $request->folioC['name'] ?? $folio->folio_c_name,
                    'folio_c_charge' => $request->folioC['charge'] ?? $folio->folio_c_charge ?? 0,
                    'folio_c_amount' => $request->folioC['amount'] ?? $folio->folio_c_amount ?? 0,
                    'folio_d_name' => $request->folioD['name'] ?? $folio->folio_d_name,
                    'folio_d_charge' => $request->folioD['charge'] ?? $folio->folio_d_charge ?? 0,
                    'folio_d_amount' => $request->folioD['amount'] ?? $folio->folio_d_amount ?? 0,
                ]);
                if ($request->filled('folioB') && $request->folioB['name'] === null) {
                    $folio->update([
                        'folio_b_name' => null,
                    ]);   
                } elseif ($request->filled('folioC') && $request->folioC['name'] === null) {
                    $folio->update([
                        'folio_c_name' => null,
                    ]);   
                } elseif ($request->filled('folioD') && $request->folioD['name'] === null) {
                    $folio->update([
                        'folio_d_name' => null,
                    ]);   
                }
            }
            
            return $this->success('Folio record updated successfully', $folio);
        } catch (\Exception) {
            return $this->error('Folio update failed');
        }
    }
}
