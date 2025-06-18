<?php

return [
    // versioning the prediiction models
    'models' => [
        'v1' => [
            'modelVersion' => 'v1',
            'modelName' => 'MPM v1',
            'mlModel' => 'https://motora-ai-ml-app.kindmushroom-23aeb924.southindia.azurecontainerapps.io/predict',
            'aiModel' => 'https://motora-ai-ml-app.kindmushroom-23aeb924.southindia.azurecontainerapps.io/output',
        ]
    ],
    'default' => 'v1'
];