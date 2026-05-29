<x-guest-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-5xl py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 leading-tight">給食食材・在庫管理</h1>
                <p class="text-sm text-gray-600">園内厨房のリアルタイム在庫状況です。</p>
            </div>
            <a href="{{ route('items.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 text-sm">
                ＋ 新しい食材を登録
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr
                        class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-5 py-3">食材名 / アレルギー</th>
                        <th class="px-5 py-3">保管場所</th>
                        <th class="px-5 py-3">現在の在庫数</th>
                        <th class="px-5 py-3">メニュー予約数</th>
                        <th class="px-5 py-3">フリー在庫</th>
                        <th class="px-5 py-3">状態</th>
                        <th class="px-5 py-3 text-right">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($stocks as $stock)
                        {{-- コントローラー側で $stock->qty - $stock->reserved_qty を計算して free_qty として渡す想定、またはBlade側で引く --}}
                        @php
                            $free_qty = $stock->qty - $stock->reserved_qty;
                            $is_low_stock = $stock->qty < $stock->item->target_stock_qty;
                        @endphp
                        <tr class="{{ $is_low_stock ? 'bg-red-50/50' : '' }}">
                            <td class="px-5 py-4">
                                <div class="font-medium text-gray-900">{{ $stock->item->name }}</div>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @forelse($stock->item->allergens as $allergen)
                                        <span class="px-1.5 py-0.5 text-xs font-medium bg-orange-100 text-orange-800 rounded">
                                            {{ $allergen->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400">アレルギーなし</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                        {{ $stock->item->storage_location == '冷蔵' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $stock->item->storage_location == '冷凍' ? 'bg-cyan-100 text-cyan-800' : '' }}
                                        {{ $stock->item->storage_location == '常温' ? 'bg-amber-100 text-amber-800' : '' }}">
                                    {{ $stock->item->storage_location }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-semibold">
                                {{ $stock->qty }} <span class="text-xs font-normal text-gray-500">{{ $stock->unit }}</span>
                            </td>
                            <td class="px-5 py-4 text-gray-500">
                                {{ $stock->reserved_qty }} {{ $stock->unit }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold {{ $free_qty < 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $free_qty }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $stock->unit }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($is_low_stock)
                                    <span
                                        class="inline-flex items-center text-xs font-medium text-red-700 bg-red-100 px-2 py-0.5 rounded">
                                        発注要（下限: {{ $stock->item->target_stock_qty }}）
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded">
                                        適正
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('items.edit', $stock->item->id) }}"
                                    class="text-yellow-600 hover:text-yellow-900 font-medium text-xs">編集</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-500">
                                在庫データがありません。入庫登録するか、食材を登録してください。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-guest-layout>