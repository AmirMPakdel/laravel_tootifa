## GET STUDENT PROFILE

**path**

    /profile/load

**format**

    P11STA

**output**

    SUCCESS:
    {
        first_name:string,
        last_name:string,
        email:string,
        phone_number:string,
        state: string,
        city:string
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## UPDATE STUDENT PROFILE

**path**

    /profile/update

**format**

    P11STA

**input**

    first_name:string

    last_name:string

    email:string

    phone_number:string

    state: string

    city:string

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FECH STUDENT REGISTERED COURSES

**path**

    /courses/fetch

**format**

    P11STA

**output**

    SUCCESS:Array[CourseItem]

**types**

```javascript

    def CourseItem = {
        "id":number,
        "title":string,
        "is_online":number|b,
    }

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD COURSE

**path**

    /course/load

**format**

    P11STA

**input**

    course_id:number

**output**

    SUCCESS:CourseItem

**types**

```javascript
    def CourseItem = {
        "id":number,
        "registered":number,
        "has_access":number,
        "title":string,
        "price":number,
        "sells":number,
        "score":number,
        "visits_count":number,
        "g1":number,
        "g2":number,
        "g3":number,
        "tags":Array[Tag],
        "duration":number|f:minutes,
        "is_online":number|b,
        "discount_price":number,
        "holding_status":enum(coming_soon|is_holding|finished),
        "release_date":string|f:yyyy-mm-dd,
        "subjects":Array(string),
        "short_desc":string,
        "long_desc":string,
        "requirements":Array(string),
        "suggested_courses":Array(number),  "decription: It's an array of course ids"
        "suggested_posts":Array(number),  "decription: It's an array of post ids"
        "is_encrypted":number|b,
        "intro_video":IntroVideo,
        "content_hierarchy":string,
        "is_comments_open":number|b
        "headings":Array[Heading],
        "contents":Array[Content],
        "educators":Array[Educator],
        "logo":string, "decription: upload_key"
        "cover":string, "decription: upload_key"
        "is_favorite":number|b,
        "updated_at":date
    }

    def Tag = {
        "id":number,
        "title":string,
    }

    def IntroVideo = {
        "id":number,
        "url":string,
        "size":number,
    }

    def Heading = {
        "id":number,
        "title":string,
    }

    def Content = {
        "id":number,
        "url":string,
        "title":string,
        "type":enum("ct_video"|"ct_document"|"ct_voice"),
        "is_free":number|b,
        "size":number,
    }

    def Educator = {
        "id":number,
        "first_name":string,
        "last_name":string,
        "bio":string,
        "image":string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD COURSE EXTENDED

**path**

    /course/load

**format**

    P12STIA

**input**

    course_id:number

**output**

    SUCCESS:CourseItem

**types**

```javascript
    def CourseItem = {
        "id":number,
        "access_type":enum("1"|"2"|"3"|"4"),
        "title":string,
        "price":number,
        "sells":number,
        "score":number,
        "visits_count":number,
        "g1":number,
        "g2":number,
        "g3":number,
        "tags":Array[Tag],
        "duration":number|f:minutes,
        "is_online":number|b,
        "discount_price":number,
        "holding_status":enum(coming_soon|is_holding|finished),
        "release_date":string|f:yyyy-mm-dd,
        "subjects":Array(string),
        "short_desc":string,
        "long_desc":string,
        "requirements":Array(string),
        "suggested_courses":Array(number),  "decription: It's an array of course ids"
        "suggested_posts":Array(number),  "decription: It's an array of post ids"
        "is_encrypted":number|b,
        "intro_video":IntroVideo,
        "content_hierarchy":string,
        "is_comments_open":number|b
        "headings":Array[Heading],
        "contents":Array[Content],
        "educators":Array[Educator],
        "logo":string, "decription: upload_key"
        "cover":string, "decription: upload_key"
        "is_favorite":number|b|n,        
    }

    def Tag = {
        "id":number,
        "title":string,
    }

    def IntroVideo = {
        "id":number,
        "url":string,
        "size":number,
    }

    def Heading = {
        "id":number,
        "title":string,
    }

    def Content = {
        "id":number,
        "url":string,
        "title":string,
        "type":enum("ct_video"|"ct_document"|"ct_voice"),
        "is_free":number|b,
        "size":number,
    }

    def Educator = {
        "id":number,
        "first_name":string,
        "last_name":string,
        "bio":string,
        "image":string,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FECH STUDENT FAVORITE COURSES

**path**

    /courses/favorite

**format**

    P11STA

**output**

    SUCCESS:Array[CourseItem]

**types**

```javascript

    def CourseItem = {
        "id":number,
        "title":string,
        "is_online":number|b,
    }

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## ADD STUDENT FAVORITE COURSE

**path**

    /course/favorite/add

**format**

    P11STA

**input**

    course_id:number

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## REMOVE STUDENT FAVORITE COURSE

**path**

    /course/favorite/remove

**format**

    P11STA

**input**

    course_id:number

**output**

    SUCCESS:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## GET COURSE SCORE (SET BY STUDENT)

**path**

    /course/score/get

**format**

    P11STA

**output**

    SUCCESS:number (between 0 to 5, if its 0 use course score attribute)


::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## UPDATE COURSE SCORE 

**path**

    /course/score/update

**format**

    P11STA

**output**

    SUCCESS:null (it affects course score attribute)

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD MAINPAGE

**path**

    /main/load

**format**

    P10PSTA

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

## FECH MAIN LIST COURSES

**path**

    /main/course_list/load

**format**

    P11PSTA

**input**

    course_list_id:number

    default_type:enum("mldt_last_created"|"mldt_most_sells"|"mldt_most_visited"|"mldt_highest_score")

    groups:GroupInput

**output**

    SUCCESS:Array[CourseItem]

**types**

```javascript

    def CourseItem = {
        "id":number,
        "title":string,
        "logo":string,
        "price":number,
        "discount_price":number,
        "educators_name":Array[string],
    }

    def GroupInput = {
        "g1": number,
        "g2": number,
        "g3" : number,
    }

    description: "You can't have lower group levels without specializing higher group ids"
    description: "set the g#level to null or empty string if it's not necessary" 

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## LOAD STUDENT COURSE LK

**path**

    /course/lk

**format**

    P11STA

**input**

    course_id:number

**output**

    SUCCESS:LicenseKeyObject

    LISCENSE_KEY_NOT_FOUND:null

**types**

```javascript
    def LicenseKeyObject = {
        "key":string
        "device_one":{
            "uid": string,
            "platform": string,
            "platform_version" : string,
            "app_version" : string,
        }|n,
        "device_two":{
            "uid": string,
            "platform": string,
            "platform_version" : string,
            "app_version" : string,
        }|n,
    }
```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::