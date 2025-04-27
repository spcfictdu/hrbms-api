### Generate Daily Reservations Report

This endpoint retrieves a report of all cashier sessions for a selected date.

####  URL

```
{{base_url}}/api/report/daily-cashier
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
"query": [
	{
		"key": "date",
		"value": "2025-04-28"
	}
]
``` 

#### Response Example (Success)

``` json
{
    "message": "Daily hotel activity summary retrieved successfully.",
    "data": {
        "date": "2025-04-28",
        "checkIns": [
            {
                "guestName": "BOGAN, URBAN JACOBI",
                "roomNumber": 102,
                "checkInDate": "2025-04-28"
            }
        ],
        "checkOuts": [
            {
                "guestName": null,
                "roomNumber": 101,
                "checkOutDate": "2025-04-28"
            }
        ],
        "inHouse": [
            {
                "guestName": "BOGAN, URBAN JACOBI",
                "roomNumber": 102,
                "checkInDate": "2025-04-28",
                "checkOutDate": "2025-04-29"
            }
        ]
    }
}
```
