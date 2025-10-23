<!DOCTYPE html>
<html>

<head>
    <title>Booking Transaction</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; box-sizing: border-box; padding: 0; margin: 0; color: #212529;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="max-width: 600px; border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px; margin: 0 auto;">
            <div style="padding: 1rem;">

                {{-- HEADER --}}
                <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070&auto=format&fit=crop"
                    alt="hotel-room" style="width: 100%; height: 250px; object-fit: cover;">
                <h2 style="margin: 16px 0;">Booking Transaction</h2>

                {{-- MESSAGE BODY --}}
                <div style="font-size: 0.9rem; margin-bottom: 16px;">
                    <p style="margin-top: 0;">Thank you, your booking at HRBMS has been confirmed.</p>
                    <p>We are delighted to welcome you and look forward to your stay.</p>
                    <p>Please review your booking details below and let us know if you wish to make any changes or requests.</p>
                </div>

                {{-- CHECK-IN / CHECK-OUT --}}
                @php
                    use Carbon\Carbon;

                    $checkInDate = \Carbon\Carbon::parse($transaction->check_in_date)->format('d M Y');
                    $checkInTime = \Carbon\Carbon::parse($transaction->check_in_time)->format('h:i A');
                    $checkOutDate = \Carbon\Carbon::parse($transaction->check_out_date)->format('d M Y');
                    $checkOutTime = \Carbon\Carbon::parse($transaction->check_out_time)->format('h:i A');
                @endphp

                <div style="margin-top: 16px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 50%; vertical-align: top; padding-right: 8px;">
                                <div style="border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px;">
                                    <div style="padding: 1rem; border-bottom: 1px solid rgba(0, 0, 0, 0.175);">
                                        <h4 style="margin: 0;">Check-in</h4>
                                    </div>
                                    <div style="padding: 1rem;">
                                        <h3 style="margin: 0;">{{ $checkInDate }}</h3>
                                        <div style="color: #6c757d;"><small>From {{ $checkInTime }}</small></div>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 50%; vertical-align: top; padding-left: 8px;">
                                <div style="border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px;">
                                    <div style="padding: 1rem; border-bottom: 1px solid rgba(0, 0, 0, 0.175);">
                                        <h4 style="margin: 0;">Check-out</h4>
                                    </div>
                                    <div style="padding: 1rem;">
                                        <h3 style="margin: 0;">{{ $checkOutDate }}</h3>
                                        <div style="color: #6c757d;"><small>Until {{ $checkOutTime }}</small></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- CONTACT DETAILS --}}
                <div style="margin-top: 16px;">
                    <h3 style="margin: 0 0 12px 0;">Address and Contact</h3>
                    <div style="border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px;">
                        <div style="padding: 1rem; border-bottom: 1px solid rgba(0, 0, 0, 0.175);">
                            <h4 style="margin: 0;">Hotel Room and Booking Management System</h4>
                            <small><a href="mailto:hrbms.spcf@gmail.com" style="text-decoration: none;">hrbms.spcf@gmail.com</a></small>
                        </div>
                        <div style="padding: 1rem;">
                            <small>
                                <p style="margin: 0;">HRBMS is a system developed for CHTM Students in SPCF.</p>
                            </small>
                        </div>
                    </div>
                </div>

                {{-- GUEST DETAILS --}}
                @php
                    $mask = fn($text) => substr($text, 0, 1) . str_repeat('*', max(strlen($text) - 1, 0));

                    $maskedFirstName = $mask($transaction->guest->first_name);
                    $maskedMiddleName = $transaction->guest->middle_name ? $mask($transaction->guest->middle_name) : '';
                    $maskedLastName = $mask($transaction->guest->last_name);

                    $email = $transaction->guest->email;
                    if (strpos($email, '@') !== false) {
                        [$local, $domain] = explode('@', $email, 2);
                        $maskedEmail = substr($local, 0, 1) . str_repeat('*', max(strlen($local) - 1, 0)) . '@' . $domain;
                    } else {
                        $maskedEmail = $email;
                    }

                    $bookedOn = \Carbon\Carbon::parse($transaction->created_at)->format('d M Y');
                @endphp

                <div style="margin-top: 16px;">
                    <h3 style="margin: 0 0 12px 0;">Reservation Details</h3>
                    <div style="border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px;">
                        <div style="padding: 1rem; border-bottom: 1px solid rgba(0, 0, 0, 0.175);">
                            <h4 style="margin: 0;">Guest Details</h4>
                            <small>
                                <div>{{ $maskedFirstName }} {{ $maskedMiddleName }} {{ $maskedLastName }}</div>
                                <div>{{ $maskedEmail }}</div>
                            </small>
                        </div>
                        <div style="padding: 1rem;">
                            <h4 style="margin: 0;">Booked on</h4>
                            <p style="margin: 0 0 16px 0;"><small>{{ $bookedOn }}</small></p>
                            <h4 style="margin: 0;">Personal Request</h4>
                            <p style="margin: 0;"><small>Please leave a bottle of champagne on the bedside table. [Sample only]</small></p>
                        </div>
                    </div>
                </div>

                {{-- CHARGES --}}
                @php
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

                    //Discount
                    $discount = $transaction->seniorPwdDiscount ?? $transaction->voucherDiscount ?? null;
                    $discountRate = $discount->value ?? 0;

                    // Totals
                    $grandTotal = (1 - $discountRate) * ($totalRoomRate + $extraGuestTotal) + $addonsTotal;
                    $paymentReceived = $transaction->payment->sum('amount_received');
                    $balance = max($grandTotal - $paymentReceived, 0);
                @endphp

                <div style="margin-top: 16px;">
                    <h3 style="margin: 0 0 12px 0;">Charges</h3>
                    <div style="border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px;">
                        <div style="padding: 1rem;">
                            {{-- Booking Summary --}}
                            <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.175); margin-bottom: 12px;">
                                <h4 style="margin: 0;">Booking Summary</h4>
                            </div>

                            <small>
                                <table style="width: 100%;">
                                    <tr>
                                        <td>Room ({{ $days }} night{{ $days > 1 ? 's' : '' }})</td>
                                        <td style="text-align: end;">₱{{ number_format($totalRoomRate, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Extra Guest Charge</td>
                                        <td style="text-align: end;">₱{{ number_format($extraGuestTotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Discounted</td>
                                        <td style="text-align: end;">₱{{ number_format($discountRate * ($totalRoomRate + $extraGuestTotal), 2) }}</td>
                                    </tr>

                                    {{-- Add-ons List --}}
                                    @if ($addons->count() > 0)
                                        <tr>
                                            <td colspan="2" style="padding-top: 8px;"><strong>Add-ons</strong></td>
                                        </tr>
                                        @foreach ($addons as $addon)
                                            <tr>
                                                <td>&nbsp;&nbsp;{{ $addon->name }}</td>
                                                <td style="text-align: end;">₱{{ number_format($addon->total_price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><em>Add-ons Total</em></td>
                                            <td style="text-align: end;">₱{{ number_format($addonsTotal, 2) }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td style="text-align: end;"><strong>₱{{ number_format($grandTotal, 2) }}</strong></td>
                                    </tr>
                                </table>
                            </small>

                            {{-- Payment Summary --}}
                            <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.175); margin: 12px 0;">
                                <h4 style="margin: 0;">Payment Summary</h4>
                            </div>

                            <small>
                                <table style="width: 100%;">
                                    <tr>
                                        <td>Total Received</td>
                                        <td style="text-align: end;">₱{{ number_format($paymentReceived, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Outstanding Balance</td>
                                        <td style="text-align: end;">₱{{ number_format($balance, 2) }}</td>
                                    </tr>
                                </table>
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
