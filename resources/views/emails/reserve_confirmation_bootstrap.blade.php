<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/44c6248935.js" crossorigin="anonymous"></script>
    <title>Reserve Transaction Confirmation</title>
</head>

<body>
    <canvas id="canvas">
        <div class="container">
            <div class="card mx-auto" style="max-width: 600px;">
                <div class="card-body">

                    {{-- Header --}}
                    <img src="{{ asset('img/hotel-image.png') }}" class="img-fluid" alt="hotel-image">
                    <div class="header my-4 d-flex align-items-center justify-content-start">
                        <i class="fa-regular fa-circle-check fa-xl success text-success"></i>
                        <h4 class="card-title mb-0 ms-2">Reserve Confirmation</h4>
                    </div>
                    <div class="welcome-message">
                        <p>Thank you, your reservation at (HRBMS) has been confirmed.</p>
                        <p>Thank you for choosing to stay with us and we are delighted to welcome you.</p>
                        <p>Please have read through your reservation details and let us know if you need anything
                            changed or
                            added to make your stay more comfortable and enjoyable.</p>
                        <p>We look forward to meeting you!</p>
                    </div>

                    {{-- Check In Check Out --}}
                    <div class="row gx-3">
                        <div class="col">
                            <div class="card" style="max-width: 100%;">
                                <div class="d-flex align-items-center p-3 border-bottom">
                                    <i class="fa-solid fa-arrow-down fa-xs"></i>
                                    <h6 class="ms-2 mb-0">Check-in</h6>
                                </div>
                                <div class="card-body">
                                    <h5>09 Nov 2023</h5>
                                    <div class="text-muted">From 02:00 PM</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card" style="max-width: 100%; height: 100%">
                                <div class="d-flex align-items-center p-3 border-bottom">
                                    <i class="fa-solid fa-arrow-up fa-xs"></i>
                                    <h6 class="ms-2 mb-0">Check-out</h6>
                                </div>
                                <div class="card-body">
                                    <h5>10 Nov 2023</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="address mt-4">
                        <h5 class="mb-3">Address and Contact</h4>
                            <div class="card">
                                <div class="d-flex flex-column p-3 border-bottom">
                                    <h6 class="mb-0">Hotel Room and Booking Management System</h6>
                                    <a href="mail:hrbms.spcf@gmail.com" class="card-link ms-0"
                                        style="text-decoration: none">hrbms.spcf@gmail.com</a>
                                </div>
                                <div class="card-body">
                                    <small>
                                        <p class="mb-0">
                                            HRBMS is a system developed for Hotel and Room Students in a Bulacan School.
                                        </p>
                                    </small>

                                </div>
                            </div>
                    </div>

                    {{-- Reservation Details --}}
                    <div class="reservation-details mt-4">
                        <h5 class="mb-3">Reservation Details</h4>
                            <div class="card">
                                <div class="d-flex flex-column p-3 border-bottom">
                                    <h6 class="mb-0">Guest Details</h6>
                                    <small>
                                        <div>J*** D**</div>
                                        <div>j***.d**@e****.c**</div>
                                    </small>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title mb-1">Booked on</h6>
                                    <p><small>09 Oct 2023</small></p>
                                    <h6 class="card-title mb-1">Personal Request</h6>
                                    <p class="lh-sm"><small>Please leave a bottle of champagne on the bedside table.
                                            [This
                                            is a fake
                                            reservation for sample purposes only]</small></p>

                                </div>
                            </div>
                    </div>

                    {{-- Charges --}}
                    <div class="charges mt-4">
                        <h5 class="mb-3">Charges</h4>
                            <div class="card">
                                <div class="card-body">
                                    <div class="border-bottom">
                                        <h6 class="card-title">Booking Summary</h6>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>Room</div>
                                                <div>₱1000</div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>Extra Guest Total * (Extra Guest Price)</div>
                                                <div>₱600</div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>Total</div>
                                                <div>₱1600</div>
                                            </div>
                                        </small>
                                    </div>
                                    <div class="border-bottom mt-2">
                                        <h6 class="card-title">Payment Summary</h6>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>Total Received</div>
                                                <div>₱1000</div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>Total Outstanding Balance</div>
                                                <div>₱600</div>
                                            </div>
                                        </small>
                                    </div>
                                </div>
                            </div>
                    </div>

                    {{-- Policies --}}
                    <div class="reservation-details mt-4">
                        <h5 class="mb-3">Policies</h4>
                            <div class="card">
                                <div class="card-body">
                                    <div class=" pb-2 border-bottom">
                                        <h6 class="card-title">Cancellation Policy</h6>
                                        <small>
                                            <p>The Cancellation Policy of any reservations may vary depending on the
                                                room
                                                rates, occupancy levels, availability or other Hotel requirements.
                                                Cancellation of any booking less than one week from the date of arrival
                                                will
                                                result in 20% charge of the total room cost</p>
                                        </small>
                                    </div>
                                    <div class="mt-3 pb-2 border-bottom">
                                        <h6 class="card-title">Terms and Conditions</h6>
                                        <small>
                                            <p>Our desire is for you to have the very best experience of our
                                                accommodations,
                                                dining and other services. To assist us in delivering you that
                                                experience we
                                                have developed the following policies and guidelines which we
                                                respectfully
                                                request that you adhere to before, during and after your stay.</p>

                                            <div>* Arrival and Departure</div>
                                            <p>Check-in time is from 2:00 PM<br>
                                                Check-out time is prior to 11:00 AM</p>
                                            <p>Early check-in or late check-out is available and rates are available on
                                                request. Although our team members are available 24 hours a day, we
                                                would
                                                appreciate your advising us if you expect to arrive after 6.00 pm.</p>

                                            <div>* Small Pets and Animals</div>
                                            <p>No pets and animals are allowed within our guest rooms</p>

                                            <div>* Restaurant Trading Hours</div>
                                            <p>Lunch is from 11:30 AM to 2:30 PM <br>
                                                Dinner is from 5:30 PM to 9:00 PM</p>
                                        </small>
                                    </div>
                                    <div class="mt-3 pb-2">
                                        <h6 class="card-title">Payment Policy</h6>
                                        <small>
                                            <div>Accepted Payment Methods:</div>
                                            <p>We accept MasterCard, Visa, Diners Club and American Express as well as
                                                cash
                                                and travellers cheques.</p>
                                            <div>*Please note that a $20 postage fee applies and a credit card surcharge
                                                of
                                                1.5% for Mastercard and Visa, and a surcharge of 3% for American
                                                Express,
                                                JCB and Diners.</div>
                                        </small>
                                    </div>
                                </div>
                            </div>
                    </div>
    </canvas>

    {{-- <div class="details">
                    <p><strong>Hotel Name:</strong> HRBMS</p>
                    <p><strong>Room Number:</strong> {{ $transaction->room->room_number }}</p>
                    <p><strong>Room Floor:</strong> {{ $transaction->room->room_floor }}</p>
                    <p><strong>Room Name:</strong> {{ $transaction->room->roomType->name }}</p>
                    <p><strong>Check-in Date and Time:</strong> {{ $transaction->check_in_date }} /
                        {{ date('h:i A', strtotime($transaction->check_in_time)) }}</p>
                    <p><strong>Check-out Date and Time:</strong> {{ $transaction->check_out_date }} /
                        {{ date('h:i A', strtotime($transaction->check_out_time)) }}</p>
                </div>
                <div class="cta">
                    <a href="#" style="background-color: #ee4d2d;">Full Transaction Details</a>
                </div>
                <div class="footer">
                    <p>Thank you for booking with us!</p>
                    <p>This email was sent automatically. Please do not reply.</p>
                </div>f --}}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            html2canvas(document.querySelector("#canvas")).then(canvas => {
                canvas.toBlob(blob => {
                    const formData = new FormData();
                    formData.append('image', blob, 'reservation.png');

                    fetch('/upload-reservation-image', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        }).then(response => response.json())
                        .then(data => {
                            if (data.message === 'Email sent successfully') {
                                alert('Email sent successfully!');
                            } else {
                                alert('Failed to send email.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>

</body>

</html>
