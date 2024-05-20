### Update Amenity

This endpoint is used to update an amenity using the provided reference number of the amenity.

#### URL

```
{{base_url}}/api/amenity/update/{amenityReferenceNumber}
```   

#### Method
```
PUT
```

#### Authentication Needed
```
TRUE
```

#### Permitted Roles
```
ADMIN
```

#### Request Body

``` json
{
    "name": "FREE BREAKFAST AND LUNCH"
}
``` 

#### Response Example (Success)

``` json
{
    "message": "Amenity updated successfully.",
    "results": {
        "referenceNumber": "61956e",
        "name": "FREE BREAKFAST AND LUNCH"
    },
    "code": 200,
    "error": false
}
```
