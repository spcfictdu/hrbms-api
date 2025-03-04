### Create Transaction

This endpoint is used to create a transaction.

#### URL

```
{{base_url}}/api/transaction/create
```

#### Method

```
POST
```

#### Authentication Needed

```
TRUE
```

#### Permitted Roles

```
ADMIN
FRONT DESK
```

#### Request Body

```json
{
    "status": "RESERVED",
    "room": {
        "referenceNumber": "1a3e4628"
    },
    "guest": {
        // For front desk, dbId is required
        // For guest that has an account, dbId is not required
        "accountId": 1, // nullable

        "firstName": "Alex",
        "middleName": "Mosing",
        "lastName": "Camaddo",
        "address": {
            "province": "Pampanga",
            "city": "Angeles"
        },
        "contact": {
            "phoneNum": "09605607559",
            "email": "test@gmail.com"
        },
        "id": {
            "type": "National-ID",
            "number": "1234"
        }
    },

    "addons": [
                {
                    "name": "extra pillow",
                    "quantity": "1"
                },
                {
                    "name": "extra towel",
                    "quantity": "3"
                },
                {
                    "name": "champagne bottle",
                    "quantity": "12"
                }
    ],

     // IF PAYMENT HAVE DISCOUNT
        // "discount": "VOUCHER",
        // "voucherCode" : "1234" ,
        // "idNumber" : "13123",
        
    // IF HAVE PAYMENTS E.G BOOKING FORM
    // "payment": {
    //     "paymentType": "CASH",
    //     "amountReceived": 1858

    // IF PAYMENT TYPE IS CHEQUE
        // "paymentType": "CHEQUE",
        // "chequeNumber" : "12236789",
        // "chequeBankName" : "asdf"

        // IF PAYMENT TYPE IS CREDIT CARD
        // "paymentType": "CREDIT_CARD",
        // "cardHolderName": "John Doe",
        // "cardNumber": "4111111111111111",
        // "expirationDate": "12/27",
        // "cvv": "123"
    // },
    "roomTotal": 1234,
    "checkIn": {
        "date": "2024-05-13",
        "time": "09:00"
    },
    "checkOut": {
        "date": "2024-05-14",
        "time": "14:00"
    }
}
```

#### Response Example (Success)

```json
{
    "message": "Room type created successfully.",
    "results": {
        "firstName": "Alex",
        "middleName": "Mosing",
        "lastName": "Camaddo",
        "province": "Pampanga",
        "city": "Angeles",
        "phoneNumber": "09605607559",
        "email": "test@gmail.com",
        "idType": "National-ID",
        "idNumber": "1234",
        "referenceNumber": "d9fb48e9",
        "status": "RESERVED",
        "checkInDate": "2024-05-13",
        "checkInTime": "09:00",
        "checkOutDate": "2024-05-14",
        "checkOutTime": "14:00",
        "numberOfGuest": 2
    },
    "code": 200,
    "error": false
}
```
