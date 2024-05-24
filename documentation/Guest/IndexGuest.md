### Index Guest

This endpoint is used to get the list of all guests.

#### URL

```
{{base_url}}/api/guest
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
            "id": 1,
            "referenceNumber": "64065b9b",
            "fullName": "LITTEL, ANSEL LIND",
            "email": "mpurdy@yahoo.com",
            "phone": "2691752667"
        },
        {
            "id": 2,
            "referenceNumber": "5dd2c2a5",
            "fullName": "LARKIN, FRANKIE PFANNERSTILL",
            "email": "kuhlman.jimmy@haley.info",
            "phone": "5458958787"
        },
        {
            "id": 3,
            "referenceNumber": "b0d7395f",
            "fullName": "MARQUARDT, LELIA BEATTY",
            "email": "otto69@gmail.com",
            "phone": "9470218251"
        },
        ...
    ],
    "code": 200,
    "error": false
}
```
