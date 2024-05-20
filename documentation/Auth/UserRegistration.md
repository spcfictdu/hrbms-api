### User Registration

This endpoint allows the user to register a new account.

#### URL

```
{{base_url}}/api/user/register
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
```

#### Request Body

``` json
{
    "username": "frontdesk",
    "firstName": "JANE",
    "lastName": "DOE",
    "email": "sample@gmail.com",
    "password": "developer",
    "role": "FRONT DESK"
}
``` 

#### Response Example (Success)

``` json
{
    "message": "User created successfully",
    "results": {
        "username": "frontdesk",
        "firstName": "JANE",
        "lastName": "DOE",
        "email": "sample@gmail.com",
        "role": "FRONT DESK"
    },
    "code": 200,
    "error": false
}
```
