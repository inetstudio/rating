@php
    $colors = [
        0 => 'danger',
        5 => 'primary',
    ];

    $ratingsDescription = [
        0 => 'Лайки',
        5 => 'Дизлайки',
    ];
@endphp

<div class="ibox float-e-margins">
    <div class="ibox-content">
        <h2>Оценки</h2>
        <ul class="todo-list m-t">
            @foreach ($ratings as $rating)
                <li>
                    <small class="label label-{{ (isset($colors[$rating->rating])) ? $colors[$rating->rating] : 'info' }}">{{ $rating->total }}</small>
                    <span class="m-l-xs">{{ (isset($ratingsDescription[$rating->rating])) ? $ratingsDescription[$rating->rating] : $rating->rating }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>