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

# Minfo User Registration

## CHECK PHONE NUMBER

**path**

    /user/checkphonenumber

**format**

    P00MA

**input**

    phone_number:string

**output**

    SUCCESS:null

    REPETITIVE_PHONE_NUMBER:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOGIN WITH PASSWORD

**path**

    /user/login

**format**

    P00MA

**input**

    phone_number:string
    password:string

**output**

    SUCCESS:
    {
        token:string,
        username:string,
    }

    INVALID_PHONE_NUMBER:null

    INVALID_PASSWORD:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## SEND VERIICATION CODE

**path**

    /user/verificationcode/send

**format**

    P00MA

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

    /user/verificationcode/check

**format**

    P00MA

**input**

    code:string

**output**

    SUCCESS:
    {
       user_id:number
    }

    INVALID_VERIFICATION_CODE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CHECK TENANT

**path**

    /user/tenant/check/{user_name}

**format**

    G00MA

**input**

    user_name:string|ui

**output**

    SUCCESS:null

    REPETITIVE_USERNAME : null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## COMPLETE REGISTRATION

**path**

    /user/register

**format**

    P00MA

**input**

    national_code:string

    phone_number:string

    first_name:string

    last_name:string

    password:string

    user_name:string
    description: it's the generated tenant's id

    user_id:number

**output**

    SUCCESS:
    {
       token:string
    }

    REPETITIVE_NATIONAL_CODE:null

    REPETITIVE_USERNAME:null

    INVALID_ID:null
    description: when user_id not exist or incompatible with inserted phone_number

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

# Minfo User Panel

    Note1: These results are possible in all edit course routes in this section:
            1. NEGETIVE_MAINTANANCE_BALANCE
            2. COURSE_NOT_FOUND
            
## GET USER PROFILE

**path**

    /profile/load

**format**

    P11UTA

