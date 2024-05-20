### Create Special Room Type Rate

This endpoint is used to create a new special room type rate. 

#### URL

```
{{base_url}}/api/room-type/rate/special/create
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
```

#### Request Body

``` json
{   
    "roomType": "DELUXE",
    "discountName": "May Discount",
    "startDate": "2024-05-01",
    "endDate": "2024-05-31",
    "rates": {
        "monday": 1447,
        "tuesday": 1447,
        "wednesday": 1447,
        "thursday": 1447,
        "friday": 1447,
        "saturday": 1547,
        "sunday": 1547
    }
}
``` 

#### Response Example (Success)

``` json
{
    "message": "Special room type rate created successfully.",
    "results": {
        "roomType": "DELUXE",
        "rates": {
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
    },
    "code": 200,
    "error": false
}
```
