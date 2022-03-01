<script>
    window.addEventListener('scroll', (event) => {
        var elem = document.getElementById('gototop');
        if (scrollY > 900) elem.style.display = 'block';
        else elem.style.display = 'none';
    });

    function backToTop() {
        var i = Number(scrollY);
        window.scrollTo(0, 0);
    }

    function OpenCloseMenu() {
        var elem = document.getElementById('menu');
        if (elem.style.display == 'block') {
            elem.style.display = 'none';
        } else {
            elem.style.display = 'block';
        }
    }
</script>
<header class="bg-white shadow">
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100" style="background-color: #ffbeb5">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex" style="width: 345px; justify-content: space-between;">
                    <!-- Location -->
                    <div class="flex-shrink-0 flex items-center">
                        <svg style="margin-right: 10px" width="18" height="22" viewBox="0 0 18 22" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.34606 20.0871C11.7469 17.5594 13.5235 15.2893 14.6964 13.2752C15.8724 11.2556 16.4127 9.54244 16.4127
                            8.11431C16.4127 4.29846 13.0786 1.11707 8.85332 1.11707C4.62805 1.11707 1.29395 4.29846 1.29395
                            8.11431C1.29395 9.54244 1.8342 11.2556 3.01028 13.2752C4.18311 15.2893 5.95976 17.5594 8.36056
                            20.0871L9.34606 20.0871ZM9.34606 20.0871C9.3299 20.1041 9.31252 20.1203 9.29396 20.1357L9.34606
                            20.0871ZM9.29388 20.1358C9.02106 20.3612 8.59269 20.3315 8.36058 20.0871L9.29388 20.1358Z"
                                  stroke="#D9124A" stroke-width="1.5"></path>
                            <circle cx="8.85329" cy="8.67654" r="3.12771" stroke="#D9124A" stroke-width="1.5"></circle>
                        </svg>
                        г. Тюмень
                    </div>
                    <!-- Links -->
                    <div class="flex-shrink-0 flex items-center" id="links">
                        <div class="sociallinks">
                            <a href="https://vk.com/cakemechtai" target="_blank" rel="noopener">
                                <svg class="t-sociallinks__svg" version="1.1" id="Layer_1"
                                     xmlns="http://www.w3.org/2000/svg"
                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px"
                                     height="30px" viewBox="0 0 48 48" enable-background="new 0 0 48 48"
                                     xml:space="preserve">
                                    <desc>
                                        VK
                                    </desc>
                                    <path style="fill:#d9124a;" d="M47.761,24c0,13.121-10.639,23.76-23.76,23.76C10.878,47.76,0.239,
                                    37.121,0.239,24c0-13.123,10.639-23.76,23.762-23.76C37.122,0.24,47.761,10.877,47.761,24 M35.259,
                                    28.999c-2.621-2.433-2.271-2.041,0.89-6.25c1.923-2.562,2.696-4.126,
                                    2.45-4.796c-0.227-0.639-1.64-0.469-1.64-0.469l-4.71,0.029c0,0-0.351-0.048-0.609,0.106c-0.249,
                                    0.151-0.414,0.505-0.414,0.505s-0.742,1.982-1.734,3.669c-2.094,3.559-2.935,3.747-3.277,
                                    3.524c-0.796-0.516-0.597-2.068-0.597-3.171c0-3.449,0.522-4.887-1.02-5.259c-0.511-0.124-0.887-0.205-2.195-0.219c-1.678-0.016-3.101,0.007-3.904,
                                    0.398c-0.536,0.263-0.949,0.847-0.697,0.88c0.31,0.041,1.016,0.192,1.388,0.699c0.484,0.656,0.464,
                                    2.131,0.464,2.131s0.282,4.056-0.646,4.561c-0.632,0.347-1.503-0.36-3.37-3.588c-0.958-1.652-1.68-3.481-1.68-3.481s-0.14-0.344-0.392-0.527c-0.299-0.222-0.722-0.298-0.722-0.298l-4.469,
                                    0.018c0,0-0.674-0.003-0.919,0.289c-0.219,0.259-0.018,0.752-0.018,0.752s3.499,8.104,7.573,
                                    12.23c3.638,3.784,7.764,3.36,7.764,3.36h1.867c0,0,0.566,0.113,0.854-0.189c0.265-0.288,
                                    0.256-0.646,0.256-0.646s-0.034-2.512,1.129-2.883c1.15-0.36,2.624,2.429,4.188,3.497c1.182,
                                    0.812,2.079,0.633,2.079,0.633l4.181-0.056c0,0,2.186-0.136,1.149-1.858C38.281,32.451,37.763,31.321,35.259,28.999">
                                    </path>
                                </svg>
                            </a>
                        </div>
                        <div class="sociallinks">
                            <a href="https://www.instagram.com/cakemechtai.tmn/" target="_blank" rel="noopener">
                                <svg class="t-sociallinks__svg" version="1.1" id="Layer_1"
                                     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     width="30px" height="30px" viewBox="0 0 30 30" xml:space="preserve">
                                    <desc>
                                        Instagram
                                    </desc>
                                    <path style="fill:#d9124a;" d="M15,11.014 C12.801,11.014 11.015,12.797 11.015,15 C11.015,
                                    17.202 12.802,18.987 15,18.987 C17.199,18.987 18.987,17.202 18.987,15 C18.987,12.797 17.199,11.014 15,
                                    11.014 L15,11.014 Z M15,17.606 C13.556,17.606 12.393,16.439 12.393,15 C12.393,13.561 13.556,12.394 15,
                                    12.394 C16.429,12.394 17.607,13.561 17.607,15 C17.607,16.439 16.444,17.606 15,17.606 L15,17.606 Z">

                                    </path>
                                    <path style="fill:#d9124a;" d="M19.385,9.556 C18.872,9.556 18.465,9.964 18.465,10.477 C18.465,
                                    10.989 18.872,11.396 19.385,11.396 C19.898,11.396 20.306,10.989 20.306,10.477 C20.306,9.964 19.897,9.556 19.385,9.556 L19.385,9.556 Z">

                                    </path>
                                    <path style="fill:#d9124a;" d="M15.002,0.15 C6.798,0.15 0.149,6.797 0.149,15 C0.149,23.201 6.798,29.85 15.002,29.85 C23.201,29.85 29.852,
                                    23.202 29.852,15 C29.852,6.797 23.201,0.15 15.002,0.15 L15.002,0.15 Z M22.666,18.265 C22.666,20.688 20.687,22.666 18.25,22.666 L11.75,
                                    22.666 C9.312,22.666 7.333,20.687 7.333,18.28 L7.333,11.734 C7.333,9.312 9.311,7.334 11.75,7.334 L18.25,7.334 C20.688,7.334 22.666,
                                    9.312 22.666,11.734 L22.666,18.265 L22.666,18.265 Z">

                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center" style="align-self: center;">
                    <a href="{{ route('main') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600"/>
                    </a>
                </div>
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6" style="width: 325px; position: relative">

                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none
                                    focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                                        style="color:#d9124a;">
                                    <div style="color: black">{{ Auth::user()->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Dashboard -->
                                <x-dropdown-link :href="route('dashboard')">
                                    {{ __('Личный кабинет') }}
                                </x-dropdown-link>
                                <!-- Админская часть -->
                                @if(Auth::user()->id_user_status == '2')
                                    <x-dropdown-link :href="route('adminOrders', ['date' => date('Y.m.d')])">
                                        {{ __('Заказы') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('adminProducts')">
                                        {{ __('Ассортимент') }}
                                    </x-dropdown-link>
                                @endif
                            <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                        {{ __('Выйти') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}" class="text-sm dark:text-gray-500" style="margin-left: auto;">Войти</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="ml-4 text-sm dark:text-gray-500">Зарегистрироваться</a>
                    @endif
                @endauth
                <!-- sale-basket -->
                    <div class="sale-basket-icons-item-wrapper">
                        <a href="{{route('cart')}}">
                            <svg width="26" height="25" viewBox="0 0 26 25" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.7677 21.8164C11.343 21.8164 11.8094 21.3687 11.8094 20.8164C11.8094 20.2641 11.343 19.8164 10.7677 19.8164C10.1924 19.8164
                                9.72607 20.2641 9.72607 20.8164C9.72607 21.3687 10.1924 21.8164 10.7677 21.8164Z"
                                      fill="#D9124A" stroke="#D9124A" stroke-width="1.5" stroke-linecap="square"></path>
                                <path d="M19.1012 21.8164C19.6765 21.8164 20.1429 21.3687 20.1429 20.8164C20.1429 20.2641 19.6765 19.8164 19.1012 19.8164C18.5259
                                19.8164 18.0596 20.2641 18.0596 20.8164C18.0596 21.3687 18.5259 21.8164 19.1012 21.8164Z"
                                      fill="#D9124A" stroke="#D9124A" stroke-width="1.5" stroke-linecap="square"></path>
                                <path d="M2.95508 2.81641H6.08008L8.89258 15.2164C8.98998 15.675 9.2518 16.0862 9.63246 16.3786C10.0131 16.6709 10.4885 16.8258 10.9759
                                16.8164H18.9967C19.4841 16.8258 19.9595 16.6709 20.3402 16.3786C20.7209 16.0862 20.9827
                                15.675 21.0801 15.2164L22.7467 6.81641H7.74675" stroke="#D9124A" stroke-width="1.5"
                                      stroke-linecap="square"></path>
                            </svg>
                        </a>
                    </div>
                    <!-- Hamburger -->
                    <div onclick="OpenCloseMenu()" id="hamb">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100
                         focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                id="buttonMenu">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"
                                 style="color: #d9124a;">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                      stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                      stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div id="menu">
                        <ul id="menu_ul">
                            <a href="/" class="menu_a">
                                <li>Главная</li>
                            </a><a href="{{route('catalog')}}" class="menu_a">
                                <li>Каталог</li>
                                {{--                            </a><a href="" class="menu_a">--}}
                                {{--                                <li>Акции</li>--}}
                            </a><a href="" class="menu_a">
                                <li>Для покупателя</li>
                            </a>
                        </ul>
                    </div>
                </div>
                <!-- Hamburger -->
                <div @click="open = ! open" class="-mr-2 flex items-center sm:hidden" onclick="OpenCloseMenu()">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500
                    hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Responsive Navigation Menu -->
        @auth
        @endauth
    </nav>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex" style="min-height: 73px;">
        @if(isset($header)) {{ $header }} @else
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: -160px;">
                Главная
            </h2>
        @endif
    </div>
</header>
<div id="gototop" onclick="backToTop()">
    <div id="indicator">
        <img src="{{asset(Storage::url('public/logo/indicator.png'))}}" alt="Вверх"
             style="margin: auto; width: 15px; align-items: center;">
    </div>
</div>

