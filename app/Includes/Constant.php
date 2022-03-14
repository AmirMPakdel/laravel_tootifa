<?php

namespace App\Includes;

class Constant
{
    public static $SUCCESS = 1000;
    public static $INVALID_PHONE_NUMBER = 1101;
    public static $INVALID_PASSWORD = 1102;
    public static $INVALID_TOKEN = 1103;
    public static $REPETITIVE_NATIONAL_CODE = 1107;
    public static $REPETITIVE_PHONE_NUMBER = 1108;
    public static $INVALID_VERIFICATION_CODE = 1109;
    public static $DEVICE_NOT_FOUND = 1110;
    public static $INVALID_REQUEST = 1112;
    public static $INVALID_EMAIL = 1113;
    public static $INVALID_FILE = 1114;
    public static $SERVER_ISSUE = 1115;
    public static $NOT_DELETABLE = 1116;
    public static $OLD_LISCENSE_KEY_NOT_FOUND = 1117;
    public static $SERVER_NOT_AVAILABLE = 1119;
    public static $INVALID_ID = 1120;
    public static $VIDEO_UNAVAILABLE = 1121;
    public static $SMS_NOT_SENT = 1122;
    public static $PLAN_NOT_FREE = 1123;
    public static $INVALID_INSTALLMENT_ID = 1124;
    public static $DOWNLOAD_UNAVAILABLE = 1125;
    public static $USER_ALREADY_VERIFIED = 1126;
    public static $INVALID_GROUP_HIERARCHY = 1127;
    public static $REPETITIVE_TITLE = 1128;
    public static $FILE_SIZE_LIMIT_EXCEEDED = 1129;
    public static $INVALID_VALUE = 1130;
    public static $INVALID_EDIT_TYPE = 1131;
    public static $ENTITY_NOT_FOUND = 1132;
    public static $RELATED_ENTITIES = 1133;
    public static $COMMENTS_NOT_OPEN = 1134;
    public static $NO_DATA = 1135;
    public static $USER_NOT_FOUND = 1136;
    public static $PASSWORD_RESET_REQUEST_LIMIT_ERROR = 1137;
    public static $PASSWORD_RESET_VALID_LIMIT_ERROR = 1138;
    public static $STUDENT_NOT_FOUND = 1139;
    public static $DEVICE_LIMIT = 1140;
    public static $LISCENSE_KEY_NOT_FOUND = 1141;
    public static $INVALID_UPLOAD_KEY = 1142;
    public static $NOT_REGISTERED_IN_COURSE = 1143;
    public static $NO_ACCESS_TO_COURSE = 1144;
    public static $COURSE_NOT_FOUND = 1145;
    public static $POST_NOT_FOUND = 1146;
    public static $NO_FILE_STATE = 1147;
    public static $INVALID_OLD_UPLOAD_KEY = 1148;
    public static $CONVERTOR_SERVER_ISSUE_MOVING_FILE = 1149;
    public static $CONVERTOR_SERVER_ISSUE_DELETING_FILE = 1150;
    public static $CONTENT_NOT_FOUND = 1151;
    public static $REPETITIVE_USERNAME = 1152;
    public static $REPETITIVE_SMS_TYPE_NAME = 1153;
    public static $NEGETIVE_MAINTANANCE_BALANCE = 1154;
    public static $INVALID_USER_NAME = 1155;
    public static $COURSE_NOT_VALID = 1156;

    public static $GENDER_MALE = 1;
    public static $GENDER_MALE_TITLE = "پسر";
    public static $GENDER_FEMALE = 0;
    public static $GENDER_FEMALE_TITLE = "دختر";

    public static $PAYMENT_SUCCEEDED = 1;
    public static $PAYMENT_FAILED = 0;

    public static $PT_INCREMENTAL = "pt_incremental";
    public static $PT_ACTIVATION = "pt_activation";

    public static $PRT_SMS = "prt_sms";
    public static $PRT_MAINTENANCE = "prt_maintenance";
    public static $PRT_TEST = "prt_test";


    public static $PASSWORD_RESET_REQUEST_LIMIT_MIN = 5;
    public static $PASSWORD_RESET_VALID_LIMIT_MIN = 45;

