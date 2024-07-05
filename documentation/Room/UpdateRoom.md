### Update Room

This endpoint is used to update a room using the provided reference number of the room.

#### URL

```
{{base_url}}/api/room/update/{roomReferenceNumber}
```

#### Method

```
PUT
```

#### Authentication Needed

```
TRUE
```

#### Permitted Roles

```
ADMIN
```

#### Request Body

```json
{
    "roomNumber": 602,
    "roomName": "Room 602",
    "roomType": "DELUXE"
}
```

#### Response Example (Success)

```json
{
    "message": "Room updated successfully.",
    "results": {
        "referenceNumber": "840b4991",
        "roomNumber": 602,
        "roomType": "DELUXE",
        "status": "READY FOR OCCUPANCY"
    },
    "code": 200,
    "error": false
}
```
