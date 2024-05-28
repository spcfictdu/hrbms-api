# Guest Enum

This endpoint is used for dropdowns in the frontend to display the guest names.

#### URL

```
{{base_url}}/api/enum/guest
```

#### Method

```
GET
```

#### Authentication Needed

```
FALSE
```

#### Permitted Roles

```
ALL
```

#### Response Example (Success)

```json
{
    "message": "Guests retrieved successfully.",
    "results": [
        {
            "id": 1,
            "referenceNumber": "64065b9b",
            "fullName": "LITTEL, ANSEL LIND"
        },
        {
            "id": 2,
            "referenceNumber": "5dd2c2a5",
            "fullName": "LARKIN, FRANKIE PFANNERSTILL"
        },
        {
            "id": 3,
            "referenceNumber": "b0d7395f",
            "fullName": "MARQUARDT, LELIA BEATTY"
        },
        ...
    ],
    "code": 200,
    "error": false
}
```