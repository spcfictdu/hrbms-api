### Update Room Status

This endpoint retrieves the list of all occupied rooms.

#### URL

```
{{base_url}}/api/room-status/update/{roomReferenceNumber}
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

#### Request Body

```json
{
    "status": "AVAILABLE" // OCCUPIED, UNCLEAN, UNALLOCATED, AVAILABLE
}
```

#### Response Example (Success)

```json
{
    "message": "Room status updated successfully.",
    "results": {
        "reference_number": "2227e3c3",
        "room_number": 101,
        "room_floor": 1,
        "status": "UNCLEAN"
    },
    "code": 200,
    "error": false
}
```
