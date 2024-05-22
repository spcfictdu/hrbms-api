### Show Transaction 

This endpoint is used to get the details of a transaction.

#### URL

```
{{base_url}}/api/transaction/show/{transactionRefNum}
```

#### Method

```
GET
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

#### Response Example (Success)

```json
{
    "message": "Transaction Info",
    "results": {
        "bookingHistory": {
            "room": {
                "number": 201,
                "name": "STANDARD",
                "capacity": 2
            },
            "transaction": {
                "status": "RESERVED",
                "checkInDate": "2024-05-21",
                "checkInTime": "09:00:00",
                "checkOutDate": "2024-05-22",
                "checkOutTime": "14:00:00"
            },
            "transactionHistory": {
                "checkInDate": null,
                "checkInTime": null,
                "checkOutDate": null,
                "checkOutTime": null
            },
            "guestName": "KOVACEK, JAMESON KEEBLER",
            "priceSummary": {
                "roomTotal": 1444
            }
        }
    },
    "code": 200,
    "error": false
}
```