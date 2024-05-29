### Room Number Enum

This endpoint is used for dropdowns in the frontend to display the room numbers.

#### URL

```
{{base_url}}/api/enum/room-number
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

#### Query Parameters

-   roomType: STRING (optional) - The room type to filter the room numbers
    -   Example: {{base_url}}/api/enum/room-number?roomType=DELUXE

#### Response Example (Success)

```json
{
    "message": "Room numbers fetched successfully",
    "results": [
        {
            "referenceNumber": "4752f327",
            "roomNumber": 101
        },
        {
            "referenceNumber": "e5c494b1",
            "roomNumber": 201
        },
        {
            "referenceNumber": "2c774ef9",
            "roomNumber": 301
        },
        {
            "referenceNumber": "b120379a",
            "roomNumber": 401
        },
        {
            "referenceNumber": "81f4091e",
            "roomNumber": 501
        }
    ],
    "code": 200,
    "error": false
}
```
