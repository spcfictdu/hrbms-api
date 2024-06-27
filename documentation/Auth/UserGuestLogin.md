### User Login

This endpoint allows users to log in and obtain an authentication token.

####  URL

```
{{base_url}}/api/user/guest/login
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
    "email": "1@gmail.com",
    "password": "developer"
}
```

#### Response Example (Success)

``` json
{
    "message": "Login successful",
    "results": {
        "firstName": "lex",
        "lastName": "csd",
        "email": "try@gmail.com",
        "token": "6|nNoOnOVKclqiD7dBbf81mop34EZmA539vUZpaSpNbd1611b5",
        "role": "GUEST"
    },
    "code": 200,
    "error": false
}
```
