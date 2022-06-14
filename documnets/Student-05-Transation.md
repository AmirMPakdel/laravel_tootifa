## GENERATE TRANSACTION

**path**

    /transaction/generate

**format**

    P11STA

**input**

    title:string

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

## GET TRANSACTION LIST

**path**

    /transaction/get/list

**format**

    P11STA

**input**

    transaction_id:number

**output**

    SUCCESS:Array[
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
    ]

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
