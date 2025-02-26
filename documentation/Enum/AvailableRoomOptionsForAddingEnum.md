# Guest Enum

This endpoint is used for dropdowns in the frontend to display the the available room numbers, and room floors when adding a room.

#### URL

```
{{base_url}}/api/enum/rooms/available-options-for-adding
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

#### Response Example (Success)

```json
{
    "availableRoomNumbers": [
        121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134,
        135, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 147, 148,
        149, 150, 151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162,
        163, 164, 165, 166, 167, 168, 169, 170
    ],
    "availableRoomFloors": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    "roomTypes": [
        "JUNIOR STANDARD",
        "STANDARD",
        "JUNIOR SUITE",
        "SUITE",
        "SUPERIOR",
        "SMALL FUNCTION ROOM"
    ]
}
```
