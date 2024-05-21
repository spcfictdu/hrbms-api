### Index Guest

This endpoint is used to get the list of all guests.

#### URL

```
{{base_url}}/api/guests
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
FRONT DESK
```

#### Query Parameters

-   firstName : STRING (optional) - The first name of the guest
-   middleName : STRING (optional) - The middle name of the guest
-   lastName : STRING (optional) - The last name of the guest
-   email : STRING (optional) - The email of the guest
-   phone : STRING (optional) - The phone number of the guest

#### Response Example (Success)

```json
{
    "message": "Successfully retrieved guests",
    "results": [
        {
            "id": 2,
            "fullName": "FAHEY, LIBBY WITTING ",
            "email": "zprice@west.info",
            "phone": "4153120367"
        },
        {
            "id": 3,
            "fullName": "KERTZMANN, ELBERT GREENFELDER ",
            "email": "kathryn89@mccullough.info",
            "phone": "3757448793"
        },
        {
            "id": 4,
            "fullName": "MORISSETTE, DAHLIA THIEL ",
            "email": "brakus.orlando@yahoo.com",
            "phone": "3984082821"
        },
        {
            "id": 5,
            "fullName": "STEUBER, INES LAKIN ",
            "email": "pascale66@effertz.com",
            "phone": "9989655340"
        }
    ],
    "code": 200,
    "error": false
}
```
