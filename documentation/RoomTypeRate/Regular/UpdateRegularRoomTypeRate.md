### Update Regular Room Type Rate

This endpoint is used to update a regular room type rate using the provided reference number of the room type rate.

#### URL

```
{{base_url}}/api/room-type/rate/regular/update/{roomTypeRateReferenceNumber}
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
    "rates": {
        "monday": 1547,
        "tuesday": 1547,
        "wednesday": 1547,
        "thursday": 1547,
        "friday": 1547,
        "saturday": 1747,
        "sunday": 1747
    }
}
``` 

#### Response Example (Success)

``` json
{
    "message": "Regular room type rate updated successfully.",
    "results": {
        "roomType": "DELUXE",
        "rates": {
            "referenceNumber": "d149c3f8",
            "monday": 1547,
            "tuesday": 1547,
            "wednesday": 1547,
            "thursday": 1547,
            "friday": 1547,
            "saturday": 1747,
            "sunday": 1747
        }
    },
    "code": 200,
    "error": false
}
```
