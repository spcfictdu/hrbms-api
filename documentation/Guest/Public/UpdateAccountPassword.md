### Update Account Password

This endpoint is used to update the user guest account password.

#### URL

```
{{base_url}}/guest/account/change-password
```

#### Method

```
PUT
```

#### Authentication Needed

```
TRUE
```

#### Permitted Roles

```
FRONT DESK
GUEST
```

#### Request Body

``` json
{
    "oldPassword": "developer",
    "newPassword": "developer1",
    "newPasswordConfirmation": "developer1"
}
```

#### Response Example (Success)

```json
{
    "message": "User Details Updated Successfully",
    "results": [],
    "code": 200,
    "error": false
}
```
