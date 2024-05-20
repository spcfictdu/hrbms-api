### Archived Special Room Type Rate

This endpoint retrieves the list of all archived room type rates of specific room type using the room type reference number.

####  URL

```
{{base_url}}/api/room-type/rate/special/archived/{roomTypeReferenceNumber}
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
```

#### Response Example (Success)

``` json
{
    "message": "List of archived special room rates for room type.",
    "results": {
        "roomType": {
            "referenceNumber": "0185739a",
            "name": "DELUXE",
            "rates": [
                {
                    "referenceNumber": "5558d1c8",
                    "discountName": "May Discount 2024",
                    "startDate": "2024-05-01",
                    "endDate": "2024-05-31",
                    "monday": 1347,
                    "tuesday": 1447,
                    "wednesday": 1447,
                    "thursday": 1347,
                    "friday": 1447,
                    "saturday": 1547,
                    "sunday": 1447
                }
            ]
        }
    },
    "code": 200,
    "error": false
}
```
