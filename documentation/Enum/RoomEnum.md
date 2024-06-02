### Room Enum

This endpoint is used for dropdowns in the frontend to display the room numbers.

#### URL

```
{{base_url}}/api/enum/room
```

#### Method

```
GET
```

#### Authentication Needed

```
FALSE
```

#### Permitted Roles

```
NO ROLE NEEDED
```

#### Query Parameters

-   roomType: STRING (optional) - The room type to filter the room numbers
    -   Example: {{base_url}}/api/enum/room-number?roomType=DELUXE
-   roomNumber: INT (optional) - The room number to filter the room numbers
    -   Example: {{base_url}}/api/enum/room-number?roomNumber=101
-   dateRange: STRING (optional) - The date range to filter the room numbers
    -   Example: {{base_url}}/api/enum/room-number?dateRange=2021-01-01,2021-01-02

#### Response Example (Success)

```json
{
    // Experimental - the chosen one would be the only one to be displayed
    "message": "Rooms fetched successfully",
    "results": [
        {
            "referenceNumber": "356de3b5",
            "roomNumber": 101,
            "roomFloor": 1,
            "roomType": "JUNIOR STANDARD",
            "roomTypeCapacity": 2,
            // Room total only for today's date
            "roomTotal": 1440,
            // Room total with filter applied
            "roomTotalWithFilter": 2780,
            // Room total from Monday to Sunday
            "roomTotalFromMondayToSunday": 9580,
            "roomRateType": "REGULAR",
            // Room rate with filter applied
            "roomRateArrayV1": [
                {
                    "date": "2024-05-23",
                    "dayOfWeek": "thursday",
                    "rate": 1340
                },
                {
                    "date": "2024-05-25",
                    "dayOfWeek": "saturday",
                    "rate": 1440
                }
            ],
            // Room rate from Monday to Sunday
            "roomRateArrayV2": {
                "Monday": 1340,
                "Tuesday": 1340,
                "Wednesday": 1340,
                "Thursday": 1340,
                "Friday": 1340,
                "Saturday": 1440,
                "Sunday": 1440
            },
            "extraPersonRate": 360
        },
        {
            "referenceNumber": "11794310",
            "roomNumber": 201,
            "roomFloor": 1,
            "roomType": "STANDARD",
            "roomTypeCapacity": 2,
            "roomTotal": 1544,
            "roomTotalWithFilter": 2988,
            "roomTotalFromMondayToSunday": 10308,
            "roomRateArrayV1": [
                {
                    "date": "2024-05-23",
                    "dayOfWeek": "thursday",
                    "rate": 1444
                },
                {
                    "date": "2024-05-25",
                    "dayOfWeek": "saturday",
                    "rate": 1544
                }
            ],
            "roomRateArrayV2": {
                "Monday": 1444,
                "Tuesday": 1444,
                "Wednesday": 1444,
                "Thursday": 1444,
                "Friday": 1444,
                "Saturday": 1544,
                "Sunday": 1544
            },
            "extraPersonRate": 386
        },
        ...
    ],
    "code": 200,
    "error": false
}
```
