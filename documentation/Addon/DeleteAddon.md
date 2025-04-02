### Delete Addon

This endpoint is used to is used to delete a specific addon using the provided reference number of the addon.

#### URL

```
{{base_url}}/api/addon/delete/{addonReferenceNumber}
```   

#### Method
```
DELETE
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
    "message": "addon deleted successfully.",
    "results": [],
    "code": 200,
    "error": false
}
```
