<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-2xl py-8">
        {{-- ヘッダー部分 --}}
        <div>
            <a href="{{ route('stocks.index') }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 mb-2">
                &larr; 在庫一覧に戻る
            </a>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-2 gap-4">
            <h1 class="text-2xl font-semibold text-gray-800 leading-tight">発注・納品の記録</h1>
        </div>
        <div class = "mb-4">
            <p class="text-sm text-gray-600 mt-1">
                食材: <span class="font-bold text-gray-900">{{ $item->name }}</span> のステータスおよび詳細情報を編集します。
            </p>
            <p class="text-sm text-gray-600 mt-1">
                現時点における使用予定量: <span class="font-bold text-gray-900">調整中{{ $item->unit }}</span>
            </p>        
        </div>

        {{-- 該当の食材に紐づくorderデータだけをループ表示 --}}
        <div class="space-y-8">
            @forelse($orders as $order)
                <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 p-6 md:p-8">

                    {{-- 識別用のインデックス --}}
                    <div class="mb-4 pb-2 border-b border-gray-100 flex justify-between items-center text-xs text-gray-500">
                        <div class="py-2 font-semibold text-sm flex items-center gap-2">
                            @if(old('status', $order->status) == '0')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">0: 未発注 (pending)</span>
                            @elseif(old('status', $order->status) == '1')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">1: 発注済 (ordered)</span>
                            @elseif(old('status', $order->status) == '2')
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">2: 納品済 (received)</span>
                            @endif
                        </div>
                        <span>発注日:
                            {{ $order->ordered_date ? \Carbon\Carbon::parse($order->ordered_date)->format('Y/m/d') : '未設定' }}</span>
                        <span>納品日:
                            {{ $order->received_date ? \Carbon\Carbon::parse($order->received_date)->format('Y/m/d') : '未設定' }}</span>
                    </div>

                    <form action="{{ route('orders.update', $order->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="item_id" value="{{ $order->item_id }}">

                        {{--- 1. 発注記録 ---}}
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">1. 発注記録</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                <div>
                                    <label for="ordered_date_{{ $order->id }}" class="block text-xs font-medium text-gray-600 mb-1">
                                        発注日
                                    </label>
                                    <input type="date" name="ordered_date" id="ordered_date_{{ $order->id }}"
                                        class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                        value="{{ old('ordered_date', $order->ordered_date ? \Carbon\Carbon::parse($order->ordered_date)->format('Y-m-d') : '') }}">
                                </div>

                                <div>
                                    <label for="ordered_qty_{{ $order->id }}" class="block text-xs font-medium text-gray-600 mb-1">
                                        発注数
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="ordered_qty" id="ordered_qty_{{ $order->id }}" step="0.1"
                                            class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                            value="{{ old('ordered_qty', $order->ordered_qty) }}" placeholder="0.0">
                                        <span class="text-sm text-gray-500 font-medium">{{ $order->item->unit }}</span>
                                    </div>
                                </div>

                                <div class="sm:col-span-2 md:col-span-1">
                                    <label for="vendor_{{ $order->id }}" class="block text-xs font-medium text-gray-600 mb-1">
                                        発注業者
                                    </label>
                                    <select name="vendor_id" id="vendor_{{ $order->id }}" required
                                        class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm font-medium">
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{--- 2. 納品記録 ---}}
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">2. 納品記録</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label for="received_date_{{ $order->id }}" class="block text-xs font-medium text-gray-600 mb-1">
                                        納品日
                                    </label>
                                    <input type="date" name="received_date" id="received_date_{{ $order->id }}"
                                        class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                        value="{{ old('received_date', $order->received_date ? \Carbon\Carbon::parse($order->received_date)->format('Y-m-d') : '') }}">
                                </div>

                                <div>
                                    <label for="expiration_date_{{ $order->id }}" class="block text-xs font-medium text-gray-600 mb-1">
                                        賞味期限 / 消費期限
                                    </label>
                                    <input type="date" name="expiration_date" id="expiration_date_{{ $order->id }}"
                                        class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                        value="{{ old('expiration_date', $order->expiration_date ? \Carbon\Carbon::parse($order->expiration_date)->format('Y-m-d') : '') }}">
                                </div>

                                <div>
                                    <label for="lot_number_{{ $order->id }}" class="block text-xs font-medium text-gray-600 mb-1">
                                        製造番号 (ロットNo.)
                                    </label>
                                    <input type="text" name="lot_number" id="lot_number_{{ $order->id }}"
                                        class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                        value="{{ old('lot_number', $order->lot_number) }}" placeholder="例: LOT-12345">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('stocks.index') }}"
                                class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm transition duration-150 text-sm">
                                キャンセル
                            </a>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition duration-150 text-sm">
                                このデータを更新する
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                {{-- 万が一紐づく在庫データが1つもない場合の表示 --}}
                <div class="bg-white shadow-md rounded-lg p-10 text-center text-gray-500 border border-gray-200">
                    現在、この食材に紐づく発注・納品記録データはありません。
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>