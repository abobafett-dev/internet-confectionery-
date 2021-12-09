<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: -160px;">
            {{ __('Личный кабинет') }}
        </h2>
        @if(session()->exists('was_updated'))
            <div
                style="padding: 0px 10px; margin-left:auto; text-align: center; background-color: #9df99d; border-radius: 10px;">{{session('was_updated')}}</div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-white border-gray-200">
                    <div id="history">
                        <div class="items-center" id="nav">
                            <div>
                                История заказов:
                            </div>
                            <div id="filter">
                                <label>Поиск
                                    <input type="text" name="nameOfTort" onkeypress="">
                                </label>
                                <label>с
                                    <input type="date" name="from" onkeypress="">
                                </label>
                                <label>до
                                    <input type="date" name="to" onkeypress="">
                                </label>
                            </div>
                        </div>
                        @if(count($orders) > 0)
                            <div style="display: flex">
                                @foreach($orders as $order)
                                    <div class="order">
                                        <a href="">
                                            <div class="info">
                                                <div style="background-color:
                                                @switch($order['status']['status'])
                                                @case('В корзине')
                                                    #979595
                                                @break
                                                @case('Принят')
                                                    #edab23
                                                @break
                                                @case('Готовится')
                                                    #88d792
                                                @break
                                                @case('Готов')
                                                    #52b7ff
                                                @break
                                                @case('Оплачен')
                                                    #ffbdb5
                                                @break
                                                @case('Выдан')
                                                    #24d53a
                                                @break
                                                @case('Отменён')
                                                    #df3535
                                                @break
                                                @default
                                                    red
                                                @endswitch
                                                    ;font-family: sans-serif;border-radius:10px 10px 0px 0px;">{{$order['status']['status']}}</div>
                                                @foreach($order['products'] as $product)
                                                    <img src="{{$product['photo']}}" style="width:10em;">
                                                    <span>{{$product['name']}}<br></span>
                                                    <span>
                                                        {{date('d.m.Y',strtotime($order['will_cooked_at']))}}
                                                        {{date('h:i',strtotime($order['interval']['start']))}}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </a>
                                        <div style="text-align: center; margin: 5px 0px;">
                                            <a href="/dashboard" style="padding: 5px 15px;">Оставить отзыв</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div>Вы ещё не совершали заказов</div>
                        @endif

                        @if($user['id_user_status'] == 2 && count($ordersToAdmin) > 0)
                            <div>{{var_dump($ordersToAdmin)}}</div>
                        @endif
                    </div>
                    <div id="profile">
                        @if(count($errors)>0)
                            <div class="error" style="color:red; text-align: center;">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <img src="{{asset($user->avatar)}}" alt="Ваша аватарка" id="avatar">
                        <form action="{{route('updateProfileUser')}}" method="POST" enctype="multipart/form-data"
                              onchange="fixProfile()">
                            <div style="text-align: center; width: 100%; margin-top: 15px;">
                                <label style="background-color: #FFE6EF;padding: 5px 20px; border: 1px solid #b8b7b7;">Загрузить
                                    новый аватар
                                    <input type="file" name="avatarFile" style="display: none;">
                                </label>
                            </div>
                            <label class="block">Имя
                                <br>
                                <input type="text" value="{{$user->name}}" name="name">
                            </label>
                            <label class="block">Пол
                                <br>
                                <select name="gender">
                                    @if($user->gender == null)
                                        <option disabled selected></option>
                                    @endif
                                    <option @if($user->gender == 'M') selected @endif value="M">Мужской</option>
                                    <option @if($user->gender == 'F') selected @endif value="F">Женский</option>
                                </select>
                            </label>
                            <label class="block">Дата рождения
                                <input type="date" value="{{$user->birthday}}" name="birthday">
                            </label>
                            <label class="block">Номер телефона
                                <input type="text" value="{{$user->phone}}" name="phone">
                            </label>
                            <label class="block">Откуда узнали о нас?
                                <select name="id_source">
                                    @if($user->id_source == null)
                                        <option disabled selected></option>
                                    @else
                                        <option value="{{$user->id_source->id}}"
                                                selected>{{$user->id_source->source}}</option>
                                    @endif
                                    @foreach($sources as $fr)
                                        @if(isset($user->id_source->id) && $user->id_source->id == $fr->id)
                                            @continue
                                        @endif
                                        <option value="{{$fr->id}}">{{$fr->source}}</option>
                                    @endforeach
                                </select>
                            </label>
                            @if($user->bonus > -1)
                                <div id="status">Ваш статус - <span
                                        style="text-decoration:underline;">{{ $userStatus->name }}</span>
                                </div>
                                <div id="bonus">Ваши бонусы - <span
                                        style="text-decoration:underline;">{{ $user->bonus }}</span>
                                </div>
                            @endif
                            <button type="submit" style="border:1px solid black;all: revert; display:none;"
                                    id="SaveChanges">Сохранить изменения
                            </button>
                            {{ csrf_field() }}
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function fixProfile() {
        document.getElementById('SaveChanges').setAttribute('style', 'border:1px solid black;all: revert;padding:8px;')
    }
</script>