**output**

    SUCCESS:
    {
        first_name:string,
        last_name:string,
        email:string,
        address:string,
        phone_number:string,
        email: string,
        is_email_verified:number|b,
        m_balance:number,
        s_balance:number,
        bio:string ,
        holdable_test_count:number,
        infinit_test_finish_date:date|f:YYY-MM-DD,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING EDUCATOR

**path**

    /educators/create

**format**

    P11UTA

**input**

    first_name:string

    last_name:string

    bio:string|nr

    upload_key:string|nr

**output**

    SUCCESS:
    {
        educator_id:number
    }

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## UPDATING EDUCATOR

**path**

    /educators/update

**format**

    P11UTA

**input**

    educator_id:number

    first_name:string

    last_name:string

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    bio:string|nr

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

    NO_FILE_STATE:null

    INVALID_OLD_UPLOAD_KEY:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING EDUCATOR

**path**

    /educators/delete

**format**

    P11UTA

**input**

    educator_id:number

**output**

    SUCCESS:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCH EDUCATORS

**path**

    /educators/fetch

**format**

    P11UTA

**output**

    SUCCESS:Array[EducatorItem]

**types**

```javascript
    def EducatorItem = {
        "id":number,
        "first_name":string,
        "last_name":string,
        "bio":string,
        "image":string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING LEVEL ONE GROUP

**path**

    /levelonegroups/create

**format**

    P11UTA

**input**

    title:string

    type: enum("gt_course"|"gt_post")

**output**

    SUCCESS:{
        g1_id:number
    }

    REPETITIVE_TITLE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING LEVEL ONE GROUP

**path**

    /levelonegroups/edit

**format**

    P11UTA

**input**

    id:number

    title:string

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING LEVEL ONE GROUP

**path**

    /levelonegroups/delete

**format**

    P11UTA

**input**

    id:number

    force_delete:number|b

**output**

    SUCCESS:null

    RELATED_ENTITIES:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING LEVEL TWO GROUP

**path**

    /leveltwogroups/create

**format**

    P11UTA

**input**

    title:string

    g1_id:number

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:{
        g2_id:number
    }

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING LEVEL TWO GROUP

**path**

    /leveltwogroups/edit

**format**

    P11UTA

**input**

    id:number

    title:string

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING LEVEL TWO GROUP

**path**

    /leveltwogroups/delete

**format**

    P11UTA

**input**

    id:number

    force_delete:number|b

**output**

    SUCCESS:null

    RELATED_ENTITIES:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING LEVEL THREE GROUP

**path**

    /levelthreegroups/create

**format**

    P11UTA

**input**

    title:string

    g2_id:number

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:{
        g3_id:number
    }

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING LEVEL THREE GROUP

**path**

    /levelthreegroups/edit

**format**

    P11UTA

**input**

    id:number

    title:string

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING LEVEL THREE GROUP

**path**

    /levelthreegroups/delete

**format**

    P11UTA

**input**

    id:number

    force_delete:number|b

**output**

    SUCCESS:null

    RELATED_ENTITIES:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING TAG

**path**

    /tags/create

**format**

    P11UTA

**input**

    title:string

**output**

    SUCCESS:{
        tag_id:number
    }

    REPETITIVE_TITLE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING TAG

**path**

    /tags/edit

**format**

    P11UTA

**input**

    id:number

    title:string

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING TAG

**path**

    /tags/delete

**format**

    P11UTA

**input**

    id:number

    force_delete:number|b

**output**

    SUCCESS:null

    RELATED_ENTITIES:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCHING GROUPS

**path**

    /groups/fetch/{type}

**format**

    G10PTA

**input**

    type: enum("gt_course"|"gt_post")|ui

**output**

    SUCCESS:Array[LevelOneGroup]

**types**

```javascript
    def LevelOneGroup = {
        "level": 1,
        "id": number,
        "title" : string,
        "groups": Array[LevelTwoGroup]
    }

    def LevelTwoGroup = {
        "level": 2,
        "id": number,
        "title" : string,
        "groups": Array[LevelThreeGroup]
    }

    def LevelThreeGroup = {
        "level": 3,
        "id": number,
        "title" : string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCHING TAGS

**path**

    /tags/fetch

**format**

    G10PTA

**output**

    SUCCESS:Array[Tag]

**types**

```javascript
    def Tag = {
        "id": number,
        "title" : string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCHING CATEGORIES

**path**

    /categories/fetch

**format**

    G10PTA

**output**

    SUCCESS:Array[Category]

**types**

```javascript
    def Category = {
        "id": number,
        "title" : string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING COURSE

**path**

    /courses/create

**format**

    P11UTA

**input**

    title:string

    price:number

    is_encrypted:number|b

    groups:GroupInput

    tags:Array[numbers]
    decription: It's an array of selected tag ids

    educators:Array[numbers]
    decription: It's an array of educator ids

    category_id:number

**output**

    SUCCESS:{
        course_id:number
    }

    REPETITIVE_TITLE:null

    INVALID_GROUP_HIERARCHY:null

**types**

```javascript
    def GroupInput = {
        "g1": number,
        "g2": number,
        "g3" : number,
    }

    description: "You can't have lower group levels without specializing higher group ids"
    description: "set the g#level to null or empty string if it's not necessary"
    
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## REQUEST COURSE VALIDATION CHECK

**path**

    /user/course/validation/check

**format**

    P11MA

**input**

    course_id:number

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCH COURSES

**path**

    /courses/fetch/{chunk_count}/{page_count}

**format**

    P11UTA

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
        "sells":number,
        "score":number,
        "visits_count":number,
        "validation_status":enum(not_valid|is_checking|valid),
        "g1":number,
        "g2":number,
        "g3":number,
    }

    description: "You can't have lower group levels without specializing higher group ids"
    description: "set the group and searching_phrase to null or empty string if it's not necessary"

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCH SPECIAL COURSES

**path**

    /courses/fetch/specific

**format**

    P11UTA

**input**

    ids:Array(number)
    decription: It's an array of course ids

**output**

    SUCCESS:Array[CourseItem]

**types**

```javascript
    def CourseItem = {
        "id":number,
        "title":string,
        "price":number,
        "sells":number,
        "score":number,
        "visits_count":number,
        "validation_status":enum(not_valid|is_checking|valid),
        "g1":number,
        "g2":number,
        "g3":number,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD COURSE

**path**

    /course/load

**format**

    P11UTA

**input**

    course_id:number

**output**

    SUCCESS:CourseItem

**types**

```javascript
    def CourseItem = {
        "id":number,
        "title":string,
        "price":number,
        "sells":number,
        "score":number,
        "visits_count":number,
        "validation_status":enum(not_valid|is_checking|valid),
        "validation_status_message":string,
        "g1":number,
        "g2":number,
        "g3":number,
        "tags":Array[Tag],
        "duration":number|f:minutes,
        "has_discount":number|b,
        "discount":number|f:percent
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

## FETCH COURSE STUDENTS

**path**

    /course/students/fetch/{chunk_count}/{page_count}

**format**

    P11UTA

**input**

    course_id:number

    chunk_count:string|ui
    description: by what fraction devide the whole items (at least 1)

    page_count:string|ui
    description: which fraction of items to return (starts from 1)

**output**

    SUCCESS:Data

    NO_DATA:null

    COURSE_NOT_FOUND:null

**types**

```javascript
    def Data = {
        "total_size":number,
        "list":Array[Student]
    }

    def Student = {
        "id":number,
        "first_name":string,
        "last_name":string,
        "phone_number":string,
        "national_code":string,
        "access":number|b, 
    }
```
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## ADD COURSE STUDENT

**path**

    /course/students/add

**format**

    P11UTA

**input**

    course_id:number

    student_id:number

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## ADD COURSE STUDENTS

**path**

    /course/students/remove

**format**

    P11UTA

**input**

    course_id:number

    student_ids:Array(number)

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CHANGE COURSE STUDENTS ACCESS

**path**

    /course/students/changeaccess

**format**

    P11UTA

**input**

    access:number|b
    
    course_id:number

    student_ids:Array(number)

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## IMPORT COURSE STUDENTS EXCEL

**path**

    /course/students/importexcel

**format**

    P11UTA

**input**

    file:file
    description: the first column of excel file contains students' national_code
    
    course_id:number

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EXPORT COURSE STUDENTS EXCEL

**path**

    /course/students/exportexcel

**format**

    P11UTA

**input**
    
    course_id:number

**output**

    COURSE_NOT_FOUND:null

    SUCEESS: Starts downloading the excel file containing students information

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE COMMENTS AVAILABILITY

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_comments_availability)|ui

    course_id:number

    open:number|b

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE COMMENTS VALIDITY

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_comments_validity)|ui

    course_id:number

    valid:number|b

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE COVER

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_cover)|ui

    course_id:number

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

    INVALID_UPLOAD_KEY:null

    INVALID_OLD_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE LOGO

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_logo)|ui

    course_id:number

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

    INVALID_UPLOAD_KEY:null

    INVALID_OLD_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE DURATION

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_duration)|ui

    course_id:number

    duration:number|f:minutes

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE HOLDING STATUS

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_holding_status)|ui

    course_id:number

    status:enum(coming_soon|is_holding|finished)

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE LONG DESC

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_long_desc)|ui

    course_id:number

    desc:string

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE SHORT DESC

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_short_desc)|ui

    course_id:number

    desc:string

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE PRICE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_price)|ui

    course_id:number

    price:number

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE DISCOUNT PRICE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_discount_price)|ui

    course_id:number

    discount_price:number

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE RELEASE DATE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_release_date)|ui

    course_id:number

    date:string|f:yyyy-mm-dd

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE TITLE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_title)|ui

    course_id:number

    title:string

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE SUGGESTED COURSES

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_suggested_courses)|ui

    course_id:number

    ids:Array(number)
    decription: It's an array of course ids

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE SUGGESTED POSTS

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_suggested_posts)|ui

    course_id:number

    ids:Array(number)
    decription: It's an array of post ids

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE SUBJECTS

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_subjects)|ui

    course_id:number

    subjects:Array(string)

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE REQUIREMENT

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_requirement)|ui

    course_id:number

    requirements:Array(string)

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE GROUPS

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_groups)|ui

    course_id:number

    groups:GroupInput

**output**

    SUCCESS:null

    INVALID_VALUE:null

    INVALID_GROUP_HIERARCHY:null

    COURSE_NOT_FOUND:null

**types**

```javascript
    def GroupInput = {
        "g1": number,
        "g2": number,
        "g3" : number,
    }

    description: "You can't have lower group levels without specializing higher group ids"
    description: "set the g#level to null or empty string if it's not necessary" 
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE TAGS

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_tags)|ui

    course_id:number

    tags:Array[numbers]
    decription: It's an array of selected tag ids

**output**

    SUCCESS:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT VIDEO ADD

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_video_add)|ui

    course_id:number

    title:string

    is_free:number|b

    upload_key:string

**output**

    SUCCESS:{
        content_id:number
    }

    INVALID_VALUE:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT VIDEO UPDATE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_video_update)|ui

    course_id:number

    content_id:number

    title:string

    is_free:number|b

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

    INVALID_VALUE:null

    CONTENT_NOT_FOUND:null

    NO_FILE_STATE:null

    INVALID_OLD_UPLOAD_KEY:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT VIDEO DELETE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_video_delete)|ui

    course_id:number

    content_id:number

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT VOICE ADD

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_voice_add)|ui

    course_id:number

    title:string

    is_free:number|b

    upload_key:string

