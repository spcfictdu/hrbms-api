### Index Room Status

This endpoint retrieves the list of all occupied rooms.

#### URL

```
{{base_url}}/api/room-status/
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
NO ROLE NEEDED
```

#### Query Parameters

-   `page` - The page number to retrieve. (Optional)
-   `perPage` - The number of items to retrieve per page. (Optional)
-   `sortBy` - The column to sort by. (Optional)
-   `sortOrder` - The order to sort by. (Optional)
-   `roomType` - The type of room to filter by. (Optional)

#### Response Example (Success)

```json
{
    "message": "success",
    "results": {
        "rooms": [
            {
                "roomId": 1,
                "roomReferenceNumber": "ebfafd22",
                "roomNumber": 101,
                "roomType": "JUNIOR STANDARD",
                "status": "AVAILABLE",
                "guest": null
            },
            {
                "roomId": 2,
                "roomReferenceNumber": "ef03c1e1",
                "roomNumber": 102,
                "roomType": "STANDARD",
                "status": "AVAILABLE",
                "guest": null
            },
            {
                "roomId": 3,
                "roomReferenceNumber": "4fa7ff88",
                "roomNumber": 103,
                "roomType": "JUNIOR SUITE",
                "status": "AVAILABLE",
                "guest": null
            }
        ],
        "pagination": {
            "total": 20,
            "perPage": 3,
            "currentPage": 1,
            "lastPage": 7,
            "from": 1,
            "to": 3
        }
    },
    "code": 200,
    "error": false
}
```
