### Index Room Type

This endpoint retrieves the list of all room types.

#### URL

```
{{base_url}}/api/room-type/
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

#### Response Example (Success)

```json
{
    "message": "List of all room types.",
    "results": [
        {
            "referenceNumber": "0192674d",
            "name": "STANDARD",
            "price": 1000,
            "image": "storage/0192674d/RoomPic_0.png",
            "description": "This modern room comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom.",
            "capacity": 2,
            "totalRooms": 4
        }
    ],
    "code": 200,
    "error": false
}
```
