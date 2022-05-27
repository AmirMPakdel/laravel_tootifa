## FETCH STORE COURSES 

**path**

    /store/fetch/{chunk_count}/{page_count}

**format**

    P11PSTA

**input**

    filters:Filter|nr

    sorting_mode:enum(
        sm_most_visits|
        sm_least_visits|
        sm_most_sells|
        sm_least_sells|
        sm_newest|
        sm_oldest|
        sm_lowest_price|
        sm_highest_price|
    )|nr

    chunk_count:string|ui
    description: by what fraction devide the whole items (at least 1)

    page_count:string|ui
    description: which fraction of items to return (starts from 1)

**output**

    SUCCESS:Data

    NO_DATA:null

**types**

```javascript
    def Data = {
        "total_size":number,
        "list":Array[CourseItem]
    }

    def Filter = {
        "searching_phrase":string,
        "group":Group
    }

    def Group = {
        "level":enum(1|2|3),
        "id":number
    }

    def CourseItem = {
        "id":number,
        "title":string,
        "price":number,
        "discount_price":number,
        "sells":number,
        "score":number,
        "visits_count":number,
        "validation_status":enum(not_valid|is_checking|valid),
        "g1":number,
        "g2":number,
        "g3":number,
        "logo":string,
        "educators_name":Array[string],
    }

    description: "You can't have lower group levels without specializing higher group ids"
    description: "set the group and searching_phrase to null or empty string if it's not necessary"

```

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## FETCH COURSE DETAIL

**path**

    /course/load

**format**

    P10ISTA

**input**

    course_id:number

**output**

    SUCCESS:CourseItem

**types**

```javascript
    def CourseItem = {
        "id":number,
        "access_type": AccessType,
        "title":string,
        "price":number,
        "sells":number,
        "score":number,
        "visits_count":number,
        "validation_status":enum(not_valid|is_checking|valid),
        "validation_status_message":string,
        "g1":number,
        "g2":number,
        "g3":number,
        "tags":Array[Tag],
        "duration":number|f:minutes,
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
        "headings":Array[Heading],
        "contents":Array[Content],
        "educators":Array[Educator],
        "logo":string, "decription: upload_key"
        "cover":string, "decription: upload_key"
        "updated_at":date
    }

    def AccessType = enum(
         "1" /* invalid or no auth token*/
        |"2" /* loggedin but hasn't been registered in this course*/
        |"3" /* registered in this course but has not access*/
        |"4" /* registered in this course and has access*/
    )

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