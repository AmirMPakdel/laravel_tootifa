NOTE_1: Whenever there is a token input, there could be INVALID_TOKEN error
NOTE_2: In a llTENANT section routs, X_TENANT header is requiered
****************************************************************************************************************************

---------------------------------------------------------PUBLIC---------------------------------------------------------
prefix -> api/main

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
CHECK_PHONE_NUMBER
/user/checkphonenumber

input: 
	phone_number:string

SUCCESS : {"result_code": ####, "data": null}
REPETITIVE_PHONE_NUMBER : {"result_code": ####, "data": null}
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
LOGIN_WITH_PASSWORD
/user/login

input: 
	phone_number:string
	password:string

SUCCESS : {"result_code": ####, "data": string(json_object)}
	json_object: {
		"token":string,
		"username":string,
	}
INVALID_PHONE_NUMBER : {"result_code": ####, "data": null}
INVALID_PASSWORD : {"result_code": ####, "data": null}
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
SEND_VERIICATION_CODE
/user/verificationcode/send

NOTE: "Verification code is always set to 1111 in test mode, in production mode it would be sent via sms"

input: 
	phone_number:string

SUCCESS : {"result_code": ####, "data": null} 
USER_ALREADY_VERIFIED : {"result_code": ####, "data": null}
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
CHECK_VERIICATION_CODE
/user/verificationcode/check
input: 
	code:string

SUCCESS : {"result_code": ####, "data": string(json_object)}
	json_object: {
		"user_id":number,
	}
INVALID_VERIFICATION_CODE : {"result_code": ####, "data": null}
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
COMPLETE_REGISTRATION
/user/register

input: 
	national_code:string
	phone_number:string
	first_name:string
	last_name:string
	password:string
	user_name:string -> tenant username
	user_id:integer

SUCCESS : {"result_code": ####, "data": string(json_object)}
	json_object: {
		"token":string,
	}
REPETITIVE_NATIONAL_CODE : {"result_code": ####, "data": null}
REPETITIVE_USERNAME : {"result_code": ####, "data": null}
INVALID_ID : {"result_code": ####, "data": null} -> when user_id not exist or incompatible with inserted phone_number


---------------------------------------------------------TENANT---------------------------------------------------------
prefix -> api/tenant/user

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
"GET USER PROFILE"
/profile/load POST, returns a json

input: 
	token:string

SUCCESS : {"result_code": ####, "data": string(json_object)}
	json_object: {
		"first_name":string,
		"last_name":string,
		"email":string,
		"address":string,
		"phone_number":string,
		"email": string #file,
		"is_email_verified": number,
		"m_balance": string number,
		"s_balance": string number,
		"bio": string ,
		"holdable_test_count": number,
		"infinit_test_finish_date": date,
	}
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
"""creating educator"""
url: http://tootifa.ir/api/tenant/user/educators/create
input:
    token
    first_name
    last_name
    bio 
    upload_key (nullable -> when there is not any image to move it to ftp)

output:
    success: {
        "result_code": 1000,
        "data": {
            "educator_id": 1
        }   
    }

    invalid token:  {
        "result_code": 1103,
        "data": null
    } 
