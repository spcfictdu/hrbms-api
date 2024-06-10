### Create Room

This endpoint is used to create a new room. 

#### URL

```
{{base_url}}/api/room/create
```   

#### Method
```
POST
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

``` json
{
    "roomNumber": 601,
    "roomFloor": 6,
    "roomType": "DELUXE"
}
``` 

#### Response Example (Success)

``` json
{
    "message": "Room created successfully.",
    "results": {
        "referenceNumber": "840b4991",
        "roomNumber": 601,
        "roomFloor": 6,
        "roomType": "DELUXE",
        "status": "UNALLOCATED"
    },
    "code": 200,
    "error": false
}
```
