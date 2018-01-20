<?php

return [
        'client' => [
            'id' => env('FITBIT_CLIENT_ID'),
            'secret' => env('FITBIT_CLIENT_SECRET')
        ],
        'resource' => [
            'activities' => [
                'route' => 'activities',
                'resources' => [
                    'calories',
                    'caloriesBMR',
                    'steps',
                    'distance',
                    'floors',
                    'elevation',
                    'minutesSedentary',
                    'minutesLightlyActive',
                    'minutesFairlyActive',
                    'minutesVeryActive',
    //                    'activityCalories'
                ]
            ],
            'trackerActivities' => [
                'route' => 'activities/tracker',
                'resources' => [
                    'calories',
                    'steps',
                    'distance',
                    'floors',
                    'elevation',
                    'minutesSedentary',
                    'minutesLightlyActive',
                    'minutesFairlyActive',
                    'minutesVeryActive',
//                    'activityCalories'
                ]
            ],
            'weight' => [
                'route' => 'body',
                'resources' => [
                    'bmi',
                    'weight',
                    'fat'
                ]
            ]
        ]
];
