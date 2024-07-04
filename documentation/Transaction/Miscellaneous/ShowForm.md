### Delete Transaction

This endpoint is used to delete a transaction.

#### URL

```
{{base_url}}/api/transaction/form/{RoomRefNum}
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
FRONT DESK
```

#### Response Example (Success) - ADMIN

```json
{
    "message": "Transaction Form existing info.",
    "results": {
        "bookingSummary": {
            "roomName": "JUNIOR STANDARD",
            "capacity": 2,
            "roomTotal": 1340
        },
        "guests": [
            {
                "id": 1,
                "reference_number": "ca594841",
                "first_name": "KIRK",
                "middle_name": "GUTMANN",
                "last_name": "HOWE",
                "province": "NEW YORK",
                "city": "GARRISONSTAD",
                "phone_number": "4344865217",
                "email": "aschmeler@gmail.com",
                "user_id": null,
                "full_name": "HOWE, KIRK GUTMANN"
            },
            {
                "id": 2,
                "reference_number": "6964dc7e",
                "first_name": "MICHELLE",
                "middle_name": "ARMSTRONG",
                "last_name": "GAYLORD",
                "province": "CONNECTICUT",
                "city": "ERNIESIDE",
                "phone_number": "6770330973",
                "email": "osinski.chase@gutmann.com",
                "user_id": null,
                "full_name": "GAYLORD, MICHELLE ARMSTRONG"
            }
        ]    
    },
    "code": 200,
    "error": false
}
```

#### Response Example (Success) - GUEST

```json
{
    "message": "Transaction Form existing info.",
    "results": {
        "bookingSummary": {
            "roomName": "JUNIOR STANDARD",
            "capacity": 2,
            "roomTotal": 1340
        },
        "guestInfo": {
            "id": 6,
            "reference_number": "6f217013",
            "first_name": "LEX",
            "middle_name": "B",
            "last_name": "CSD",
            "province": null,
            "city": null,
            "phone_number": "09605607559",
            "email": "try1@gmail.com",
            "id_type": null,
            "id_number": null,
            "user_id": 5,
            "full_name": "CSD, LEX B"
        }
    },
    "code": 200,
    "error": false
}
```
