### Delete Room

This endpoint is used to is used to delete a specific room using the provided reference number of the room.

#### URL

```
{{base_url}}/api/room/delete/{roomReferenceNumber}
```   

#### Method
```
DELETE
```

#### Authentication Needed
```
TRUE
```

#### Permitted Roles
```
ADMIN
```

#### Response Example (Success)

``` json
{
    "message": "Room deleted successfully.",
    "results": [],
    "code": 200,
    "error": false
}
```
