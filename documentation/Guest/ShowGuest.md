### Show Room

This endpoint retrieves information about a specific room using the provided reference number of the room.

#### URL

```
{{base_url}}/api/guest/{id}
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
ADMIN
FRONT DESK
```

#### Query Parameters

-   referenceNumber: STRING (optional) - The reference number of the room
    -   Example: {{base_url}}/api/room/4752f327?referenceNumber=4752f327
-   checkInDate: DATE (optional) - The check-in date of the room
    -   Example: {{base_url}}/api/room/4752f327?checkInDate=2024-05-22
-   checkOutDate: DATE (optional) - The check-out date of the room
    -   Example: {{base_url}}/api/room/4752f327?checkOutDate=2024-05-23

#### Response Example (Success)

```json
{
    "message": "Successfully retrieved guest",
    "results": {
        "id": 2,
        "fullName": "FAHEY, LIBBY WITTING ",
        "email": "zprice@west.info",
        "phone": null,
        "idNumber": "123456789",
        "province": "OREGON",
        "city": "LAKE JERAMIE",
        "transactions": [
            {
                "status": "CHECKED-OUT",
                "reference": "1f01de47",
                "ocupants": 3,
                "checkIn": "2024-05-22",
                "checkOut": "2024-05-23",
                "booked": "2024-05-21T07:06:19.000000Z",
                "room": 201,
                "total": 1444
            }
        ]
    },
    "code": 200,
    "error": false
}
```
