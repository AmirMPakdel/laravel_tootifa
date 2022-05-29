# USER BASE-INFO
            
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
        total_saved_income:number,
        account_owner_first_name:string,
        account_owner_last_name:string,
        bank:string,
        account_number:string,
        shaba_number:string,
        credit_cart_number:string,
        national_cart_image:string|upload_key,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## UPDATE USER PROFILE

**path**

    /profile/update

**format**

    P11UTA

**input**

    first_name:string

    last_name:string

    email:string

    national_code:string

    bio:string

    state:string

    city:string

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr 
    description: it is required when file_state is ufs_new or ufs_replace
    description: it is for national_cart_image

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## UPDATE USER BANK INFO

**path**

    /profile/update/bank_info

**format**

    P11UTA

**input**

    account_owner_first_name:string

    account_owner_last_name:string

    bank:string

    account_number:string

    shaba_number:string

    credit_cart_number:string

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

# USER DASHBOARD OVERVIEW

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

# USER DASHBOARD FINANCIALS

## LOAD RECORDS

**path**

    /dashboard/records/load/{chunk_count}/{page_count}

**format**

    P11UTA

**input**

    filter:enum(rf_sells|rf_increase_m_balacne|rf_decrease_m_balacne)

    chunk_count:string|ui
    description: by what fraction devide the whole items (at least 1)

    page_count:string|ui
    description: which fraction of items to return (starts from 1)

**output**

    SUCCESS:Data

**types**

```javascript
    def Data = {
        "total_size":number,
        "list":Array[Record]
    }

    def Record = { // when filter is rf_increase_m_balacne
        "id": number
        "created_at": date,
        "price": number,
        "title":string
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
