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
                    <div>
                        <form action="{{route('updateProfileUser')}}" method="POST" enctype="multipart/form-data">
                            <img src="{{$user->avatar}}" alt="Ваша аватарка">
                            <input type="file" name="avatar">
                            <input type="text" value="{{$user->name}}" name="name">
                            <input type="text" value="{{$user->email}}" name="email">
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
                            <button>Изменить данные</button>
                            {{ csrf_field() }}
                        </form>
                    </div>

                    <div>
                        Ваш статус - {{ $userStatus->name }}
                    </div>
                    <div>
                        Бонусов - {{ $user->bonus }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
