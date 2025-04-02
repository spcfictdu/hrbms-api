### Index Voucher

This endpoint retrieves the list of all voucher.

#### URL

```
{{base_url}}/api/voucher/
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
```

#### Response Example (Success)

```json

{
    "message": "list of all vouchers",
    "results": [
        {
            "code": "1234",
            "discount": "10%",
            "usage": 10,
            "status": "ACTIVE"
        },
        {
            "code": "Early Bird",
            "discount": "20%",
            "usage": 1,
            "status": "ACTIVE"
        }
    ],
    "code": 200,
    "error": false
}
```
