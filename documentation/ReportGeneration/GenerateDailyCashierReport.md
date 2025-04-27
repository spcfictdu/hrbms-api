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
    "message": "Daily cashier report retrieved successfully",
    "data": [
        {
            "user": "admin123",
            "openingBalance": 0,
            "closingBalance": 3198,
            "openedAt": "2025-04-27T00:00:00.000000Z",
            "closedAt": "2025-04-27T09:00:00.000000Z",
            "transactions": [
                {
                    "type": "payment",
                    "amount": 1340,
                    "method": "CASH",
                    "timestamp": "2025-04-27T06:20:41.000000Z",
                    "roomNumber": 101,
                    "roomType": "JUNIOR STANDARD"
                },
                {
                    "type": "payment",
                    "amount": 1858,
                    "method": "GCASH",
                    "timestamp": "2025-04-27T06:20:41.000000Z",
                    "roomNumber": 104,
                    "roomType": "SUITE"
                }
            ]
        },
        {
            "user": "user123",
            "openingBalance": 0,
            "closingBalance": 5371,
            "openedAt": "2025-04-27T00:00:00.000000Z",
            "closedAt": "2025-04-27T09:00:00.000000Z",
            "transactions": [
                {
                    "type": "payment",
                    "amount": 1444,
                    "method": "GCASH",
                    "timestamp": "2025-04-27T06:20:41.000000Z",
                    "roomNumber": 102,
                    "roomType": "STANDARD"
                },
                {
                    "type": "payment",
                    "amount": 1754,
                    "method": "GCASH",
                    "timestamp": "2025-04-27T06:20:41.000000Z",
                    "roomNumber": 103,
                    "roomType": "JUNIOR SUITE"
                },
                {
                    "type": "payment",
                    "amount": 2173,
                    "method": "CASH",
                    "timestamp": "2025-04-27T06:20:41.000000Z",
                    "roomNumber": 105,
                    "roomType": "SUPERIOR"
                }
            ]
        }
    ]
}
```
