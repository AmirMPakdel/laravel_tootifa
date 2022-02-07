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

# User Transactions
            
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
        total_saved_income:number 
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## GENERATE TRANSACTION

**path**

    /transaction/generate

**format**

    P11UTA

**input**

    title:string

    price:number

    pt:enum(pt_incremental|pt_activation)

    prt:enum(prt_sms|prt_maintenance|prt_test)

    value:number

    days:number

    portal:enum(zarinpal)

    redirect_url:string

**output**

    SUCCESS:
    {
        id:number,
        title:string,
        price:number,
        pt:enum(pt_incremental|pt_activation),
        prt:enum(prt_sms|prt_maintenance|prt_test),
        value:number,
        days:number
        portal:enum(zarinpal),
        redirect_url:string,
        success:number,
        ref_id:string,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## GET TRANSACTION

**path**

    /transaction/get

**format**

    P11UTA

**input**

    transaction_id:number

**output**

    SUCCESS:
    {
        id:number,
        title:string,
        price:number,
        pt:enum(pt_incremental|pt_activation),
        prt:enum(prt_sms|prt_maintenance|prt_test),
        value:number,
        days:number
        portal:enum(zarinpal),
        redirect_url:string,
        success:number,
        ref_id:string,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## PAY FOR PRODUCT

**path**

    /product/pay

**format**

    transaction_id:number

    G11UTA 

**input**

    * description: send inputs (tenant and token) as query params

**output**

    SUCCESS: redirects to the portal


::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
