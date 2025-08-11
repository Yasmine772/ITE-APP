<?php

return [

    'roles_permissions' => [

        'admin' => [
            'auth'=> ['login','logout'],
            'users'=>['create','update','delete','show'],
            'paths'=>['create','update','delete','show'],
            'categories'=>['create','update','delete','show'],
            'advertisements'=>['create','update','delete','show'],
            'courses'=>['exams.create'],
            'content'=>['manage'],
            'notifications'=>['send']
        ],

        'teacher' => [
            'auth'=> ['login','logout'],
            'courses'=>['create','update','delete','show'],
            'lectures'=>['create','update','delete','show'],
            'references'=>['create','update','delete','show'],
            'study_tips'=>['create','update','delete','show'],
            'reports'=>['create','update','delete','show'],
            'advertisements'=>['create','update','delete','show'],
            'correction_files'=>['submit'],
            'live_broadcast'=>['create'],
            'subjects_exam'=>['create'],
            'tests'=>['create'],
            'notifications'=>['send'],
            'complaints'=>['submit'],
        ],

        'student' => [
            'auth'=> ['signup','login','logout'],
            'account'=>['verify'],
            'password'=>['change'],
            'profile'=>['show','update'],
            'current_courses'=>['show'],
            'courses_progress'=>['show'],
            'course'=>['evaluate'],
            'completed_courses'=>['show'],
            'courses_list'=>['show'],
            'courses_details'=>['show'],
            'free_course'=>['show'],
            'paid_course'=>['payment'],
            'lowest_rated_courses'=>['show'],
            'current_path'=>['show'],
            'completed_paths'=>['show'],
            'coupons'=>['use'],
            'wallet'=>['access'],
            'loyalty_points'=>['access'],
            'exams'=>['take'],
            'exams_result'=>['show'],
            'completed_exams'=>['show'],
            'academic_year'=>['choose'],
            'current_chapter'=>['choose'],
            'study_materials'=>['choose'],
            'type_study_materials'=>['choose'],
            'lectures'=>['show','download'],
            'study_tips'=>['show'],
            'homeworks'=>['solve'],
            'subjects_exams'=>['solution'],
            'translate'=>['show'],
            'complaints'=>['create','update','delete','show'],
            'notes'=>['show'],
            'articles'=>['create','update','delete','show'],
            'references'=>['show'],
            'notifications'=>['show']
        ],
    ],

    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        'role_pivot_key' => null,
        'permission_pivot_key' => null,
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'team_id',
    ],

    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'events_enabled' => false,
    'teams' => false,
    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,
    'use_passport_client_credentials' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],
];
