<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-2xl py-8">
        {{-- ヘッダー部分 --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="mb-4">
                <a href="{{ route('stocks.index') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 mb-2">
                    &larr; 在庫一覧に戻る
                </a>
                <h1 class="text-2xl font-semibold text-gray-800 leading-tight">新規発注の登録</h1>
                <p class="text-sm text-gray-600 mt-1">
                    食材: <span class="font-bold text-gray-900">{{ $item->name }}</span> の新しい発注情報を記録します。
                </p>
            </div>
            <div class="mb-2">
                <a href="{{ route('orders.edit', $item->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 text-sm inline-flex items-center gap-1">
                    + この食材の発注履歴一覧へ
                </a>
            </div>
        </div>

        {{-- 登録フォーム --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 p-6 md:p-8">
            <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- item_id を隠しフィールドで送信 --}}
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="status" value="0">

                {{-- 発注情報の入力 --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">1. 発注情報の入力</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        {{-- 発注日 --}}
                        <div>
                            <label for="ordered_date" class="block text-xs font-medium text-gray-600 mb-1">
                                発注日
                            </label>
                            <input type="date" name="ordered_date" id="ordered_date"
                                class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                value="{{ old('ordered_date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                        </div>
                        
                        {{-- 発注数 --}}
                        <div>
                            <label for="ordered_qty" class="block text-xs font-medium text-gray-600 mb-1">
                                発注数 <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="ordered_qty" id="ordered_qty" step="0.1" required
                                    value="{{ old('ordered_qty') }}"
                                    class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    placeholder="0.0">
                                <span
                                    class="text-sm text-gray-500 font-medium whitespace-nowrap">{{ $item->unit }}</span>
                            </div>
                        </div>

                        {{-- 発注業者 --}}
                        <div class="sm:col-span-2 md:col-span-1">
                            <label for="vendor_id" class="block text-xs font-medium text-gray-600 mb-1">
                                発注業者 <span class="text-red-500">*</span>
                            </label>
                            <select name="vendor_id" id="vendor_id" required
                                class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm font-medium">
                                <option value="">選択してください</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 納品情報の入力 --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">2. 納品情報の入力</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        {{-- 納品日 --}}
                        <div>
                            <label for="received_date" class="block text-xs font-medium text-gray-600 mb-1">
                                納品日
                            </label>
                            <input type="date" name="received_date" id="received_date"
                                class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                value="{{ old('received_date') }}">
                        </div>

                        {{-- 賞味期限/消費期限 --}}
                        <div>
                            <label for="expiration_date" class="block text-xs font-medium text-gray-600 mb-1">
                                賞味期限/消費期限
                            </label>
                            <input type="date" name="expiration_date" id="expiration_date"
                                class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                value="{{ old('expiration_date') }}">
                        </div>

                        {{-- 製造番号 --}}
                        <div>
                            <label for="lot_number" class="block text-xs font-medium text-gray-600 mb-1">
                                製造番号 (ロットNo.)
                            </label>
                            <input type="text" name="lot_number" id="lot_number"
                                class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                placeholder="例: LOT-12345" value="{{ old('lot_number') }}">
                        </div>
                    </div>
                </div>

                {{-- 登録ボタンエリア --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('stocks.index') }}"
                        class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm transition duration-150 text-sm">
                        キャンセル
                    </a>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition duration-150 text-sm">
                        発注記録を登録する
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>