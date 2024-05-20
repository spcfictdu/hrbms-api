### Archive Room Type

This endpoint is used to is used to archive a specific room type rate using the provided reference number of the room type rate.

#### URL

```
{{base_url}}/api/room-type/rate/special/archive/{roomTypeRateReferenceNumber}
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
    "message": "Special room type rate archived successfully.",
    "results": [],
    "code": 200,
    "error": false
}
```
