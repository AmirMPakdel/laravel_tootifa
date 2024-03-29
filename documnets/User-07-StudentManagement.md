## FETCH COURSE STUDENTS

**path**

    /course/students/fetch/{chunk_count}/{page_count}

**format**

    P11UTA

**input**

    course_id:number|null (returns all sudents when course_id is null)

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
        "list":Array[Student]
    }

    def Student = {
        "id":number,
        "first_name":string,
        "last_name":string,
        "phone_number":string,
        "national_code":string,
        "access":number|b, 
    }
```
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## ADD STUDENTS TO A COURSE

**path**

    /course/students/add

**format**

    P11UTA

**input**

    course_id:number

    student_ids:Array[number]

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## REMOVE STUDENTS FROM COURSE

**path**

    /course/students/remove

**format**

    P11UTA

**input**

    course_id:number

    student_ids:Array(number)

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## CHANGE COURSE STUDENTS ACCESS

**path**

    /course/students/changeaccess

**format**

    P11UTA

**input**

    access:number|b
    
    course_id:number

    student_ids:Array(number)

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## IMPORT COURSE STUDENTS EXCEL

**path**

    /course/students/importexcel

**format**

    P11UTA

**input**

    file:file
    description: the first column of excel file contains students' national_code
    
    course_id:number

**output**

    SUCCESS:null

    COURSE_NOT_FOUND:null

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

## EXPORT COURSE STUDENTS EXCEL

**path**

    /course/students/exportexcel

**format**

    P11UTA

**input**
    
    course_id:number

**output**

    COURSE_NOT_FOUND:null

    SUCEESS: Starts downloading the excel file containing students information

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
