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

#### Response Example (Success)

```json
{
    "message": "Transaction Form existing info.",
    "results": {
        "bookingSummary": {
            "roomName": "JUNIOR STANDARD",
            "capacity": 2,
            "roomTotal": 1340
        }
    },
    "code": 200,
    "error": false
}
```