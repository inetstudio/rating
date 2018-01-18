<?php

return [

    'rateable' => [
        'model' => 'model path',
    ],

    'datatables' => [
        'ajax' => [
            'index' => [
                'url' => 'back.rating.data',
                'type' => 'POST',
                'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
            ],
        ],
        'table' => [
            'index' => [
                'paging' => true,
                'pagingType' => 'full_numbers',
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'language' => [
                    'url' => '/admin/js/plugins/datatables/locales/russian.json',
                ],
            ],
        ],
        'columns' => [
            'index' => [
                ['data' => 'title', 'name' => 'title', 'title' => 'Заголовок'],
                ['data' => 'rating', 'name' => 'rating', 'title' => 'Рейтинг'],
                ['data' => 'likes', 'name' => 'likes', 'title' => 'Количество лайков'],
                ['data' => 'dislikes', 'name' => 'dislikes', 'title' => 'Количество дизлайков'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
            ],
        ],
    ],

];
