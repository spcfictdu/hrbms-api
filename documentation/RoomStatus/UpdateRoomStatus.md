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
    "status": "READY FOR OCCUPANCY" // OCCUPIED, DIRTY, UNALLOCATED, READY FOR OCCUPANCY
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
        "status": "DIRTY"
    },
    "code": 200,
    "error": false
}
```
