<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-6xl py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 leading-tight">給食食材・在庫管理</h1>
                <p class="text-sm text-gray-600">使用予定または在庫がある全食材のリストです。</p>
            </div>
            {{-- 別途食材を作成する画面へのリンク --}}
            <a href="{{ route('items.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 text-sm">
                ＋ マスタに新しい食材を追加
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr
                        class="bg-gray-50 border-b border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-4 py-3 w-56">食材名 / アレルギー</th>
                        <th class="px-4 py-3 w-32">保管場所</th>
                        <th class="px-4 py-3 w-32">使用予定量</th>
                        <th class="px-4 py-3 w-32">在庫数</th>
                        <th class="px-4 py-3 w-32">発注中</th>
                        <th class="px-4 py-3 w-32">発注の必要性</th>
                        <th class="px-4 py-3 w-32">操作</th>
                    </tr>
                </thead>
                    @forelse($items as $item)
                        @php
                        $reservedQty = $item->reserved_qty ?? 0; // 使用予定量（予約数）
                        $stockQty = $item->stocks->sum('stock') ?? 0; // 在庫数
                        $orderedQty = $item->stocks->sum('ordered_qty') ?? 0; // 発注中数量
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

                            <td class="px-4 py-3 text-gray-500">
                                {{ $item->storage_location }}
                            </td>

                            <td class="px-4 py-3 text-gray-500">
                                {{ $reservedQty }} <span class="text-xs">{{ $unit }}</span>
                            </td>

                            <td class="px-4 py-3 text-gray-500">
                                {{ $stockQty }} <span class="text-xs">{{ $unit }}</span>
                            </td>

                            <td class="px-4 py-3 text-gray-500">
                                {{ $orderedQty }} <span class="text-xs">{{ $unit }}</span>
                            </td>

                            <td class="px-4 py-3 text-gray-500">
                                @if($isLowStock)
                                    <span
                                        class="inline-flex items-center text-xs font-medium text-red-700 bg-red-100 px-2 py-0.5 rounded">
                                        要発注
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded">
                                        なし
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-6 flex justify-end items-center space-x-1">
                                <a href="{{ route('stocks.edit', $item->stocks->first()->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded text-xs transition shadow-sm">
                                    管理
                                </a>
                                <a href="{{ route('items.edit', $item->id) }}"
                                    class="bg-green-500 hover:bg-green-600 text-white font-medium py-1 px-2 rounded text-xs transition shadow-sm">
                                    編集
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-500">
                                食材マスタにデータがありません。「新しい食材を追加」から食材を登録してください。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>