    public static $SATURDAY = "شنبه";
    public static $SUNDAY = "یکشنبه";
    public static $MONDAY = "دوشنبه";
    public static $TUESDAY = "سه شنبه";
    public static $WEDNESDAY = "چهارشنبه";
    public static $THURSDAY = "پنجشنبه";
    public static $FRIDAY = "جمعه";

    public static $DAYS = ["شنبه", "یکشنبه", "دوشنبه", "سه شنبه", "چهارشنبه", "پنجشنبه", "جمعه"];

    public static $CONTENT_TYPE_VIDEO = "ct_video";
    public static $CONTENT_TYPE_IMAGE = "ct_image";
    public static $CONTENT_TYPE_DOCUMENT = "ct_document";
    public static $CONTENT_TYPE_VOICE = "ct_voice";
    public static $CONTENT_TYPE_TEXT = "ct_text";
    public static $CONTENT_TYPE_SLIDER = "ct_slider";
    public static $CONTENT_TYPE_NONE = "ct_none";

    public static $HOLDING_STATUS_COMING_SOON = "coming_soon";
    public static $HOLDING_STATUS_IS_HOLDING = "is_holding";
    public static $HOLDING_STATUS_FINISHED = "finished";

    public static $VALIDATION_STATUS_NOT_VALID = "not_valid";
    public static $VALIDATION_STATUS_IS_CHECKING = "is_checking";
    public static $VALIDATION_STATUS_VALID = "valid";

    public static $DISCOUNT_TYPE_PERCENT = "dt_percent";
    public static $DISCOUNT_TYPE_PRICE = "dt_price";

    public static $MAIN_CP_LIST_DEFAULT_TYPE_MOST_PURCHASED = "most_purchased";
    public static $MAIN_CP_LIST_DEFAULT_TYPE_HIGHEST_SCORE = "highest_score";
    public static $MAIN_CP_LIST_DEFAULT_TYPE_NEWEST = "newest";
    public static $MAIN_CP_LIST_DEFAULT_TYPE_MOST_VISITED = "most_visited";

    public static $SM_MOST_VISITS = "sm_most_visits";
    public static $SM_LEAST_VISITS = "sm_least_visits";
    public static $SM_MOST_SELLS = "sm_most_sells";
    public static $SM_LEAST_SELLS = "sm_least_sells";
    public static $SM_NEWEST = "sm_newest";
    public static $SM_OLDEST = "sm_oldest";
    public static $SM_LOWEST_PRICE = "sm_lowest_price";
    public static $SM_HIGHEST_PRICE = "sm_highest_price";

    public static $MAIN_LIST_DEFAULT_TYPE_LAST_CREATED = "mldt_last_created";
    public static $MAIN_LIST_DEFAULT_TYPE_MOST_SELLS = "mldt_most_sells";
    public static $MAIN_LIST_DEFAULT_TYPE_MOST_VISITED = "mldt_most_visited";
    public static $MAIN_LIST_DEFAULT_TYPE_HIGHEST_SCORE = "mldt_highest_score";

    public static $LT_INSTAGRAM = "lt_instagram";
    public static $LT_EMAIL = "lt_email";
    public static $LT_TELEGRAM = "lt_telegram";
    public static $LT_WHATSAPP = "lt_whatsapp";

    public static $FILE_ACTION_CREATE = "create";
    public static $FILE_ACTION_UPDATE = "update";
    public static $FILE_ACTION_DELETE = "delete";

    public static $COVER_SIZE_LIMIT = 1200;
    public static $COVER_SIZE_NAME_LIMIT = 1000;

    public static $LOGO_SIZE_LIMIT = 900;
    public static $LOGO_SIZE_NAME_LIMIT = 700;

    public static $BELONGING_COURSE = "course";
    public static $BELONGING_POST = "post";
    public static $BELONGING_MAIN = "main";
    public static $BELONGING_LP = "lp";

    public static $SMS_TYPE_REGISTRATION = "st_registration";
    public static $SMS_TYPE_FORGET_PASS = "st_forget_pass";
    public static $SMS_TYPE_MESSAGE = "st_message";

