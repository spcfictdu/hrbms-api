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

- checkInDate : STRING (optional) - The check in date the guest wants to book
- checkOutDate : STRING (optional) - The check out date the guest wants to book
- capacity : STRING (optional) - The capacity of the room
- roomType : STRING (optional) - The type of the room
- page : INTEGER (optional) - The page number
- perPage : INTEGER (optional) - The number of items per page
- sortBy : STRING (optional) - The field to sort by
- sortOrder : STRING (optional) - The order of the sort

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
