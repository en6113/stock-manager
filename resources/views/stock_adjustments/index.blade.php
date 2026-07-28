<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-6xl py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <a href="{{ route('stocks.index') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 mb-2">
                    &larr; 在庫一覧に戻る
                </a>
                <h1 class="text-2xl font-semibold text-gray-800 leading-tight">在庫調整履歴</h1>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 p-6">
            {{-- 検索・絞り込みフォーム --}}
            <form action="{{ route('stock_adjustments.index') }}" method="GET"
                class="bg-gray-50 p-4 rounded mb-6 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">調整日</label>
                    <input type="date" name="adjusted_on" value="{{ request('adjusted_on') }}"
                        class="rounded border-gray-300 py-1.5 px-3 text-sm bg-white focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">食材</label>
                    <select name="item_id"
                        class="rounded border-gray-300 py-1.5 px-3 text-sm bg-white focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">すべて</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ (string) request('item_id') === (string) $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">調整理由</label>
                    <select name="reason"
                        class="rounded border-gray-300 py-1.5 px-3 text-sm bg-white focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">すべて</option>
                        @foreach ($reasons as $reason)
                            <option value="{{ $reason->value }}" {{ request('reason') === $reason->value ? 'selected' : '' }}>
                                {{ $reason->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="bg-gray-700 hover:bg-gray-800 text-white font-medium py-1.5 px-4 rounded text-sm transition">
                    検索
                </button>
                <a href="{{ route('stock_adjustments.index') }}"
                    class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-1.5 px-4 border border-gray-300 rounded text-sm transition">
                    リセット
                </a>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="px-4 py-3 w-28">調整日</th>
                            <th class="px-4 py-3 w-48">食材</th>
                            <th class="px-4 py-3 w-28">調整量</th>
                            <th class="px-4 py-3 w-24">理由</th>
                            <th class="px-4 py-3">メモ</th>
                            <th class="px-4 py-3 w-32">調整したユーザー</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($adjustments as $adjustment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-600 text-center whitespace-nowrap">
                                    {{ $adjustment->adjusted_on->format('Y-m-d') }}
                                </td>

                                <td class="px-4 py-3 text-gray-900 font-medium">
                                    {{ $adjustment->item->name ?? '(削除済み)' }}
                                </td>

                                <td class="px-4 py-3 text-right font-medium {{ $adjustment->quantity_g >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $adjustment->quantity_g > 0 ? '+' : '' }}{{ number_format($adjustment->quantity_g, 1) }}
                                    <span class="text-xs text-gray-500">{{ $adjustment->item->unit ?? '' }}</span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center text-xs font-medium text-gray-700 bg-gray-100 px-2 py-0.5 rounded">
                                        {{ $adjustment->reason->label() }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-gray-600 text-sm">
                                    {{ $adjustment->note ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 text-center">
                                    {{ $adjustment->user->name ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-gray-500">
                                    該当する在庫調整の履歴がありません。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center mt-4">
                {{ $adjustments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
