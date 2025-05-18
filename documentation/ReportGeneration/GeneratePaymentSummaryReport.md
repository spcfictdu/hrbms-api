### Generate Payment Summary Report

This endpoint retrieves a report of total payments, broken down by payment type, during a selected date range.

####  URL

```
{{base_url}}/api/report/payment-summary
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

#### Request Body

``` 
"query": [
	{
		"key": "start",
		"value": "2025-04-28"
	},
    {
        "key": "end",
        "value": "2025-05-23"
    }
]
``` 

#### Response Example (Success)

``` json
{
    "summary": {
        "cash": 4520,
        "creditCard": 0,
        "gcash": 3180,
        "other": 0
    }
}
```
