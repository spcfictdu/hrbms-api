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
FALSE
```

#### Permitted Roles

```
NO ROLE NEEDED
```

#### Parameters

-   search : STRING (optional) - The search query
-   roomType : STRING (optional) - The type of the room
-   page : INTEGER (optional) - The page number
-   perPage : INTEGER (optional) - The number of items per page
-   sortBy : STRING (optional) - The field to sort by
-   sortOrder : STRING (optional) - The order of the sort
-   checkInDate : STRING (optional) - The check in date the guest wants to book
-   checkOutDate : STRING (optional) - The check out date the guest wants to book
-   capacity : STRING (optional) - The capacity of the room

-   Example:
    -   `{{base_url}}/api/room?page=1&perPage=10&sortBy=roomNumber&sortOrder=asc`
    -   `{{base_url}}/api/room?checkInDate=2024-05-31&checkOutDate=2024-06-01&capacity=2`

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
                    // "amenities": [
                    //     "AIR CONDITIONING",
                    //     "SATELLITE/CABLE TV",
                    //     "TELEPHONE",
                    //     "FREE WI-FI",
                    //     "REFRIGERATOR",
                    //     "IN-ROOM SAFE",
                    //     "CLOSET"
                    // ]
                    "amenities": [
                        {
                            "name": "AIR CONDITIONING",
                            "quantity": 1
                        }
                    ]
                }
            }
        ],
        "pagination": {
            "total": 1,
            "perPage": 10,
            "currentPage": 1,
            "lastPage": 1,
            "from": 1,
            "to": 1
        }
    },
    "code": 200,
    "error": false
}
```
