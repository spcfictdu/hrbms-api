<!DOCTYPE html>
<html>
<head>
    <title>Book Transaction Confirmation</title>
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
            <h1>Booking Confirmation</h1>
        </div>
        <div class="welcome-message">
            <p>Dear Guest,</p>
            <p>We are delighted to confirm your booking. Below are the details of your reservation:</p>
            <p>Guest Contact Number: {{ $transaction->guest->phone_number }}</p>
        </div>
        <div class="details">
            <p><strong>Hotel Name:</strong> HRBMS</p>
            <p><strong>Room Number:</strong> {{ $transaction->room->room_number }}</p>
            <p><strong>Room Floor:</strong> {{ $transaction->room->room_floor }}</p>
            <p><strong>Room Name:</strong> {{ $transaction->room->roomType->name }}</p>
            <p><strong>Check-in Date and Time:</strong> {{ $transaction->check_in_date }}  /  {{ date('h:i A', strtotime($transaction->check_in_time)) }}</p>
            <p><strong>Check-out Date and Time:</strong> {{ $transaction->check_out_date }}  /  {{ date('h:i A', strtotime($transaction->check_out_time)) }}</p>
        </div>
        <div class="cta">
            <a href="#" style="background-color: #ee4d2d;">Full Transaction Details</a>
        </div>
        <div class="footer">
            <p>Thank you for booking with us!</p>
            <p>This email was sent automatically. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
