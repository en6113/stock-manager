<x-app-layout>
    {{-- ポップアップ風オーバーレイ(画面全体を覆う固定表示) --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
            <a href="{{ route('stocks.index') }}"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl leading-none"
                aria-label="閉じる">
                &times;
            </a>

            <h2 class="text-lg font-semibold text-gray-800 mb-1">在庫調整</h2>
            <p class="text-sm text-gray-600 mb-5">
                食材: <span class="font-bold text-gray-900">{{ $item->name }}</span>
            </p>

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('stock_adjustments.store', $item->id) }}" method="POST" class="space-y-4">
                @csrf

                {{-- 実際の在庫数 --}}
                <div>
                    <label for="actual_qty" class="block text-xs font-medium text-gray-600 mb-1">
                        現在 {{ number_format($currentStock) }}{{ $item->unit }} → 実際は何{{ $item->unit }}？
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="actual_qty" id="actual_qty" step="0.1" required
                            value="{{ old('actual_qty') }}"
                            class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            placeholder="0.0">
                        <span class="text-sm text-gray-500 font-medium whitespace-nowrap">{{ $item->unit }}</span>
                    </div>
                </div>

                {{-- 調整理由 --}}
                <div>
                    <label for="reason" class="block text-xs font-medium text-gray-600 mb-1">
                        調整理由 <span class="text-red-500">*</span>
                    </label>
                    <select name="reason" id="reason" required
                        class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">選択してください</option>
                        @foreach ($reasons as $reason)
                            <option value="{{ $reason->value }}" {{ old('reason') === $reason->value ? 'selected' : '' }}>
                                {{ $reason->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 調整日（任意） --}}
                <div>
                    <label for="adjusted_on" class="block text-xs font-medium text-gray-600 mb-1">
                        調整日
                    </label>
                    <input type="date" name="adjusted_on" id="adjusted_on"
                        class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        value="{{ old('adjusted_on', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                </div>

                {{-- メモ（任意） --}}
                <div>
                    <label for="note" class="block text-xs font-medium text-gray-600 mb-1">
                        メモ
                    </label>
                    <textarea name="note" id="note" rows="2"
                        class="w-full rounded-lg border-gray-300 py-1.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        placeholder="例: 棚卸で3本不足">{{ old('note') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('stocks.index') }}"
                        class="bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg shadow-sm transition duration-150 text-sm">
                        キャンセル
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition duration-150 text-sm">
                        調整を登録する
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
