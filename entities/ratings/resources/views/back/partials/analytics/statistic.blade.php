@inject('statisticService', 'InetStudio\RatingsPackage\Ratings\Contracts\Services\Back\StatisticServiceContract')

@php
    $ratings = $statisticService->getRatingStatistic();
    $colors = $statisticService->getRatingsColors();
    $titles = $statisticService->getRatingsTitles();
@endphp

<div class="ibox float-e-margins">
    <div class="ibox-content">
        <h2>Оценки</h2>
        <ul class="todo-list m-t">
            @foreach ($ratings as $rating)
                <li>
                    <small class="label label-{{ $colors[$rating->rating] ?? 'info' }}">{{ $rating->total }}</small>
                    <span class="m-l-xs">{{ $ratingsDescription[$rating->rating] ?? $rating->rating }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
