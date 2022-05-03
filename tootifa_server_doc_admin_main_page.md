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

# Edit Mainpage
            
## EDIT MAINPAGE TITLE

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_title)|ui

    title:string

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT MAINPAGE STOREOPEN

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_param_store_open)|ui

    store_open:number

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT MAINPAGE FOOTER LINKS

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_footer_links)|ui

    links:json

    telephone:json

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT MAINPAGE COVER

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_cover)|ui

    title:string

    text:string

    has_link:number

    link:string

    link_title:string

    template:number

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT MAINPAGE LOGO

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_logo)|ui

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT MAINPAGE BOXINFO ADD

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_main_box_info_add)|ui

    title:string

    text:string

    has_link:number

    link:string

    link_title:string

    type:enum(ct_video|ct_image|ct_none)

    visible:number

    upload_key:string

**output**

    SUCCESS:{
        content_id:number
    }

    INVALID_VALUE:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT MAINPAGE BOXINFO UPDATE

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_main_box_info_add)|ui

    content_id:number

    title:string

    text:string

    has_link:number

    link:string

    link_title:string

    type:enum(ct_video|ct_image|ct_none)

    visible:number

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

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT MAINPAGE BOXINFO DELETE

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_main_box_info_delete)|ui

    content_id:number

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT MAINPAGE COURSE LIST ADD

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_course_list_add)|ui

    title:string

    list:Array[number]

    default_type:enum("dt_most_visited"|"dt_most_sell"|"dt_most_score"|"dt_most_newest")

    groups:GroupInput

    visible:number

**output**

    SUCCESS:{
        list_id:number
    }

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

## EDIT MAINPAGE COURSE LIST UPDATE

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_course_list_update)|ui

    list_id:number

    title:string

    list:Array[number]

    default_type:enum("dt_most_visited"|"dt_most_sell"|"dt_most_score"|"dt_most_newest")

    groups:GroupInput

    visible:number

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

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

## EDIT MAINPAGE COURSE LIST DELETE

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_course_list_delete)|ui

    list_id:number

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD MAINPAGE

**path**

    /mainpage/load

**format**

    P11UTA

**output**

    SUCCESS:Mainpage

**types**

```javascript
    def Mainpage = {
        "page_cover":string,
        "page_cover_title":string,
        "page_cover_text":string,
        "page_cover_has_link":number,
        "page_cover_link":string,
        "page_cover_link_title":string,
        "page_cover_template":number,
        "page_logo":string,
        "store_open":number,
        "page_title":string,
        "content_hierarchy":string,
        "footer_links":string,
        "footer_telephones":string,
        "footer_app_links":string,
        "contents":Array[Content],
        "course_lists":Array[CourseList],
    }

    def Content = {
        "id":number,
        "url":string,
        "title":string,
        "link":string,
        "has_link":number|b,
        "link_title":string,
        "text":string,
        "visible":number,
        "type":enum("ct_video"|"ct_document"|"ct_none"),
        "size":number,
    }

    def CourseList = {
        "id":number,
        "title":string,
        "default_type":enum("dt_most_visited"|"dt_most_sell"|"dt_most_score"|"dt_most_newest"),
        "list":Array[number],
        "g1":number,
        "g2":number,
        "g3":number,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDIT MAIN CONTENT HIERARCHY

**path**

    /course/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_hierarchy)|ui

    hierarchy:Array[H_object]

**output**

    SUCCESS:null

    CONTENT_NOT_FOUND:null

**types**

```javascript
    def H_object = {
        "any format prefered"
    }

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## TOGGLE MAIN INFO BOX VISIBILITY

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_main_box_info_toggle_visibility)|ui

    content_id:number

**output**

    SUCCESS:{
        "visibile":number
    }

    CONTENT_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## TOGGLE MAIN COURSE LIST VISIBILITY

**path**

    /mainpage/edit/{ep}

**format**

    P11UTA

**input**

    ep:string(ep_content_main_course_list_toggle_visibility)|ui

    list_id:number

**output**

    SUCCESS:{
        "visibile":number
    }

    CONTENT_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::