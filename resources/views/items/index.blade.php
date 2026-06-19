<x-app-layout>

<div class="container mx-auto px-4 sm:px-8 max-w-6xl py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 leading-tight">食材一覧</h1>
        </div>
        <a href="{{ route('items.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow">
            ＋ 食材を新規登録
        </a>
    </div>

    <div class="bg-gray-50 p-8">
        <div class="max-w-full mx-4 bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('items.index') }}" method="GET"
                class="bg-gray-50 p-4 rounded mb-6 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700">食材名検索</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        class="mt-1 block rounded border-gray-300 p-2 bg-white" placeholder="キーワード">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">カテゴリ</label>
                    <select name="item_category" class="mt-1 block rounded border-gray-300 p-2 bg-white">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">検索</button>
                <div>
                    <a href="{{ route('items.index') }}"
                        class="px-4 py-2 bg-[#e8ddd2] text-[#9a938c] rounded hover:bg-[#ddd2c7] inline-block">
                        リセット
                    </a>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="px-6 py-3 w-60">食材名</th>
                            <th class="px-6 py-3 w-40">適正在庫数(単位)</th>
                            <th class="px-6 py-3 w-40">規格容量</th>
                            <th class="px-6 py-3 w-40">保管場所</th>
                            <th class="px-6 py-3 w-40">カテゴリー</th>
                            <th class="px-6 py-3 w-40">アレルギー物質</th>
                            <th class="px-6 py-3 text-right">操作</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($items as $item)
                            <tr class="hover:bg-gray-50 transition-colors">

                                <td class="px-6 py-4 font-medium text-gray-950">
                                    {{ $item->name }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $item->target_stock_qty ?? 0 }}<span class="text-xs">{{ $item->unit }}</span>
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $item->capacity ?? '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->storage_location?->colorClass() }}">
                                        {{ $item->storage_location?->label() }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-xs text-gray-600">
                                    {{ $item->itemCategory->name ?? '未指定' }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($item->allergens as $allergen)
                                            <span
                                                class="inline-block bg-red-50 text-red-700 border border-red-200 rounded px-1.5 py-0.5 text-xs">
                                                {{ $allergen->name }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400 text-xs">なし</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('items.edit', $item->id) }}"
                                        class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-xs px-3 py-1.5 rounded font-medium shadow-sm transition-colors">
                                        編集
                                    </a>
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('本当にこの食材を削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded font-medium shadow-sm transition-colors">
                                            削除
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                    登録されている食材がありません。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- ページネーション -->
            <div class="flex items-center">
                {{ $items->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

</x-app-layout>