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
    "discount" : "VOUCHER",
    "voucherCode": "1234",
    // "amountReceived": 2000
    
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
