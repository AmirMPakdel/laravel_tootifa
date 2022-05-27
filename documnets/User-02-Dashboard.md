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
    }

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
