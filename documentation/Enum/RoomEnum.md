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
    -   Example: {{base_url}}/api/enum/room-number?dateRange=2024-05-23,2024-05-25
-   extraPerson: INT (optional) - The number of extra persons to filter the room numbers
    -   Example: {{base_url}}/api/enum/room-number?extraPerson=1

#### Response Example (Success)

```json
{
    "message": "Rooms fetched successfully",
    "results": [
        {
            "referenceNumber": "356de3b5",
            "roomNumber": 101,
            "roomFloor": 1,
            "roomType": "JUNIOR STANDARD",
            "roomTypeCapacity": 2,
            "roomRateType": "REGULAR",
            "roomRatesArray": [
                {
                    "date": "2024-05-23",
                    "dayOfWeek": "thursday",
                    "rate": 1340,
                    "extraPersonRate": 335
                },
                {
                    "date": "2024-05-24",
                    "dayOfWeek": "friday",
                    "rate": 1340,
                    "extraPersonRate": 335
                },
                {
                    "date": "2024-05-25",
                    "dayOfWeek": "saturday",
                    "rate": 1440,
                    "extraPersonRate": 360
                }
            ],
            "roomTotal": 4120,
            "extraPersonCount": null,
            "ExtraPersonTotal": 0,
            "roomTotalWithExtraPerson": 4120
        }
    ],
    "code": 200,
    "error": false
}
```
