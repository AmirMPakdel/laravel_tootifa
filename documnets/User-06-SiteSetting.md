# CONFIGS

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






# EDUCATOR CRUD

## CREATING EDUCATOR

**path**

    /educators/create

**format**

    P11UTA

**input**

    first_name:string

    last_name:string

    bio:string|nr

    upload_key:string|nr

**output**

    SUCCESS:
    {
        educator_id:number
    }

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCH EDUCATORS

**path**

    /educators/fetch

**format**

    P11UTA

**output**

    SUCCESS:Array[EducatorItem]

**types**

```javascript
    def EducatorItem = {
        "id":number,
        "first_name":string,
        "last_name":string,
        "bio":string,
        "image":string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## UPDATING EDUCATOR

**path**

    /educators/update

**format**

    P11UTA

**input**

    educator_id:number

    first_name:string

    last_name:string

    file_state:enum(ufs_no_change|ufs_new|ufs_replace|ufs_delete)

    bio:string|nr

    upload_key:string|nr
    description: it is required when file_state is ufs_new or ufs_replace

**output**

    SUCCESS:null

    NO_FILE_STATE:null

    INVALID_OLD_UPLOAD_KEY:null

    INVALID_UPLOAD_KEY:null

    CONVERTOR_SERVER_ISSUE_MOVING_FILE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING EDUCATOR

**path**

    /educators/delete

**format**

    P11UTA

**input**

    educator_id:number

**output**

    SUCCESS:null

    CONVERTOR_SERVER_ISSUE_DELETING_FILE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

# GROUP CRUD

## CREATING LEVEL ONE GROUP

**path**

    /levelonegroups/create

**format**

    P11UTA

**input**

    title:string

    type: enum("gt_course"|"gt_post")

**output**

    SUCCESS:{
        g1_id:number
    }

    REPETITIVE_TITLE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING LEVEL TWO GROUP

**path**

    /leveltwogroups/create

**format**

    P11UTA

**input**

    title:string

    g1_id:number

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:{
        g2_id:number
    }

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CREATING LEVEL THREE GROUP

**path**

    /levelthreegroups/create

**format**

    P11UTA

**input**

    title:string

    g2_id:number

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:{
        g3_id:number
    }

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCHING GROUPS

**path**

    /groups/fetch/{type}

**format**

    G10PTA

**input**

    type: enum("gt_course"|"gt_post")|ui

**output**

    SUCCESS:Array[LevelOneGroup]

**types**

```javascript
    def LevelOneGroup = {
        "level": 1,
        "id": number,
        "title" : string,
        "groups": Array[LevelTwoGroup]
    }

    def LevelTwoGroup = {
        "level": 2,
        "id": number,
        "title" : string,
        "groups": Array[LevelThreeGroup]
    }

    def LevelThreeGroup = {
        "level": 3,
        "id": number,
        "title" : string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING LEVEL ONE GROUP

**path**

    /levelonegroups/edit

**format**

    P11UTA

**input**

    id:number

    title:string

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING LEVEL TWO GROUP

**path**

    /leveltwogroups/edit

**format**

    P11UTA

**input**

    id:number

    title:string

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING LEVEL THREE GROUP

**path**

    /levelthreegroups/edit

**format**

    P11UTA

**input**

    id:number

    title:string

    type:enum("gt_course"|"gt_post")

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING LEVEL ONE GROUP

**path**

    /levelonegroups/delete

**format**

    P11UTA

**input**

    id:number

    force_delete:number|b

**output**

    SUCCESS:null

    RELATED_ENTITIES:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING LEVEL TWO GROUP

**path**

    /leveltwogroups/delete

**format**

    P11UTA

**input**

    id:number

    force_delete:number|b

**output**

    SUCCESS:null

    RELATED_ENTITIES:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING LEVEL THREE GROUP

**path**

    /levelthreegroups/delete

**format**

    P11UTA

**input**

    id:number

    force_delete:number|b

**output**

    SUCCESS:null

    RELATED_ENTITIES:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

# TAG CRUD

## CREATING TAG

**path**

    /tags/create

**format**

    P11UTA

**input**

    title:string

**output**

    SUCCESS:{
        tag_id:number
    }

    REPETITIVE_TITLE:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCHING TAGS

**path**

    /tags/fetch

**format**

    G10PTA

**output**

    SUCCESS:Array[Tag]

**types**

```javascript
    def Tag = {
        "id": number,
        "title" : string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EDITING TAG

**path**

    /tags/edit

**format**

    P11UTA

**input**

    id:number

    title:string

**output**

    SUCCESS:null

    REPETITIVE_TITLE:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## DELETING TAG

**path**

    /tags/delete

**format**

    P11UTA

**input**

    id:number

    force_delete:number|b

**output**

    SUCCESS:null

    RELATED_ENTITIES:null

    ENTITY_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::


# CATEGORIES

## FETCHING CATEGORIES

**path**

    /categories/fetch

**format**

    G10PTA

**output**

    SUCCESS:Array[Category]

**types**

```javascript
    def Category = {
        "id": number,
        "title" : string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
