### Index Room Type Rate

This endpoint retrieves the list of all room type rates.

####  URL

```
{{base_url}}/api/room-type/rate
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

``` json
{
    "message": "List of room type with their regular and special rates.",
    "results": [
        {
            "roomType": {
                "referenceNumber": "5d09bb11",
                "name": "JUNIOR STANDARD"
            },
            "rates": {
                "regular": {
                    "referenceNumber": "7e8fecb2",
                    "monday": 1340,
                    "tuesday": 1340,
                    "wednesday": 1340,
                    "thursday": 1340,
                    "friday": 1340,
                    "saturday": 1440,
                    "sunday": 1440
                },
                "special": []
            }
        },
        {
            "roomType": {
                "referenceNumber": "322bae76",
                "name": "STANDARD"
            },
            "rates": {
                "regular": {
                    "referenceNumber": "1278b600",
                    "monday": 1444,
                    "tuesday": 1444,
                    "wednesday": 1444,
                    "thursday": 1444,
                    "friday": 1444,
                    "saturday": 1544,
                    "sunday": 1544
                },
                "special": []
            }
        },
        {
            "roomType": {
                "referenceNumber": "77eadf22",
                "name": "JUNIOR SUITE"
            },
            "rates": {
                "regular": {
                    "referenceNumber": "47f7e259",
                    "monday": 1754,
                    "tuesday": 1754,
                    "wednesday": 1754,
                    "thursday": 1754,
                    "friday": 1754,
                    "saturday": 1854,
                    "sunday": 1854
                },
                "special": []
            }
        },
        {
            "roomType": {
                "referenceNumber": "356a7691",
                "name": "SUITE"
            },
            "rates": {
                "regular": {
                    "referenceNumber": "6fe200e2",
                    "monday": 1858,
                    "tuesday": 1858,
                    "wednesday": 1858,
                    "thursday": 1858,
                    "friday": 1858,
                    "saturday": 1958,
                    "sunday": 1958
                },
                "special": []
            }
        },
        {
            "roomType": {
                "referenceNumber": "d5922c1b",
                "name": "SUPERIOR"
            },
            "rates": {
                "regular": {
                    "referenceNumber": "e328b6bc",
                    "monday": 2173,
                    "tuesday": 2173,
                    "wednesday": 2173,
                    "thursday": 2173,
                    "friday": 2173,
                    "saturday": 2273,
                    "sunday": 2273
                },
                "special": []
            }
        },
        {
            "roomType": {
                "referenceNumber": "0185739a",
                "name": "DELUXE"
            },
            "rates": {
                "regular": {
                    "referenceNumber": "d149c3f8",
                    "monday": 1547,
                    "tuesday": 1547,
                    "wednesday": 1547,
                    "thursday": 1547,
                    "friday": 1547,
                    "saturday": 1647,
                    "sunday": 1647
                },
                "special": [
                    {
                        "referenceNumber": "5558d1c8",
                        "discountName": "May Discount",
                        "startDate": "2024-05-01",
                        "endDate": "2024-05-31",
                        "monday": 1447,
                        "tuesday": 1447,
                        "wednesday": 1447,
                        "thursday": 1447,
                        "friday": 1447,
                        "saturday": 1547,
                        "sunday": 1547
                    }
                ]
            }
        }
    ],
    "code": 200,
    "error": false
}
```
