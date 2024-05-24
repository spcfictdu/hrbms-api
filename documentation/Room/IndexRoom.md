### Index Room

This endpoint retrieves the list of all rooms.

#### URL

```
{{base_url}}/api/room/
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
ADMIN
FRONT DESK
```

#### Parameters

-   roomType : STRING (optional) - The type of the room
-   page : INTEGER (optional) - The page number
-   perPage : INTEGER (optional) - The number of items per page
-   sortBy : STRING (optional) - The field to sort by
-   sortOrder : STRING (optional) - The order of the sort

-   Example:
    -   `{{base_url}}/api/room?page=1&perPage=10&sortBy=roomNumber&sortOrder=asc`

#### Response Example (Success)

```json
{
    "message": "List of all rooms.",
    "results": {
        "rooms": [
            {
                "referenceNumber": "69b82a1d",
                "roomNumber": 101,
                "status": "READY FOR OCCUPANCY",
                "roomType": {
                    "name": "JUNIOR STANDARD",
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
                }
            }
        ],
        "pagination": {
            "total": 1,
            "per_page": 10,
            "current_page": 1,
            "last_page": 1,
            "from": 1,
            "to": 1
        }
    },
    "code": 200,
    "error": false
}
```