    public static $REGISTRATION_TYPE_WEBSITE = "rt_website";
    public static $REGISTRATION_TYPE_CUSTOM = "rt_custom";

    public static $ONE_G_MAITENANCE_COST = 5000; // TOMAN
    public static $SMS_COST = 10; // TOMAN

    public static $STR_HAS_ACCESS = "دارد";
    public static $STR_HAS_NOT_ACCESS = "ندارد";

    public static $STR_CUSTOM = "دستی";
    public static $STR_WEBSITE = "وبسایت";

    public static $FORM_SUBMIT_TEXT = "ذخیره";

    public static $FORM_ENTITY_TYPE_MAIN = "fet_main";
    public static $FORM_ENTITY_TYPE_POST = "fet_post";
    public static $FORM_ENTITY_TYPE_LP = "fet_lp";
    public static $FORM_ENTITY_TYPE_POPUP = "fet_popup";

    public static $UPLOAD_TRANSACTION_STATUS_GENERATED = "uts_generated";
    public static $UPLOAD_TRANSACTION_STATUS_VERIFIED = "uts_verified";
    public static $UPLOAD_TRANSACTION_STATUS_FTP = "uts_ftp";
    public static $UPLOAD_TRANSACTION_STATUS_DELETED = "uts_deleted";
    public static $UPLOAD_TRANSACTION_STATUS_UPDATING = "uts_updating";

    public static $UPDATE_FILE_STATE_NO_CHANGE = "ufs_no_change";
    public static $UPDATE_FILE_STATE_NEW = "ufs_new";
    public static $UPDATE_FILE_STATE_REPLACE = "ufs_replace";
    public static $UPDATE_FILE_STATE_DELETE = "ufs_delete";

    public static $GROUP_TYPE_COURSE = "gt_course";
    public static $GROUP_TYPE_POST = "gt_post";

    public static $SMS_DEFAULT_TYPES = [
        "send_verification" => "کد فعالسازی: %code",
        "welcome" => "%name عزیز خوش آمدید",
    ];

    public static $DEFAULT_CATEGORIES = [
        "کامپیوتر",
        "کنکور",
    ];

    public static $APP_LINKS = [
        ["name" => "android", "url" => "/"]
    ];

    // UPLOAD TYPES
    public static $UPLOAD_TYPE_WRITER_IMAGE = "ut_writer_image";
    public static $UPLOAD_TYPE_EDUCATOR_IMAGE = "ut_educator_image";

    public static $UPLOAD_TYPE_MAIN_PAGE_LOGO = "ut_main_page_logo";
    public static $UPLOAD_TYPE_MAIN_PAGE_COVER = "ut_main_page_cover";
    public static $UPLOAD_TYPE_BANNER_COVER = "ut_banner_cover";
    public static $UPLOAD_TYPE_MAIN_PAGE_VIDEO = "ut_main_page_video";
    public static $UPLOAD_TYPE_MAIN_PAGE_VOICE = "ut_main_page_voice";
    public static $UPLOAD_TYPE_MAIN_PAGE_IMAGE = "ut_main_page_image";
    public static $UPLOAD_TYPE_MAIN_PAGE_SLIDER_IMAGE = "ut_main_page_slider_image";

    public static $UPLOAD_TYPE_COURSE_LOGO = "ut_course_logo";
    public static $UPLOAD_TYPE_COURSE_COVER = "ut_course_cover";
    public static $UPLOAD_TYPE_COURSE_VIDEO = "ut_course_video";
    public static $UPLOAD_TYPE_COURSE_VIDEO_INTRODUCTION = "ut_course_video_introduction";
    public static $UPLOAD_TYPE_COURSE_DOCUMENT = "ut_course_document";
    public static $UPLOAD_TYPE_COURSE_VOICE = "ut_course_voice";
    public static $UPLOAD_TYPE_COURSE_VIDEO_FREE = "ut_course_video_free";
    public static $UPLOAD_TYPE_COURSE_DOCUMENT_FREE = "ut_course_document_free";
    public static $UPLOAD_TYPE_COURSE_VOICE_FREE = "ut_course_voice_free";



