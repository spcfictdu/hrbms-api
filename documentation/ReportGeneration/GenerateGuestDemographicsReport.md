### Generate Guest Demographics Report

This endpoint shows guest counts by location during a selected date range.

####  URL

```
{{base_url}}/api/report/guest-demographics
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
		"value": "2025-05-29"
	},
	{
		"key": "end",
		"value": "2025-06-02"
	}
]
``` 

#### Response Example (Success)

``` json
{
    "byProvince": {
        "ARKANSAS": 1,
        "NEW MEXICO": 1,
        "UTAH": 1,
        "NEW YORK": 1,
        "VIRGINIA": 1
    },
    "byCity": {
        "WISOZKBURY": 1,
        "WEST KAVONBURGH": 1,
        "PORT ENOLAVILLE": 1,
        "BRIELLEHAVEN": 1,
        "MALLIEBURY": 1
    }
}
```
