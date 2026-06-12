<x-app-layout>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>食品群別給与量・報告書データ出力</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="max-w-6xl mx-auto px-4 py-8">

        <div class="mb-8 border-b border-gray-200 pb-4">
            <h1 class="text-2xl font-bold text-gray-900">特定給食施設栄養報告書（様式６）データ出力</h1>
            <p class="mt-2 text-sm text-gray-600">指定した期間の食品群別給与量を計算しCSVで出力します。</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                出力する期間を設定
            </h2>

            <form action="{{ route('reports.export') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">期間（開始日）<span
                                class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" required
                            value="{{ old('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">期間（終了日）<span
                                class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="end_date" required
                            value="{{ old('end_date', now()->endOfMonth()->format('Y-m-d')) }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out flex items-center justify-center sm:text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            CSV出力
                        </button>
                    </div>
                </div>
            </form>
        </div>


        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        月間平均データのプレビュー（確認用）
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">※アプリ内で計算された1人1日あたりの平均値の推移です。</p>
                </div>

                <div class="mt-3 sm:mt-0">
                    <select name="display_month"
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="2026-02">2026年02月度（報告対象月）</option>
                        <option value="2026-01">2026年01月度</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>提供食品量（食品群別）
                    </h3>
                    <div class="overflow-x-auto border border-gray-100 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">コード</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">食品群名</th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-500">1人1日あたり平均</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr>
                                    <td class="px-4 py-2 text-gray-500 font-mono">A01</td>
                                    <td class="px-4 py-2 font-medium text-gray-900">魚介類</td>
                                    <td class="px-4 py-2 text-right font-mono">25.4 g</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-gray-500 font-mono">A02</td>
                                    <td class="px-4 py-2 font-medium text-gray-900">肉類</td>
                                    <td class="px-4 py-2 text-right font-mono">45.2 g</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-gray-500 font-mono">B01</td>
                                    <td class="px-4 py-2 font-medium text-gray-900">緑黄色野菜類</td>
                                    <td class="px-4 py-2 text-right font-mono">35.0 g</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>実給与栄養量
                    </h3>
                    <div class="overflow-x-auto border border-gray-100 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">栄養素名</th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-500">実給与量（平均）</th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-500 text-xs text-gray-400">
                                        給与基準量</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr class="bg-gray-50/50">
                                    <td class="px-4 py-2 font-medium text-gray-900">エネルギー</td>
                                    <td class="px-4 py-2 text-right font-mono font-semibold text-gray-900">2,150 kcal
                                    </td>
                                    <td class="px-4 py-2 text-right font-mono text-gray-400">2,200 kcal</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-900">たんぱく質</td>
                                    <td class="px-4 py-2 text-right font-mono">72.5 g</td>
                                    <td class="px-4 py-2 text-right font-mono text-gray-400">70.0 g</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-900">脂質</td>
                                    <td class="px-4 py-2 text-right font-mono">58.0 g</td>
                                    <td class="px-4 py-2 text-right font-mono text-gray-400">60.0 g</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</x-app-layout>