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

# COURSE SECTION

## REGISTER COURSE IN DEVICE

**path**

    /course/register

**format**

    P00AA

**input**

    lk:string

    device_info:DeviceInfo

**output**

    SUCCESS:{
        user_info:UserInfo,
        student_id:number,
        course:Course
    }

    USER_NOT_FOUND:null

    LISCENSE_KEY_NOT_FOUND:null

    DEVICE_LIMIT:null

    COURSE_NOT_VALID:null

**types**

```javascript
    def DeviceInfo = {
        "imei": string,
        "model": string,
        "android_version" : string,
        "android_api_level" : number,
    }

    def UserInfo = {
        "username": string,
        "domain": string,
        "title" : string,
    }

    def Course = {
        "id":number,
        "has_access":number|b,
        "title":string,
        "is_encrypted":number|b,
        "headings":Array[Heading],
        "contents":Array[Content],
        "content_hierarchy":Array(HierarchyObj),
        "logo":string, "decription: upload_key.file_type"
        "cover":string, "decription: upload_key.file_type"
    }

    def Heading = {
        "id":number,
        "title":string,
    }

    def Content = {
        "id":number,
        "url":string,
        "title":string,
        "type":enum("ct_video"|"ct_document"|"ct_voice"),
        "is_free":number|b,
        "size":number,
    }

    def HierarchyObj = {
        'heading_id':number
        'content_ids':Array(number)
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD COURSES 

**path**

    /courses/load

**format**

    P00AA

**input**

    imei:string

    keys:Array[Key]

**output**

    SUCCESS:{
        course:Array[Course]
    }

**types**

```javascript
    def Key = {
        "username": string,
        "lk": string,
    }

    def Course = {
        "id":number,
        "has_access":number|b,
        "title":string,
        "is_encrypted":number|b,
        "headings":Array[Heading],
        "contents":Array[Content],
        "content_hierarchy":string,
        "logo":string, "decription: upload_key"
        "cover":string, "decription: upload_key"
    }

    description: "Each key in keys array corresponds with it's respective course in courses array which have the same indexes"

    description: "Instead of a respective course, there could be one of the error codes bewlow:"
                 "USER_NOT_FOUND|LISCENSE_KEY_NOT_FOUND|DEVICE_NOT_FOUND|COURSE_NOT_VALID"

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CHECK VERSION 

**path**

    /courses/load

**format**

    P00AA

**input**

    username:string|nr 

    platform:string

    app_version:number -> it's version-code

**output**

    SUCCESS:{
        course:null
    }

    SHOULD_UPDATE:{
        version_name:string,
        version_code:number,
        last_changes_list:Array[string],
        must:number,
        url:string
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
