### Generate Daily Reservations Report

This endpoint retrieves a report of which rooms are occupied, reserved, available, or under maintenance at the current date.

####  URL

```
{{base_url}}/api/report/room-occupancy
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


#### Response Example (Success)

``` json
{
    "message": "Room occupancy report generated successfully.",
    "data": {
        "occupied": [],
        "reserved": [],
        "available": [
            {
                "roomNumber": 101
            },
            {
                "roomNumber": 102
            },
            {
                "roomNumber": 103
            },
            {
                "roomNumber": 104
            },
            {
                "roomNumber": 105
            },
            {
                "roomNumber": 106
            },
            {
                "roomNumber": 107
            },
            {
                "roomNumber": 108
            },
            {
                "roomNumber": 109
            },
            {
                "roomNumber": 110
            },
            {
                "roomNumber": 111
            },
            {
                "roomNumber": 112
            },
            {
                "roomNumber": 113
            },
            {
                "roomNumber": 114
            },
            {
                "roomNumber": 115
            },
            {
                "roomNumber": 116
            },
            {
                "roomNumber": 117
            },
            {
                "roomNumber": 118
            },
            {
                "roomNumber": 119
            },
            {
                "roomNumber": 120
            }
        ],
        "unallocated": [],
        "unclean": []
    }
}
```
