<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-2xl py-8">
        {{-- ヘッダー部分 --}}
        <div class="mb-6">
            <a href="{{ route('stocks.index') }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 mb-2">
                &larr; 在庫一覧に戻る
            </a>
            <h1 class="text-2xl font-semibold text-gray-800 leading-tight">在庫・発注情報の編集</h1>
            <p class="text-sm text-gray-600 mt-1">
                食材: <span class="font-bold text-gray-900">{{ $item->name }}</span> のステータスおよび詳細情報を編集します。
            </p>
        </div>

        {{-- エラーメッセージ表示 --}}
        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                <strong class="font-bold">入力内容にエラーがあります。</strong>
                <ul class="list-disc list-inside mt-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 該当の食材に紐づくstockデータだけをループ表示 --}}
        <div class="space-y-8">
            @forelse($stocks as $stock)
                <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 p-6 md:p-8">

                    {{-- 識別用のインデックス --}}
                    <div class="mb-4 pb-2 border-b border-gray-100 flex justify-between items-center text-xs text-gray-500">
                        <span>在庫データ ID: {{ $stock->id }}</span>
                        <span>発注日:
                            {{ $stock->ordered_date ? \Carbon\Carbon::parse($stock->ordered_date)->format('Y/m/d') : '未設定' }}</span>
                    </div>

                    <form action="{{ route('stocks.update', $stock->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="item_id" value="{{ $stock->item_id }}">

                        {{--- 1. メイン情報 ---}}
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">1. メイン情報</h3>

                            <div>
                                <label for="status_{{ $stock->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                    対応ステータス
                                </label>
                                <select name="status" id="status_{{ $stock->id }}" required
                                    class="w-full md:w-64 rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm font-medium">
                                    <option value="0" {{ old('status', $stock->status) == '0' ? 'selected' : '' }}>0: pending
                                        (未発注)</option>
                                    <option value="1" {{ old('status', $stock->status) == '1' ? 'selected' : '' }}>1: ordered
                                        (発注済)</option>
                                    <option value="2" {{ old('status', $stock->status) == '2' ? 'selected' : '' }}>2: received
                                        (納品済)</option>
                                </select>
                            </div>

                            <div>
                                <label for="required_qty_{{ $stock->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    使用予定量
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="required_qty" id="required_qty_{{ $stock->id }}" step="0.1"
                                        required
                                        class="w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                        value="{{ old('required_qty', $stock->item->required_qty ?? 0) }}"
                                        placeholder="0.0">
                                    <span class="text-sm text-gray-500 font-medium">{{ $stock->item->unit }}</span>
                                </div>
                            </div>

                            <div>
                                <label for="stock_{{ $stock->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                    現在の在庫数 <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="stock" id="stock_{{ $stock->id }}" step="0.1" required
                                        class="w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                        value="{{ old('stock', $stock->stock ?? 0) }}" placeholder="0.0">
                                    <span class="text-sm text-gray-500 font-medium">{{ $stock->item->unit }}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-4">

                        {{--- 2. 発注内容 ---}}
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">2. 発注内容</h3>

                            <div>
                                <label for="ordered_qty_{{ $stock->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    発注数
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="ordered_qty" id="ordered_qty_{{ $stock->id }}" step="0.1"
                                        class="w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                        value="{{ old('ordered_qty', $stock->ordered_qty) }}" placeholder="0.0">
                                    <span class="text-sm text-gray-500 font-medium">{{ $stock->item->unit }}</span>
                                </div>
                            </div>

                            <div>
                                <label for="ordered_date_{{ $stock->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    発注日
                                </label>
                                <input type="date" name="ordered_date" id="ordered_date_{{ $stock->id }}"
                                    class="w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    value="{{ old('ordered_date', $stock->ordered_date ? \Carbon\Carbon::parse($stock->ordered_date)->format('Y-m-d') : '') }}">
                            </div>

                            <div>
                                <label for="vendor_{{ $stock->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                    発注業者
                                </label>
                                <select name="vendor" id="vendor_{{ $stock->id }}" required
                                    class="w-full md:w-64 rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm font-medium">
                                    <option value="1" {{ old('vendor', $stock->item->vendor_id) == 1 ? 'selected' : '' }}>1:
                                        総合卸業者</option>
                                    <option value="2" {{ old('vendor', $stock->item->vendor_id) == 2 ? 'selected' : '' }}>2:
                                        八百屋</option>
                                    <option value="3" {{ old('vendor', $stock->item->vendor_id) == 3 ? 'selected' : '' }}>3:
                                        精肉屋</option>
                                    <option value="4" {{ old('vendor', $stock->item->vendor_id) == 4 ? 'selected' : '' }}>4:
                                        精魚屋</option>
                                </select>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-4">

                        {{--- 3. 納品状況 ---}}
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">3. 納品状況</h3>

                            <div>
                                <label for="received_date_{{ $stock->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    納品日
                                </label>
                                <input type="date" name="received_date" id="received_date_{{ $stock->id }}"
                                    class="w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    value="{{ old('received_date', $stock->received_date ? \Carbon\Carbon::parse($stock->received_date)->format('Y-m-d') : '') }}">
                            </div>

                            <div>
                                <label for="expiration_date_{{ $stock->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    賞味期限 / 消費期限
                                </label>
                                <input type="date" name="expiration_date" id="expiration_date_{{ $stock->id }}"
                                    class="w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    value="{{ old('expiration_date', $stock->expiration_date ? \Carbon\Carbon::parse($stock->expiration_date)->format('Y-m-d') : '') }}">
                            </div>

                            <div>
                                <label for="lot_number_{{ $stock->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    製造番号 (ロットNo.)
                                </label>
                                <input type="text" name="lot_number" id="lot_number_{{ $stock->id }}"
                                    class="w-full rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    value="{{ old('lot_number', $stock->lot_number) }}" placeholder="例: LOT-12345">
                            </div>
                        </div>

                        <hr class="border-gray-200 my-6">

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
                    現在、この食材に紐づく在庫・発注データはありません。
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>