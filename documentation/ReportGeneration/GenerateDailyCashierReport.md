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
            "user": "admin",
            "openingBalance": "1000.00",
            "closingBalance": null,
            "openedAt": "2025-04-28 04:25:40",
            "closedAt": null,
            "transactions": [
                {
                    "type": "payment",
                    "amount": "1340.00",
                    "method": "CASH",
                    "timestamp": "2025-05-02T16:00:00.000000Z",
                    "roomNumber": 101,
                    "roomType": "JUNIOR STANDARD"
                },
                {
                    "type": "payment",
                    "amount": "1440.00",
                    "method": "GCASH",
                    "timestamp": "2025-05-02T16:00:00.000000Z",
                    "roomNumber": 102,
                    "roomType": "STANDARD"
                },
                {
                    "type": "payment",
                    "amount": "1640.00",
                    "method": "GCASH",
                    "timestamp": "2025-05-02T16:00:00.000000Z",
                    "roomNumber": 104,
                    "roomType": "SUITE"
                }
            ]
        },
        {
            "user": "frontdesk",
            "openingBalance": "1000.00",
            "closingBalance": null,
            "openedAt": "2025-04-28 04:25:40",
            "closedAt": null,
            "transactions": [
                {
                    "type": "payment",
                    "amount": "1540.00",
                    "method": "GCASH",
                    "timestamp": "2025-05-02T16:00:00.000000Z",
                    "roomNumber": 103,
                    "roomType": "JUNIOR SUITE"
                },
                {
                    "type": "payment",
                    "amount": "1740.00",
                    "method": "GCASH",
                    "timestamp": "2025-05-02T16:00:00.000000Z",
                    "roomNumber": 105,
                    "roomType": "SUPERIOR"
                }
            ]
        }
    ]
}
```
