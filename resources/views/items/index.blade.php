<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('食材一覧') }}
            </h2>
            <a href="{{ route('items.create') }}"
                class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium shadow">
                ＋ 食材を新規登録
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                        <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-3">食材名</th>
                                <th class="px-6 py-3">適正在庫数</th>
                                <th class="px-6 py-3">単位</th>
                                <th class="px-6 py-3">規格容量</th>
                                <th class="px-6 py-3">保管場所</th>
                                <th class="px-6 py-3">メイン仕入れ業者</th>
                                <th class="px-6 py-3">アレルギー物質</th>
                                <th class="px-6 py-3 text-right">アクション</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($items as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-950">{{ $item->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $item->target_stock_qty }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $item->capacity ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($item->storage_location === '冷蔵') bg-blue-100 text-blue-800
                                                @elseif($item->storage_location === '冷凍') bg-cyan-100 text-cyan-800
                                                @else bg-amber-100 text-amber-800 @endif">
                                            {{ $item->storage_location }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $item->vendors->name ?? '未指定' }}</td>
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
            </div>
        </div>
    </div>
</x-app-layout>