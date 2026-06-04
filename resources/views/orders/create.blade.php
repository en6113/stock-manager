<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-2xl py-8">
        {{-- ヘッダー部分 --}}
        <div class="mb-6">
            <a href="{{ route('stocks.index') }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 mb-2">
                &larr; 在庫一覧に戻る
            </a>
            <h1 class="text-2xl font-semibold text-gray-800 leading-tight">新規発注の登録</h1>
            <p class="text-sm text-gray-600 mt-1">
                食材: <span class="font-bold text-gray-900">{{ $item->name }}</span> の新しい発注予定を作成します。
            </p>
            <p class="text-sm text-gray-600 mt-1">
                現時点における使用予定量: <span
                    class="font-bold text-gray-900">{{ $item->getReservedQty() }}{{ $item->unit }}</span>
            </p>
        </div>

        {{-- 登録フォーム --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 p-6 md:p-8">
            <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- 事前に特定されている item_id を隠しフィールドで送信 --}}
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                {{-- 新規登録時はデフォルトで「0:未発注」を想定。Controllerで制御してもOKですが、明示用に用意（hiddenでも可） --}}
                <input type="hidden" name="status" value="0">

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">発注情報の入力</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        {{-- 発注数 --}}
                        <div>
                            <label for="ordered_qty" class="block text-xs font-medium text-gray-600 mb-1">
                                発注数 <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="ordered_qty" id="ordered_qty" step="0.1" required
                                    class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm @error('ordered_qty') border-red-300 @enderror"
                                    value="{{ old('ordered_qty') }}" placeholder="0.0">
                                {{-- 要件：発注数のすぐ後に食材に対する単位(unit)を表示 --}}
                                <span
                                    class="text-sm text-gray-500 font-medium whitespace-nowrap">{{ $item->unit }}</span>
                            </div>
                        </div>

                        {{-- 発注日 --}}
                        <div>
                            <label for="ordered_date" class="block text-xs font-medium text-gray-600 mb-1">
                                発注日
                            </label>
                            <input type="date" name="ordered_date" id="ordered_date"
                                class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm @error('ordered_date') border-red-300 @enderror"
                                value="{{ old('ordered_date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                            <p class="text-[10px] text-gray-400 mt-1">空欄の場合は「未設定」になります</p>
                        </div>

                        {{-- 発注業者 --}}
                        <div class="sm:col-span-2 md:col-span-1">
                            <label for="vendor_id" class="block text-xs font-medium text-gray-600 mb-1">
                                発注業者 <span class="text-red-500">*</span>
                            </label>
                            <select name="vendor_id" id="vendor_id" required
                                class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm font-medium">
                                <option value="" disabled {{ old('vendor_id') ? '' : 'selected' }}>業者を選択してください</option>
                                {{-- 食材にデフォルト業者(item->vendor_id)があれば、それを初期選択させるロジックにしています --}}
                                <option value="1" {{ old('vendor_id', $item->vendor_id) == 1 ? 'selected' : '' }}>1: 総合卸業者
                                </option>
                                <option value="2" {{ old('vendor_id', $item->vendor_id) == 2 ? 'selected' : '' }}>2: 八百屋
                                </option>
                                <option value="3" {{ old('vendor_id', $item->vendor_id) == 3 ? 'selected' : '' }}>3: 精肉屋
                                </option>
                                <option value="4" {{ old('vendor_id', $item->vendor_id) == 4 ? 'selected' : '' }}>4: 精魚屋
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 my-6">

                {{-- 登録ボタンエリア --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('stocks.index') }}"
                        class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm transition duration-150 text-sm">
                        キャンセル
                    </a>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition duration-150 text-sm">
                        発注予定を登録する
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>