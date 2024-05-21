### Generate Revenue Report

This endpoint retrieves the revenue report based on the specified date range, room type, or room number.

####  URL

```
{{base_url}}/api/report/revenue
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
    "dateFrom": "2024-05-21",
    "dateTo": "2024-05-25"
    // "roomType": "JUNIOR SUITE" // nullable
    // "roomNumber": 201 // nullable
    
} 
``` 

#### Response Example (Success)

``` json
{
    "message": "Revenue report.",
    "results": {
        "dateFrom": "2024-05-21",
        "dateTo": "2024-05-25",
        "total": "8569.00",
        "taxes": "1028.28",
        "bookings": 5,
        "occupancy": "25%"
    },
    "code": 200,
    "error": false
}
```
