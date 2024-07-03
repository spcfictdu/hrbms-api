### Public Index Room

This endpoint retrieves the list of all rooms.

#### URL

```
{{base_url}}/api/hotel-room
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

#### Response Example (Success)

```json
[
    {
        "image": "storage/1bb0667e/RoomPic_0.png",
        "roomName": "JUNIOR STANDARD",
        "rate": 1340,
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
        ]
    },
    {
        "image": "storage/fade6116/RoomPic_0.png",
        "roomName": "STANDARD",
        "rate": 1444,
        "capacity": 2,
        "description": "This modern room comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom.",
        "amenities": [
            "AIR CONDITIONING",
            "BATHTUB",
            "SATELLITE/CABLE TV",
            "TELEPHONE",
            "FREE WI-FI",
            "REFRIGERATOR",
            "IN-ROOM SAFE",
            "CLOSET"
        ]
    },
    ...
]
```
