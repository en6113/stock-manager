<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-6xl py-8">
        {{-- ヘッダー部分 --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 leading-tight">発注履歴一覧</h1>
                <p class="text-sm text-gray-600 mt-1">過去および現在のすべての発注・納品状態を管理します。</p>
            </div>
            <div>
                <a href="{{ route('stocks.index') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow">
                    + 新しく発注情報を登録する(在庫一覧へ)
                </a>
            </div>
        </div>

        {{-- 検索・絞り込みフィルター（ロジックの組みがいポイント） --}}
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
            <form action="{{ route('orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                {{-- ステータス検索 --}}
                <div>
                    <label for="filter_status" class="block text-xs font-medium text-gray-600 mb-1">ステータス</label>
                    <select name="status" id="filter_status" class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">すべて</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>0: 未発注</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>1: 発注済</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>2: 納品済</option>
                    </select>
                </div>
                {{-- 発注業者検索 --}}
                <div>
                    <label for="filter_vendor" class="block text-xs font-medium text-gray-600 mb-1">発注業者</label>
                    <select name="vendor_id" id="filter_vendor" class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">すべて</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->id }}: {{ $vendor->name }}
                                </option>
                            @endforeach
                    </select>
                </div>
                {{-- 検索ボタン --}}
                <div class="sm:col-span-2 md:col-span-2 flex items-end gap-2">
                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-1.5 px-4 rounded-lg shadow-sm transition duration-150 text-sm">
                        検索・絞り込み
                    </button>
                    <a href="{{ route('orders.index') }}" class="w-1/3 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-1.5 px-4 rounded-lg border border-gray-300 shadow-sm transition duration-150 text-sm">
                        クリア
                    </a>
                </div>
            </form>
        </div>

        {{-- 履歴テーブル --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="py-3 px-4">ステータス</th>
                            <th class="py-3 px-4">食材名</th>
                            <th class="py-3 px-4 text-right">発注量</th>
                            <th class="py-3 px-4">発注日</th>
                            <th class="py-3 px-4">納品日</th>
                            <th class="py-3 px-4">発注業者</th>
                            <th class="py-3 px-4 text-center">操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                {{-- ステータス --}}
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($order->status == '0')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">未発注</span>
                                    @elseif($order->status == '1')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">発注済</span>
                                    @elseif($order->status == '2')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">納品済</span>
                                    @endif
                                </td>
                                {{-- 食材名 --}}
                                <td class="py-3 px-4 font-medium text-gray-900">
                                    {{ $order->item->name }}
                                </td>
                                {{-- 発注量 --}}
                                <td class="py-3 px-4 text-right whitespace-nowrap font-mono">
                                    <span class="font-bold text-gray-800">{{ $order->ordered_qty }}</span>
                                    <span class="text-xs text-gray-500 ml-1">{{ $order->item->unit }}</span>
                                </td>
                                {{-- 発注日 --}}
                                <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                    {{ $order->ordered_date ? \Carbon\Carbon::parse($order->ordered_date)->format('Y/m/d') : '-' }}
                                </td>
                                {{-- 納品日 --}}
                                <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                    {{ $order->received_date ? \Carbon\Carbon::parse($order->received_date)->format('Y/m/d') : '-' }}
                                </td>
                                {{-- 発注業者 --}}
                                <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                    {{ $order->vendor->name ?? '未設定' }}
                                </td>
                                {{-- 操作ボタン --}}
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        <a href="{{ route('orders.edit', $order->id) }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-xs px-3 py-1.5 rounded font-medium shadow-sm transition-colors">
                                            編集
                                        </a>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('本当に発注履歴を削除しますか？')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded font-medium shadow-sm transition-colors">削除</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-gray-500">
                                    該当する発注履歴が見つかりませんでした。
                                </td>
                            </tr>
                        @endempty
                    </tbody>
                </table>
            </div>
            
            {{-- ページネーション（ロジックの組みがいポイント） --}}
            @if($orders->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>