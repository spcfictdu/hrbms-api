### Public Filter Room

This endpoint retrieves the list of all rooms.

#### URL

```
{{base_url}}/api/hotel-room/search
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

-   roomName (required) - The name of the room.
    -   Example: `roomName=JUNIOR STANDARD`

#### Response Example (Success)

```json
{
    "message": "Room data retrieved successfully",
    "results": {
        "roomImages": [
            {
                "filename": "storage/1bb0667e/RoomPic_0.png"
            },
            {
                "filename": "storage/1bb0667e/RoomPic_1.png"
            },
            {
                "filename": "storage/1bb0667e/RoomPic_2.png"
            },
            {
                "filename": "storage/1bb0667e/RoomPic_3.png"
            }
        ],
        "roomName": "JUNIOR STANDARD",
        "rate": null,
        "capacity": 2,
        "description": "This single room has a tile/marble floor, cable TV and air conditioning.",
        "amenities": [
            "AIR CONDITIONING",
            "SATELLITE/CABLE TV",
            "TELEPHONE",
            "FREE WI-FI",
            "REFRIGERATOR",
            "IN-ROOM SAFE",
            "CLOSET"
        ],
        "weeklyRate": [
            {
                "reference_number": "41e6a0bf",
                "type": "REGULAR",
                "discount_name": null,
                "start_date": null,
                "end_date": null,
                "monday": 1340,
                "tuesday": 1340,
                "wednesday": 1340,
                "thursday": 1340,
                "friday": 1340,
                "saturday": 1440,
                "sunday": 1440
            },
            {
                "reference_number": "RT-0001-1",
                "type": "SPECIAL",
                "discount_name": "DREAMSTAY DISCOUNT1-1",
                "start_date": "2021-01-01",
                "end_date": "2021-12-31",
                "monday": 1000,
                "tuesday": 1000,
                "wednesday": 1000,
                "thursday": 1000,
                "friday": 1000,
                "saturday": 1000,
                "sunday": 1000
            },
            {
                "reference_number": "RT-0001-2",
                "type": "SPECIAL",
                "discount_name": "DREAMSTAY DISCOUNT1-2",
                "start_date": "2021-01-01",
                "end_date": "2021-12-31",
                "monday": 1000,
                "tuesday": 1000,
                "wednesday": 1000,
                "thursday": 1000,
                "friday": 1000,
                "saturday": 1000,
                "sunday": 1000
            }
        ]
    },
    "code": 200,
    "error": false
}
```