    public static $MAIN_COURSE_LIST_DEFAULT_TYPE_MOST_VISITED = "dt_most_visited";
    public static $MAIN_COURSE_LIST_DEFAULT_TYPE_MOST_SELL = "dt_most_sell";
    public static $MAIN_COURSE_LIST_DEFAULT_TYPE_MOST_SCORE = "dt_most_score";
    public static $MAIN_COURSE_LIST_DEFAULT_TYPE_NEWEST = "dt_most_newest";

    public static function getCourseFreeUploadTypes()
    {
        return [
            Constant::$UPLOAD_TYPE_COURSE_DOCUMENT_FREE,
            Constant::$UPLOAD_TYPE_COURSE_VIDEO_FREE,
            Constant::$UPLOAD_TYPE_COURSE_VOICE_FREE
        ];
    }

    public static function getCourseItemsUploadTypes()
    {
        return [
            Constant::$UPLOAD_TYPE_COURSE_DOCUMENT_FREE,
            Constant::$UPLOAD_TYPE_COURSE_VIDEO_FREE,
            Constant::$UPLOAD_TYPE_COURSE_VOICE_FREE,
            Constant::$UPLOAD_TYPE_COURSE_DOCUMENT,
            Constant::$UPLOAD_TYPE_COURSE_VIDEO,
            Constant::$UPLOAD_TYPE_COURSE_VOICE
        ];
    }

    public static function getCourseEncryptUploadTypes()
    {
        return [
            Constant::$UPLOAD_TYPE_COURSE_VIDEO,
        ];
    }

    public static $BANKING_PORTAL_ZARINPAL = "bp_zarinpal";


