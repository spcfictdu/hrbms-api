<!DOCTYPE html>
<html>
<head>
    <title>Reserve Transaction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd; /* Border around the entire message */
            border-radius: 10px;
            background-color: #fff;
            position: relative; /* Required for absolute positioning */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #ee4d2d;
            margin: 0;
            padding: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .welcome-message {
            margin-bottom: 30px;
        }
        .welcome-message p {
            margin: 0;
            padding: 0;
            font-size: 18px;
            line-height: 1.5;
            color: #666;
            margin-bottom: 10px;
        }
        .details p {
            margin: 0;
            padding: 0;
            font-size: 16px;
            line-height: 1.5;
            color: #666;
            margin-bottom: 10px;
        }
        .details strong {
            color: #333;
        }
        .cta {
            text-align: right; /* Align button to the right */
            position: absolute; /* Positioning relative to the container */
            bottom: 20px; /* Distance from the bottom */
            right: 20px; /* Distance from the right */
        }
        .cta a {
            display: inline-block;
            background-color: #ee4d2d;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .cta a:hover {
            background-color: #ff6347;
        }
        .footer {
            text-align: center;
            color: #999;
            margin-top: 20px; /* Add margin to the top */
        }
        .footer p {
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reserve Transaction</h1>
        </div>
        <div class="welcome-message">
            <p>Dear {{ $transaction->guest->full_name }},</p>
            <p>We have received your reservation. Below are the details of your reservation:</p>
            <p>Guest Contact Number: {{ $transaction->guest->phone_number }}</p>
        </div>
        <div class="details">
            @php
                use Carbon\Carbon;
                use Illuminate\Support\Str;

                $rate = $transaction->room->roomType->rates->firstWhere('type', 'REGULAR');

                $today = now()->toDateString();
                $specialRate = $transaction->room->roomType->rates->first(function ($rate) use ($today) {
                    return $rate->type === 'SPECIAL'
                        && (!$rate->start_date || $rate->start_date <= $today)
                        && (!$rate->end_date || $rate->end_date >= $today);
                });

                if ($specialRate) {
                    $rate = $specialRate;
                }

                $checkIn = \Carbon\Carbon::parse($transaction->check_in_date);
                $checkOut = \Carbon\Carbon::parse($transaction->check_out_date);
                $days = $checkIn->diffInDays($checkOut) ?: 1;

                // Room rates per day
                $totalRoomRate = 0;
                for ($date = $checkIn->copy(); $date->lt($checkOut); $date->addDay()) {
                    $day = strtolower($date->format('l'));
                    $totalRoomRate += $rate->$day ?? 0;
                }

                // Extras
                $roomCapacity = $transaction->room->roomType->capacity ?? 1;
                $extraPersonCount = max(($transaction->number_of_guest ?? 1) - $roomCapacity, 0);

                // Use current day's rate for extra person calculation
                $dayOfWeek = strtolower(now()->format('l'));
                $todayRate = $rate->$dayOfWeek ?? 0;

                $extraPersonRate = ($todayRate / $roomCapacity) / 2;
                $extraGuestTotal = $extraPersonCount * $extraPersonRate * $days;

                // Add-ons
                $addons = $transaction->bookingAddon ?? collect();
                $addonsTotal = $addons->sum('total_price');

                // Totals
                $grandTotal = $totalRoomRate + $extraGuestTotal + $addonsTotal;
                $paymentReceived = $transaction->payment->sum('amount_received');
                $balance = max($grandTotal - $paymentReceived, 0);
            @endphp
            <p><strong>Hotel Name:</strong> HRBMS</p>
            <p><strong>Room Number:</strong> {{ $transaction->room->room_number }}</p>
            <p><strong>Room Floor:</strong> {{ $transaction->room->room_floor }}</p>
            <p><strong>Room Name:</strong> {{ $transaction->room->roomType->name }}</p>
            <p><strong>Check-in Date and Time:</strong> {{ $transaction->check_in_date }}  /  {{ date('h:i A', strtotime($transaction->check_in_time)) }}</p>
            <p><strong>Check-out Date and Time:</strong> {{ $transaction->check_out_date }}  /  {{ date('h:i A', strtotime($transaction->check_out_time)) }}</p>
            <p><strong>Room Total:</strong> ₱{{ $totalRoomRate }}</p>
            <p><strong>Extra Person Charge:</strong> ₱{{ $extraGuestTotal }}</p>
            <p><strong>Addon Total:</strong> ₱{{ $addonsTotal }}</p>
            <p><strong>Grand Total:</strong> ₱{{ $grandTotal }}</p>
        </div>
        <div class="footer">
            <p>Thank you for reserving with us!</p>
            <p>This email was sent automatically. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
