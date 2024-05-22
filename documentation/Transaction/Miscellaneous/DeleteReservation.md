### Delete Transaction

This endpoint is used to delete a transaction.

#### URL

```
{{base_url}}/api/transaction/delete/{status}/{transactionRefNum}
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
    "message": "Reservation Successfully Deleted",
    "results": [],
    "code": 200,
    "error": false
}
```