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
        is_email_verified:number,
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

    file_state:string
    description: find the default states in Constants file

    bio:string|nr

    upload_key:string|nr
    description: it is required when there is a new file to be moved to ftp

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

## CREATING LEVEL ONE GROUP

**path**

   /levelonegroups/create

**format**

    P11UTA

**input**

    title:string

    type:string
    description: find the default types in Constants file

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

    type:string
    description: find the default types in Constants file

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null
    
    GROUP_NOT_EXISTS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING LEVEL ONE GROUP

**path**

   /levelonegroups/delete

**format**

    P11UTA

**input**

    id:number 

    force_delete (0,1):number

**output**

    SUCCESS:null

    RELATED_ENTITIES:null
    
    GROUP_NOT_EXISTS:null
    
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING LEVEL TWO GROUP

**path**

    /leveltwogroups/create

**format**

    P11UTA

**input**

    title:string

    g1_id:number

    type:string
    description: find the default types in Constants file

**output**

    SUCCESS:{
        g2_id:number
    } 

    REPETITIVE_TITLE:null

    GROUP_NOT_EXISTS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING LEVEL TWO GROUP

**path**

   /leveltwogroups/edit

**format**

    P11UTA

**input**

    id:number 

    title:string

    type:string
    description: find the default types in Constants file

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null
    
    GROUP_NOT_EXISTS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING LEVEL TWO GROUP

**path**

    /leveltwogroups/delete

**format**

    P11UTA

**input**

    id:number 

    force_delete (0,1):number

**output**

    SUCCESS:null

    RELATED_ENTITIES:null
    
    GROUP_NOT_EXISTS:null
    
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING LEVEL THREE GROUP

**path**

    /levelthreegroups/create

**format**

    P11UTA

**input**

    title:string

    g2_id:number

    type:string
    description: find the default types in Constants file

**output**

    SUCCESS:{
        g3_id:number
    } 

    REPETITIVE_TITLE:null

    GROUP_NOT_EXISTS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING LEVEL THREE GROUP

**path**

   /levelthreegroups/edit

**format**

    P11UTA

**input**

    id:number 

    title:string

    type:string
    description: find the default types in Constants file

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null
    
    GROUP_NOT_EXISTS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING LEVEL THREE GROUP

**path**

    /levelthreegroups/delete

**format**

    P11UTA

**input**

    id:number 

    force_delete (0,1):number

**output**

    SUCCESS:null

    RELATED_ENTITIES:null
    
    GROUP_NOT_EXISTS:null

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
    
    TAG_NOT_EXIST:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING TAG

**path**

   /tags/delete

**format**

    P11UTA

**input**

    id:number 

    force_delete (0,1):number

**output**

    SUCCESS:null

    RELATED_ENTITIES:null
    
    TAG_NOT_EXIST:null
    
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCHING GROUPS

**path**

   /groups/fetch

**format**

    G10PTA

**input**

    type:string 

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

## CREATING COURSE

**path**

   /tags/create

**format**

    P11UTA

**input**

    title:string

    price:number

    is_encrypted:number

    groups:GroupInput

    tags:Array[numbers]
    decription: It's an array of selected tag id's

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
        "g1": number|nr,
        "g2": number|nr,
        "g3" : number|nr,
    }

    description: "You can't have lower group levels without specializing higher group ids"
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
