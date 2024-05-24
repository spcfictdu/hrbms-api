# Index Availability Calendar API

This API is used to get the availability calendar of a property.

#### URL

```
{{base_url}}/api/availability-calendar
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
ALL
```

#### Query Parameters

-   roomType: STRING (optional) - The room type to filter the availability calendar
    -   Example: {{base_url}}/api/availability-calendar?roomType=DELUXE
-   roomNumber: INT (optional) - The room number to filter the availability calendar
    -   Example: {{base_url}}/api/availability-calendar?roomNumber=101
-   dateRange: STRING (optional) - The date range to filter the availability calendar
    -   Example: {{base_url}}/api/availability-calendar?dateRange=2021-01-01,2021-01-31

#### Response Example (Success)

```json
{
    "message": "List of all transactions",
    "results": [
        {
            "roomReferenceNumber": "69b82a1d",
            "referenceNumber": "bc96eb30",
            "roomType": "JUNIOR STANDARD",
            "roomNumber": 101,
            "roomStatus": "READY FOR OCCUPANCY",
            "guest": "BERNHARD, AUTUMN BRAUN ",
            "checkIn": "2024-05-23T09:00:00",
            "checkOut": "2024-05-24T14:00:00",
            "status": "CONFIRMED"
        },
        {
            "roomReferenceNumber": "5446314c",
            "referenceNumber": "ed4e9488",
            "roomType": "STANDARD",
            "roomNumber": 201,
            "roomStatus": "READY FOR OCCUPANCY",
            "guest": "SIMONIS, SCOTTIE ORTIZ ",
            "checkIn": "2024-05-24T09:00:00",
            "checkOut": "2024-05-25T14:00:00",
            "status": "CONFIRMED"
        },
        {
            "roomReferenceNumber": "69b82a1d",
            "referenceNumber": "081201c9",
            "roomType": "JUNIOR STANDARD",
            "roomNumber": 101,
            "roomStatus": "READY FOR OCCUPANCY",
            "guest": "Gatbonton, Gabriel  ",
            "checkIn": "2024-05-24T14:00:00",
            "checkOut": "2024-05-25T11:00:00",
            "status": "RESERVED"
        },
        ...
    ],
    "code": 200,
    "error": false
}
```
