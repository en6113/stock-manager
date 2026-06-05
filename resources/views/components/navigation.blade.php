<nav class="bg-gray-800 shadow-lg">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="flex justify-between items-center py-4">
            {{-- ロゴ --}}
            <a href="{{ route('stocks.index') }}" class="text-white text-xl font-bold hover:text-gray-300">
                📋 給食・在庫管理アプリ
            </a>

            {{-- ナビゲーションリンク --}}
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('meal_plans.index') }}" class="text-gray-300 hover:text-white">
                        献立一覧
                    </a>
                    <a href="{{ route('menus.index') }}" class="text-gray-300 hover:text-white">
                        メニュー一覧
                    </a>
                    <a href="{{ route('items.index') }}" class="text-gray-300 hover:text-white">
                        食材一覧
                    </a>
                    <a href="{{ route('orders.index') }}" class="text-gray-300 hover:text-white">
                        発注履歴一覧
                    </a>
                    <span class="text-gray-300">
                        {{ auth()->user()->name }}さん
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white">
                            ログアウト
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">
                        ログイン
                    </a>
                    <a href="{{ route('register') }}" class="text-gray-300 hover:text-white">
                        新規登録
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>