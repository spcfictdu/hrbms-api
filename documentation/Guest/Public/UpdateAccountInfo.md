### Update Account Info

This endpoint is used to update the user guest account info.

#### URL

```
{{base_url}}/guest/account/update-details
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

```json
{
    "firstName": "alexs",
    "middleName": "m",
    "lastName": "camaddo",
    "email": "alex1@gmail.com",
    "phoneNumber": "09605607559",
    "province": "Cebu",
    "city": "Cebu City"
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
