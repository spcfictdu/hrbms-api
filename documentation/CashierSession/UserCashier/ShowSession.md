### Show User Cashier Session

This endpoint is used to show a user cashier session.

#### URL

```
{{base_url}}/api/cashier-session/user/show
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
FRONT DESK
```

#### Response Example (Success)

```json
{
    "message": "Success, the user's cashier has been opened.",
    "results": {
        "drawerCash": "1000.00",
        "payments": [
            {
                "name": "GCASH",
                "totalAmount": "4520.00"
            },
            {
                "name": "CASH",
                "totalAmount": "3180.00"
            },
            {
                "name": "CHEQUE",
                "totalAmount": "0.00"
            },
            {
                "name": "CREDIT_CARD",
                "totalAmount": "0.00"
            }
        ]
    },
    "code": 200,
    "error": false
}
```
