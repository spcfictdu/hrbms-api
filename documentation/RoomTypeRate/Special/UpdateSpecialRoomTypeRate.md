### Update Special Room Type Rate

This endpoint is used to update a special room type rate using the provided reference number of the room type rate.

#### URL

```
{{base_url}}/api/room-type/rate/special/update/{roomTypeRateReferenceNumber}
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
```

#### Request Body

``` json
{
    "discountName": "May Discount 2024",
    "startDate": "2024-05-01",
    "endDate": "2024-05-31",
    "rates": {
        "monday": 1347,
        "tuesday": 1447,
        "wednesday": 1447,
        "thursday": 1347,
        "friday": 1447,
        "saturday": 1547,
        "sunday": 1447
    }
}
``` 

#### Response Example (Success)

``` json
{
    "message": "Special room type rate updated successfully.",
    "results": {
        "roomType": "DELUXE",
        "rates": {
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
    },
    "code": 200,
    "error": false
}
```
