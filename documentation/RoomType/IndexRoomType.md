### Index Room Type

This endpoint retrieves the list of all room types.

####  URL

```
{{base_url}}/api/room-type/
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
    "message": "List of all room types.",
    "results": [
        {
            "referenceNumber": "5d09bb11",
            "name": "JUNIOR STANDARD",
            "description": "This single room has a tile/marble floor, cable TV and air conditioning.",
            "capacity": 2
        },
        {
            "referenceNumber": "322bae76",
            "name": "STANDARD",
            "description": "This modern room comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom.",
            "capacity": 2
        },
        {
            "referenceNumber": "77eadf22",
            "name": "JUNIOR SUITE",
            "description": "This large Jr. Suite comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom. Jr. Suite features a sofa seating area.",
            "capacity": 2
        },
        {
            "referenceNumber": "356a7691",
            "name": "SUITE",
            "description": "This spacious suite comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom. Suite features a separate dining area and sofa seating area.",
            "capacity": 2
        },
        {
            "referenceNumber": "d5922c1b",
            "name": "SUPERIOR",
            "description": "This spacious suite comes with a flat-screen cable TV, work space and mini-fridge. Free toiletries and a bidet are included in the private bathroom. Suite features a separate dining area and sofa seating area.",
            "capacity": 2
        }
    ],
    "code": 200,
    "error": false
}
```
