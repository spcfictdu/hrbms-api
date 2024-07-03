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

#### Response Example (Success)

```json
{
    "message": "Success",
    "results": {
        "data": [
            {
                "image": "storage/1bb0667e/RoomPic_0.png",
                "name": "JUNIOR STANDARD",
                "rate": 1340,
                "capacity": 2,
                "description": "This single room has a tile/marble floor, cable TV and air conditioning.",
                "roomsAvailable": 4
            },
            ...
        ],
        "pagination": {
            "total": 5,
            "perPage": 10,
            "currentPage": 1,
            "lastPage": 1
        }
    },
    "code": 200,
    "error": false
}
```
