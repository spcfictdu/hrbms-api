### Delete Room Type

This endpoint is used to is used to delete a specific room type using the provided reference number of the room type.

#### URL

```
{{base_url}}/api/room-type/delete/{roomTypeReferenceNumber}
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
    "message": "Room type deleted successfully.",
    "results": [],
    "code": 200,
    "error": false
}
```