**output**

    SUCCESS:{
        content_id:number
    }

    INVALID_VALUE:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT VOICE UPDATE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_voice_update)|ui

    course_id:number

    content_id:number

    title:string

    is_free:number|b

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

    INVALID_VALUE:null

    CONTENT_NOT_FOUND:null

    NO_FILE_STATE:null

    INVALID_OLD_UPLOAD_KEY:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT VOICE DELETE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_voice_delete)|ui

    course_id:number

    content_id:number

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT DOCUMENT ADD

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_document_add)|ui

    course_id:number

    title:string

    is_free:number|b

    upload_key:string

**output**

    SUCCESS:{
        content_id:number
    }

    INVALID_VALUE:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT DOCUMENT UPDATE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_document_update)|ui

    course_id:number

    content_id:number

    title:string

    is_free:number|b

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

    INVALID_VALUE:null

    CONTENT_NOT_FOUND:null

    NO_FILE_STATE:null

    INVALID_OLD_UPLOAD_KEY:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT DOCUMENT DELETE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_document_delete)|ui

    course_id:number

    content_id:number

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT DOCUMENT ADD

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_document_add)|ui

    course_id:number

    title:string

    is_free:number|b

    upload_key:string

**output**

    SUCCESS:{
        content_id:number
    }

    INVALID_VALUE:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT DOCUMENT UPDATE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_document_update)|ui

    course_id:number

    content_id:number

    title:string

    is_free:number|b

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

    INVALID_VALUE:null

    CONTENT_NOT_FOUND:null

    NO_FILE_STATE:null

    INVALID_OLD_UPLOAD_KEY:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT DOCUMENT DELETE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_document_delete)|ui

    course_id:number

    content_id:number

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE INTRO VIDEO ADD

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_intro_video_add)|ui

    course_id:number

    upload_key:string

