<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Личный кабинет') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Вы успешно авторизованы!
                    @if(session()->exists('was_updated'))
                        {{session('was_updated')}}
                    @endif
                    <div>
                        <img src="{{asset($user->avatar)}}" alt="Ваша аватарка">
                        <form action="{{route('updateProfileUser')}}" method="POST" enctype="multipart/form-data">
                            <input type="file" name="avatarFile">
                            <input type="text" value="{{$user->name}}" name="name">
                            <select name="gender" value="{{$user->gender}}">
                                @if($user->gender == null)
                                    <option disabled selected></option>
                                @endif
                                <option @if($user->gender == 'M') selected @endif value="M">Мужской</option>
                                <option @if($user->gender == 'F') selected @endif value="F">Женский</option>
                            </select>
                            <input type="date" value="{{$user->birthday}}" name="birthday">
                            <input type="text" value="{{$user->phone}}" name="phone">
                            <input type="text" value="{{$user->from}}" name="from">
                            <button type="submit">Изменить данные</button>
                            {{ csrf_field() }}
                            {{$user}}
                        </form>

                        @if(count($errors)>0)
                            <div class="error" style="color:red">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    </div>

                    <div>
                        Ваш статус - {{ $userStatus->name }}
                    </div>
                    <div>
                        Бонусов - {{ $user->bonus }}
                    </div>

                    @empty(!$orders)
                        <div style="background-color: #87ecd4">
                            @foreach($orders as $order)
                                <div>{{$order}}</div>
                                <div>{{$intervals[$order['id']]}}</div>
                                <div>{{$schedule_standards[$order['id']]}}</div>
                                <div>{{$order_statuses[$order['id']]}}</div>
                                @foreach($products[$order['id']] as $product)
                                    <div>{{$product}}</div>
{{--                                    <img src="{{asset($product->photo)}}">--}}
                                @endforeach
                            @endforeach
                        </div>
                    @endempty

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
