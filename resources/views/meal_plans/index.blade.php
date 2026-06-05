<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-8xl">{{ $currentMonth->format('Y年m月') }} の献立一覧</h1>

            <div class="inline-flex rounded-md shadow-sm">
                <a href="{{ route('meal_plans.index', ['month' => $prevMonth]) }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                    先月
                </a>
                <a href="{{ route('meal_plans.index', ['month' => $nextMonth]) }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                    次月
                </a>
            </div>

            <a href="{{ route('meal_plans.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow">
                ＋ 新規献立登録
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-7 bg-gray-100 text-center text-sm font-semibold text-gray-700 border-b">
                <div class="py-2 text-red-500">日</div>
                <div class="py-2">月</div>
                <div class="py-2">火</div>
                <div class="py-2">水</div>
                <div class="py-2">木</div>
                <div class="py-2">金</div>
                <div class="py-2 text-blue-500">土</div>
            </div>

            @foreach ($calendarWeeks as $week)
                <div class="grid grid-cols-7 border-b min-h-[120px]">

                    @foreach ($week as $day)
                        {{-- $day->isCurrentMonth で判定 --}}
                        <div
                            class="p-2 border-r last:border-r-0 flex flex-col justify-between {{ $day->isCurrentMonth ? 'bg-white' : 'bg-gray-50 text-gray-400' }}">

                            <div class="flex justify-between items-center mb-2">
                                {{-- $day->carbon->format() や $day->isToday を使用 --}}
                                <span
                                    class="font-bold text-sm {{ $day->isToday ? 'bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center' : '' }}">
                                    {{ $day->carbon->format('j') }}
                                </span>

                                {{-- 既存の献立データチェック --}}
                                @if(isset($mealPlans[$day->carbon->format('Y-m-d')]))
                                    <a href="{{ route('meal_plans.edit', $mealPlans[$day->carbon->format('Y-m-d')]->id) }}"
                                        class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">
                                        編集
                                    </a>
                                @else
                                    <a href="{{ route('meal_plans.create', ['date' => $day->carbon->format('Y-m-d')]) }}"
                                        class="text-xs text-gray-400 hover:text-blue-600">
                                        +追加
                                    </a>
                                @endif
                            </div>

                            <div class="space-y-1 flex-grow overflow-y-auto max-h-[80px]">
                                @if(isset($mealPlans[$day->carbon->format('Y-m-d')]))
                                    @foreach($mealPlans[$day->carbon->format('Y-m-d')]->menus as $menu)
                                        <div class="text-xs p-1 rounded border shadow-sm bg-gray-50 border-gray-200">
                                            {{ $menu->name }}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach {{-- 日のループ終了 --}}

                </div>
            @endforeach {{-- 週のループ終了 --}}
        </div>
    </div>
</x-app-layout>