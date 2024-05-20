### Show Room Type Rate

This endpoint retrieves information about a specific room using the provided reference number of the room.

#### URL

```
{{base_url}}/api/room-type/rate/{roomTypeReferneceNumber}
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
    "message": "Rates of room type found.",
    "results": {
        "roomType": {
            "referenceNumber": "0185739a",
            "name": "DELUXE"
        },
        "rates": {
            "regular": {
                "referenceNumber": "d149c3f8",
                "monday": 1547,
                "tuesday": 1547,
                "wednesday": 1547,
                "thursday": 1547,
                "friday": 1547,
                "saturday": 1647,
                "sunday": 1647
            },
            "special": [
                {
                    "referenceNumber": "5558d1c8",
                    "discountName": "May Discount",
                    "startDate": "2024-05-01",
                    "endDate": "2024-05-31",
                    "monday": 1447,
                    "tuesday": 1447,
                    "wednesday": 1447,
                    "thursday": 1447,
                    "friday": 1447,
                    "saturday": 1547,
                    "sunday": 1547
                }
            ]
        }
    },
    "code": 200,
    "error": false
}
```
