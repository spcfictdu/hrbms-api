### Public Filter Room

This endpoint retrieves the list of all rooms.

#### URL

```
{{base_url}}/api/hotel-room/{referenceNumber}
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

#### Response Example (Success)

```json
{
    "message": "Room data retrieved successfully",
    "results": {
        "roomReferenceNumber": "35c74d39",
        "images": [
            "storage/1bb0667e/RoomPic_0.png",
            "storage/1bb0667e/RoomPic_1.png",
            "storage/1bb0667e/RoomPic_2.png",
            "storage/1bb0667e/RoomPic_3.png"
        ],
        "name": "JUNIOR STANDARD",
        "price": 1340,
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
        "rates": {
            "regular": {
                "referenceNumber": "41e6a0bf",
                "monday": 1340,
                "tuesday": 1340,
                "wednesday": 1340,
                "thursday": 1340,
                "friday": 1340,
                "saturday": 1440,
                "sunday": 1440
            },
            "special": [
                {
                    "referenceNumber": "RT-0001-1",
                    "discountName": "DREAMSTAY DISCOUNT1-1",
                    "startDate": "2021-01-01",
                    "endDate": "2021-12-31",
                    "monday": 1000,
                    "tuesday": 1000,
                    "wednesday": 1000,
                    "thursday": 1000,
                    "friday": 1000,
                    "saturday": 1000,
                    "sunday": 1000
                },
                {
                    "referenceNumber": "RT-0001-2",
                    "discountName": "DREAMSTAY DISCOUNT1-2",
                    "startDate": "2021-01-01",
                    "endDate": "2021-12-31",
                    "monday": 1000,
                    "tuesday": 1000,
                    "wednesday": 1000,
                    "thursday": 1000,
                    "friday": 1000,
                    "saturday": 1000,
                    "sunday": 1000
                }
            ]
        }
    },
    "code": 200,
    "error": false
}
```
