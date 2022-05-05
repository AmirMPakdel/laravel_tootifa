# Info

## Notes

    * Whenever there is a token input, there could be INVALID_TOKEN error

## RequestType Description

    RequestType : {Method}{Tenant}{Token}{Prefix}

    Method:
        can be "P" or "G"
        "P" means its POST method
        "G" means its GET method

    Tenant:
        can be "0" or "1"
        "0" means no need for X-TENANT in header
        "1" means it needs X-TENANT in header

    Token:
        can be "0" or "1"
        "0" means no need for token in body
        "1" means it needs token in body

    Prefix:
        can be one the values in Prefixes section below

    example:
        P01MA -> its POST method that needs tenant to be specified
                but no need for token base authentication and path
                prefix starts with /api/main/...

## Prefixes

    MA -> api/main
    UTA -> api/tenant/user
    PTA -> api/tenant/public
    PSTA -> api/tenant/student/public
    STA -> api/tenant/student
    AA -> api/app

## Input/Output Flags

    flags:
        nr -> input not required
        ui -> url input
        f:### -> format
        b -> boolean

    example:
        date:string|nr|f:YYY/MM/DD
        

## FETCH STORE COURSES 

**path**

    /store/fetch/{chunk_count}/{page_count}

**format**

    P11PSTA

**input**

    filters:Filter|nr

    sorting_mode:enum(
        sm_most_visits|
        sm_least_visits|
        sm_most_sells|
        sm_least_sells|
        sm_newest|
        sm_oldest|
        sm_lowest_price|
        sm_highest_price|
    )|nr

    chunk_count:string|ui
    description: by what fraction devide the whole items (at least 1)

    page_count:string|ui
    description: which fraction of items to return (starts from 1)

**output**

    SUCCESS:Data

    NO_DATA:null

**types**

```javascript
    def Data = {
        "total_size":number,
        "list":Array[CourseItem]
    }

    def Filter = {
        "searching_phrase":string,
        "group":Group
    }

    def Group = {
        "level":enum(1|2|3),
        "id":number
    }

    def CourseItem = {
        "id":number,
        "title":string,
        "price":number,
        "discount_price":number,
        "sells":number,
        "score":number,
        "visits_count":number,
        "validation_status":enum(not_valid|is_checking|valid),
        "g1":number,
        "g2":number,
        "g3":number,
        "logo":string,
        "educators_name":Array[string],
    }

    description: "You can't have lower group levels without specializing higher group ids"
    description: "set the group and searching_phrase to null or empty string if it's not necessary"

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::