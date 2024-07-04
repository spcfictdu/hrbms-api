### Edit Account Info

This endpoint is used to display current guest credentials before update.

#### URL

```
{{base_url}}/guest/account/edit-details
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
FRONT DESK
GUEST
```

#### Query Parameters

#### Response Example (Success)

```json
{
    "message": "Guest Current Credentials",
    "results": {
        "firstName": "ALEX",
        "middleName": "M",
        "lastName": "CAMADDO",
        "email": "try1@gmail.com",
        "phoneNumber": "09605607559"
    },
    "code": 200,
    "error": false
}
```
