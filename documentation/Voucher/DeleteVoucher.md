### Delete Voucher

This endpoint is used to is used to delete a specific voucher using the provided reference number of the voucher.

#### URL

```
{{base_url}}/api/voucher/delete/{voucherReferenceNumber}
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
    "message": "Voucher deleted successfully.",
    "results": [],
    "code": 200,
    "error": false
}
```
