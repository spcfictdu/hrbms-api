### Generate Guest History Report

This endpoint retrieves a list of guests who spent the most within a selected date range.

####  URL

```
{{base_url}}/api/report/top-guests
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
	    "key": "start",
		"value": "2025-04-28"
	},
	{
		"key": "end",
		"value": "2025-05-23"
	}
]
``` 

#### Response Example (Success)

``` json
{
    "topGuests": {
        "guests": [
            {
                "guestId": 1,
                "guestName": "tester, tester",
                "totalSpent": 2780
            },
            {
                "guestId": 5,
                "guestName": "HERZOG, EDMOND BAYER",
                "totalSpent": 1740
            },
            {
                "guestId": 4,
                "guestName": "REICHERT, WINONA HARTMANN",
                "totalSpent": 1640
            },
            {
                "guestId": 3,
                "guestName": "ABSHIRE, MARISA NOLAN",
                "totalSpent": 1540
            }
        ]
    }
}
```
