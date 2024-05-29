### Room Type Enum

This endpoint is used for dropdowns in the frontend to display the room types.

#### URL

```
{{base_url}}/api/enum/room-type
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

#### Response Example (Success)

```json
{
    "message": "Room types fetched successfully",
    "results": [
        {
            "referenceNumber": "d1007c65",
            "roomType": "JUNIOR STANDARD"
        },
        {
            "referenceNumber": "4537cfcb",
            "roomType": "STANDARD"
        },
        {
            "referenceNumber": "7ec88ab5",
            "roomType": "JUNIOR SUITE"
        },
        {
            "referenceNumber": "41547143",
            "roomType": "SUITE"
        },
        {
            "referenceNumber": "be73731e",
            "roomType": "SUPERIOR"
        }
    ],
    "code": 200,
    "error": false
}
```
