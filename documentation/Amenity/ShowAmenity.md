### Show Amenity

This endpoint retrieves information about a specific amenity using the provided reference number of the amenity.

#### URL

```
{{base_url}}/api/amenity/{amenityReferenceNumber}
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
    "message": "Amenity found.",
    "results": {
        "referenceNumber": "de69e9",
        "name": "AIR CONDITIONING"
    },
    "code": 200,
    "error": false
}
```
