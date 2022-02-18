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

# Student Registration

## CHECK PHONE NUMBER

**path**

    /checkphonenumber

**format**

    P10PSTA

**input**

    phone_number:string

**output**

    SUCCESS:null

    REPETITIVE_PHONE_NUMBER:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOGIN WITH PASSWORD

**path**

    /login

**format**

    P10PSTA

**input**

    phone_number:string
    password:string

**output**

    SUCCESS:
    {
        token:string,
    }

    INVALID_PHONE_NUMBER:null

    INVALID_PASSWORD:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## SEND VERIICATION CODE

**path**

    /verificationcode/send

**format**

    P10PSTA

**input**

    phone_number:string

**output**

    SUCCESS:null

    USER_ALREADY_VERIFIED:null

**notes**

    * Verification code is always set to 1111 in test mode, in production mode it would be sent via sms

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CHECK VERIICATION CODE

**path**

    /verificationcode/check

**format**

    P10PSTA

**input**

    code:string
    phone_number:string

**output**

    SUCCESS:
    {
       student_id:number
    }

    INVALID_VERIFICATION_CODE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## COMPLETE REGISTRATION

**path**

    /register

**format**

    P10PSTA

**input**

    national_code:string

    phone_number:string

    first_name:string

    last_name:string

    password:string

    student_id:number

**output**

    SUCCESS:
    {
       token:string
    }

    REPETITIVE_NATIONAL_CODE:null

    INVALID_ID:null
    description: when user_id not exist or incompatible with inserted phone_number

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

# Student Profile Panel
            
## GET STUDENT PROFILE

**path**

    /profile/load

**format**

    P11STA

**output**

    SUCCESS:
    {
        first_name:string,
        last_name:string,
        email:string,
        phone_number:string,
        state: string,
        city:string
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## UPDATE STUDENT PROFILE

**path**

    /profile/update

**format**

    P11STA

**input**

    first_name:string

    last_name:string

    email:string

    phone_number:string

    state: string

    city:string

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FECH STUDENT REGISTERED COURSES

**path**

    /courses/fetch

**format**

    P11STA

**output**

    SUCCESS:Array[CourseItem]

**types**

```javascript

    def CourseItem = {
        "id":number,
        "title":string,
        "is_online":number|b,
    }

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD COURSE

**path**

    /course/load

**format**

    P11STA

**input**

    course_id:number

**output**

    SUCCESS:CourseItem

**types**

```javascript
    def CourseItem = {
        "id":number,
        "registered":number,
        "has_access":number,
        "title":string,
        "price":number,
        "sells":number,
        "score":number,
        "visits_count":number,
        "g1":number,
        "g2":number,
        "g3":number,
        "tags":Array[Tag],
        "duration":number|f:minutes,
        "is_online":number|b,
        "discount_price":number,
        "holding_status":enum(coming_soon|is_holding|finished),
        "release_date":string|f:yyyy-mm-dd,
        "subjects":Array(string),
        "short_desc":string,
        "long_desc":string,
        "requirements":Array(string),
        "suggested_courses":Array(number),  "decription: It's an array of course ids"
        "suggested_posts":Array(number),  "decription: It's an array of post ids"
        "is_encrypted":number|b,
        "intro_video":IntroVideo,
        "content_hierarchy":string,
        "is_comments_open":number|b
        "headings":Array[Heading],
        "contents":Array[Content],
        "educators":Array[Educator],
        "logo":string, "decription: upload_key"
        "cover":string, "decription: upload_key"
    }

    def Tag = {
        "id":number,
        "title":string,
    }

    def IntroVideo = {
        "id":number,
        "url":string,
        "size":number,
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

    def Educator = {
        "id":number,
        "first_name":string,
        "last_name":string,
        "bio":string,
        "image":string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FECH STUDENT FAVORITE COURSES

**path**

    /courses/favorite

**format**

    P11STA

**output**

    SUCCESS:Array[CourseItem]

**types**

```javascript

    def CourseItem = {
        "id":number,
        "title":string,
        "is_online":number|b,
    }

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## ADD STUDENT FAVORITE COURSE

**path**

    /course/favorite/add

**format**

    P11STA

**input**

    course_id:number

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## REMOVE STUDENT FAVORITE COURSE

**path**

    /course/favorite/remove

**format**

    P11STA

**input**

    course_id:number

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## GET COURSE SCORE (SET BY STUDENT)

**path**

    /course/score/get

**format**

    P11STA

**output**

    SUCCESS:number (between 0 to 5, if its 0 use course score attribute)


::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## UPDATE COURSE SCORE 

**path**

    /course/score/update

**format**

    P11STA

**output**

    SUCCESS:null (it affects course score attribute)

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::