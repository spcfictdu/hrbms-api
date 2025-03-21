### Start User Cashier Session

This endpoint is used to start a user cashier session.

#### URL

```
{{base_url}}/api/cashier-session/user/start
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

#### Request Body

```json
{
    // This is where the cashier puts the amount of money they have in their cash register.
    "openingBalance": "5000"
}
```

#### Response Example (Success)

```json
{
    "message": "Success, the user's cashier has been opened.",
    "results": {
        "opening_balance": "5000.00",
        "opened_at": "2025-03-21T03:44:23.177816Z",
        "status": "ACTIVE",
        "id": 2
    },
    "code": 200,
    "error": false
}
```
