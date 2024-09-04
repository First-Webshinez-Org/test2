<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'আপনাকে :attribute গ্রহণ করতে হবে।',
    'accepted_if' => 'যখন :other হল :value, তখন :attribute গ্রহণ করতে হবে।',
    'active_url' => ':attribute একটি বৈধ URL নয়।',
    'after' => ':attribute তারিখটি :date তারিখের পরে হতে হবে।',
    'after_or_equal' => ':attribute তারিখটি :date তারিখের পরে অথবা সমান হতে হবে।',
    'alpha' => ':attribute শুধুমাত্র অক্ষর ধারণ করতে পারে।',
    'alpha_dash' => ':attribute শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর ধারণ করতে পারে।',
    'alpha_num' => ':attribute শুধুমাত্র অক্ষর এবং সংখ্যা ধারণ করতে পারে।',
    'array' => ':attribute অ্যারে হতে হবে।',
    'ascii' => ':attribute শুধুমাত্র একটি সিঙ্গল-বাইট সংখ্যার অক্ষর এবং প্রতীক ধারণ করতে পারে।',
    'before' => ':attribute তারিখটি :date তারিখের আগে হতে হবে।',
    'before_or_equal' => ':attribute তারিখটি :date তারিখের আগে অথবা সমান হতে হবে।',
    'between' => [
        'array' => ':attribute অ্যারেটির মধ্যে :min থেকে :max আইটেম থাকা আবশ্যক।',
        'file' => ':attribute ফাইলটি :min থেকে :max কিলোবাইটের মধ্যে হতে হবে।',
        'numeric' => ':attribute মানটি :min থেকে :max এর মধ্যে হতে হবে।',
        'string' => ':attribute স্ট্রিংটি :min থেকে :max টি অক্ষর হতে হবে।',
    ],
    'boolean' => ':attribute ফিল্ডটি সত্য বা মিথ্যা হতে হবে।',
    'confirmed' => ':attribute নিশ্চিতকরণ মেলে না।',
    'current_password' => 'পাসওয়ার্ডটি ভুল।',
    'date' => ':attribute একটি বৈধ তারিখ নয়।',
    'date_equals' => ':attribute তারিখটি :date তারিখের সমান হতে হবে।',
    'date_format' => ':attribute ফরম্যাটটি :format এর সাথে মেলে না।',
    'decimal' => ':attribute একটি দশমিক স্থান থাকা আবশ্যক।',
    'declined' => ':attribute অগ্রাহ্য হতে হবে।',
    'declined_if' => 'যখন :other হল :value, তখন :attribute অগ্রাহ্য হতে হবে।',
    'different' => ':attribute এবং :other ভিন্ন হতে হবে।',
    'digits' => ':attribute অংকগুলি :digits হতে হবে।',
    'digits_between' => ':attribute অংকগুলি :min থেকে :max হতে হবে।',
    'dimensions' => ':attribute অবৈধ চিত্র মাত্রা ধারণ করে।',
    'distinct' => ':attribute ফিল্ডটির একটি সদৃশ মান আছে।',
    'doesnt_end_with' => ':attribute এর শেষ অংশ এই মধ্যে একটিতেও শেষ হতে পারে না: :values।',
    'doesnt_start_with' => ':attribute এর শুরু এই মধ্যে একটিতেও শুরু হতে পারে না: :values।',
    'email' => ':attribute একটি বৈধ ইমেল ঠিকানা হতে হবে।',
    'ends_with' => ':attribute শেষ হতে হবে নিম্নলিখিত একটির সাথে: :values।',
    'enum' => 'নির্বাচিত :attribute অকার্যকর।',
    'exists' => 'নির্বাচিত :attribute অকার্যকর।',
    'file' => ':attribute একটি ফাইল হতে হবে।',
    'filled' => ':attribute ফিল্ডটির মান থাকা আবশ্যক।',
    'gt' => [
        'array' => ':attribute আইটেম সংখ্যা :value এর চেয়ে বেশি হতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে বড় হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে বড় হতে হবে।',
        'string' => ':attribute :value অক্ষর এর চেয়ে বড় হতে হবে।',
    ],
    'gte' => [
        'array' => ':attribute আইটেম সংখ্যা :value এর চেয়ে বড় অথবা সমান হতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে বড় অথবা সমান হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে বড় অথবা সমান হতে হবে।',
        'string' => ':attribute :value অক্ষর এর চেয়ে বড় অথবা সমান হতে হবে।',
    ],
    'image' => ':attribute একটি ছবি হতে হবে।',
    'in' => 'নির্বাচিত :attribute অকার্যকর।',
    'in_array' => ':attribute ফিল্ডটি :other এ অস্তিত্ব নেই।',
    'integer' => ':attribute একটি পূর্ণসংখ্যা হতে হবে।',
    'ip' => ':attribute একটি বৈধ IP ঠিকানা হতে হবে।',
    'ipv4' => ':attribute একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6' => ':attribute একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json' => ':attribute একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lowercase' => ':attribute অক্ষরগুলি লোয়ারকেস হতে হবে।',
    'lt' => [
        'array' => ':attribute আইটেম সংখ্যা :value এর চেয়ে কম হতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে কম হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে কম হতে হবে।',
        'string' => ':attribute :value অক্ষর এর চেয়ে কম হতে হবে।',
    ],
    'lte' => [
        'array' => ':attribute আইটেম সংখ্যা :value এর চেয়ে বেশি হবে না।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে কম অথবা সমান হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে কম অথবা সমান হতে হবে।',
        'string' => ':attribute :value অক্ষর এর চেয়ে কম অথবা সমান হতে হবে।',
    ],
    'mac_address' => ':attribute একটি বৈধ MAC ঠিকানা হতে হবে।',
    'max' => [
        'array' => ':attribute আইটেম সংখ্যা :max এর চেয়ে বেশি হতে পারে না।',
        'file' => ':attribute :max কিলোবাইটের চেয়ে বড় হতে পারে না।',
        'numeric' => ':attribute :max এর চেয়ে বড় হতে পারে না।',
        'string' => ':attribute :max অক্ষর এর চেয়ে বড় হতে পারে না।',
    ],
    'max_digits' => ':attribute এ সর্বাধিক :max সংখ্যা থাকতে পারে না।',
    'mimes' => ':attribute একটি ফাইল হতে হবে এই ধরণের: :values।',
    'mimetypes' => ':attribute একটি ফাইল হতে হবে এই ধরণের: :values।',
    'min' => [
        'array' => ':attribute অন্তত অবজেক্ট :min টি থাকতে হবে।',
        'file' => ':attribute কমপক্ষে :min কিলোবাইট হতে হবে।',
        'numeric' => ':attribute অন্তত :min হতে হবে।',
        'string' => ':attribute অন্ততপক্ষে :min অক্ষর হতে হবে।',
    ],
    'min_digits' => ':attribute অন্ততপক্ষে :min সংখ্যা থাকতে হবে।',
    'multiple_of' => ':attribute :value এর একটি গুণিতক হতে হবে।',
    'not_in' => 'নির্বাচিত :attribute অবৈধ।',
    'not_regex' => ':attribute বিন্যাস অবৈধ।',
    'numeric' => ':attribute একটি সংখ্যা হতে হবে।',
    'password' => [
        'letters' => ':attribute কমপক্ষে একটি অক্ষর থাকতে হবে।',
        'mixed' => ':attribute কমপক্ষে একটি বড় হাতের ও একটি ছোট হাতের অক্ষর থাকতে হবে।',
        'numbers' => ':attribute কমপক্ষে একটি সংখ্যা থাকতে হবে।',
        'symbols' => ':attribute কমপক্ষে একটি চিহ্ন থাকতে হবে।',
        'uncompromised' => 'দেওয়া গেল এই :attribute ডেটা লিকে পাওয়া গেছে। দয়া করে একটি পৃথক :attribute চয়ন করুন।',
    ],
    'present' => ':attribute ক্ষেত্রটি উপস্থিত থাকতে হবে।',
    'prohibited' => ':attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibited_if' => ':other এর মান :value হলে :attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibited_unless' => ':other এর মান :values এ না থাকলে :attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibits' => ':attribute ক্ষেত্রটি বাধা দেয় :other এর উপস্থিতিকে।',
    'regex' => ':attribute বিন্যাস অবৈধ।',
    'required' => ':attribute ক্ষেত্রটি প্রয়োজন।',
    'required_array_keys' => ':attribute ক্ষেত্রটিতে নিম্নলিখিত জন্য এন্ট্রি থাকা আবশ্যক: :values।',
    'required_if' => ':other এর মান :value হলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_if_accepted' => ':other এর মান গ্রহণ করা হলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_unless' => ':other এর মান :values এ না থাকলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_with' => ':values উপস্থিত হলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_with_all' => ':values উপস্থিত হলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_without' => ':values উপস্থিত না হলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'required_without_all' => ':values সবগুলো উপস্থিত না হলে :attribute ক্ষেত্রটি প্রয়োজন।',
    'same' => ':attribute এবং :other মিলতে হবে।',
    'size' => [
        'array' => ':attribute অবজেক্টটিতে :size আইটেম থাকতে হবে।',
        'file' => ':attribute :size কিলোবাইট হতে হবে।',
        'numeric' => ':attribute :size হতে হবে।',
        'string' => ':attribute :size অক্ষর হতে হবে।',
    ],
    'starts_with' => ':attribute নিম্নলিখিত সহ শুরু হতে হবে: :values।',
    'string' => ':attribute একটি স্ট্রিং হতে হবে।',
    'timezone' => ':attribute একটি বৈধ সময় অঞ্চল হতে হবে।',
    'unique' => ':attribute ইতিমধ্যে নেওয়া হয়েছে।',
    'uploaded' => ':attribute আপলোড করা ব্যর্থ হয়েছে।',
    'uppercase' => ':attribute বড় হাতের অক্ষর হতে হবে।',
    'url' => ':attribute একটি বৈধ ইউআরএল হতে হবে।',
    'ulid' => ':attribute একটি বৈধ ইউএলআইডি হতে হবে।',
    'uuid' => ':attribute একটি বৈধ ইউইইডি হতে হবে।',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'কাস্টম-মেসেজ',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

    'custom-messages' => [
        'quantity_not_available' => 'পরিমাণ :qty :একক পাওয়া যায়নি',
        'this_field_is_required' => 'এই ফিল্ডটি প্রয়োজন',
    ],

];