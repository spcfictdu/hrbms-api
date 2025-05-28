### Generate Guest Booking Frequency Report

This endpoint shows how many times each guest booked a room during a date range.

####  URL

```
{{base_url}}/api/report/guest-frequency
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
		"value": "2025-05-29"
	},
	{
		"key": "end",
		"value": "2025-06-02"
	}
]
``` 

#### Response Example (Success)

``` json
{
    "frequentGuests": [
        {
            "guestId": 1,
            "name": "tester, tester",
            "bookings": 1
        },
        {
            "guestId": 2,
            "name": "OLSON, GEORGIANA O'KON",
            "bookings": 1
        },
        {
            "guestId": 3,
            "name": "KLEIN, HEBER DIBBERT",
            "bookings": 1
        },
        {
            "guestId": 4,
            "name": "SKILES, JERMAINE SCHIMMEL",
            "bookings": 1
        },
        {
            "guestId": 5,
            "name": "LUBOWITZ, THOMAS O'REILLY",
            "bookings": 1
        }
    ]
}
```
