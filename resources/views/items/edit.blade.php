<x-guest-layout>
    <div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
                <div class="max-w-md mx-auto">
                    <div class="flex items-center space-x-5">
                        <div class="h-14 w-14 bg-yellow-500 rounded-full flex flex-col justify-center items-center text-white text-2xl font-mono">編集</div>
                        <div class="block pl-2 font-semibold text-xl self-start text-gray-700">
                            <h2 class="leading-relaxed">食材の編集</h2>
                            <p class="text-sm text-gray-500 font-normal leading-relaxed">「{{ $item->name }}」の基本情報を変更します。</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('items.update', $item->id) }}" method="POST" class="divide-y divide-gray-200">
                        @csrf
                        @method('PUT')
                        
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                            <div class="flex flex-col">
                                <label class="leading-loose text-sm font-medium">食材名</label>
                                <input type="text" name="name" value="{{ old('name', $item->name) }}" class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600" required>
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex flex-col flex-1">
                                    <label class="leading-loose text-sm font-medium">保管場所</label>
                                    <select name="storage_location" class="px-4 py-2 border w-full sm:text-sm border-gray-300 rounded-md text-gray-600">
                                        <option value="常温" {{ $item->storage_location == '常温' ? 'selected' : '' }}>常温パントリー</option>
                                        <option value="冷蔵" {{ $item->storage_location == '冷蔵' ? 'selected' : '' }}>冷蔵庫</option>
                                        <option value="冷凍" {{ $item->storage_location == '冷凍' ? 'selected' : '' }}>冷凍庫</option>
                                    </select>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="leading-loose text-sm font-medium">適正在庫数（下限）</label>
                                    <input type="number" name="target_stock_qty" value="{{ old('target_stock_qty', $item->target_stock_qty) }}" class="px-4 py-2 border w-full sm:text-sm border-gray-300 rounded-md text-gray-600" min="0" required>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <label class="leading-loose text-sm font-medium">メイン仕入れ業者</label>
                                <select name="vendor_id" class="px-4 py-2 border w-full sm:text-sm border-gray-300 rounded-md text-gray-600">
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ $item->vendor_id == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label class="leading-loose text-sm font-medium text-gray-700">含まれるアレルギー物質</label>
                                <div class="grid grid-cols-2 gap-2 mt-1">
                                    @foreach($allergens as $allergen)
                                        <label class="inline-flex items-center text-sm text-gray-600">
                                            {{-- 食材がそのアレルギーを持っているか判定して checked をつける --}}
                                            <input type="checkbox" name="allergens[]" value="{{ $allergen->id }}" 
                                                {{ $item->allergens->contains($allergen->id) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ $allergen->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex items-center space-x-4">
                            <a href="{{ route('stocks.index') }}" class="flex justify-center items-center w-full text-gray-900 px-4 py-3 rounded-md focus:outline-none border border-gray-300 hover:bg-gray-50 text-sm">キャンセル</a>
                            <button type="submit" class="bg-yellow-500 flex justify-center items-center w-full text-white px-4 py-3 rounded-md focus:outline-none hover:bg-yellow-600 text-sm font-medium">更新する</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>