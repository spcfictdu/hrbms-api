### Update Transaction

This endpoint is used to update a transaction.

#### URL

```
{{base_url}}/api/transaction/update/
```

#### Method

```
PUT
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

``` json
{
    "referenceNumber": "fc0303ca",

    // IF RESERVED UPDATE
    // "status": "RESERVED",
    // "paymentType": "CASH",
    // "amountReceived": 2000
    
    // IF HAVE DISCOUNT
    // "discount" : "sNr",
    // "idNumber" : "2131231",
    // "voucherCode": "1234",

    // IF PAYMENT TYPE IS CHEQUE
    // "paymentType": "CHEQUE",
    // "chequeNumber" : "123672289",
    // "chequeBankName" : "asdf",

    // IF PAYMENT TYPE IS CREDIT CARD
    // "paymentType": "CREDIT_CARD",
    // "cardHolderName": "John Doe",
    // "cardNumber": "4111111111111111",
    // "expiration_date": "12/27",
    // "cvc": "123"
    
    //IF CONFIRMED UPDATE (BOOKING)
    //CHECK-IN CHECK-OUT
    "checkInDate": "2024-05-18",
    "checkInTime": "10:20:00",
    "checkOutDate": "2024-05-19",
    "checkOutTime": "14:00:00"
}
```

#### Response Example (Success)

```json
{
    "message": "Transaction updated successfully.",
    "results": [],
    "code": 200,
    "error": false
}
```
