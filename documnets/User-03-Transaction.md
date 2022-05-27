# USER TANSACTION PORTALS

## FETCH PORTAL LIST

**path**

    /portals/get

**format**

    G00MA

**output**

    SUCCESS:
    {
        id:number,
        title:string,
        logo:string,
        name:string,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

# USER TRANSACTION PROCESS

## GENERATE TRANSACTION INVOICE

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
        name:string
        ref_id:string,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## PAY FOR INVOICE

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

# USER LOAD TRANSACTION RESULT

## LOAD CREDIT PURCHASE TRANSACTION RESULT

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
        name:string,
        date:timestamp
        error_msg:string
        ref_id:string,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::




## LOAD COURSE PURCHASE TRANSACTION RESULT

**path**

    /dashboard/student_transaction/load

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
