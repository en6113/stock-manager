<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-6xl py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">給食メニュー一覧</h1>
            <a href="{{ route('menus.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow">
                + 新規メニュー登録
            </a>
        </div>

        <div class="bg-gray-50 p-8">
            <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
                <form action="{{ route('menus.index') }}" method="GET" class="bg-gray-50 p-4 rounded mb-6 flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">メニュー名検索</label>
                        <input type="text" name="search" class="mt-1 block rounded border-gray-300 p-2 bg-white" placeholder="キーワード">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">カテゴリ</label>
                        <select name="filter_category" class="mt-1 block rounded border-gray-300 p-2 bg-white">
                            <option value="">すべて</option>
                            <option value="1">主菜</option>
                            <option value="2">副菜</option>
                            <option value="3">汁物</option>
                            <option value="4">おやつ</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">検索</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">メニュー名</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">カテゴリ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">カロリー</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">使用食材数</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($menus as $menu)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $menu->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $menu->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $menu->dish_category_label }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $menu->calories ? $menu->calories . ' kcal' : '未設定' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="font-semibold text-blue-600">{{ $menu->items_count ?? $menu->items->count() }}</span> 品目
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-3">
                                    <a href="{{ route('menus.edit', $menu->id) }}" class="text-indigo-600 hover:text-indigo-900">編集</a>
                                    
                                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">削除</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 text-sm">メニューが登録されていません。</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>