# User Authentication

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

## SEND VERIFICATION CODE

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

## CHECK VERIFICATION CODE

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

# PASSWORD RESET

## REQUEST PASSWORD REST

**path**

    /user/passwordreset/request

**format**

    P00MA

**input**

    phone_number:string

**output**

    SUCCESS:null

    USER_NOT_FOUND:null

    PASSWORD_RESET_REQUEST_LIMIT_ERROR:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## PASSWORD REST CHECK TOKEN

**path**

    /user/passwordreset/checktoken

**format**

    P00MA

**input**

    token:string

**output**

    SUCCESS:null

    INVALID_TOKEN:null

    PASSWORD_RESET_REQUEST_LIMIT_ERROR:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## RESET PASSWORD

**path**

    /user/passwordreset/reset

**format**

    P00MA

**input**

    token:string

    password:string

**output**

    SUCCESS:null

    INVALID_TOKEN:null

    USER_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
