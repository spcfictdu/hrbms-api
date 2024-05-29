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

#### Response Example (Success)

```json
{
    "message": "Rooms fetched successfully",
    "results": [
        {
            "referenceNumber": "69b82a1d",
            "roomNumber": 101,
            "roomFloor": 1,
            "roomType": "JUNIOR STANDARD",
            "roomTypeCapacity": 2,
            "roomTotal": 1340,
            "extraPersonTotal": 335,
            "total": 1675
        },
        {
            "referenceNumber": "5446314c",
            "roomNumber": 201,
            "roomFloor": 1,
            "roomType": "STANDARD",
            "roomTypeCapacity": 2,
            "roomTotal": 1444,
            "extraPersonTotal": 361,
            "total": 1805
        },
        ...
    ],
    "code": 200,
    "error": false
}
```
