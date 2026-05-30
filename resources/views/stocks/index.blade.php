<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-6xl py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 leading-tight">給食食材・在庫管理</h1>
                <p class="text-sm text-gray-600">全食材のリストです。在庫数以外は食材編集画面にて変更ください。</p>
            </div>
            {{-- 別途食材を作成する画面へのリンク --}}
            <a href="{{ route('items.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 text-sm">
                ＋ マスタに新しい食材を追加
            </a>
        </div>

        {{-- フラッシュメッセージ --}}
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                <span class="block sm:inline">入力内容にエラーがあります。各行を確認してください。</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr
                        class="bg-gray-50 border-b border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-4 py-3 w-56">食材名 / アレルギー</th>
                        <th class="px-4 py-3 w-32">保管場所</th>
                        <th class="px-4 py-3 w-32 text-blue-600">在庫数</th>
                        <th class="px-4 py-3 w-32">使用予定量</th>
                        <th class="px-4 py-3 w-32">発注の必要性</th>
                        <th class="px-4 py-3 text-right w-24">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    {{-- $stocks ではなく、登録済みの全食材 $items をループさせます --}}
                    @forelse($items as $item)
                        @php
                        // この食材に紐づく在庫データがあるか確認するロジック
                        $hasStock = !is_null($item->stock);
                        $stockQty = $hasStock ? $item->stock->qty : 0;
                        $reservedQty = $item->reserved_qty ?? 0;
                        $unit = $item->unit;
                        $freeQty = $stockQty - $reservedQty;
                        $isLowStock = $stockQty < $reservedQty + $item->target_stock_qty;
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

                            <form
                                action="{{ $hasStock ? route('stocks.update', $item->stock->id) : route('stocks.store') }}"
                                method="POST">
                                @csrf
                                @if($hasStock)
                                    @method('PUT')
                                @endif
                                {{-- 在庫がない場合、どの食材の在庫を登録するか伝えるために item_id を隠しデータで送る --}}
                                <input type="hidden" name="item_id" value="{{ $item->id }}">

                                <td class="px-4 py-3 text-gray-500">
                                    {{ $item->storage_location }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        <input type="number" name="qty" step="0.1"
                                            class="w-20 rounded border-gray-300 py-1 px-2 text-sm text-left focus:border-indigo-500 focus:ring-indigo-500"
                                            value="{{ old('qty', $stockQty) }}" placeholder="0">
                                        <span class="text-xs text-gray-500">{{ $unit }}</span>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-gray-500">
                                    {{ $reservedQty }} <span class="text-xs">{{ $unit }}</span>
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

                                <td class="px-4 py-3 text-right">
                                    @if($hasStock)
                                        <button type="submit"
                                            class="bg-amber-500 hover:bg-amber-600 text-white font-medium py-1 px-3 rounded text-xs transition shadow-sm">
                                            在庫更新
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white font-medium py-1 px-3 rounded text-xs transition shadow-sm" route="items.edit">
                                            詳細
                                        </button>
                                    @endif
                                </td>
                            </form>
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