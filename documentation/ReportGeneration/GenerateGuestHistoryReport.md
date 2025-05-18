### Generate Guest History Report

This endpoint retrieves a report of a specified guest's past stays.

####  URL

```
{{base_url}}/api/report/guest-history/{guest_id}
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

#### Request Body

``` 
"query": []
``` 

#### Response Example (Success)

``` json
{
    "guest": {
        "name": "tester, tester",
        "totalSpent": 2780
    },
    "stays": [
        {
            "room": 101,
            "checkIn": "2025-05-18",
            "checkOut": "2025-05-19",
            "amountPaid": "1340.00"
        },
        {
            "room": 102,
            "checkIn": "2025-05-19",
            "checkOut": "2025-05-20",
            "amountPaid": "1440.00"
        }
    ]
}
```