    //**************************************************** EDIT PARAMS *************************************************
    public static $EDIT_PARAM_LOGO = "ep_logo";
    public static $EDIT_PARAM_COVER = "ep_cover";
    public static $EDIT_PARAM_TITLE = "ep_title";
    public static $EDIT_PARAM_DURATION = "ep_duration";
    public static $EDIT_PARAM_PRICE = "ep_price";
    public static $EDIT_PARAM_DISCOUNT_PRICE = "ep_discount_price";
    public static $EDIT_PARAM_SUGGESTED_COURSES = "ep_suggested_courses";
    public static $EDIT_PARAM_SUGGESTED_POSTS = "ep_suggested_posts";
    public static $EDIT_PARAM_HOLDING_STATUS = "ep_holding_status";
    public static $EDIT_PARAM_SHORT_DESC = "ep_short_desc";
    public static $EDIT_PARAM_LONG_DESC = "ep_long_desc";
    public static $EDIT_PARAM_RELEASE_DATE = "ep_release_date";
    public static $EDIT_PARAM_COMMENTS_AVAILABILITY = "ep_comments_availability";
    public static $EDIT_PARAM_COMMENTS_VALIDITY = "ep_comments_validity";
    public static $EDIT_PARAM_SUBJECTS = "ep_subjects";
    public static $EDIT_PARAM_REQUIREMENT = "ep_requirement";
    public static $EDIT_PARAM_CONTENT_HIERARCHY = "ep_content_hierarchy";
    public static $EDIT_PARAM_COURSE_EDUCATORS = "ep_course_educators";
    public static $EDIT_PARAM_POST_WRITERS = "ep_post_writers";
    public static $EDIT_PARAM_GROUPS = "ep_groups";
    public static $EDIT_PARAM_TAGS = "ep_tags";
    public static $EDIT_PARAM_CONTENT_VIDEO_ADD = "ep_content_video_add";
    public static $EDIT_PARAM_CONTENT_VIDEO_UPDATE = "ep_content_video_update";
    public static $EDIT_PARAM_CONTENT_VIDEO_DELETE = "ep_content_video_delete";
    public static $EDIT_PARAM_CONTENT_DOCUMENT_ADD = "ep_content_document_add";
    public static $EDIT_PARAM_CONTENT_DOCUMENT_UPDATE = "ep_content_document_update";
    public static $EDIT_PARAM_CONTENT_DOCUMENT_DELETE = "ep_content_document_delete";
    public static $EDIT_PARAM_CONTENT_VOICE_ADD = "ep_content_voice_add";
    public static $EDIT_PARAM_CONTENT_VOICE_UPDATE = "ep_content_voice_update";
    public static $EDIT_PARAM_CONTENT_VOICE_DELETE = "ep_content_voice_delete";
    public static $EDIT_PARAM_CONTENT_SLIDER_ADD = "ep_content_slider_add";
    public static $EDIT_PARAM_CONTENT_SLIDER_UPDATE = "ep_content_slider_update";
    public static $EDIT_PARAM_CONTENT_SLIDER_DELETE = "ep_content_slider_delete";
    public static $EDIT_PARAM_CONTENT_TEXT_ADD = "ep_content_text_add";
    public static $EDIT_PARAM_CONTENT_TEXT_UPDATE = "ep_content_text_update";
    public static $EDIT_PARAM_CONTENT_TEXT_DELETE = "ep_content_text_delete";
    public static $EDIT_PARAM_CONTENT_IMAGE_ADD = "ep_content_image_add";
    public static $EDIT_PARAM_CONTENT_IMAGE_UPDATE = "ep_content_image_update";
    public static $EDIT_PARAM_CONTENT_IMAGE_DELETE = "ep_content_image_delete";
    public static $EDIT_PARAM_COURSE_INTRO_VIDEO_ADD = "ep_intro_video_add";
    public static $EDIT_PARAM_COURSE_INTRO_VIDEO_UPDATE = "ep_intro_video_update";
    public static $EDIT_PARAM_COURSE_INTRO_VIDEO_DELETE = "ep_intro_video_delete";
    public static $EDIT_PARAM_COURSE_HEADING_ADD = "ep_course_heading_add";
    public static $EDIT_PARAM_COURSE_HEADING_UPDATE = "ep_course_heading_update";
    public static $EDIT_PARAM_COURSE_HEADING_DELETE = "ep_course_heading_delete";
    public static $EDIT_PARAM_STORE_OPEN = "ep_param_store_open";
    public static $EDIT_PARAM_BLOG_OPEN = "ep_param_blog_open";
    public static $EDIT_PARAM_BANNER_COVER = "ep_banner_cover";
    public static $EDIT_PARAM_BANNER_LINK = "ep_banner_link";
    public static $EDIT_PARAM_BANNER_TEXT = "ep_banner_text";
    public static $EDIT_PARAM_BANNER_STATUS = "ep_banner_status";
    public static $EDIT_PARAM_FOOTER_LINKS = "ep_footer_links";
    public static $EDIT_PARAM_COURSE_LIST_ADD = "ep_content_course_list_add";
    public static $EDIT_PARAM_COURSE_LIST_UPDATE = "ep_content_course_list_update";
    public static $EDIT_PARAM_COURSE_LIST_DELETE = "ep_content_course_list_delete";
    public static $EDIT_PARAM_POST_LIST_ADD = "ep_content_post_list_add";
    public static $EDIT_PARAM_POST_LIST_UPDATE = "ep_content_post_list_update";
    public static $EDIT_PARAM_POST_LIST_DELETE = "ep_content_post_list_delete";
    public static $EDIT_PARAM_MAIN_FORM_ADD = "ep_content_main_form_add";
    public static $EDIT_PARAM_MAIN_FORM_UPDATE = "ep_content_main_form_update";
    public static $EDIT_PARAM_MAIN_FORM_DELETE = "ep_content_main_form_delete";
    public static $EDIT_PARAM_POST_FORM_ADD = "ep_content_post_form_add";
    public static $EDIT_PARAM_POST_FORM_UPDATE = "ep_content_post_form_update";
    public static $EDIT_PARAM_POST_FORM_DELETE = "ep_content_post_form_delete";
    public static $EDIT_PARAM_MAIN_INFO_BOX_ADD = "ep_content_main_box_info_add";
    public static $EDIT_PARAM_MAIN_INFO_BOX_UPDATE = "ep_content_main_box_info_update";
    public static $EDIT_PARAM_MAIN_INFO_BOX_DELETE = "ep_content_main_box_info_delete";
    public static $EDIT_PARAM_MAIN_INFO_BOX_TOGGLE_VISIBILITY = "ep_content_main_box_info_toggle_visibility"
    //******************************************************************************************************************

}
