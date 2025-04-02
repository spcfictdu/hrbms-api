### Index Discount

This endpoint retrieves the list of all discounts.

#### URL

```
{{base_url}}/api/discount/
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
```

#### Response Example (Success)

```json

{
    "message": "list of all discounts",
    "results": [
        {
            "name": "PWD",
            "discount": "10%"
        },
        {
            "name": "SNR",
            "discount": "10%"
        },
        {
            "name": "VOUCHER",
            "discount": "10%"
        }
    ],
    "code": 200,
    "error": false
}
```