**output**

    SUCCESS:{
        course_introduction_id:number
    }

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE INTRO VIDEO UPDATE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_intro_video_update)|ui

    course_id:number

    intro_id:number

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

    INVALID_VALUE:null

    CONTENT_NOT_FOUND:null

    NO_FILE_STATE:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE INTRO VIDEO DELETE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_intro_video_delete)|ui

    course_id:number

    intro_id:number

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE HEADING ADD

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_course_heading_add)|ui

    course_id:number

    title:string

**output**

    SUCCESS:{
        heading_id:number
    }

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE HEADING UPDATE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_course_heading_update)|ui

    course_id:number

    heading_id:number

    title:string

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

    INVALID_VALUE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE HEADING DELETE

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_course_heading_delete)|ui

    course_id:number

    heading_id:number

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE CONTENT HIERARCHY

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_hierarchy)|ui

    hierarchy:Array[H_object]

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

    INVALID_VALUE:null

**types**

```javascript
    def H_object = {
        "heading":number,
        "contents": Array[number], 
    }

    description: "heading is equivalent to heading id"
    description: "contents contain content_ids under the heading"
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT COURSE EDUCATORS

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_course_educators)|ui

    educators:Array[number]
    decription: It's an array of educator ids

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

    INVALID_VALUE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::