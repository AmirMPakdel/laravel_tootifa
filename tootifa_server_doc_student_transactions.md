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


# Student Transactions

## GENERATE TRANSACTION

**path**

    /transaction/generate

**format**

    P11STA

**input**

    title:string

    price:number

    course_id:number

    course_title:string

    portal:enum(zarinpal)

    redirect_url:string

**output**

    SUCCESS:
    {
        id:number,
        title:string,
        price:number,
        course_id:number,
        course_title:string
        portal:enum(zarinpal),
        redirect_url:string,
        success:number,
        name:string
        ref_id:string,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## GET TRANSACTION

**path**

    /transaction/get

**format**

    P11STA

**input**

    transaction_id:number

**output**

    SUCCESS:
    {
        id:number,
        title:string,
        price:number,
        course_id:number,
        course_title:string
        portal:enum(zarinpal),
        redirect_url:string,
        success:number,
        name:string,
        date:timestamp
        error_msg:string
        ref_id:string,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## PAY FOR COURSE

**path**

    /course/pay

**format**

    transaction_id:number

    G11STA 

**input**

    * description: send inputs (tenant and token) as query params

**output**

    SUCCESS: redirects to the portal


::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
