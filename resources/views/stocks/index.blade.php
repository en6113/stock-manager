<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-6xl py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 leading-tight">給食食材・在庫管理</h1>
            </div>
            {{-- 別途食材を作成する画面へのリンク --}}
            <a href="{{ route('items.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 text-sm">
                ＋ マスタに新しい食材を追加
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="px-4 py-3 w-48">食材名 / アレルギー</th>
                            <th class="px-4 py-3 w-28">保管場所</th>
                            <th class="px-4 py-3 w-28">使用予定量</th>
                            <th class="px-4 py-3 w-28 text-blue-600">在庫数</th>
                            <th class="px-4 py-3 w-28">発注中</th>
                            <th class="px-4 py-3 w-28">発注の必要性</th>
                            <th class="px-4 py-3 w-48">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            @php
                            // この食材に紐づく在庫データがあるか確認するロジック
                            $hasStock = !is_null($item->stock);
                            $reservedQty = $item->getReservedQty(); // 使用予定量
                            $stockQty = $item->orders()->where('status', '2')->sum('ordered_qty') ?? 0; // 在庫数(2:received)
                            $orderedQty = $item->orders()->where('status', '1')->sum('ordered_qty') ?? 0; // 発注中(1:ordered)
                            $unit = $item->unit; // 単位
                            $isLowStock = $stockQty + $orderedQty < $reservedQty + $item->target_stock_qty; // 発注の必要性判定
                            @endphp

                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @forelse($item->allergens as $allergen)
                                            <span class="px-1.5 py-0.5 text-xs font-medium bg-orange-100 text-orange-800 rounded">
                                                {{ $allergen->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400">アレルギーなし</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-gray-500 text-center">
                                    {{ $item->storage_location }}
                                </td>

                                <td class="px-4 py-3 text-gray-500 text-right">
                                    {{ $reservedQty }} <span class="text-xs">{{ $unit }}</span>
                                </td>

                                <form action="{{ $hasStock ? route('stocks.update', $item->stock->id) : route('stocks.store') }}" method="POST">
                                    @csrf
                                    @if($hasStock)
                                        @method('PUT')
                                    @endif
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">

                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1">
                                            <input type="number" name="qty" step="0.1"
                                                class="w-20 rounded border-gray-300 py-1 px-2 text-sm text-left focus:border-indigo-500 focus:ring-indigo-500"
                                                value="{{ old('qty', $stockQty) }}" placeholder="0">
                                            <span class="text-xs text-gray-500">{{ $unit }}</span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-gray-500 text-right">
                                        {{ $orderedQty }} <span class="text-xs">{{ $unit }}</span>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        @if($isLowStock)
                                            <span class="inline-flex items-center text-xs font-medium text-red-700 bg-red-100 px-2 py-0.5 rounded">
                                                要発注
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded">
                                                なし
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2.5 rounded text-xs transition shadow-sm">
                                                更新
                                            </button>
                                            
                                            <a href="{{ route('orders.edit', $item->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-1 px-2 rounded text-xs transition shadow-sm whitespace-normal max-w-[70px] inline-block text-center leading-tight">
                                                発注・管理
                                            </a>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-gray-500">
                                    食材マスタにデータがありません。「新しい食材を追加」から食材を登録してください。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>