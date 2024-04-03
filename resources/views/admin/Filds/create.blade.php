@extends('admin.layouts.default')
@section('title')
    Поля
@endsection

<style>
    input{
        color: white !important;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>


@section('content')

    @if(session('created'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 3000
            })
        </script>
    @endif

    <div class="col-12 grid-margin stretch-card" bis_skin_checked="1">
        <div class="card" bis_skin_checked="1">
            <div class="card-body" bis_skin_checked="1">
                <h4 class="card-title">Добавить поле</h4>
                <form  id="create_product" action="{{route('create_fild')}}" method="post" class="forms-sample">
                    @csrf
                    <br>
                    <div style="display: flex; flex-wrap: wrap; gap: 20px">
                    @foreach($sports as $item)
                    <div class="form-check" bis_skin_checked="1" style="width: 10%">
                        <label class="form-check-label" style="color: white !important;">
                            <input style="cursor: pointer;" type="checkbox" class="form-check-input checkbox" value="{{$item->id}}" name="sport_type[]" > {{$item->name}} <i class="input-helper"></i></label>
                    </div>
                    @endforeach
                    </div>
                        <br>
                    <br>

                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleFormControlSelect2">Город</label>
                        <select name="city_id" style="color: white" class="form-control" id="exampleFormControlSelect2">
                            @foreach($city as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Название</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Название" name="name">
                    </div>

                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Долгота</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Долгота" name="latitude">
                    </div>


                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Широта</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Широта" name="longitude">
                    </div>

                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Адрес</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Адрес" name="address">
                    </div>

                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Вместимость Пользвателей</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Вместимость Пользвателей" name="users_count">
                    </div>
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Особенности</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Особенности" name="peculiarities">
                    </div>
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Описание</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Описание" name="description">
                    </div>
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Размер</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Размер" name="size">
                    </div>
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Покрытие</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Покрытие" name="covering">
                    </div>

                    <br>
                    <br>
                    <h3>График работы</h3>
                    <br>
                    <div id="input-container" bis_skin_checked="1">

                    </div>
                    <button type="button" class="btn btn-inverse-light btn-fw" id="add-inputs">Добавить ещё</button>
                    <br><br>
                    <div class="form-group" bis_skin_checked="1">
                        <label class="btn btn-outline-warning" for="file">Выберете фотографии</label>
                        <input style="display: none"  type="file"  id="file" accept="image/*" multiple  >
                        <div id="imagePreview">
                            <div id="newDivqwe"></div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>

                    <button type="submit" class="btn btn-inverse-success btn-fw">Добавить</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="{{asset('admin//js/product.js')}}"></script>
@endsection

