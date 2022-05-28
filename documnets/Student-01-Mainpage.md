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
        "motto":string,
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

## LOAD FOOTER

**path**

    /footer/load

**format**

    P10PSTA

**output**

    SUCCESS:{
        "footer_links":string,
        "footer_telephones":string,
        "footer_app_links":string,
    }

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
