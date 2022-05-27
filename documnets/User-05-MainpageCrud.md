# USER SORT ELEMENTS

## MAINPAGE CONTENT HIERARCHY

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

# USER ADD ELEMENT

## MAINPAGE INFOBOX ADD

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

## MAINPAGE COURSE LIST ADD

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

# USER UPDATE ELEMENT

## MAINPAGE UPDATE COVER

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

## MAINPAGE UPDATE FOOTER LINKS

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


## MAINPAGE UPDATE INFOBOX

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

## MAINPAGE UPDATE COURSE LIST

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

# USER DELETE ELEMENT

## EDIT MAINPAGE INFOBOX DELETE

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

# USER TOGGLE ELEMENT VISIBILTY

## MAINPAGE INFOBOX TOGGLE VISIBILITY

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

## MAINPAGE COURSELIST TOGGLE VISIBILITY

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
