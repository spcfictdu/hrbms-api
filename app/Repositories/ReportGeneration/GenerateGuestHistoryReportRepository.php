<?php 

namespace App\Repositories\ReportGeneration; 

use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request; 
use App\Models\Transaction\Transaction; 
use App\Models\Transaction\Payment; 
use App\Models\User; 

class GenerateGuestHistoryReportRepository {

	public function execute($guestId) { 
		if (!Auth::user()->hasRole('ADMIN') && !Auth::user()->hasRole('FRONT DESK')) { 
			return [ 
				'message' => 'Unauthorized access.', 
				'data' => [] 
			]; 
		} 

		$transactions = Transaction::where('guest_id', $guestId)
			->get(); 

		if ($transactions->isEmpty()) { 
			return [ 
				'message' => 'No transactions found for this guest.', 
				'data' => [] 
			]; 
		} 

		$guestName = optional($transactions->first()->guest)->full_name ?? 'Unknown Guest'; 
        
        $totalSpent = 0; 
        
        $stays = []; 

		foreach ($transactions as $transaction) {
			$amountPaid = Payment::where('transaction_id', $transaction->id)->sum('amount_received'); 
			
			$stays[] = [ 
				'room' => optional($transaction->room)->room_number, 
				'checkIn' => $transaction->check_in_date, 
				'checkOut' => $transaction->check_out_date, 
				'amountPaid' => $amountPaid 
			]; 
			
			$totalSpent += $amountPaid; 
		} 
		
		return [ 
			'guest' => [ 
				'name' => $guestName, 
				'totalSpent' => $totalSpent, 
			], 
			'stays' => $stays 
		]; 
	} 
}









