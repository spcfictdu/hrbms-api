### Close User Cashier Session

This endpoint is used to close a user cashier session.

#### URL

```
{{base_url}}/api/cashier-session/user/close
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
    // Closing balance must be equal to the amount of money in the cash register.
    "closingBalance": "5000"
}
```

#### Response Example (Success)

```json
{
    "message": "The user's cashier session has been closed.",
    "results": [],
    "code": 200,
    "error": false
}
```
