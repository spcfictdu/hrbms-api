### Index Room Status

This endpoint retrieves the list of all occupied rooms.

#### URL

```
{{base_url}}/api/room-status/
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

#### Response Example (Success)

```json
{
    "roomStatusCount": {
        "READY FOR OCCUPANCY": 5
    },
    "rooms": [
        {
            "roomId": 1,
            "roomReferenceNumber": "2227e3c3",
            "roomNumber": 101,
            "roomType": "JUNIOR STANDARD",
            "status": "READY FOR OCCUPANCY",
            "guest": null
        },
        {
            "roomId": 2,
            "roomReferenceNumber": "72a0423f",
            "roomNumber": 201,
            "roomType": "STANDARD",
            "status": "READY FOR OCCUPANCY",
            "guest": null
        },
        {
            "roomId": 3,
            "roomReferenceNumber": "def8993a",
            "roomNumber": 301,
            "roomType": "JUNIOR SUITE",
            "status": "READY FOR OCCUPANCY",
            "guest": null
        },
        {
            "roomId": 4,
            "roomReferenceNumber": "3fb00296",
            "roomNumber": 401,
            "roomType": "SUITE",
            "status": "READY FOR OCCUPANCY",
            "guest": null
        },
        {
            "roomId": 5,
            "roomReferenceNumber": "1252b4ea",
            "roomNumber": 501,
            "roomType": "SUPERIOR",
            "status": "READY FOR OCCUPANCY",
            "guest": null
        }
    ]
}
```
