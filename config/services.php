<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google_sheet' =>[
        "type"=>"service_account",
        "project_id"=>"light-application-v2",
        "private_key_id"=>"4c21656e5a682163037939dd6686747efa3af506",
        "private_key"=>"-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCvNCY64rYC7bH6\niRpAt4UhAk+62tcUKxPGV0gs2cGD8kiVBFIZ8VeXMVxLzjsEtKG91Y+wHaAXS6AC\nMvNSF/8oJIj5o1J13yzUeQWIsHGw96nx+hwwk+/zAktRpDU3ux2BafNHykn5aOwb\nxWUWSqLpoo4smmHDJibDYCL2xu77CasJ4x6I9BHTOrUM6ztahlessTa7CcWZaGEN\nHhCnZz4BSk1OY9T5+e04ft7wYSzEAMcWGJo2khWqUlAbgxZmmZeHwlaD31vV94Br\nWYNiV8vZxReCvlFf1lVLY4KqPdlXkpr+gM++0V8YXk70xB+10dmopV44yUT0eD4z\nOM00RvNDAgMBAAECggEAAdLcTlhGG7wHUmZzRawuBbzFQVp+IEwd0xPdK6ECoNHN\n9psfvacOSdkY2a3RCHNW4afOQeo1BzkMISm68FD79hosliigjEXhQhRBE1NULkri\n/nf41iVGu0lRU7gOrmtTWyGpXMcaw66svSdDWPA4R4/1GeVrfMFcvZjhFEotek3I\nLdrXWDhG1+LEvG5zYxrzXxALbThCwIu86HLC9gHmUKXtZo2Cw8k79FpE9q848Clb\nklRzmNNtpC12b65ZSB1344L8ph0NZJMLRpTetzW09xxJ4M3knpqpfIByH7DuyD/r\n9XnC8DJIiRXr1OI1MmmDDUUi1qhklRocafeP5EPHUQKBgQDxSllVDNIdtFZfJqHi\nyyUIDbWFajuh6Ao7l3l5nanLBT9M5RiPnsQWY339a/p9AdD3Lb39NOaDcPf6ny0X\ntmBUeNqDDb+3yP3zUSbGNrLRfMPtsM+KjE+nYq0a6vUkuHiLDcVl4/pyamXcqDby\n2jrhS7zXoBXJfcBX/LS6ZeJxswKBgQC54m5hMriKS3H0wOBmTJRbb+rvMwaQ+Gxs\nkRl1qajQKOwl8j10R4I+EAZ0NFXQ04apzzOsufu0ZZimPa7i7nzvYSMvLa6+Rbo/\nn53EtWY9yy1zNsemzcqR8779Ti4SLyF9TxfzeK8++frastyUA2pjdessq6DQ5zpt\nKFO3TdwQMQKBgQDv9woavHJmmkffv1L2cyOz+7ZQJdOCdHtgwodLvNH1F5XZimm/\nw5yty2qsUuu90MWaXYJ6RFcP15S3SgCVeYoZ2EswVMcbJyfwCP/v1sxF7LgKNnJh\nDqPVCxyvDYaZa2BuolZzu6QCj/AX368uHHy8PQ9kvk+MoKRenPK8AcGPYwKBgQC3\nZOpIJXWipArbUoxTAc0BZashsnMRBrhaaNH4n5n5Pda3HYd6OK8MMl1buuLL9FYR\nJWezS49FjVMM+SCZrng+6NSA0I5uFXdLHFzY3avw3YuK94oFTVZFp3lQixizQiLF\ncgqMYQ5tkM7phLxRoAkP9iA/41j3opqnZbkqybuLkQKBgQDuJDqFrz+764kWgRsA\nhB9m1WeSSmaYcN6SY8u6Y4ZF4ryuP8tXH4riPUsTyAJUq9exPp2LKgbOHVbqssfs\nlJ8dIKd51QcpZRmkA3IFCJj1TNkjo7lrFRf/S+B2OmesAzkUWQ0n51JsXixXz9Yz\nfxjimqmUqYpO5F4CzQ+YTOfyqQ==\n-----END PRIVATE KEY-----\n",
        "client_email"=>"light-laf-v2@light-application-v2.iam.gserviceaccount.com",
        "client_id"=>"118030514020525812645",
        "auth_uri"=>"https://accounts.google.com/o/oauth2/auth",
        "token_uri"=>"https://oauth2.googleapis.com/token",
        "auth_provider_x509_cert_url"=>"https://www.googleapis.com/oauth2/v1/certs",
        "client_x509_cert_url"=>"https://www.googleapis.com/robot/v1/metadata/x509/light-laf-v2%40light-application-v2.iam.gserviceaccount.com"
    ]
];
