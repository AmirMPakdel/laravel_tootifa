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

# DASHBOARD
            
## LOAD DASHBOARD MAIN INFO

**path**

    /dashboard/info/load

**format**

    P11UTA

**output**

    SUCCESS:{
        total_income:number,
        total_sell_count:number,
        total_courses_count:number,
        daily_cost:number,
        balance:number,
        remaining_days:number|n -> (null means forever)
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD DASHBOARD CHART 

**path**

    /Dashboard/chart/load'

**format**

    P11UTA

**input**

    filter:enum(icf_all|icf_year|icf_month|icf_week)

**output**

    SUCCESS:[
        created_at(date):price(number)
    ]

    

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD RECORDS

**path**

    /dashboard/records/load

**format**

    P11UTA

**input**

    filter:enum(rf_sells|rf_increase_m_balacne|rf_decrease_m_balacne)

**output**

    SUCCESS:array[Record]

**types**

```javascript
    def Record = { // when filter is rf_increase_m_balacne
        "id": number
        "created_at": date,
        "price": number,
    }

    def Record = { // when filter is rf_decrease_m_balacne
        "id": number,
        "created_at": date,
        "total_cost": number,
    }

    def Record = { // when filter is rf_sells
        "id": number,
        "created_at": date,
        "price": number,
        "title":string
    }

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD STUDENT TRANSACTION

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