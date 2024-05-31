<!DOCTYPE html>
<html>

<head>
    <title>Reserve Transaction Confirmation</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; box-sizing: border-box; padding: 0; margin: 0; color: #212529;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="max-width: 600px; border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px; margin: 0 auto;">
            <div style="padding: 1rem;">
                <div class="header">
                    @php
                        if($transaction->status === "RESERVED"){
                            $status = "Reserve";
                        } elseif($transaction->status === "CONFIRMED"){
                            $status = "Booking";
                        }
                    @endphp
                    <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="hotel-room" style="width: 100%; height: 250px; object-fit: cover;">
                    <h2 style="margin: 16px 0 16px 0;">{{ $status }} Confirmation</h2>
                </div>
                <div style="font-size: 0.9rem; margin: 0 0 16px 0;">
                    <p style="margin-top: 0;">Thank you, your reservation at (HRBMS) has been confirmed.</p>
                    <p>Thank you for choosing to stay with us and we are delighted to welcome you.</p>
                    <p>Please have read through your reservation details and let us know if you need anything changed or
                        added to make your stay more comfortable and enjoyable.</p>
                    <p>We look forward to meeting you!</p>
                </div>

                <div style="margin-top: 16px;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align: top;">
                                <div
                                    style="max-width: 100%; border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px;">
                                    <div style="padding: 1rem; border-bottom: 1px solid rgba(0, 0, 0, 0.175);">
                                        <h4 style="margin: 0;">Check-in</h4>
                                    </div>
                                    <div style="padding: 1rem;">
                                        @php
                                            use Carbon\Carbon;
                                            $checkInDate = Carbon::parse($transaction->check_in_date)->format('d M Y');
                                            $checkInTime = Carbon::parse($transaction->check_in_time)->format('h:i A');
                                        @endphp
                                        <h3 style="margin: 0;">{{ $checkInDate }}</h3>
                                        <div style="color: #6c757d;"><small>From {{ $checkInTime }}</small></div>
                                    </div>
                                </div>
                            </td>
                            <td style="vertical-align: top;">
                                <div
                                    style="max-width: 100%; border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px; height: 100%;">
                                    <div style="padding: 1rem; border-bottom: 1px solid rgba(0, 0, 0, 0.175);">
                                        <h4 style="margin: 0;">Check-out</h4>
                                    </div>
                                    <div style="padding: 1rem;">
                                        @php
                                            $checkOutDate = Carbon::parse($transaction->check_out_date)->format('d M Y');
                                            $checkOutTime = Carbon::parse($transaction->check_out_time)->format('h:i A');
                                        @endphp
                                        <h3 style="margin: 0;">{{ $checkOutDate }}</h3>
                                        <div style="color: #6c757d;"><small>To {{ $checkOutTime }}</small></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="margin-top: 16px;">
                    <h3 style="margin: 0 0 12px 0;">Address and Contact</h3>
                    <div style="max-width: 600px; border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px;">
                        <div style="padding: 1rem; border-bottom: 1px solid rgba(0, 0, 0, 0.175);">
                            <h4 style="margin: 0 0 0 0;">Hotel Room and Booking Management System</h4>
                            <small>
                                <a href="mailto:hrbms.spcf@gmail.com"
                                    style="text-decoration: none;">hrbms.spcf@gmail.com</a>
                            </small>

                        </div>
                        <div style="padding: 1rem;">
                            <small>
                                <p style="margin: 0 0 16px 0;">HRBMS is a system developed for Hotel and Room Students
                                    in a Bulacan School.</p>
                            </small>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <h3 style="margin: 0 0 12px 0;">Reservation Details</h3>
                    <div style="max-width: 600px; border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px;">
                        <div style="padding: 1rem; border-bottom: 1px solid rgba(0, 0, 0, 0.175);">
                            <h4 style="margin: 0 0 0 0;">Guest Details</h4>
                            <small>
                                @php
                                   // Mask guest details
                                    $maskedFirstName = substr($transaction->guest->first_name, 0, 1) . str_repeat('*', strlen($transaction->guest->first_name) - 1);
                                    $maskedMiddleName = $transaction->guest->middle_name ? substr($transaction->guest->middle_name, 0, 1) . str_repeat('*', strlen($transaction->guest->middle_name) - 1) : null;
                                    $maskedLastName = substr($transaction->guest->last_name, 0, 1) . str_repeat('*', strlen($transaction->guest->last_name) - 1);

                                    // Extract local and domain parts of the email address
                                    $atPosition = strpos($transaction->guest->email, '@');

                                    // Check if the '@' symbol is found
                                    if ($atPosition !== false) {
                                        // Extract local and domain parts of the email address
                                        $localPart = substr($transaction->guest->email, 0, $atPosition);
                                        $domainPart = substr($transaction->guest->email, $atPosition);

                                        // Mask the local part
                                        $maskedLocalPart = substr($localPart, 0, 1) . str_repeat('*', strlen($localPart) - 1);

                                        // Combine masked local part with the domain part to form the masked email
                                        $maskedEmail = $maskedLocalPart . $domainPart;
                                    } else {
                                        // If '@' symbol is not found, use the entire email address as local part
                                        $maskedEmail = $transaction->guest->email;
                                    }
                                @endphp
                                <div>
                                    {{ $maskedFirstName }}
                                    @if ($maskedMiddleName)
                                        {{ $maskedMiddleName }}
                                    @endif
                                    {{ $maskedLastName }}
                                </div>
                                <div>{{ $maskedEmail }}</div>
                            </small>
                        </div>
                        <div style="padding: 1rem;">
                            @php
                                $bookedOn = Carbon::parse($transaction->created_at)->format('d M Y');
                            @endphp
                            <h4 style="margin: 0 0 0 0;">Booked on</h4>
                            <p style="margin: 0 0 16px 0;"><small>{{ $bookedOn }}</small></p>
                            <h4 style="margin: 0 0 0 0;">Personal Request</h4>
                            <p style="margin: 0 0 0 0;"><small>Please leave a bottle of champagne on the bedside table.
                                    [This is a fake reservation for sample purposes only]</small></p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <h3 style="margin: 0 0 12px 0;">Charges</h3>
                    <div style="max-width: 600px; border: 1px solid rgba(0, 0, 0, 0.175); border-radius: 5px;">
                        <div style="padding: 1rem;">
                            <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.175); margin-bottom: 12px;">
                                <h4 style="margin: 0 0 12px 0;">Booking Summary</h4>
                            </div>
                            <div style="margin-top: 4px;">
                                <small>
                                    <table style="width: 100%">
                                        @php
                                            $rate = $transaction->room->roomType->rates->first();
                                            $day = Str::lower(Carbon::now()->format('l'));
                                            $total = $rate->$day + 600;
                                        @endphp
                                        <tr>
                                            <td>Room</td>
                                            <td style="text-align: end">₱{{ $rate->$day }}</td>
                                        </tr>
                                        <tr>
                                            <td>Extra Guest Total * (Extra Guest
                                                Price)</td>
                                            <td style="text-align: end">(Not yet implemented) ₱600</td>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td style="text-align: end">{{ $total }}</td>
                                        </tr>
                                    </table>
                                </small>
                            </div>
                            <div
                                style="border-bottom: 1px solid rgba(0, 0, 0, 0.175); margin-top: 8px; margin-bottom: 12px;">
                                <h4 style="margin: 0 0 12px 0;">Payment Summary</h4>
                            </div>
                            <div style="margin-top: 4px;">
                                <small>
                                    <table style="width: 100%">
                                        @php
                                            $payment = $transaction->payment->amount_received ?? 0;
                                        @endphp
                                        <tr>
                                            <td>Total Received</td>
                                            <td style="text-align: end">₱{{ $payment }}</td>
                                        </tr>
                                        <tr>
                                            <td>Extra Guest Total * (Extra Guest
                                                Price)</td>
                                            <td style="text-align: end">(Not yet implemented) ₱600</td>
                                        </tr>
                                        <tr>
                                            <td>Total Outstanding Balance</td>
                                            <td style="text-align: end">₱0</td>
                                        </tr>
                                    </table>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
