### User Login

This endpoint allows users to log in and obtain an authentication token.

####  URL

```
{{base_url}}/api/user/admin/login
```

#### Method
```
POST
```

#### Authentication Needed
```
FALSE
```

#### Permitted Roles
```
NO ROLE NEEDED
```

#### Request Body
``` json
{
    "username": "admin123",
    "password": "developer"
}
```

#### Response Example (Success)

``` json
{
    "message": "Login successful",
    "results": {
        "username": "admin123",
        "firstName": "JOHN",
        "lastName": "DOE",
        "token": "1|kDBtn2qBvCCqLueIp9r9rqknqpjbzSYmIF7Rkl7x72b03945",
        "role": "ADMIN"
    },
    "code": 200,
    "error": false
}
```
